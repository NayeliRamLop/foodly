<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Recipe;
use App\Models\Categories;
use App\Models\Subcategory;
use App\Models\RecipeRating;
use App\Models\RecipeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes for public view
     */
  public function index()
{
    $recipes = Recipe::with(['category', 'subcategory', 'user'])
                    ->where('status', 1) // Solo recetas activas (status = 1)
                    ->latest()
                    ->paginate(10);
    
    $categories = Categories::with('subcategories')->get();
    
    return view('recipes.index', compact('recipes', 'categories'));
}

    /**
     * Public search for recipes
     */
    public function search(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        $recipesQuery = Recipe::with(['category', 'subcategory', 'user'])
            ->where('status', 1);

        if ($query !== '') {
            $recipesQuery->where(function ($builder) use ($query) {
                $builder->where('recipe_title', 'like', "%{$query}%")
                    ->orWhere('recipe_description', 'like', "%{$query}%")
                    ->orWhere('ingredients', 'like', "%{$query}%");
            });
        }

        $recipes = $recipesQuery->latest()->paginate(12)->appends(['q' => $query]);

        return view('recipes.search', [
            'recipes' => $recipes,
            'query' => $query,
        ]);
    }

    /**
     * Return recipe suggestions for the search box
     */
    public function suggest(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $suggestions = Recipe::where('status', 1)
            ->where('recipe_title', 'like', "%{$query}%")
            ->orderBy('recipe_title')
            ->limit(5)
            ->get(['id', 'recipe_title', 'image']);

        return response()->json($suggestions);
    }

    /**
     * Display admin recipes management view
     */
    public function adminIndex()
    {
        // Obtener todas las recetas con relaciones
        $recipes = Recipe::with(['category', 'subcategory', 'user'])
                        ->latest()
                        ->get();
        
        // Preparar datos para DataTables
        $recipesData = $this->prepareRecipesDataTable($recipes);
        
        return view('admin.recipes.index', [
            'recipes' => $recipesData,
            'categories' => Categories::with('subcategories')->get()
        ]);
    }

    /**
     * Prepare recipes data for DataTables
     */
    private function prepareRecipesDataTable($recipes)
    {
        return $recipes->map(function ($recipe) {
            return [
                'actions' => $this->getAdminActionButtons($recipe),
                'id' => $recipe->id,
                'title' => $recipe->recipe_title,
                'user' => $recipe->user->name ?? 'N/A',
                'category' => $recipe->category->name ?? 'N/A',
                'subcategory' => $recipe->subcategory->name ?? 'N/A',
                'created_at' => $recipe->created_at->format('d/m/Y'),
                'status' => $recipe->status ? 'Activo' : 'Inactivo'
            ];
        });
    }

    /**
     * Generate action buttons for admin view
     */
   /**
 * Generate action buttons for admin view
 */
