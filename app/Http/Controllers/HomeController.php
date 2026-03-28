<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Recipe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
{
    $this->middleware('auth')->only('index');
}
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function landing()
{
    return view('home', $this->buildHomeViewData());
}

public function index()
{
    $user = Auth::user();

    return view('home', array_merge(
        $this->buildHomeViewData(),
        [
            'recipes' => $this->getRecommendedRecipes($user),
            'nombreMostrar' => $this->getShortName($user->name),
        ]
    ));
}

    private function buildHomeViewData(): array
    {
        $categories = Categories::with('subcategories')->get();
        $popularRecipes = $this->getPopularRecipes();

        return [
            'categories' => $categories,
            'topRecipes' => $popularRecipes->take(5),
            'popularSections' => [
                'Mas guardadas' => $popularRecipes->sortByDesc('favorited_by_count')->take(4)->values(),
                'Mas comentadas' => $popularRecipes->sortByDesc('comments_count')->take(4)->values(),
                'Mejor calificadas' => $popularRecipes->sortByDesc('avg_rating')->take(4)->values(),
                'Recientes' => $popularRecipes->sortByDesc('created_at')->take(4)->values(),
            ],
        ];
    }

    private function getPopularRecipes()
    {
        return Recipe::query()
            ->where('status', 1)
            ->with(['category', 'subcategory', 'user'])
            ->withCount(['favoritedBy', 'comments', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->latest()
            ->get()
            ->map(function ($recipe) {
                $recipe->avg_rating = round((float) ($recipe->ratings_avg_rating ?? 0), 1);
                $recipe->popularity_score = ($recipe->favorited_by_count * 3)
                    + ($recipe->comments_count * 2)
                    + $recipe->ratings_count
                    + $recipe->avg_rating;

                return $recipe;
            })
            ->sortByDesc('popularity_score')
            ->values();
    }

    /**
     * Obtiene recetas recomendadas para el usuario
     */
    private function getRecommendedRecipes($user)
    {
        // Si no hay usuario, retornar colección vacía
        if (!$user) {
            return new Collection();
        }

        // Obtener IDs de categorías preferidas del usuario
        $categoryIds = DB::table('user_category_preferencias')
                       ->where('user_id', $user->id)
                       ->pluck('category_id');

        // Consulta para recetas recomendadas
        return Recipe::where('user_id', $user->id)
               ->when($categoryIds->isNotEmpty(), function($query) use ($categoryIds) {
                   $query->orWhereIn('category_id', $categoryIds);
               })
               ->with(['category', 'subcategory'])
               ->latest()
               ->take(6) // Limitar a 6 recetas para la página de inicio
               ->get();
    }

    /**
     * Obtiene el nombre corto del usuario (primer nombre + primer apellido)
     */
    private function getShortName($fullName)
    {
        $nombreCompleto = explode(' ', $fullName);
        $nombreMostrar = $nombreCompleto[0];

        if (count($nombreCompleto) > 1) {
            $nombreMostrar .= ' ' . $nombreCompleto[1];
        }

        return $nombreMostrar;
    }
}
