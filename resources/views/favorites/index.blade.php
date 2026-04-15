@extends('adminlte::page')

@section('title', 'MIS FAVORITOS')

@php
    $recipePayloads = $favorites->getCollection()->mapWithKeys(function ($recipe) {
        return [
            $recipe->id => [
                'id' => $recipe->id,
                'recipe_title' => $recipe->recipe_title,
                'recipe_description' => $recipe->recipe_description,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
                'preparation_time' => $recipe->preparation_time,
                'difficulty' => $recipe->difficulty,
                'category_id' => $recipe->category_id,
                'subcategory_id' => $recipe->subcategory_id,
                'brand' => $recipe->brand,
                'dish_type' => $recipe->dish_type,
                'daily_category' => $recipe->daily_category,
                'special_occasion' => $recipe->special_occasion,
                'baking_category' => $recipe->baking_category,
                'seasonality' => $recipe->seasonality,
                'preparation_method' => $recipe->preparation_method,
                'video_link' => $recipe->video_link,
                'image_url' => $recipe->image_url,
                'video_url' => $recipe->video_url,
                'video_direct_url' => $recipe->video_direct_url,
                'video_embed_url' => $recipe->video_embed_url,
                'user_id' => optional($recipe->user)->id,
            ],
        ];
    })->all();
@endphp

@section('content_header')
    <h1 class="m-0 page-title-my-recipes">MIS FAVORITOS</h1>
@stop

