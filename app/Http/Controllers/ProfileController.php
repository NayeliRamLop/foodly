<?php

namespace App\Http\Controllers;

use App\Notifications\UserFollowedNotification;
use Illuminate\Http\Request;
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
        $data = $this->buildProfileData($user, false);
        $viewer = auth()->user();

        $data['isOwner'] = $viewer && $viewer->id === $user->id;
        $data['isFollowing'] = $viewer
            ? $viewer->following()->where('user_id', $user->id)->exists()
            : false;

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
            ->withCount('favoritedBy')
            ->withAvg('ratings', 'rating')
            ->latest();

        if (!$includeInactive) {
            $recipesQuery->active();
        }

        $recipes = $recipesQuery->get();

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
