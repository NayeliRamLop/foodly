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
                ->where('status', 1)
                ->where(function ($builder) use ($query) {
                    $builder->where('recipe_title', 'like', "%{$query}%")
                        ->orWhere('recipe_description', 'like', "%{$query}%");
                })
                ->latest()
                ->take(12)
                ->get();

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
}
