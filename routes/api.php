<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Categories;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para obtener subcategorías de una categoría
Route::get('/categories/{category}/subcategories', function(Categories $category) {
    return $category->subcategories;
});