@section('content')
    <div class="container-fluid px-0 my-recipes-page favorites-page">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="successAlert" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>No se pudo guardar la receta.</strong>
                <ul class="mb-0 mt-2 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card filter-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('favorites.index') }}" id="favoriteFilterForm">
                    <input type="hidden" name="category_id" id="filter_category_id" value="{{ $selectedCategory }}">
                    <input type="hidden" name="brand" id="filter_brand" value="{{ $selectedFilters['brand'] ?? '' }}">
                    <input type="hidden" name="dish_type" id="filter_dish_type" value="{{ $selectedFilters['dish_type'] ?? '' }}">
                    <input type="hidden" name="daily_category" id="filter_daily_category" value="{{ $selectedFilters['daily_category'] ?? '' }}">
                    <input type="hidden" name="special_occasion" id="filter_special_occasion" value="{{ $selectedFilters['special_occasion'] ?? '' }}">
                    <input type="hidden" name="baking_category" id="filter_baking_category" value="{{ $selectedFilters['baking_category'] ?? '' }}">
                    <input type="hidden" name="seasonality" id="filter_seasonality" value="{{ $selectedFilters['seasonality'] ?? '' }}">
                    <input type="hidden" name="preparation_method" id="filter_preparation_method" value="{{ $selectedFilters['preparation_method'] ?? '' }}">

                    <div class="filter-tab-row mb-3">
                        <button type="button" class="filter-tab filter-tab-reset {{ $activeFilterKey === 'all' ? 'active' : '' }}" data-panel="all" data-reset-all="true">Todas</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'brand' ? 'active' : '' }}" data-panel="brand">Recetas con...</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'dish_type' ? 'active' : '' }}" data-panel="dish_type">Tipo de platillo</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'daily_category' ? 'active' : '' }}" data-panel="daily_category">Para todos los dias</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'special_occasion' ? 'active' : '' }}" data-panel="special_occasion">Ocasion especial</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'baking_category' ? 'active' : '' }}" data-panel="baking_category">Reposteria y panaderia</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'seasonality' ? 'active' : '' }}" data-panel="seasonality">Temporalidad</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'preparation_method' ? 'active' : '' }}" data-panel="preparation_method">Metodos de preparacion</button>
                        <button type="button" class="filter-tab {{ $activeFilterKey === 'category_id' ? 'active' : '' }}" data-panel="category_id">Categorias</button>
                    </div>

                    <div class="filter-section {{ $activeFilterKey === 'category_id' ? 'is-visible' : '' }}" data-panel="category_id">
                        <div class="filter-chip-row" data-target="filter_category_id" data-group="single">
                            @foreach($categories as $category)
                                <button type="button" class="filter-chip {{ (string) $selectedCategory === (string) $category->id ? 'active' : '' }}" data-value="{{ $category->id }}">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-section {{ $activeFilterKey === 'brand' ? 'is-visible' : '' }}" data-panel="brand">
                        <div class="filter-chip-row" data-target="filter_brand" data-group="single">
                            @foreach($brands as $brand)
                                <button type="button" class="filter-chip {{ ($selectedFilters['brand'] ?? '') === $brand ? 'active' : '' }}" data-value="{{ $brand }}">
                                    {{ $brand }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach ([
                        'dish_type' => 'dish_type',
                        'daily_category' => 'daily_category',
                        'special_occasion' => 'special_occasion',
                        'baking_category' => 'baking_category',
                        'seasonality' => 'seasonality',
                        'preparation_method' => 'preparation_method',
                    ] as $panel => $target)
                        <div class="filter-section {{ $activeFilterKey === $panel ? 'is-visible' : '' }}" data-panel="{{ $panel }}">
                            <div class="filter-chip-row" data-target="filter_{{ $target }}" data-group="single">
                                @foreach($filterOptions[$panel] as $option)
                                    <button type="button" class="filter-chip {{ ($selectedFilters[$panel] ?? '') === $option ? 'active' : '' }}" data-value="{{ $option }}">
                                        {{ $option }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>

        @if($favorites->isEmpty())
            <div class="empty-my-recipes-state">
                <div class="empty-my-recipes-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>No hay recetas favoritas</h3>
                <p>No se encontraron recetas favoritas con el filtro seleccionado. Prueba con otra opcion o vuelve a "Todas".</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($favorites as $recipe)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 recipe-card my-recipe-card">
                            @php
                                $hoverEmbedUrl = null;
                                if ($recipe->video_embed_url) {
                                    $separator = str_contains($recipe->video_embed_url, '?') ? '&' : '?';
                                    $hoverEmbedUrl = $recipe->video_embed_url . $separator . 'autoplay=1&mute=1&controls=0&playsinline=1';
                                }
                                $hoverDirectVideoUrl = $recipe->video_direct_url ?: $recipe->video_url;
                            @endphp
                            <div class="image-wrapper">
                                @if($recipe->image_url)
                                    <img src="{{ $recipe->image_url }}" class="img-fluid" alt="{{ $recipe->recipe_title }}">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
                                        <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
                                    </div>
                                @endif
                                @if($hoverDirectVideoUrl)
                                    <video class="recipe-video-preview" muted playsinline preload="metadata">
                                        <source src="{{ $hoverDirectVideoUrl }}" type="video/mp4">
                                    </video>
                                @elseif($hoverEmbedUrl)
                                    <div class="recipe-embed-preview">
                                        <iframe
                                            data-src="{{ $hoverEmbedUrl }}"
                                            title="Vista previa de video de {{ $recipe->recipe_title }}"
                                            allow="autoplay; encrypted-media; picture-in-picture"
                                            allowfullscreen
                                            referrerpolicy="strict-origin-when-cross-origin"></iframe>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ \Illuminate\Support\Str::limit($recipe->recipe_title, 48) }}</h5>
                                <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($recipe->recipe_description, 100) }}</p>
                                <p class="card-text recipe-metrics mb-0">
                                    <small>
                                        {{ $recipe->preparation_time }} min
                                        &middot; {{ $recipe->difficulty }}
                                        &middot; {{ optional($recipe->category)->name ?? 'Sin categoria' }}
                                    </small>
                                </p>
                            </div>
                            <div class="card-footer my-recipe-footer">
                                <button type="button" class="btn btn-sm view-recipe-btn" data-recipe-id="{{ $recipe->id }}">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $favorites->links() }}
            </div>
        @endif

        @include('partials.recipe-preview-modal', [
            'modalId' => 'favoritesRecipeModal',
            'titleId' => 'favoritesRecipeModalLabel',
            'bodyId' => 'favoritesRecipeModalBody',
        ])

        @include('partials.recipe-manage-modal', [
            'modalId' => 'manageFavoriteRecipeModal',
            'titleId' => 'manageFavoriteRecipeModalLabel',
            'categories' => $categories,
            'brands' => $brands,
            'filterOptions' => $filterOptions,
        ])
    </div>
@stop

@section('css')
    @include('partials.recipe-ui-styles')
    @include('partials.my-recipes-styles')
    <style>
        .filter-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
            background: linear-gradient(180deg, #fff9f4 0%, #ffffff 100%);
        }

        .filter-tab-row,
        .filter-chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .filter-tab,
        .filter-chip {
            border: 1.5px solid #ff8f43;
            background: #fff;
            color: #a74f1f;
            border-radius: 999px;
            padding: 0.75rem 1.3rem;
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .filter-tab:hover,
        .filter-tab.active,
        .filter-chip:hover,
        .filter-chip.active {
            background: #ff8f43;
            color: #fff;
            box-shadow: 0 8px 18px rgba(255, 143, 67, 0.22);
        }

        .filter-section {
            display: none;
            margin-top: 0.25rem;
        }

        .filter-section.is-visible {
            display: block;
        }

        @media (max-width: 768px) {
            .filter-tab,
            .filter-chip {
                padding: 0.65rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
@stop

@section('js')
    @include('partials.recipe-preview-modal-script')
    @include('partials.favorites-script', ['recipePayloads' => $recipePayloads])
@stop