private function getAdminActionButtons($recipe)
{
    $deleteUrl = route('admin.recipes.delete', $recipe->id);
    $toggleStatusUrl = route('recipes.toggle-status', $recipe->id);
    
    // Obtener todas las categorías con sus subcategorías
    $categories = Categories::with('subcategories')->get();
    
    // Determinar el color y el icono del botón de estado
    $statusColor = $recipe->status ? 'success' : 'danger';
    $statusIcon = $recipe->status ? 'fa-toggle-on' : 'fa-toggle-off';
    $statusTitle = $recipe->status ? 'Desactivar receta' : 'Activar receta';
    
    $return = '
    <div class="btn-group">
        <!-- Botón para abrir modal de edición -->
        <button type="button" class="btn btn-warning btn-sm" title="Editar receta" data-toggle="modal" data-target="#editRecipeModal'.$recipe->id.'">
            <i class="fas fa-edit"></i>
        </button>
        
        <!-- Botón de estado (toggle) -->
        <a href="'.$toggleStatusUrl.'" class="btn btn-'.$statusColor.' btn-sm" title="'.$statusTitle.'">
            <i class="fas '.$statusIcon.'"></i>
        </a>
        
    </div>
    
    <!-- Modal de edición -->
    <div class="modal fade" id="editRecipeModal'.$recipe->id.'" tabindex="-1" role="dialog" aria-labelledby="editRecipeModalLabel'.$recipe->id.'">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #F28241; color: white;">
                    <h4 class="modal-title" id="editRecipeModalLabel'.$recipe->id.'" style="font-size: 1.5rem;">
                        <i class="fas fa-edit mr-2"></i>Editar Receta: '.$recipe->recipe_title.'
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <form action="'.route('recipes.update', $recipe->id).'" method="POST" enctype="multipart/form-data">
                    '.csrf_field().'
                    '.method_field('PUT').'
                    <div class="modal-body" style="font-size: 1.1rem;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_title'.$recipe->id.'" style="font-size: 1.1rem;">Título de la receta *</label>
                                    <input type="text" class="form-control" id="edit_title'.$recipe->id.'" name="title" 
                                           value="'.e($recipe->recipe_title).'" required style="font-size: 1.1rem;">
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_description'.$recipe->id.'" style="font-size: 1.1rem;">Descripción *</label>
                                    <textarea class="form-control" id="edit_description'.$recipe->id.'" name="description" rows="3" required style="font-size: 1.1rem;">'.e($recipe->recipe_description).'</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_ingredients'.$recipe->id.'" style="font-size: 1.1rem;">Ingredientes *</label>
                                    <textarea class="form-control" id="edit_ingredients'.$recipe->id.'" name="ingredients" rows="5" required style="font-size: 1.1rem;">'.e($recipe->ingredients).'</textarea>
                                    <small class="form-text text-muted">Separar cada ingrediente con una nueva línea</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_instructions'.$recipe->id.'" style="font-size: 1.1rem;">Pasos de preparación *</label>
                                    <textarea class="form-control" id="edit_instructions'.$recipe->id.'" name="instructions" rows="5" required style="font-size: 1.1rem;">'.e($recipe->instructions).'</textarea>
                                    <small class="form-text text-muted">Separar cada paso con una nueva línea</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_preparation_time'.$recipe->id.'" style="font-size: 1.1rem;">Tiempo de preparación (minutos) *</label>
                                    <input type="number" class="form-control" id="edit_preparation_time'.$recipe->id.'" name="preparation_time" 
                                           value="'.$recipe->preparation_time.'" min="1" required style="font-size: 1.1rem;">
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_difficulty'.$recipe->id.'" style="font-size: 1.1rem;">Dificultad *</label>
                                    <select class="form-control" id="edit_difficulty'.$recipe->id.'" name="difficulty" required style="font-size: 1.1rem;">
                                        <option value="Fácil" '.($recipe->difficulty == 'Fácil' ? 'selected' : '').'>Fácil</option>
                                        <option value="Media" '.($recipe->difficulty == 'Media' ? 'selected' : '').'>Media</option>
                                        <option value="Difícil" '.($recipe->difficulty == 'Difícil' ? 'selected' : '').'>Difícil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_category_id'.$recipe->id.'" style="font-size: 1.1rem;">Categoría *</label>
                                    <select class="form-control" id="edit_category_id'.$recipe->id.'" name="category_id" required style="font-size: 1.1rem;">
                                        <option value="">Seleccione una categoría</option>';
    
    // Generar opciones de categorías
    foreach($categories as $category) {
        $selected = ($recipe->category_id == $category->id) ? 'selected' : '';
        $return .= '<option value="'.$category->id.'" '.$selected.'>
                        '.e($category->name).'
                    </option>';
    }
    
    $return .= '
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_subcategory_id'.$recipe->id.'" style="font-size: 1.1rem;">Subcategoría (opcional)</label>
                                    <select class="form-control" id="edit_subcategory_id'.$recipe->id.'" name="subcategory_id" style="font-size: 1.1rem;">
                                        <option value="">Seleccione una subcategoría</option>';
    
    // Generar opciones de subcategorías
    foreach($categories as $category) {
        foreach($category->subcategories as $subcategory) {
            $selected = ($recipe->subcategory_id == $subcategory->id) ? 'selected' : '';
            $return .= '<option value="'.$subcategory->id.'" 
                            data-category="'.$category->id.'" 
                            class="edit-subcategory-option" 
                            '.$selected.'>
                        '.e($subcategory->name).'
                    </option>';
        }
    }
    
    $return .= '
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size: 1.1rem;">Imagen actual</label>';
    
    if($recipe->image) {
        $return .= '<div class="mb-2" style="height: 150px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 8px;">
                        <img src="'.asset('storage/'.$recipe->image).'" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>';
    }
    
    $return .= '
                                    <label for="edit_image'.$recipe->id.'" style="font-size: 1.1rem;">Nueva imagen (opcional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="edit_image'.$recipe->id.'" name="image" accept="image/*">
                                        <label class="custom-file-label" for="edit_image'.$recipe->id.'" style="font-size: 1.1rem;">Seleccionar imagen...</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size: 1.1rem;">Video actual</label>';
    
    if($recipe->video) {
        $return .= '<div class="mb-2">
                        <video controls class="w-full rounded" style="max-height: 150px;">
                            <source src="'.asset('storage/'.$recipe->video).'" type="video/mp4">
                        </video>
                    </div>';
    } elseif($recipe->video_embed_url) {
        $return .= '<div class="mb-2 embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item rounded"
                                src="'.e($recipe->video_embed_url).'"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    </div>';
    }
    
    $return .= '
                                    <label for="edit_video'.$recipe->id.'" style="font-size: 1.1rem;">Nuevo video (opcional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="edit_video'.$recipe->id.'" name="video" accept="video/*">
                                        <label class="custom-file-label" for="edit_video'.$recipe->id.'" style="font-size: 1.1rem;">Seleccionar video...</label>
                                    </div>
                                    <label for="edit_video_link'.$recipe->id.'" class="mt-2" style="font-size: 1.1rem;">o URL de video (YouTube, TikTok, Vimeo)</label>
                                    <input type="url" class="form-control" id="edit_video_link'.$recipe->id.'" name="video_link" value="'.e($recipe->video_link).'" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size: 1.1rem;">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" style="background-color: #F28241; border-color: #F28241; font-size: 1.1rem;">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Formulario oculto para eliminar -->
    <form id="delete-form-'.$recipe->id.'" action="'.$deleteUrl.'" method="POST" style="display:none;">
        '.csrf_field().'
        '.method_field('DELETE').'
    </form>';
    
    return $return;
}
    public function adminDelete($recipeId)
    {
        try {
            $recipe = Recipe::findOrFail($recipeId);
            $recipe->status = 0; // Cambiar status a inactivo (borrado lógico)
            $recipe->save();

            return redirect()->route('admin.recipes.index')
                            ->with('success', 'Receta desactivada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al desactivar la receta: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new recipe
     */
    public function create()
    {
        $categories = Categories::with('subcategories')->get();
        return view('recipes.create', compact('categories'));
    }

    /**
     * Store a newly created recipe
     */
    public function store(Request $request)
    {
        try {
            // Validación
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|string',
                'instructions' => 'required|string',
                'preparation_time' => 'required|integer|min:1',
                'difficulty' => 'required|string|in:Fácil,Media,Difícil',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:subcategories,id,category_id,'.$request->category_id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
                'video_link' => 'nullable|url|max:2048',
            ]);

            // Procesar la receta
            $recipeData = [
                'recipe_title' => $validated['title'],
                'recipe_description' => $validated['description'],
                'ingredients' => $validated['ingredients'],
                'instructions' => $validated['instructions'],
                'preparation_time' => $validated['preparation_time'],
                'cooking_timer' => $validated['preparation_time'],
                'difficulty' => $validated['difficulty'],
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'status' => 1 // Activo por defecto
            ];

            // Subcategory es opcional
            if (isset($validated['subcategory_id']) && $validated['subcategory_id']) {
                $recipeData['subcategory_id'] = $validated['subcategory_id'];
            }

            // Procesar imagen
            if ($request->hasFile('image')) {
                $recipeData['image'] = $request->file('image')->store('recipes', 'public');
            }

            // Procesar video
            if ($request->hasFile('video')) {
                $recipeData['video'] = $request->file('video')->store('recipes/videos', 'public');
                $recipeData['video_link'] = null;
            } elseif (!empty($validated['video_link'])) {
                $recipeData['video_link'] = trim($validated['video_link']);
            }

            // Crear la receta
            $recipe = Recipe::create($recipeData);

            return redirect()->route('recipes.mis-recetas')
                            ->with('success', 'Receta creada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error al guardar la receta: '.$e->getMessage());
        }
    }

    /**
     * Display the specified recipe
     */
    public function show(Recipe $recipe)
    {
        $recipe->load('user', 'category', 'subcategory');

        $comments = $recipe->comments()
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($comment) {
                return [
                    'user' => $comment->user ? $comment->user->name : 'Usuario',
                    'rating' => $comment->rating,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->format('d/m/Y H:i'),
                ];
            });

        $ratingCounts = $recipe->comments()
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $commentRatingCounts = [];
        foreach ([1, 2, 3, 4, 5] as $rating) {
            $commentRatingCounts[$rating] = (int) ($ratingCounts[$rating] ?? 0);
        }

        $avgRating = (float) $recipe->ratings()->avg('rating');
        $videoEmbedUrl = $recipe->video_embed_url;
    
        return response()->json([
            'id' => $recipe->id,
            'recipe_title' => $recipe->recipe_title,
            'recipe_description' => $recipe->recipe_description,
            'preparation_time' => $recipe->preparation_time,
            'difficulty' => $recipe->difficulty,
            'ingredients' => $recipe->ingredients,
            'instructions' => $recipe->instructions,
            'image' => $recipe->image,
            'video' => $recipe->video,
            'video_link' => $recipe->video_link,
            'video_embed_url' => $videoEmbedUrl,
            'user' => $recipe->user ? [
                'id' => $recipe->user->id,
                'name' => $recipe->user->name,
                'last_name' => $recipe->user->last_name,
            ] : null,
            'category' => $recipe->category ? ['name' => $recipe->category->name] : null,
            'subcategory' => $recipe->subcategory ? ['name' => $recipe->subcategory->name] : null,
            'comments' => $comments,
            'comment_rating_counts' => $commentRatingCounts,
            'avg_rating' => $avgRating,
        ]);
    }

    /**
     * Show the form for editing the specified recipe
     */
    public function edit(Recipe $recipe)
    {
        $categories = Categories::with('subcategories')->get();
        return view('recipes.edit', compact('recipe', 'categories'));
    }

    /**
     * Update the specified recipe
     */
    public function update(Request $request, Recipe $recipe)
    {
        try {
            // Validar los datos del formulario
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|string',
                'instructions' => 'required|string',
                'preparation_time' => 'required|integer|min:1',
                'difficulty' => 'required|string|in:Fácil,Media,Difícil',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:subcategories,id,category_id,'.$request->category_id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
                'video_link' => 'nullable|url|max:2048',
            ]);

            // Mapear los campos del formulario a los campos reales de la base de datos
            $recipeData = [
                'recipe_title' => $validated['title'],
                'recipe_description' => $validated['description'],
                'ingredients' => $validated['ingredients'],
                'instructions' => $validated['instructions'],
                'preparation_time' => $validated['preparation_time'],
                'cooking_timer' => $validated['preparation_time'],
                'difficulty' => $validated['difficulty'],
                'category_id' => $validated['category_id'],
            ];

            // Agregar subcategory_id si existe
            if (isset($validated['subcategory_id'])) {
                $recipeData['subcategory_id'] = $validated['subcategory_id'];
            }

            // Procesar imagen si existe
            if ($request->hasFile('image')) {
                if ($recipe->image) {
                    Storage::disk('public')->delete($recipe->image);
                }
                $recipeData['image'] = $request->file('image')->store('recipes', 'public');
            }

            // Procesar video si existe
            if ($request->hasFile('video')) {
                if ($recipe->video) {
                    Storage::disk('public')->delete($recipe->video);
                }
                $recipeData['video'] = $request->file('video')->store('recipes/videos', 'public');
                $recipeData['video_link'] = null;
            } elseif (array_key_exists('video_link', $validated) && !empty($validated['video_link'])) {
                if ($recipe->video) {
                    Storage::disk('public')->delete($recipe->video);
                }
                $recipeData['video'] = null;
                $recipeData['video_link'] = trim($validated['video_link']);
            }

            $recipe->update($recipeData);

            return redirect()->route('recipes.mis-recetas', $recipe)
                            ->with('success', 'Receta actualizada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error al actualizar la receta: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified recipe (hard delete)
     */
    public function destroy(Recipe $recipe)
{
    try {
        // Verificar si la receta ya está desactivada
        if ($recipe->status == 0) {
            return redirect()->back()
                            ->with('warning', 'La receta ya está desactivada.');
        }

        // Borrado lógico - cambiar status a 0 en lugar de eliminar
        $recipe->status = 0;
        $recipe->save();

        return redirect()->route('recipes.mis-recetas')
                        ->with('success', 'Receta desactivada exitosamente.');

    } catch (\Exception $e) {
        return redirect()->back()
                        ->with('error', 'Error al desactivar la receta: '.$e->getMessage());
    }
}

    /**
     * Show personalized recipes for the user
     */
 public function paraTi()
{
    $user = auth()->user();
    
    $categories = Categories::with('subcategories')->get();
    $favoriteIds = $user ? $user->favorites()->pluck('recipes.id')->toArray() : [];
    $followingIds = $user ? $user->following()->pluck('users.id')->toArray() : [];

    $recipes = Recipe::where('status', 1) // Solo recetas activas
               ->where(function ($query) {
                   $query->whereNotNull('video')
                         ->orWhereNotNull('video_link');
               })
               ->with(['category', 'subcategory', 'user'])
               ->withCount(['favoritedBy', 'comments'])
               ->latest()
               ->get();

    $recipes = $recipes->transform(function ($recipe) use ($favoriteIds, $followingIds, $user) {
        $recipe->is_favorite = in_array($recipe->id, $favoriteIds, true);
        $recipe->is_following_author = $recipe->user && in_array($recipe->user->id, $followingIds, true);
        $recipe->is_owner = $user && $recipe->user && $recipe->user->id === $user->id;
        return $recipe;
    });
    
    return view('recipes.para-ti', compact('recipes', 'categories'));
}

    /**
     * Show recipes by category
     */
    public function porCategoria($category_id)
    {
        $category = Categories::findOrFail($category_id);
        $recipes = Recipe::where('category_id', $category_id)
                    ->with('category')
                    ->latest()
                    ->paginate(12);

        return view('recipes.por-categoria', [
            'recipes' => $recipes,
            'category' => $category
        ]);
    }

    /**
     * Show recipes by subcategory
     */
    public function porSubcategoria($subcategory_id)
    {
        $subcategory = Subcategory::findOrFail($subcategory_id);
        $recipes = Recipe::where('subcategory_id', $subcategory_id)
                    ->with(['category', 'subcategory'])
                    ->latest()
                    ->paginate(12);

        return view('recipes.por-subcategoria', [
            'recipes' => $recipes,
            'subcategory' => $subcategory
        ]);
    }

    /**
     * Show user's recipes
     */
  public function misRecetas()
{
    $recipes = Recipe::where('user_id', Auth::id())
                ->where('status', 1) // Solo recetas activas del usuario
                ->latest()
                ->paginate(10);
    
    $categories = Categories::with('subcategories')->get();

    return view('recipes.mis-recetas', compact('recipes', 'categories'));
}

    /**
     * Download recipe as PDF
     */
    public function download($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $recipe->load(['category', 'subcategory', 'user']);
            
            // Procesar imagen para compatibilidad con PDF
            if ($recipe->image) {
                $recipe->image = $this->processImageForPDF($recipe->image);
            }
            
            $pdf = PDF::loadView('recipes.pdf', compact('recipe'));
            return $pdf->download('receta-'.$recipe->recipe_title.'.pdf');
            
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Process image for PDF compatibility
     */
    private function processImageForPDF($imagePath)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            // Verificar si el archivo existe
            if (!file_exists($fullPath)) {
                return null;
            }
            
            // Obtener información del archivo
            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) {
                return null;
            }
            
            $mimeType = $imageInfo['mime'];
            
            // Si es WebP, convertir a JPEG
            if ($mimeType === 'image/webp') {
                return $this->convertWebPToJpeg($imagePath);
            }
            
            // Si es PNG con transparencia, convertir a JPEG
            if ($mimeType === 'image/png') {
                return $this->convertPNGToJpeg($imagePath);
            }
            
            // Para JPEG, GIF y otros formatos compatibles, devolver tal como está
            return $imagePath;
            
        } catch (\Exception $e) {
            // Si hay error procesando la imagen, devolver null para omitirla del PDF
            return null;
        }
    }

    /**
     * Convert WebP image to JPEG
     */
    private function convertWebPToJpeg($webpPath)
    {
        try {
            $fullPath = storage_path('app/public/' . $webpPath);
            
            if (file_exists($fullPath) && function_exists('imagecreatefromwebp')) {
                $im = imagecreatefromwebp($fullPath);
                
                if ($im !== false) {
                    $jpegPath = $this->generateConvertedImagePath($fullPath, 'jpg');
                    $success = imagejpeg($im, $jpegPath, 90);
                    imagedestroy($im);
                    
                    if ($success) {
                        return $this->getRelativePathFromFull($jpegPath);
                    }
                }
            }
            
            return null; // Si no se puede convertir, omitir imagen
            
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Convert PNG image to JPEG (removes transparency)
     */
    private function convertPNGToJpeg($pngPath)
    {
        try {
            $fullPath = storage_path('app/public/' . $pngPath);
            
            if (file_exists($fullPath) && function_exists('imagecreatefrompng')) {
                $im = imagecreatefrompng($fullPath);
                
                if ($im !== false) {
                    // Crear una imagen con fondo blanco para reemplazar transparencia
                    $width = imagesx($im);
                    $height = imagesy($im);
                    $newIm = imagecreatetruecolor($width, $height);
                    
                    // Llenar con blanco
                    $white = imagecolorallocate($newIm, 255, 255, 255);
                    imagefill($newIm, 0, 0, $white);
                    
                    // Copiar la imagen PNG encima
                    imagecopy($newIm, $im, 0, 0, 0, 0, $width, $height);
                    
                    $jpegPath = $this->generateConvertedImagePath($fullPath, 'jpg');
                    $success = imagejpeg($newIm, $jpegPath, 90);
                    
                    imagedestroy($im);
                    imagedestroy($newIm);
                    
                    if ($success) {
                        return $this->getRelativePathFromFull($jpegPath);
                    }
                }
            }
            
            // Si no se puede convertir, devolver el PNG original
            return $pngPath;
            
        } catch (\Exception $e) {
            return $pngPath;
        }
    }

    /**
     * Generate path for converted image
     */
    private function generateConvertedImagePath($originalPath, $newExtension)
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_converted.' . $newExtension;
    }

    /**
     * Get relative path from full path
     */
    private function getRelativePathFromFull($fullPath)
    {
        $publicPath = storage_path('app/public/');
        return str_replace($publicPath, '', $fullPath);
    }

public function toggleStatus($id)
{
    try {
        $recipe = Recipe::findOrFail($id);
        $recipe->status = $recipe->status == 1 ? 0 : 1; // Cambiar entre 1 y 0
        $recipe->save();

        $action = $recipe->status ? 'activada' : 'desactivada';
        return redirect()->back()->with('success', "Receta $action correctamente");
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
    }
}

    public function rate(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        RecipeRating::updateOrCreate(
            [
                'recipe_id' => $recipe->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $validated['rating'],
            ]
        );

        return response()->json([
            'success' => true,
        ]);
    }

    public function addComment(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $comment = RecipeComment::create([
            'recipe_id' => $recipe->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
        $comment->load('user');

        RecipeRating::updateOrCreate(
            [
                'recipe_id' => $recipe->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $validated['rating'],
            ]
        );

        return response()->json([
            'success' => true,
            'comment' => [
                'user' => $comment->user ? $comment->user->name : 'Usuario',
                'rating' => $comment->rating,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }
}
