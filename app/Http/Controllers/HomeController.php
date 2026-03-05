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
    $this->middleware('auth');
}
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index()
{
    $categories = Categories::with('subcategories')->get();
    $user = Auth::user();
    $recipes = $this->getRecommendedRecipes($user);

    return view('home', [
        'categories' => $categories,
        'recipes' => $recipes,
        'nombreMostrar' => $this->getShortName($user->name)
    ]);
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
