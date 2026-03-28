<?php

namespace App\Http\Controllers;

use App\Notifications\RecipeLikedNotification;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
{
    $favorites = Auth::user()->favorites()
                    ->where('status', 1) // Solo recetas activas
                    ->paginate(12);
    
    return view('favorites.index', compact('favorites'));
}

    public function store(Recipe $recipe)
    {
        Auth::user()->favorites()->syncWithoutDetaching([$recipe->id]);
        return back()->with('success', 'Receta agregada a favoritos');
    }

    public function destroy(Recipe $recipe)
    {
        Auth::user()->favorites()->detach($recipe->id);
        return back()->with('success', 'Receta eliminada de favoritos');
    }

    public function toggle(Recipe $recipe)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $wasFavorite = $user->favorites()->where('recipe_id', $recipe->id)->exists();
        $user->favorites()->toggle($recipe->id);
        $isFavorite = $user->favorites()->where('recipe_id', $recipe->id)->exists();

        if (!$wasFavorite && $isFavorite && $recipe->user && $recipe->user->id !== $user->id) {
            $recipe->user->notify(new RecipeLikedNotification($user, $recipe));
        }
        
        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite
        ]);
    }
}
