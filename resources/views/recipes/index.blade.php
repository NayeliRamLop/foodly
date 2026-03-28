@extends('adminlte::page')

@section('title', 'RECETAS - COCINA CON GUSTO')

@section('content_header')
    <h1 class="m-0" style="font-size: 4rem; font-weight: 700; color: #F28241; text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.6); letter-spacing: 0.05em; margin-bottom: 1.5rem;">
        RECETAS
        <a href="{{ route('favorites.index') }}" class="btn btn-outline-danger float-right" style="font-size: 1rem;">
            <i class="fas fa-heart mr-1"></i> Mis Favoritos
        </a>
    </h1>
@stop

@section('content')
<!-- Notificación temporal -->
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

<div class="card filter-card mb-4">
    <div class="card-body">
        @php
            $activePanel = 'all';
            foreach (['brand', 'dish_type', 'daily_category', 'special_occasion', 'baking_category', 'seasonality', 'preparation_method'] as $panelKey) {
                if (!empty($selectedFilters[$panelKey] ?? null)) {
                    $activePanel = $panelKey;
                    break;
                }
            }
            if ($activePanel === 'all' && !empty($selectedCategory)) {
                $activePanel = 'category_id';
            }
        @endphp
        <form method="GET" action="{{ route('recipes.index') }}" class="row align-items-end" id="recipeFilterForm">
            <input type="hidden" name="dish_type" id="dish_type" value="{{ $selectedFilters['dish_type'] ?? '' }}">
            <input type="hidden" name="daily_category" id="daily_category" value="{{ $selectedFilters['daily_category'] ?? '' }}">
            <input type="hidden" name="special_occasion" id="special_occasion" value="{{ $selectedFilters['special_occasion'] ?? '' }}">
            <input type="hidden" name="baking_category" id="baking_category" value="{{ $selectedFilters['baking_category'] ?? '' }}">
            <input type="hidden" name="seasonality" id="seasonality" value="{{ $selectedFilters['seasonality'] ?? '' }}">
            <input type="hidden" name="preparation_method" id="preparation_method" value="{{ $selectedFilters['preparation_method'] ?? '' }}">
            <div class="col-12 mb-3">
                <div class="filter-tab-row mb-3">
                    <button type="button" class="filter-tab filter-tab-reset {{ empty($selectedCategory) && empty(array_filter($selectedFilters ?? [])) ? 'active' : '' }}" data-panel="all" data-reset-all="true">Todas</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'brand' ? 'active' : '' }}" data-panel="brand">Recetas con...</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'dish_type' ? 'active' : '' }}" data-panel="dish_type">Tipo de platillo</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'daily_category' ? 'active' : '' }}" data-panel="daily_category">Para todos los dias</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'special_occasion' ? 'active' : '' }}" data-panel="special_occasion">Ocasion especial</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'baking_category' ? 'active' : '' }}" data-panel="baking_category">Reposteria y panaderia</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'seasonality' ? 'active' : '' }}" data-panel="seasonality">Temporalidad</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'preparation_method' ? 'active' : '' }}" data-panel="preparation_method">Metodos de preparacion</button>
                    <button type="button" class="filter-tab {{ $activePanel === 'category_id' ? 'active' : '' }}" data-panel="category_id">Categorias</button>
                </div>
                <div class="filter-section {{ $activePanel === 'category_id' ? 'is-visible' : '' }}" data-panel="category_id">
                    <div class="filter-chip-row" data-target="category_id">
                        @foreach($categories as $category)
                            <button type="button" class="filter-chip {{ (string) $selectedCategory === (string) $category->id ? 'active' : '' }}" data-value="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="filter-section mt-3 {{ $activePanel === 'brand' ? 'is-visible' : '' }}" data-panel="brand">
                    <div class="filter-chip-row" data-target="brand">
                        @foreach($brands as $brand)
                            <button type="button" class="filter-chip {{ $selectedBrand === $brand ? 'active' : '' }}" data-value="{{ $brand }}">
                                {{ $brand }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="filter-section mt-3 {{ $activePanel === 'dish_type' ? 'is-visible' : '' }}" data-panel="dish_type">
                    <div class="filter-chip-row" data-target="dish_type">
                        @foreach($filterOptions['dish_type'] as $option)
                            <button type="button" class="filter-chip {{ ($selectedFilters['dish_type'] ?? '') === $option ? 'active' : '' }}" data-value="{{ $option }}">{{ $option }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="filter-section mt-3 {{ $activePanel === 'daily_category' ? 'is-visible' : '' }}" data-panel="daily_category">
                    <div class="filter-chip-row" data-target="daily_category">
                        @foreach($filterOptions['daily_category'] as $option)
                            <button type="button" class="filter-chip {{ ($selectedFilters['daily_category'] ?? '') === $option ? 'active' : '' }}" data-value="{{ $option }}">{{ $option }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="filter-section mt-3 {{ $activePanel === 'special_occasion' ? 'is-visible' : '' }}" data-panel="special_occasion">
                    <div class="filter-chip-row" data-target="special_occasion">
                        @foreach($filterOptions['special_occasion'] as $option)
                            <button type="button" class="filter-chip {{ ($selectedFilters['special_occasion'] ?? '') === $option ? 'active' : '' }}" data-value="{{ $option }}">{{ $option }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="filter-section mt-3 {{ $activePanel === 'baking_category' ? 'is-visible' : '' }}" data-panel="baking_category">
                    <div class="filter-chip-row" data-target="baking_category">
                        @foreach($filterOptions['baking_category'] as $option)
                            <button type="button" class="filter-chip {{ ($selectedFilters['baking_category'] ?? '') === $option ? 'active' : '' }}" data-value="{{ $option }}">{{ $option }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="filter-section mt-3 {{ $activePanel === 'seasonality' ? 'is-visible' : '' }}" data-panel="seasonality">
                    <div class="filter-chip-row" data-target="seasonality">
                        @foreach($filterOptions['seasonality'] as $option)
                            <button type="button" class="filter-chip {{ ($selectedFilters['seasonality'] ?? '') === $option ? 'active' : '' }}" data-value="{{ $option }}">{{ $option }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="filter-section mt-3 {{ $activePanel === 'preparation_method' ? 'is-visible' : '' }}" data-panel="preparation_method">
                    <div class="filter-chip-row" data-target="preparation_method">
                        @foreach($filterOptions['preparation_method'] as $option)
                            <button type="button" class="filter-chip {{ ($selectedFilters['preparation_method'] ?? '') === $option ? 'active' : '' }}" data-value="{{ $option }}">{{ $option }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-3 mb-md-0">
                <label for="category_id" class="filter-label">Categoría</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5 mb-3 mb-md-0">
                <label for="brand" class="filter-label">Marca</label>
                <select name="brand" id="brand" class="form-control">
                    <option value="">Todas las marcas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ $selectedBrand === $brand ? 'selected' : '' }}>
                            {{ $brand }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-filter-action w-100">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row recipes-page">
    @foreach($recipes as $recipe)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 recipe-card" data-recipe-id="{{ $recipe->id }}">
            @php
                $hoverEmbedUrl = null;
                if ($recipe->video_embed_url) {
                    $separator = str_contains($recipe->video_embed_url, '?') ? '&' : '?';
                    $hoverEmbedUrl = $recipe->video_embed_url . $separator . 'autoplay=1&mute=1&controls=0&playsinline=1';
                }
                $hoverDirectVideoUrl = $recipe->video_direct_url;
            @endphp
            <!-- Contenedor flexible para la imagen -->
            <div class="image-wrapper" style="height: 340px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-top-left-radius: 12px; border-top-right-radius: 12px; position: relative; overflow: hidden;">
                @if($recipe->image)
                <img src="{{ asset('storage/'.$recipe->image) }}" class="img-fluid" alt="{{ $recipe->recipe_title }}" style="max-height: 100%; max-width: 100%; object-fit: scale-down;">
                @else
                <div class="text-center">
                    <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
                    <p class="mt-2" style="font-size: 1.1rem;">Sin imagen</p>
                </div>
                @endif
                @if($recipe->video)
                <video class="recipe-video-preview" muted playsinline preload="metadata">
                    <source src="{{ asset('storage/'.$recipe->video) }}" type="video/mp4">
                </video>
                @elseif($hoverDirectVideoUrl)
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
                
                <!-- Corazón de favoritos -->
                <button class="btn-favorite" data-recipe-id="{{ $recipe->id }}" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.7); border: none; border-radius: 50%; width: 36px; height: 36px; padding: 0; z-index: 10;">
                    <i class="fas fa-heart {{ auth()->check() && auth()->user()->favorites->contains($recipe->id) ? 'text-danger' : 'text-secondary' }}" style="font-size: 1.2rem;"></i>
                </button>
            </div>
            
            <div class="card-body">
                <h5 class="card-title">{{ Str::limit($recipe->recipe_title, 40) }}</h5>
                @if($recipe->brand)
                    <p class="recipe-brand mb-2">{{ $recipe->brand }}</p>
                @endif
                <p class="card-text text-muted">{{ Str::limit($recipe->recipe_description, 70) }}</p>
            </div>
            
            <!-- Botones de acción simplificados -->
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm view-recipe-btn" data-toggle="modal" data-target="#recipeModal" data-recipe-id="{{ $recipe->id }}">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal para ver receta completa -->
<div class="modal fade" id="recipeModal" tabindex="-1" role="dialog" aria-labelledby="recipeModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F28241; color: white;">
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
<style>
    body {
        font-size: 1.1rem;
    }
    .filter-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(0,0,0,0.06);
    }
    .filter-card .col-md-5,
    .filter-card .col-md-2 {
        display: none;
    }
    .filter-tab-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }
    .filter-tab {
        border: 1.5px solid #c2185b;
        background: #fff;
        color: #6e1739;
        border-radius: 999px;
        padding: 0.75rem 1.3rem;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1;
        transition: all 0.2s ease;
    }
    .filter-tab:hover,
    .filter-tab.active {
        background: #c2185b;
        color: #fff;
        box-shadow: 0 8px 18px rgba(194, 24, 91, 0.18);
    }
    .filter-section {
        margin-bottom: 0.25rem;
        display: none;
    }
    .filter-section.is-visible {
        display: block;
    }
    .filter-label {
        display: block;
        font-weight: 700;
        color: #2f2f2f;
        margin-bottom: 0.75rem;
        font-size: 1.05rem;
    }
    .filter-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }
    .filter-chip {
        border: 1.5px solid #c2185b;
        background: #fff;
        color: #6e1739;
        border-radius: 999px;
        padding: 0.7rem 1.35rem;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1;
        transition: all 0.2s ease;
    }
    .filter-chip:hover,
    .filter-chip.active {
        background: #c2185b;
        color: #fff;
        box-shadow: 0 8px 18px rgba(194, 24, 91, 0.18);
    }
    .btn-filter-action {
        background-color: #F28241;
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
    }
    .btn-filter-action:hover {
        color: #fff;
        background-color: #da6d30;
    }
    .recipe-brand {
        color: #a85d2d;
        font-weight: 600;
        text-align: center;
        font-size: 0.95rem;
    }
    @media (max-width: 768px) {
        .filter-chip-row {
            gap: 0.55rem;
        }
        .filter-chip {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
    }
    
    .recipe-card {
        transition: all 0.25s ease;
        border-radius: 16px;
        border: 1px solid #eee;
        box-shadow: 0 12px 24px rgba(0,0,0,0.06);
        overflow: hidden;
        background: var(--bg-soft, #fff6e9); /* same tono claro que top recetas */
        max-width: 100%;
    }
    .recipes-page {
        row-gap: 10px;
    }
    .recipes-page .recipe-card {
        width: calc(100% - 50px);
        margin-left: auto;
        margin-right: auto;
    }
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(var(--primary-rgb, 65,89,29), 0.15);
        border-color: rgba(var(--primary-rgb, 65,89,29), 0.3);
    }
    .recipe-video-preview {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
        background: #000;
    }
    .recipe-card:hover .recipe-video-preview {
        opacity: 1;
    }
    .recipe-embed-preview {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
        background: #000;
    }
    .recipe-embed-preview iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }
    .recipe-card:hover .recipe-embed-preview {
        opacity: 1;
    }
    .card-title {
        font-size: 1.3rem;
        margin-bottom: 0.75rem;
        color: var(--primary); /* verde destacado */
        text-align: center;
    }
    .image-wrapper {
        padding: 0;
    }
    .fade-out {
        animation: fadeOut 2s ease-in-out;
        opacity: 0;
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
        font-size: 1.1rem;
    }
    .view-recipe-btn {
        background-color: var(--primary);
        color: #fff;
    }
    .view-recipe-btn:hover {
        background-color: color-mix(in srgb, var(--primary) 85%, black);
    }
    .btn:hover {
        transform: translateY(-2px);
    }
    .card-footer {
        padding: 0.6rem 1rem 1rem;
        background: #fff;
    }
    .card-body {
        min-height: 140px;
    }
    .recipe-card .btn {
        font-size: 0.95rem;
        padding: 0.45rem 0.9rem;
    }
    .recipe-modal-img {
        max-height: 300px;
        width: auto;
        display: block;
        margin: 0 auto;
        border-radius: 8px;
    }
    .recipe-modal-media {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .recipe-modal-media-content {
        width: 100%;
        text-align: center;
    }
    .modal-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
        height: 34px;
        border: 1px solid #e0e0e0;
        border-radius: 999px;
        background: rgba(255,255,255,0.92);
        color: #a85d2d;
        z-index: 5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .modal-nav-btn.left {
        left: 8px;
    }
    .modal-nav-btn.right {
        right: 8px;
    }
    .modal-nav-btn:hover:not(:disabled) {
        background: #D2691E;
        color: #fff;
    }
    .modal-nav-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }
    .recipe-modal-title {
        color: #F28241;
        font-weight: 600;
        margin-bottom: 20px;
        text-align: center;
    }
    .recipe-video-wrap {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
    }
    .recipe-video-player {
        width: 100%;
        height: 56vh;
        min-height: 360px;
        max-height: 680px;
        background-color: #000;
        border-radius: 10px;
        object-fit: contain;
    }
    .recipe-section {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .recipe-section-title {
        color: #F28241;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .btn-favorite {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.7);
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        padding: 0;
        z-index: 10;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-favorite:hover {
        background: rgba(255,255,255,0.9);
    }
    .btn-favorite i {
        font-size: 1.2rem;
        transition: all 0.2s ease;
    }
    .btn-favorite:hover i {
        transform: scale(1.2);
    }
    .comment-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .comment-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
        border: 1px solid #e9ecef;
    }
    .comment-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.3rem;
    }
    .comment-rating {
        font-size: 0.95rem;
        color: #D2691E;
        margin-bottom: 0.35rem;
    }
    .comment-text {
        font-size: 0.95rem;
        color: #2f2f2f;
    }
    .comment-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    .comment-filter {
        border: 1px solid #f1c29c;
        background: #fff2e6;
        color: #a85d2d;
        border-radius: 999px;
        padding: 0.2rem 0.7rem;
        font-size: 0.85rem;
        cursor: pointer;
    }
    .comment-filter.active,
    .comment-filter:hover {
        background: #D2691E;
        color: #ffffff;
    }
    .comment-rating-picker {
        display: flex;
        gap: 0.4rem;
    }
    .comment-star {
        border: 1px solid #f1c29c;
        background: #fff2e6;
        color: #d28c57;
        border-radius: 6px;
        font-size: 1.1rem;
        padding: 0.2rem 0.5rem;
        cursor: pointer;
    }
    .comment-star.active,
    .comment-star:hover {
        background: #D2691E;
        color: #ffffff;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        let currentVideoElement = null;
        const recipeIds = $('.view-recipe-btn').map(function() {
            return Number($(this).data('recipe-id'));
        }).get();
        const initialRecipeId = Number(@json(request('open_recipe')));
        let currentRecipeIndex = -1;
        const profileBaseUrl = "{{ url('/perfil') }}";
        const canRate = @json(auth()->check());
        const canComment = @json(auth()->check());

        $(document).on('click', '.filter-chip-row .filter-chip', function() {
            const button = $(this);
            const group = button.closest('.filter-chip-row');
            const target = group.data('target');

            if (group.data('reset-all')) {
                $('#recipeFilterForm').find('input[type="hidden"], select').val('');
                $('.filter-chip-row').each(function() {
                    const row = $(this);
                    row.find('.filter-chip').removeClass('active');
                    row.find('.filter-chip[data-value=""]').first().addClass('active');
                });
                $('#recipeFilterForm').submit();
                return;
            }

            group.find('.filter-chip').removeClass('active');
            button.addClass('active');
            $('#' + target).val(button.data('value'));
            $('#recipeFilterForm').submit();
        });

        $(document).on('click', '.filter-tab', function() {
            const button = $(this);
            const panel = button.data('panel');

            if (button.data('reset-all')) {
                $('#recipeFilterForm').find('input[type="hidden"], select').val('');
                window.location = "{{ route('recipes.index') }}";
                return;
            }

            $('.filter-tab').removeClass('active');
            button.addClass('active');
            $('.filter-section').removeClass('is-visible');
            $('.filter-section[data-panel="' + panel + '"]').addClass('is-visible');
        });

        function loadRecipeById(recipeId) {
            if (currentVideoElement) {
                currentVideoElement.pause();
                currentVideoElement.currentTime = 0;
                currentVideoElement = null;
            }

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
                    const hasPrev = currentRecipeIndex > 0;
                    const hasNext = currentRecipeIndex < recipeIds.length - 1;
                    let modalContent = `
                        <div class="recipe-modal-media mb-4">
                            <button type="button" class="modal-nav-btn left" data-dir="prev" ${hasPrev ? '' : 'disabled'} aria-label="Receta anterior">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="recipe-modal-media-content">
                                ${response.image ? 
                                    `<img src="/storage/${response.image}" class="recipe-modal-img img-fluid" alt="${response.recipe_title}">` : 
                                    `<div class="text-center py-4" style="background-color: #f8f9fa; border-radius: 8px;">
                                        <i class="fas fa-image fa-5x" style="color: #F28241;"></i>
                                        <p class="mt-2">Sin imagen</p>
                                    </div>`
                                }
                            </div>
                            <button type="button" class="modal-nav-btn right" data-dir="next" ${hasNext ? '' : 'disabled'} aria-label="Receta siguiente">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <h3 class="recipe-modal-title">${response.recipe_title}</h3>
                        
                        <div class="recipe-section">
                            <p>${response.recipe_description}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-user-circle mr-1"></i> Creado por: ${response.user ? `<a href="${profileBaseUrl}/${response.user.id}" class="text-decoration-none">${response.user.name} ${response.user.last_name || ''}</a>` : 'Administrador'}
                                </div>
                                <div>
                                    <span class="badge badge-pill mr-2" style="background-color: #5cb85c;">
                                        <i class="fas fa-clock"></i> ${response.preparation_time} min
                                    </span>
                                    <span class="badge badge-pill" style="background-color: #6c757d;">
                                        <i class="fas fa-utensil-spoon"></i> ${response.difficulty}
                                    </span>
                                    ${response.brand ? `
                                        <span class="badge badge-pill ml-2" style="background-color: #f0ad4e;">
                                            <i class="fas fa-copyright"></i> ${response.brand}
                                        </span>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <span class="badge badge-pill mr-2" style="background-color: #F28241;">
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
                            <div class="recipe-video-wrap">
                                <video id="recipeVideo" controls class="recipe-video-player">
                                    <source src="/storage/${response.video}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </div>`;
                    } else if (response.video_link_type === 'direct' && response.video_direct_url) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                            <div class="recipe-video-wrap">
                                <video id="recipeVideo" controls class="recipe-video-player">
                                    <source src="${response.video_direct_url}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </div>`;
                    } else if (response.video_embed_url) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                            <div class="recipe-video-wrap">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe
                                        id="recipeVideoEmbed"
                                        class="embed-responsive-item rounded"
                                        src="${response.video_embed_url}"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen
                                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                                </div>
                            </div>
                        </div>`;
                    }

                    if (response.comments) {
                        const renderComments = (items, filterRating = null) => {
                            const list = filterRating
                                ? items.filter((comment) => comment.rating === filterRating)
                                : items;

                            if (!list.length) {
                                return `<div class="text-muted small">No hay comentarios con ese filtro.</div>`;
                            }

                            return list.map((comment) => `
                                <div class="comment-item" data-rating="${comment.rating}">
                                    <div class="comment-meta">
                                        <span class="comment-user">${comment.user}</span>
                                        <span class="comment-date">${comment.created_at}</span>
                                    </div>
                                    <div class="comment-rating">${'★'.repeat(comment.rating)}${'☆'.repeat(5 - comment.rating)}</div>
                                    <div class="comment-text">${comment.comment}</div>
                                </div>
                            `).join('');
                        };

                        const filters = [5,4,3,2,1].map((rating) => {
                            const count = response.comment_rating_counts[rating] || 0;
                            return `
                                <button type="button" class="comment-filter" data-rating="${rating}">
                                    ${rating} estrellas (${count})
                                </button>
                            `;
                        }).join('');

                        modalContent += `
                        <div class="recipe-section">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h5 class="recipe-section-title mb-0"><i class="fas fa-comments mr-1"></i> Comentarios</h5>
                                <div class="comment-filters" data-recipe-id="${response.id}">
                                    <button type="button" class="comment-filter active" data-rating="">Todos (${response.comments.length})</button>
                                    ${filters}
                                </div>
                            </div>
                            <div class="comment-list mt-3" data-comments="${encodeURIComponent(JSON.stringify(response.comments))}">
                                ${renderComments(response.comments)}
                            </div>
                        </div>`;
                    }

                    // Comentario y calificacion se muestran arriba

                    if (canRate) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-star mr-1"></i> Calificar</h5>
                            <div class="comment-form" data-recipe-id="${response.id}" data-rating="5">
                                <div class="comment-rating-picker">
                                    ${[1,2,3,4,5].map((rating) => `
                                        <button type="button" class="comment-star ${rating === 5 ? 'active' : ''}" data-rating="${rating}">★</button>
                                    `).join('')}
                                </div>
                                <textarea class="form-control comment-input mt-2" rows="2" placeholder="Escribe tu comentario..."></textarea>
                                <button type="button" class="btn btn-primary btn-sm mt-2 submit-comment">Enviar</button>
                            </div>
                        </div>`;
                    }
                    
                    $('#recipeModalBody').html(modalContent);
                    $('#recipeModalLabel').html(`<i class="fas fa-utensils mr-2"></i> ${response.recipe_title}`);
                    
                    if (response.video || response.video_link_type === 'direct') {
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
        }

        // Cargar receta en el modal al hacer clic en "Ver"
        $('.view-recipe-btn').on('click', function() {
            const recipeId = Number($(this).data('recipe-id'));
            currentRecipeIndex = recipeIds.indexOf(recipeId);
            loadRecipeById(recipeId);
        });

        $(document).on('click', '.modal-nav-btn', function() {
            const direction = $(this).data('dir');
            if (direction === 'prev' && currentRecipeIndex > 0) {
                currentRecipeIndex -= 1;
                loadRecipeById(recipeIds[currentRecipeIndex]);
            }
            if (direction === 'next' && currentRecipeIndex < recipeIds.length - 1) {
                currentRecipeIndex += 1;
                loadRecipeById(recipeIds[currentRecipeIndex]);
            }
        });
        
        $('.recipe-card').on('mouseenter', function() {
            const card = $(this);
            const embed = card.find('.recipe-embed-preview iframe').get(0);
            if (embed) {
                const embedSrc = embed.getAttribute('data-src');
                if (embedSrc && embed.getAttribute('src') !== embedSrc) {
                    embed.setAttribute('src', embedSrc);
                }
            }

            const video = card.find('.recipe-video-preview').get(0);
            if (!video) return;

            const runLoop = () => {
                video.currentTime = 0;
                const playPromise = video.play();
                if (playPromise && typeof playPromise.catch === 'function') {
                    playPromise.catch(() => {});
                }

                const loopTimeoutId = setTimeout(() => {
                    video.pause();
                    video.currentTime = 0;
                    if (card.is(':hover')) {
                        runLoop();
                    }
                }, 2000);

                card.data('previewLoopTimeoutId', loopTimeoutId);
            };

            runLoop();
        });

        $('.recipe-card').on('mouseleave', function() {
            const card = $(this);
            const timeoutId = card.data('previewLoopTimeoutId');
            if (timeoutId) {
                clearTimeout(timeoutId);
                card.removeData('previewLoopTimeoutId');
            }

            const video = card.find('.recipe-video-preview').get(0);
            if (video) {
                video.pause();
                video.currentTime = 0;
            }

            const embed = card.find('.recipe-embed-preview iframe').get(0);
            if (embed) {
                embed.setAttribute('src', '');
            }
        });

        // Detener el video cuando se cierra el modal
        $('#recipeModal').on('hidden.bs.modal', function () {
            if (currentVideoElement) {
                currentVideoElement.pause();
                currentVideoElement.currentTime = 0;
                currentVideoElement = null;
            }
            $(this).find('iframe').attr('src', '');
        });

        if (initialRecipeId && recipeIds.includes(initialRecipeId)) {
            currentRecipeIndex = recipeIds.indexOf(initialRecipeId);
            $('#recipeModal').modal('show');
            loadRecipeById(initialRecipeId);
        }

        // Se califica al enviar comentario

        $(document).on('click', '.comment-star', function() {
            const button = $(this);
            const container = button.closest('.comment-form');
            const rating = button.data('rating');
            container.attr('data-rating', rating);
            container.find('.comment-star').each(function() {
                const star = $(this);
                star.toggleClass('active', star.data('rating') <= rating);
            });
        });

        $(document).on('click', '.submit-comment', function() {
            const button = $(this);
            const container = button.closest('.comment-form');
            const recipeId = container.data('recipe-id');
            const rating = parseInt(container.attr('data-rating'), 10) || 5;
            const text = container.find('.comment-input').val().trim();

            if (!text) {
                return;
            }

            $.ajax({
                url: "{{ route('recipes.comments', ':id') }}".replace(':id', recipeId),
                method: 'POST',
                data: { rating: rating, comment: text },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const list = $('#recipeModalBody').find('.comment-list');
                    const item = `
                        <div class="comment-item" data-rating="${response.comment.rating}">
                            <div class="comment-meta">
                                <span class="comment-user">${response.comment.user}</span>
                                <span class="comment-date">${response.comment.created_at}</span>
                            </div>
                            <div class="comment-rating">${'★'.repeat(response.comment.rating)}${'☆'.repeat(5 - response.comment.rating)}</div>
                            <div class="comment-text">${response.comment.comment}</div>
                        </div>
                    `;
                    if (list.length) {
                        list.prepend(item);
                        const raw = list.attr('data-comments');
                        if (raw) {
                            const items = JSON.parse(decodeURIComponent(raw));
                            items.unshift(response.comment);
                            list.attr('data-comments', encodeURIComponent(JSON.stringify(items)));
                        }
                    }
                    container.find('.comment-input').val('');
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    }
                }
            });
        });

        $(document).on('click', '.comment-filter', function() {
            const button = $(this);
            const filters = button.closest('.comment-filters');
            const rating = button.data('rating');
            const list = filters.closest('.recipe-section').find('.comment-list');
            const raw = list.attr('data-comments');
            if (!raw) return;
            const items = JSON.parse(decodeURIComponent(raw));

            filters.find('.comment-filter').removeClass('active');
            button.addClass('active');

            const filtered = rating ? items.filter((comment) => comment.rating === rating) : items;
            const html = filtered.length
                ? filtered.map((comment) => `
                    <div class="comment-item" data-rating="${comment.rating}">
                        <div class="comment-meta">
                            <span class="comment-user">${comment.user}</span>
                            <span class="comment-date">${comment.created_at}</span>
                        </div>
                        <div class="comment-rating">${'★'.repeat(comment.rating)}${'☆'.repeat(5 - comment.rating)}</div>
                        <div class="comment-text">${comment.comment}</div>
                    </div>
                `).join('')
                : `<div class="text-muted small">No hay comentarios con ese filtro.</div>`;

            list.html(html);
        });
        
        // Ocultar alerta de éxito
        if ($('#successAlert').length) {
            setTimeout(function() {
                $('#successAlert').addClass('fade-out');
                setTimeout(function() {
                    $('#successAlert').remove();
                }, 2000);
            }, 2000);
        }
        
        // Toggle de favoritos
        $(document).on('click', '.btn-favorite', function(e) {
            e.preventDefault();
            const button = $(this);
            const recipeId = button.data('recipe-id');
            const heartIcon = button.find('i');
            
            $.ajax({
                url: "{{ url('favorites/toggle') }}/" + recipeId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        heartIcon.toggleClass('text-danger text-secondary');
                        
                        // Si estamos en la vista de favoritos y se quitó de favoritos
                        if (window.location.pathname.includes('favorites') && !response.is_favorite) {
                            button.closest('.col-md-4').fadeOut(300, function() {
                                $(this).remove();
                                if ($('.recipe-card').length === 0) {
                                    location.reload();
                                }
                            });
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    } else {
                        console.error(xhr);
                        alert('Ocurrió un error. Por favor recarga la página.');
                    }
                }
            });
        });
    });
</script>
@stop
