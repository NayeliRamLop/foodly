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
            <h1 class="welcome-name">Recetas para ti, {{ $nombreMostrar }}</h1>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <!-- Barra de acciones -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Dropdown de categorías -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter mr-2"></i>Filtrar por categoría
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach($categorias as $categoria)
                                <a class="dropdown-item" href="{{ route('recetas.por-categoria', $categoria->id) }}">
                                    <i class="fas fa-utensils mr-2"></i>{{ $categoria->nombre }}
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('recipes.para-ti') }}">
                                <i class="fas fa-sync-alt mr-2"></i>Mostrar todas
                            </a>
                        </div>
                    </div>
                    
                    <!-- Botón para agregar receta -->
                    <a href="{{ route('recetas.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle mr-2"></i>Agregar Receta
                    </a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($recetas->isEmpty())
                    <div class="alert alert-info text-center">
                        <h4>No hay recetas recomendadas para mostrar en este momento.</h4>
                        <p class="mt-3">
                            <a href="{{ route('recetas.index') }}" class="btn btn-primary">
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
                        @foreach($recetas as $receta)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="{{ $receta->imagen ? asset('storage/' . $receta->imagen) : asset('images/default-receta.jpg') }}" 
                                         class="card-img-top" 
                                         alt="{{ $receta->titulo }}"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="card-title mb-0">{{ $receta->titulo }}</h5>
                                            <span class="badge bg-warning text-dark">
                                                {{ $receta->categoria->nombre ?? 'Sin categoría' }}
                                            </span>
                                        </div>
                                        <p class="card-text">{{ Str::limit($receta->descripcion, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $receta->created_at->diffForHumans() }}
                                            </small>
                                            <a href="{{ route('recipes.show', $receta->id) }}" class="btn btn-sm btn-primary">
                                                Ver receta <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
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
@stop

@section('css')
    <link href="https://fonts.googleapis.com/css?family=Fira+Code:400,700|Work+Sans:400,700|Roboto:100,300,400" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        /* Estilos heredados de home.blade.php */
        body {
            background: #FBD4C5;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Rectángulo azul grisáceo claro con título */
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

        /* Estilos para el título de bienvenida */
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

        /* Contenedor principal para el resto del contenido */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Estilos para las tarjetas de recetas */
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

        .btn-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-success:hover {
            background-color: #3d8b40;
            border-color: #3d8b40;
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

        /* Estilos para el dropdown de categorías */
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: none;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            color: #355c7d;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: #D8E6F2;
            color: #355c7d;
        }

        .dropdown-divider {
            border-color: #D8E6F2;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery necesario para el dropdown de AdminLTE -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
@stop