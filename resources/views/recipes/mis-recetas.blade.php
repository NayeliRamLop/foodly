@extends('adminlte::page')

@section('title', 'MIS RECETAS')

@php
    $recipePayloads = $recipes->getCollection()->mapWithKeys(function ($recipe) {
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
            ],
        ];
    })->all();
@endphp

@section('content_header')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-recipes-header">
        <h1 class="m-0 page-title-my-recipes">MIS RECETAS</h1>
        <button type="button" class="btn view-recipe-btn add-recipe-trigger">
            <i class="fas fa-plus mr-1"></i> Agregar receta
        </button>
    </div>
@stop

@section('content')
    <div class="container-fluid px-0 my-recipes-page">
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

        @if($recipes->isEmpty())
            <div class="empty-my-recipes-state">
                <div class="empty-my-recipes-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>Aun no tienes recetas publicadas</h3>
                <p>Crea tu primera receta y administrala desde aqui con el mismo formato visual del resto del sistema.</p>
                <button type="button" class="btn view-recipe-btn add-recipe-trigger">
                    <i class="fas fa-plus mr-1"></i> Crear mi primera receta
                </button>
            </div>
        @else
            <div class="row g-4">
                @foreach($recipes as $recipe)
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
                {{ $recipes->links() }}
            </div>
        @endif

        @include('partials.recipe-preview-modal', [
            'modalId' => 'myRecipesPreviewModal',
            'titleId' => 'myRecipesPreviewModalLabel',
            'bodyId' => 'myRecipesPreviewModalBody',
        ])

        @include('partials.recipe-manage-modal', [
            'modalId' => 'manageRecipeModal',
            'titleId' => 'manageRecipeModalLabel',
            'categories' => $categories,
            'brands' => $brands,
            'filterOptions' => $filterOptions,
        ])
    </div>
@stop

@section('css')
    @include('partials.recipe-ui-styles')
    @include('partials.my-recipes-styles')
@stop

@section('js')
    @include('partials.recipe-preview-modal-script')
    @include('partials.my-recipes-script', ['recipePayloads' => $recipePayloads])
@stop
