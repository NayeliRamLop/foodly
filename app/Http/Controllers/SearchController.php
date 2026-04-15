<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        $recipes = collect();
        $users = collect();

        if ($query !== '') {
            $recipes = Recipe::with('user')
                ->withCount(['favoritedBy', 'comments'])
                ->withAvg('ratings', 'rating')
                ->where('status', 1)
                ->where(function ($builder) use ($query) {
                    $builder->where('recipe_title', 'like', "%{$query}%")
                        ->orWhere('recipe_description', 'like', "%{$query}%");
                })
                ->latest()
                ->take(12)
                ->get()
                ->map(function ($recipe) {
                    $recipe->avg_rating = round((float) ($recipe->ratings_avg_rating ?? 0), 1);
                    return $recipe;
                });

            $users = User::where('status', 1)
                ->where(function ($builder) use ($query) {
                    $builder->where('name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->orderBy('name')
                ->take(12)
                ->get();
        }

        return view('search.index', [
            'query' => $query,
            'recipes' => $recipes,
            'users' => $users,
        ]);
    }

    public function suggest(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $recipes = Recipe::where('status', 1)
            ->where('recipe_title', 'like', "%{$query}%")
            ->orderBy('recipe_title')
            ->limit(5)
            ->get(['id', 'recipe_title', 'image'])
            ->map(function ($recipe) {
                return [
                    'type' => 'recipe',
                    'id' => $recipe->id,
                    'label' => $recipe->recipe_title,
                    'image' => $recipe->image,
                ];
            });

        $users = User::where('status', 1)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(5)
            ->get(['id', 'name', 'last_name', 'avatar'])
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'id' => $user->id,
                    'label' => trim($user->name.' '.($user->last_name ?? '')),
                    'image' => $user->avatar_url,
                ];
            });

        return response()->json(
            $recipes->concat($users)->take(8)->values()
        );
    }
}
