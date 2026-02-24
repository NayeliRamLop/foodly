@extends('adminlte::page')

@section('title', 'Recetas para Ti - Cocina con Gusto')

@section('content_header')
    <!-- Eliminamos el header por defecto para usar nuestra animación personalizada -->
@stop

@section('content')
    <!-- Rectángulo azul grisáceo claro con título dentro -->
    <div class="blue-rectangle">
        <div class="welcome-container">
            @php
                $nombreCompleto = explode(' ', Auth::user()->name);
                $nombreMostrar = $nombreCompleto[0];
                if(count($nombreCompleto) > 1) {
                    $nombreMostrar .= ' ' . $nombreCompleto[1];
                }
            @endphp
            <h1 class="welcome-name">Recomendaciones para ti {{ $nombreMostrar }}</h1>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($recipes->isEmpty())
                    <div class="alert alert-info text-center">
                        <h4>No hay recetas recomendadas para mostrar en este momento.</h4>
                        <p class="mt-3">
                            <a href="{{ route('recipes.index') }}" class="btn btn-primary">
                                <i class="fas fa-utensils mr-2"></i>Explora todas las recetas
                            </a>
                        </p>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <h2 class="section-title">Tus recomendaciones personalizadas</h2>
                            <p class="section-subtitle">Basadas en tus preferencias y actividad</p>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($recipes as $recipe)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="{{ $recipe->image ? asset('storage/' . $recipe->image) : asset('images/default-recipe.jpg') }}" 
                                         class="card-img-top" 
                                         alt="{{ $recipe->title }}"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="card-title mb-0">{{ $recipe->title }}</h5>
                                            <span class="badge bg-warning text-dark">
                                                {{ $recipe->category->name ?? 'Sin categoría' }}
                                            </span>
                                        </div>
                                        <p class="card-text">{{ Str::limit($recipe->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $recipe->created_at->diffForHumans() }}
                                            </small>
                                            <button class="btn btn-sm btn-primary view-recipe-btn" 
                                                    data-toggle="modal" 
                                                    data-target="#recipeModal" 
                                                    data-recipe-id="{{ $recipe->id }}">
                                                Ver receta <i class="fas fa-arrow-right ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home mr-2"></i> Volver al inicio
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal para ver receta completa -->
    <div class="modal fade" id="recipeModal" tabindex="-1" role="dialog" aria-labelledby="recipeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #7a9cc6; color: white;">
                    <h4 class="modal-title" id="recipeModalLabel" style="font-size: 1.5rem;">
                        <i class="fas fa-utensils mr-2"></i> Receta Completa
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="recipeModalBody" style="font-size: 1.1rem;">
                    <!-- Contenido dinámico se cargará aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size: 1.1rem;">
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://fonts.googleapis.com/css?family=Fira+Code:400,700|Work+Sans:400,700|Roboto:100,300,400" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            background: #FBD4C5;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        .blue-rectangle {
            background-color: #D8E6F2;
            height: 250px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px 0;
        }

        .welcome-container {
            text-align: center;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome-name {
            font-family: "Work Sans", sans-serif;
            font-weight: 800;
            font-size: 72px;
            color: #355c7d;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .card {
            transition: transform 0.3s;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .section-title {
            color: #355c7d;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: #355c7d;
            border-color: #355c7d;
            padding: 10px 25px;
            font-size: 18px;
        }
        
        .btn-primary:hover {
            background-color: #2a4a66;
            border-color: #2a4a66;
        }

        .btn-outline-primary {
            color: #355c7d;
            border-color: #355c7d;
        }

        .btn-outline-primary:hover {
            background-color: #355c7d;
            color: white;
        }

        .badge.bg-warning {
            background-color: #F8B195 !important;
            color: #355c7d !important;
            font-weight: 600;
        }

        .alert-info {
            background-color: #D8E6F2;
            border-color: #b8d4f2;
            color: #355c7d;
            padding: 2rem;
            border-radius: 10px;
        }

        /* Estilos para el modal */
        .recipe-modal-img {
            max-height: 300px;
            width: auto;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
        }
        .recipe-modal-title {
            color: #7a9cc6;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .recipe-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .recipe-section-title {
            color: #7a9cc6;
            font-weight: 600;
            margin-bottom: 15px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentVideoElement = null;

            // Cargar receta en el modal al hacer clic en "Ver receta"
            $('.view-recipe-btn').on('click', function() {
                const recipeId = $(this).data('recipe-id');
                const url = "{{ route('recipes.show', ':id') }}".replace(':id', recipeId);
                
                $('#recipeModalBody').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando receta...</p>
                    </div>
                `);
                
                $.ajax({
                    url: url,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modalContent = `
                            <div class="text-center mb-4">
                                ${response.image ? 
                                    `<img src="/storage/${response.image}" class="recipe-modal-img img-fluid" alt="${response.recipe_title}">` : 
                                    `<div class="text-center py-4" style="background-color: #f8f9fa; border-radius: 8px;">
                                        <i class="fas fa-image fa-5x" style="color: #7a9cc6;"></i>
                                        <p class="mt-2">Sin imagen</p>
                                    </div>`
                                }
                            </div>
                            
                            <h3 class="recipe-modal-title">${response.recipe_title}</h3>
                            
                            <div class="recipe-section">
                                <p>${response.recipe_description}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-user-circle mr-1"></i> Creado por: ${response.user ? response.user.name : 'Administrador'}
                                    </div>
                                    <div>
                                        <span class="badge badge-pill mr-2" style="background-color: #5cb85c;">
                                            <i class="fas fa-clock"></i> ${response.preparation_time} min
                                        </span>
                                        <span class="badge badge-pill" style="background-color: #6c757d;">
                                            <i class="fas fa-utensil-spoon"></i> ${response.difficulty}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <span class="badge badge-pill mr-2" style="background-color: #7a9cc6;">
                                    <i class="fas fa-tag"></i> ${response.category.name}
                                </span>
                                ${response.subcategory ? 
                                    `<span class="badge badge-pill badge-light">
                                        <i class="fas fa-tags"></i> ${response.subcategory.name}
                                    </span>` : ''
                                }
                            </div>`;
                        
                        if (response.ingredients) {
                            modalContent += `
                            <div class="recipe-section">
                                <h5 class="recipe-section-title"><i class="fas fa-list-ul mr-1"></i> Ingredientes:</h5>
                                <ul class="pl-3">`;
                            
                            response.ingredients.split('\n').forEach(ingredient => {
                                if (ingredient.trim() !== '') {
                                    modalContent += `<li>${ingredient}</li>`;
                                }
                            });
                            
                            modalContent += `</ul></div>`;
                        }
                        
                        if (response.instructions) {
                            modalContent += `
                            <div class="recipe-section">
                                <h5 class="recipe-section-title"><i class="fas fa-list-ol mr-1"></i> Preparación:</h5>
                                <ol class="pl-3">`;
                            
                            response.instructions.split('\n').forEach(step => {
                                if (step.trim() !== '') {
                                    modalContent += `<li>${step}</li>`;
                                }
                            });
                            
                            modalContent += `</ol></div>`;
                        }
                        
                        if (response.video) {
                            modalContent += `
                            <div class="recipe-section">
                                <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <video id="recipeVideo" controls class="w-100 rounded" style="background-color: #f8f9fa;">
                                        <source src="/storage/${response.video}" type="video/mp4">
                                        Tu navegador no soporta el elemento de video.
                                    </video>
                                </div>
                            </div>`;
                        }
                        
                        $('#recipeModalBody').html(modalContent);
                        $('#recipeModalLabel').html(`<i class="fas fa-utensils mr-2"></i> ${response.recipe_title}`);
                        
                        if (response.video) {
                            currentVideoElement = document.getElementById('recipeVideo');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        $('#recipeModalBody').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i> 
                                No se pudo cargar la receta. Error: ${xhr.status} - ${xhr.statusText}
                            </div>
                        `);
                    }
                });
            });
            
            // Detener el video cuando se cierra el modal
            $('#recipeModal').on('hidden.bs.modal', function () {
                if (currentVideoElement) {
                    currentVideoElement.pause();
                    currentVideoElement.currentTime = 0;
                    currentVideoElement = null;
                }
            });
        });
    </script>
@stop