<?php

namespace App\Http\Controllers;

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

        Auth::user()->favorites()->toggle($recipe->id);
        
        return response()->json([
            'success' => true,
            'is_favorite' => Auth::user()->favorites()->where('recipe_id', $recipe->id)->exists()
        ]);
    }
}