<?php

namespace App\Http\Controllers;

use App\Notifications\RecipeLikedNotification;
use App\Models\Recipe;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class FavoriteController extends Controller
{
    private function getRecipeFilterOptions(): array
    {
        return [
            'brand' => $this->getAvailableBrands()->all(),
            'dish_type' => ['Desayuno', 'Comida', 'Cena', 'Entrada', 'Botana', 'Sopa', 'Ensalada', 'Bebida'],
            'daily_category' => ['Rapidas', 'Economicas', 'Saludables', 'Faciles', 'Familiares'],
            'special_occasion' => ['Cumpleanos', 'Navidad', 'Ano Nuevo', 'Fiestas patrias', 'San Valentin', 'Cuaresma'],
            'baking_category' => ['Pasteles', 'Galletas', 'Pan dulce', 'Pan salado', 'Postres'],
            'seasonality' => ['Primavera', 'Verano', 'Otono', 'Invierno', 'Todo el ano'],
            'preparation_method' => ['Horno', 'Sarten', 'Parrilla', 'Sin coccion', 'Freidora de aire', 'Olla', 'Licuadora'],
        ];
    }

    private function getAvailableBrands()
    {
        return collect([
            'Nestle',
            'Herdez',
            'McCormick',
            'Knorr',
            'Maggi',
            'La Costena',
            'Gamesa',
            'Lala',
            'Philadelphia',
            'Del Fuerte',
            'Great Value',
            'Otra',
        ]);
    }

    public function index(Request $request)
    {
        $filterOptions = $this->getRecipeFilterOptions();
        $selectedCategory = trim((string) $request->query('category_id', ''));
        $availableColumns = [
            'brand' => Schema::hasColumn('recipes', 'brand'),
            'dish_type' => Schema::hasColumn('recipes', 'dish_type'),
            'daily_category' => Schema::hasColumn('recipes', 'daily_category'),
            'special_occasion' => Schema::hasColumn('recipes', 'special_occasion'),
            'baking_category' => Schema::hasColumn('recipes', 'baking_category'),
            'seasonality' => Schema::hasColumn('recipes', 'seasonality'),
            'preparation_method' => Schema::hasColumn('recipes', 'preparation_method'),
        ];

        $selectedFilters = [];
        foreach ($availableColumns as $column => $exists) {
            $selectedFilters[$column] = $exists ? trim((string) $request->query($column, '')) : '';
        }

        $activeFilterKey = 'all';
        if ($selectedCategory !== '') {
            $activeFilterKey = 'category_id';
        }

        foreach (array_keys($availableColumns) as $column) {
            if (($selectedFilters[$column] ?? '') !== '') {
                $activeFilterKey = $column;
                $selectedCategory = '';
                break;
            }
        }

        if ($activeFilterKey !== 'category_id') {
            $selectedCategory = '';
        }

        foreach (array_keys($availableColumns) as $column) {
            if ($column !== $activeFilterKey) {
                $selectedFilters[$column] = '';
            }
        }

        $favoritesQuery = Auth::user()->favorites()
            ->select('recipes.*')
            ->with(['category', 'subcategory', 'user'])
            ->where('recipes.status', 1)
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('recipes.category_id', $selectedCategory);
            })
            ->when($selectedFilters['brand'] !== '', fn ($query) => $query->where('recipes.brand', $selectedFilters['brand']))
            ->when($selectedFilters['dish_type'] !== '', fn ($query) => $query->where('recipes.dish_type', $selectedFilters['dish_type']))
            ->when($selectedFilters['daily_category'] !== '', fn ($query) => $query->where('recipes.daily_category', $selectedFilters['daily_category']))
            ->when($selectedFilters['special_occasion'] !== '', fn ($query) => $query->where('recipes.special_occasion', $selectedFilters['special_occasion']))
            ->when($selectedFilters['baking_category'] !== '', fn ($query) => $query->where('recipes.baking_category', $selectedFilters['baking_category']))
            ->when($selectedFilters['seasonality'] !== '', fn ($query) => $query->where('recipes.seasonality', $selectedFilters['seasonality']))
            ->when($selectedFilters['preparation_method'] !== '', fn ($query) => $query->where('recipes.preparation_method', $selectedFilters['preparation_method']));

        $favorites = $favoritesQuery
            ->distinct()
            ->latest('recipes.created_at')
            ->paginate(10)
            ->appends(array_merge(['category_id' => $selectedCategory], $selectedFilters));

        $categories = Categories::with('subcategories')->get();
        $brands = $availableColumns['brand']
            ? Auth::user()->favorites()
                ->select('recipes.brand')
                ->where('recipes.status', 1)
                ->whereNotNull('recipes.brand')
                ->where('recipes.brand', '!=', '')
                ->distinct()
                ->orderBy('recipes.brand')
                ->pluck('recipes.brand')
            : collect();

        $brands = $this->getAvailableBrands()
            ->merge($brands)
            ->unique()
            ->values();

        $filterOptions['brand'] = $brands->all();

        return view('favorites.index', [
            'favorites' => $favorites,
            'categories' => $categories,
            'brands' => $brands,
            'selectedCategory' => $selectedCategory,
            'selectedBrand' => $selectedFilters['brand'],
            'selectedFilters' => $selectedFilters,
            'filterOptions' => $filterOptions,
            'activeFilterKey' => $activeFilterKey,
        ]);
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
