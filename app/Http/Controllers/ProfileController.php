<?php

namespace App\Http\Controllers;

use App\Notifications\UserFollowedNotification;
use App\Notifications\ProfileVisitedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        if ($user->registration_date) {
            // Convertir string a objeto Carbon para evitar errores en la vista
            $user->registration_date = Carbon::parse($user->registration_date);
        }

        $data = $this->buildProfileData($user, true);
        $data['isOwner'] = true;
        $data['isFollowing'] = false;

        return view('profile.show', $data);
    }

    public function showPublic(User $user)
    {
        // El perfil del admin no es visible para usuarios no-admin
        if ($user->isAdmin()) {
            $viewer = auth()->user();
            if (!$viewer || !$viewer->isAdmin()) {
                abort(404);
            }
        }

        $data = $this->buildProfileData($user, false);
        $viewer = auth()->user();

        $data['isOwner'] = $viewer && $viewer->id === $user->id;
        $data['isFollowing'] = $viewer
            ? $viewer->following()->where('user_id', $user->id)->exists()
            : false;

        // Notify profile owner when an authenticated non-owner visits (once per day per visitor)
        if ($viewer && $viewer->id !== $user->id) {
            $alreadyNotifiedToday = $user->notifications()
                ->where('type', ProfileVisitedNotification::class)
                ->whereDate('created_at', today())
                ->whereJsonContains('data->actor_id', $viewer->id)
                ->exists();

            if (!$alreadyNotifiedToday) {
                $user->notify(new ProfileVisitedNotification($viewer));
            }
        }

        return view('profile.show', $data);
    }

    public function toggleFollow(User $user)
    {
        $viewer = auth()->user();

        if ($viewer->id === $user->id) {
            return back()->with('error', 'No puedes seguirte a ti mismo.');
        }

        $alreadyFollowing = $viewer->following()->where('user_id', $user->id)->exists();
        $viewer->following()->toggle($user->id);

        if (!$alreadyFollowing) {
            $user->notify(new UserFollowedNotification($viewer));
        }

        return back();
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender'    => 'nullable|string|max:50',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'phone'     => 'nullable|string|max:20',
            'country'   => 'nullable|string|max:100',
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required';
            $rules['new_password']     = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'La contraseña actual no es correcta.'])
                    ->withInput()
                    ->with('open_edit_modal', true);
            }
            $user->password = bcrypt($request->new_password);
        }

        $user->name      = $request->name;
        $user->last_name = $request->last_name;
        $user->gender    = $request->gender;
        $user->email     = $request->email;
        $user->phone     = $request->phone;
        $user->country   = $request->country;
        $user->save();

        return redirect()->route('user.perfil')->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePrivacy(Request $request)
    {
        $user = auth()->user();
        $field = $request->input('field');
        $visible = (bool) $request->input('visible');

        $allowed = ['gender', 'registration_date', 'updated_at', 'email', 'phone', 'country'];
        if (!in_array($field, $allowed)) {
            return response()->json(['error' => 'Campo no permitido'], 422);
        }

        $fields = $user->public_fields ?? [];

        if ($visible) {
            if (!in_array($field, $fields)) {
                $fields[] = $field;
            }
        } else {
            $fields = array_values(array_filter($fields, fn($f) => $f !== $field));
        }

        $user->public_fields = $fields;
        $user->save();

        return response()->json(['ok' => true, 'public_fields' => $fields]);
    }

    private function buildProfileData(User $user, bool $includeInactive): array
    {
        $followers = $user->followers()->select('users.id', 'users.name', 'users.last_name', 'users.avatar')->get();
        $following = $user->following()->select('users.id', 'users.name', 'users.last_name', 'users.avatar')->get();
        $followersCount = $followers->count();
        $followingCount = $following->count();
        $recipesCount = $includeInactive
            ? $user->recipes()->count()
            : $user->recipes()->active()->count();

        $likesTotal = DB::table('favorites')
            ->join('recipes', 'favorites.recipe_id', '=', 'recipes.id')
            ->where('recipes.user_id', $user->id)
            ->count();

        $starsAverage = DB::table('recipe_ratings')
            ->join('recipes', 'recipe_ratings.recipe_id', '=', 'recipes.id')
            ->where('recipes.user_id', $user->id)
            ->avg('recipe_ratings.rating');

        $commentsRatingCounts = DB::table('recipe_comments')
            ->join('recipes', 'recipe_comments.recipe_id', '=', 'recipes.id')
            ->where('recipes.user_id', $user->id)
            ->select('recipe_comments.rating', DB::raw('count(*) as total'))
            ->groupBy('recipe_comments.rating')
            ->pluck('total', 'recipe_comments.rating');

        $commentsRating1 = (int) ($commentsRatingCounts[1] ?? 0);
        $commentsRating5 = (int) ($commentsRatingCounts[5] ?? 0);

        $comments = DB::table('recipe_comments')
            ->join('recipes', 'recipe_comments.recipe_id', '=', 'recipes.id')
            ->join('users', 'recipe_comments.user_id', '=', 'users.id')
            ->where('recipes.user_id', $user->id)
            ->select(
                'recipe_comments.rating',
                'recipe_comments.comment',
                'recipe_comments.created_at',
                'recipes.recipe_title',
                'users.name',
                'users.last_name'
            )
            ->orderByDesc('recipe_comments.created_at')
            ->get()
            ->map(function ($comment) {
                return [
                    'rating' => (int) $comment->rating,
                    'comment' => $comment->comment,
                    'created_at' => \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i'),
                    'recipe_title' => $comment->recipe_title,
                    'name' => $comment->name,
                    'last_name' => $comment->last_name,
                ];
            });

        $recipesQuery = $user->recipes()
            ->with('category')
            ->withCount('favoritedBy')
            ->withAvg('ratings', 'rating')
            ->latest();

        if (!$includeInactive) {
            $recipesQuery->active();
        }

        $recipes = $recipesQuery->get();

        $viewer = auth()->user();
        if ($viewer) {
            $favoriteIds = $viewer->favorites()->pluck('recipes.id')->flip();
            foreach ($recipes as $recipe) {
                $recipe->is_favorite = $favoriteIds->has($recipe->id);
            }
        } else {
            foreach ($recipes as $recipe) {
                $recipe->is_favorite = false;
            }
        }

        return [
            'user' => $user,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'followers' => $followers,
            'following' => $following,
            'recipesCount' => $recipesCount,
            'likesTotal' => $likesTotal,
            'starsAverage' => $starsAverage,
            'commentsRating1' => $commentsRating1,
            'commentsRating5' => $commentsRating5,
            'comments' => $comments,
            'recipes' => $recipes,
        ];
    }
}
