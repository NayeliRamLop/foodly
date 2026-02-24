@extends('adminlte::page')

@section('title', 'MIS RECETAS')

@section('content_header')
    <h1 class="m-0 text-dark" style="font-size: 2.8rem; font-weight: 700;">
        <i class="fas fa-utensils mr-2" style="color: #7a9cc6;"></i>MIS RECETAS
    </h1>
    <div class="mt-3">
        <button class="btn btn-primary" style="background-color: #7a9cc6; border-color: #7a9cc6; font-size: 1.1rem;" data-toggle="modal" data-target="#addRecipeModal">
            <i class="fas fa-plus mr-1"></i> Agregar Receta
        </button>
    </div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" id="successAlert" style="font-size: 1.1rem;">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" style="font-size: 1.1rem;">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    @foreach($recipes as $recipe)
    <div class="col-md-4 mb-4">
        <div class="card h-100 recipe-card" style="border-radius: 15px; border: 1px solid #e0e0e0; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;">
            <!-- Contenedor de imagen mejorado -->
            <div class="image-wrapper" style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-top-left-radius: 15px; border-top-right-radius: 15px; overflow: hidden; position: relative;">
                @if($recipe->image)
                <img src="{{ asset('storage/'.$recipe->image) }}" class="img-fluid" alt="{{ $recipe->title }}" 
                     style="max-height: 100%; max-width: 100%; width: auto; height: auto; object-fit: contain; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                @else
                <div class="text-center">
                    <i class="fas fa-image fa-3x" style="color: #7a9cc6;"></i>
                </div>
                @endif
            </div>
            
            <div class="card-body text-center">
                <h3 class="card-title" style="color: #7a9cc6; font-weight: 700; font-size: 1.4rem; margin-bottom: 0.5rem;">{{ $recipe->recipe_title }}</h3>
                <p class="card-text text-muted" style="font-size: 1.1rem;">{{ Str::limit($recipe->recipe_description, 100) }}</p>
            </div>
            
            <div class="card-footer bg-white border-top-0 pb-3">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm view-recipe-btn" style="background-color: #7a9cc6; color: white; font-size: 1.1rem; border-radius: 20px; padding: 0.25rem 1.5rem;" data-toggle="modal" data-target="#recipeModal{{ $recipe->id }}">
                        <i class="fas fa-eye mr-1"></i> VER
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar receta - Diseño Mejorado -->
    <div class="modal fade" id="recipeModal{{ $recipe->id }}" tabindex="-1" role="dialog" aria-labelledby="recipeModalLabel{{ $recipe->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #7a9cc6 0%, #5b7eb6 100%); color: white; border-top-left-radius: 15px; border-top-right-radius: 15px; padding: 1.5rem; border-bottom: none;">
                    <h3 class="modal-title" id="recipeModalLabel{{ $recipe->id }}" style="font-size: 1.8rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <i class="fas fa-utensils mr-2"></i> {{ strtoupper($recipe->recipe_title) }}
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; text-shadow: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 1.1rem; padding: 2rem;">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Sección Multimedia -->
                            <div class="multimedia-section mb-4" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                                @if($recipe->image)
                                    <div style="height: 250px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                        <img src="{{ asset('storage/' . $recipe->image) }}" class="img-fluid" alt="{{ $recipe->title }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                    </div>
                                @else
                                    <div class="text-center py-5" style="background-color: #f8f9fa; height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                        <i class="fas fa-image fa-4x mb-3" style="color: #7a9cc6;"></i>
                                    </div>
                                @endif
                                
                                @if($recipe->video)
                                    <div class="video-container p-3" style="background-color: #f8f9fa; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                                        <video id="recipeVideo{{ $recipe->id }}" controls class="w-full rounded-lg" style="max-height: 200px;">
                                            <source src="{{ asset('storage/' . $recipe->video) }}" type="video/mp4">
                                            Tu navegador no soporta el elemento de video.
                                        </video>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Sección de Información -->
                            <div class="info-section p-4" style="background-color: #f8fafc; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                <div class="info-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tag mr-2" style="color: #7a9cc6; font-size: 1.2rem;"></i>
                                        <div>
                                            <p class="mb-0" style="font-weight: 600; color: #4a5568;">CATEGORÍA</p>
                                            <p class="mb-0">{{ $recipe->category->name ?? 'Sin categoría' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock mr-2" style="color: #7a9cc6; font-size: 1.2rem;"></i>
                                        <div>
                                            <p class="mb-0" style="font-weight: 600; color: #4a5568;">TIEMPO DE PREPARACIÓN</p>
                                            <p class="mb-0">{{ $recipe->preparation_time }} minutos</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tachometer-alt mr-2" style="color: #7a9cc6; font-size: 1.2rem;"></i>
                                        <div>
                                            <p class="mb-0" style="font-weight: 600; color: #4a5568;">DIFICULTAD</p>
                                            <p class="mb-0">{{ $recipe->difficulty }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Sección de Ingredientes -->
                            <div class="ingredients-section mb-5">
                                <h5 style="color: #7a9cc6; font-weight: 700; font-size: 1.3rem; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center;">
                                    <i class="fas fa-list-ul mr-2"></i> INGREDIENTES
                                </h5>
                                <ul class="pl-5 mt-3" style="font-size: 1.15rem; list-style-type: none;">
                                    @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                                        @if(trim($ingredient))
                                            <li class="text-slate-700 mb-2" style="position: relative; padding-left: 10px;">
                                                <span style="position: absolute; left: 0; color: #7a9cc6;">•</span> 
                                                {{ ltrim($ingredient, '*.- ') }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            
                            <!-- Sección de Instrucciones -->
                            <div class="instructions-section">
                                <h5 style="color: #7a9cc6; font-weight: 700; font-size: 1.3rem; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center;">
                                    <i class="fas fa-list-ol mr-2"></i> INSTRUCCIONES
                                </h5>
                                <ol class="pl-5 mt-3" style="font-size: 1.15rem; counter-reset: step-counter; list-style-type: none;">
                                    @foreach(explode("\n", $recipe->instructions) as $instruction)
                                        @if(trim($instruction))
                                            <li class="text-slate-700 mb-3" style="position: relative; padding-left: 25px; counter-increment: step-counter;">
                                                <span style="position: absolute; left: 0; color: #7a9cc6; font-weight: bold;">
                                                    {{ $loop->iteration }}.
                                                </span> 
                                                {{ preg_replace('/^\d+\.\s*/', '', $instruction) }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; padding: 1.5rem; background-color: #f8fafc; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    <button type="button" class="btn btn-secondary rounded-full px-4 py-2" style="font-weight: 600; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> CERRAR
                    </button>
                    <button type="button" class="btn btn-warning rounded-full px-4 py-2" style="font-weight: 600; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background-color: #f6ad55; border-color: #f6ad55;" 
                            data-dismiss="modal" 
                            data-toggle="modal" 
                            data-target="#editRecipeModal{{ $recipe->id }}">
                        <i class="fas fa-edit mr-1"></i> EDITAR
                    </button>
                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-full px-4 py-2" style="font-weight: 600; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" onclick="return confirm('¿Estás seguro de querer eliminar esta receta?')">
                            <i class="fas fa-trash mr-1"></i> ELIMINAR
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar receta -->
    <div class="modal fade" id="editRecipeModal{{ $recipe->id }}" tabindex="-1" role="dialog" aria-labelledby="editRecipeModalLabel{{ $recipe->id }}">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #7a9cc6; color: white;">
                    <h4 class="modal-title" id="editRecipeModalLabel{{ $recipe->id }}" style="font-size: 1.5rem;">
                        <i class="fas fa-edit mr-2"></i>Editar Receta: {{ $recipe->recipe_title }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <form action="{{ route('recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="font-size: 1.1rem;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_title{{ $recipe->id }}" style="font-size: 1.1rem;">Título de la receta *</label>
                                    <input type="text" class="form-control" id="edit_title{{ $recipe->id }}" name="title" 
                                           value="{{ $recipe->recipe_title }}" required style="font-size: 1.1rem;">
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_description{{ $recipe->id }}" style="font-size: 1.1rem;">Descripción *</label>
                                    <textarea class="form-control" id="edit_description{{ $recipe->id }}" name="description" rows="3" required style="font-size: 1.1rem;">{{ $recipe->recipe_description }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_ingredients{{ $recipe->id }}" style="font-size: 1.1rem;">Ingredientes *</label>
                                    <textarea class="form-control" id="edit_ingredients{{ $recipe->id }}" name="ingredients" rows="5" required style="font-size: 1.1rem;">{{ $recipe->ingredients }}</textarea>
                                    <small class="form-text text-muted">Separar cada ingrediente con una nueva línea</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_instructions{{ $recipe->id }}" style="font-size: 1.1rem;">Pasos de preparación *</label>
                                    <textarea class="form-control" id="edit_instructions{{ $recipe->id }}" name="instructions" rows="5" required style="font-size: 1.1rem;">{{ $recipe->instructions }}</textarea>
                                    <small class="form-text text-muted">Separar cada paso con una nueva línea</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_preparation_time{{ $recipe->id }}" style="font-size: 1.1rem;">Tiempo de preparación (minutos) *</label>
                                    <input type="number" class="form-control" id="edit_preparation_time{{ $recipe->id }}" name="preparation_time" 
                                           value="{{ $recipe->preparation_time }}" min="1" required style="font-size: 1.1rem;">
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_difficulty{{ $recipe->id }}" style="font-size: 1.1rem;">Dificultad *</label>
                                    <select class="form-control" id="edit_difficulty{{ $recipe->id }}" name="difficulty" required style="font-size: 1.1rem;">
                                        <option value="Fácil" {{ $recipe->difficulty == 'Fácil' ? 'selected' : '' }}>Fácil</option>
                                        <option value="Media" {{ $recipe->difficulty == 'Media' ? 'selected' : '' }}>Media</option>
                                        <option value="Difícil" {{ $recipe->difficulty == 'Difícil' ? 'selected' : '' }}>Difícil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_category_id{{ $recipe->id }}" style="font-size: 1.1rem;">Categoría *</label>
                                    <select class="form-control" id="edit_category_id{{ $recipe->id }}" name="category_id" required style="font-size: 1.1rem;">
                                        <option value="">Seleccione una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $recipe->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_subcategory_id{{ $recipe->id }}" style="font-size: 1.1rem;">Subcategoría (opcional)</label>
                                    <select class="form-control" id="edit_subcategory_id{{ $recipe->id }}" name="subcategory_id" style="font-size: 1.1rem;">
                                        <option value="">Seleccione una subcategoría</option>
                                        @foreach($categories as $category)
                                            @foreach($category->subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" 
                                                        data-category="{{ $category->id }}" 
                                                        class="edit-subcategory-option" 
                                                        style="display: none;"
                                                        {{ $recipe->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size: 1.1rem;">Imagen actual</label>
                                    @if($recipe->image)
                                        <div class="mb-2" style="height: 150px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 8px;">
                                            <img src="{{ asset('storage/' . $recipe->image) }}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        </div>
                                    @endif
                                    
                                    <label for="edit_image{{ $recipe->id }}" style="font-size: 1.1rem;">Nueva imagen (opcional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="edit_image{{ $recipe->id }}" name="image" accept="image/*">
                                        <label class="custom-file-label" for="edit_image{{ $recipe->id }}" style="font-size: 1.1rem;">Seleccionar imagen...</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size: 1.1rem;">Video actual</label>
                                    @if($recipe->video)
                                        <div class="mb-2">
                                            <video controls class="w-full rounded" style="max-height: 150px;">
                                                <source src="{{ asset('storage/' . $recipe->video) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @endif
                                    
                                    <label for="edit_video{{ $recipe->id }}" style="font-size: 1.1rem;">Nuevo video (opcional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="edit_video{{ $recipe->id }}" name="video" accept="video/*">
                                        <label class="custom-file-label" for="edit_video{{ $recipe->id }}" style="font-size: 1.1rem;">Seleccionar video...</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size: 1.1rem;">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" style="background-color: #7a9cc6; border-color: #7a9cc6; font-size: 1.1rem;">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $recipes->links() }}
</div>

<!-- Modal para agregar receta -->
<div class="modal fade" id="addRecipeModal" tabindex="-1" role="dialog" aria-labelledby="addRecipeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #7a9cc6; color: white;">
                <h4 class="modal-title" id="addRecipeModalLabel" style="font-size: 1.5rem;">
                    <i class="fas fa-plus-circle mr-2"></i>Agregar Nueva Receta
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>
            <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="font-size: 1.1rem;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" style="font-size: 1.1rem;">Título de la receta *</label>
                                <input type="text" class="form-control" id="title" name="title" required style="font-size: 1.1rem;">
                            </div>
                            
                            <div class="form-group">
                                <label for="description" style="font-size: 1.1rem;">Descripción *</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required style="font-size: 1.1rem;"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="ingredients" style="font-size: 1.1rem;">Ingredientes *</label>
                                <textarea class="form-control" id="ingredients" name="ingredients" rows="5" required style="font-size: 1.1rem;" placeholder="Ejemplo:&#10;- 1 taza de harina&#10;- 2 huevos&#10;- 1 cucharada de azúcar"></textarea>
                                <small class="form-text text-muted">Separar cada ingrediente con una nueva línea</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructions" style="font-size: 1.1rem;">Pasos de preparación *</label>
                                <textarea class="form-control" id="instructions" name="instructions" rows="5" required style="font-size: 1.1rem;" placeholder="Ejemplo:&#10;1. Mezclar los ingredientes secos&#10;2. Agregar los líquidos&#10;3. Hornear a 180°C"></textarea>
                                <small class="form-text text-muted">Separar cada paso con una nueva línea</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="preparation_time" style="font-size: 1.1rem;">Tiempo de preparación (minutos) *</label>
                                <input type="number" class="form-control" id="preparation_time" name="preparation_time" min="1" required style="font-size: 1.1rem;">
                            </div>
                            
                            <div class="form-group">
                                <label for="difficulty" style="font-size: 1.1rem;">Dificultad *</label>
                                <select class="form-control" id="difficulty" name="difficulty" required style="font-size: 1.1rem;">
                                    <option value="">Seleccione...</option>
                                    <option value="Fácil">Fácil</option>
                                    <option value="Media">Media</option>
                                    <option value="Difícil">Difícil</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id" style="font-size: 1.1rem;">Categoría *</label>
                                <select class="form-control" id="category_id" name="category_id" required style="font-size: 1.1rem;">
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subcategory_id" style="font-size: 1.1rem;">Subcategoría (opcional)</label>
                                <select class="form-control" id="subcategory_id" name="subcategory_id" style="font-size: 1.1rem;">
                                    <option value="">Seleccione una subcategoría</option>
                                    @foreach($categories as $category)
                                        @foreach($category->subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" 
                                                    data-category="{{ $category->id }}" 
                                                    class="subcategory-option" 
                                                    style="display: none;">
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image" style="font-size: 1.1rem;">Imagen de la receta (opcional)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                    <label class="custom-file-label" for="image" style="font-size: 1.1rem;">Seleccionar imagen...</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="video" style="font-size: 1.1rem;">Video de la receta (opcional)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="video" name="video" accept="video/*">
                                    <label class="custom-file-label" for="video" style="font-size: 1.1rem;">Seleccionar video...</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size: 1.1rem;">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" style="background-color: #7a9cc6; border-color: #7a9cc6; font-size: 1.1rem;">
                        <i class="fas fa-save mr-1"></i> Guardar Receta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .recipe-card {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(122, 156, 198, 0.2);
        border-color: rgba(122, 156, 198, 0.4);
    }
    .recipe-card:hover .image-wrapper img {
        transform: translate(-50%, -50%) scale(1.05);
    }
    .btn {
        transition: all 0.2s;
        font-weight: 600;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .rounded-xl {
        border-radius: 12px;
    }
    .rounded-full {
        border-radius: 50px;
    }
    .custom-file-label::after {
        content: "Buscar";
    }
    .info-item {
        padding: 10px;
        border-radius: 8px;
        background-color: white;
        margin-bottom: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .multimedia-section {
        transition: all 0.3s ease;
    }
    .multimedia-section:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .view-recipe-btn:hover {
        background-color: #6a8cb6 !important;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Mostrar nombre de archivo seleccionado
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        
        // Cargar subcategorías cuando cambia la categoría (para agregar)
        $('#category_id').on('change', function() {
            var categoryId = $(this).val();
            $('.subcategory-option').hide();
            $('.subcategory-option[data-category="' + categoryId + '"]').show();
            $('#subcategory_id').val('');
        });
        
        // Cargar subcategorías cuando cambia la categoría (para editar)
        $(document).on('change', '[id^="edit_category_id"]', function() {
            var categoryId = $(this).val();
            var recipeId = $(this).attr('id').replace('edit_category_id', '');
            $('.edit-subcategory-option').hide();
            $('.edit-subcategory-option[data-category="' + categoryId + '"]').show();
            $('#edit_subcategory_id' + recipeId).val('');
        });
        
        // Inicializar subcategorías en los modales de edición
        $('[id^="editRecipeModal"]').each(function() {
            var modalId = $(this).attr('id');
            var recipeId = modalId.replace('editRecipeModal', '');
            var categoryId = $('#edit_category_id' + recipeId).val();
            
            if (categoryId) {
                $('.edit-subcategory-option[data-category="' + categoryId + '"]').show();
            }
        });
        
        // Resetear el modal cuando se cierra
        $('#addRecipeModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
            $(this).find('.custom-file-label').html('Seleccionar archivo...');
            $('.subcategory-option').hide();
            $('#subcategory_id').val('');
        });
        
        // Ocultar alerta de éxito
        if ($('#successAlert').length) {
            setTimeout(function() {
                $('#successAlert').alert('close');
            }, 3000);
        }
        
        // Pausar video cuando se cierra el modal
        $('[id^="recipeModal"]').on('hidden.bs.modal', function () {
            var modalId = $(this).attr('id');
            var recipeId = modalId.replace('recipeModal', '');
            var videoElement = document.getElementById('recipeVideo' + recipeId);
            if (videoElement) {
                videoElement.pause();
            }
        });
    });
</script>
@stop