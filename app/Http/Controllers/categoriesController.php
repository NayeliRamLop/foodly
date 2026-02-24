<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class categoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::withCount(['recipes', 'subcategories'])->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string'
        ]);

        Categories::create($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Categoría creada exitosamente');
    }

    public function show(Categories $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Categories $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Categories $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string'
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy(Categories $category)
    {
        if($category->recipes()->count() > 0 || $category->subcategories()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la categoría porque tiene recetas o subcategorías asociadas');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Categoría eliminada exitosamente');
    }
}