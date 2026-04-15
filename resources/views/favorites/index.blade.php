@extends('adminlte::page')

@section('title', 'Mis Favoritos - COCINA CON GUSTO')

@section('content_header')
    <h1 class="m-0 page-title-favorites" style="font-weight: 700; color: #F28241; text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.6); letter-spacing: 0.05em; margin-bottom: 1.5rem;">
        MIS FAVORITOS
    </h1>
@stop

@section('content')
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
        <form method="GET" action="{{ route('favorites.index') }}" class="row align-items-end" id="favoriteFilterForm">
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
                <label for="category_id" class="filter-label">Categoria</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Todas las categorias</option>
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

@if($favorites->isEmpty())
    <div class="alert alert-info" style="font-size: 1.1rem;">
        <i class="fas fa-info-circle mr-2"></i> No hay recetas favoritas con los filtros seleccionados.
    </div>
@else
<div class="row">
    @foreach($favorites as $recipe)
    <div class="col-md-4 mb-4">
        <div class="card h-100 recipe-card" data-recipe-id="{{ $recipe->id }}">
            <!-- Contenedor flexible para la imagen -->
            <div class="image-wrapper" style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-top-left-radius: 12px; border-top-right-radius: 12px; position: relative;">
                @if($recipe->image)
                <img src="{{ asset($recipe->image) }}" class="img-fluid" alt="{{ $recipe->recipe_title }}" style="max-height: 100%; max-width: 100%; object-fit: scale-down;">
                @else
                <div class="text-center">
                    <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
                    <p class="mt-2" style="font-size: 1.1rem;">Sin imagen</p>
                </div>
                @endif
                
                <!-- Corazón de favoritos -->
                <button class="btn-favorite" data-recipe-id="{{ $recipe->id }}" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.7); border: none; border-radius: 50%; width: 36px; height: 36px; padding: 0; z-index: 10;">
                    <i class="fas fa-heart text-danger" style="font-size: 1.2rem;"></i>
                </button>
            </div>
            
            <div class="card-body">
                <h5 class="card-title" style="color: #F28241; font-weight: 600; font-size: 1.3rem;">{{ $recipe->recipe_title }}</h5>
                <p class="card-text text-muted" style="font-size: 1.1rem;">{{ Str::limit($recipe->recipe_description, 100) }}</p>
            </div>
            
            <!-- Botones de acción simplificados -->
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm view-recipe-btn" style="background-color: #F28241; color: white; font-size: 1.1rem;" data-toggle="modal" data-target="#recipeModal" data-recipe-id="{{ $recipe->id }}">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $favorites->links() }}
</div>

<!-- Modal para ver receta completa -->
<div class="modal fade" id="recipeModal" tabindex="-1" role="dialog" aria-labelledby="recipeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
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
@endif
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
        border: 1.5px solid #FF8F43;
        background: #fff;
        color: #A74F1F;
        border-radius: 999px;
        padding: 0.75rem 1.3rem;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1;
        transition: all 0.2s ease;
    }
    .filter-tab:hover,
    .filter-tab.active {
        background: #FF8F43;
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
        border: 1.5px solid #FF8F43;
        background: #fff;
        color: #A74F1F;
        border-radius: 999px;
        padding: 0.7rem 1.35rem;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1;
        transition: all 0.2s ease;
    }
    .filter-chip:hover,
    .filter-chip.active {
        background: #FF8F43;
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
        transition: all 0.3s ease;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(122, 156, 198, 0.15);
        border-color: rgba(122, 156, 198, 0.3);
    }
    .card-title {
        font-size: 1.3rem;
        margin-bottom: 0.75rem;
    }
    .image-wrapper {
        padding: 10px;
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
    .btn:hover {
        transform: translateY(-2px);
    }
    .card-footer {
        padding: 0.75rem 1.25rem;
        background: linear-gradient(to bottom, rgba(255,255,255,0.9), rgba(255,255,255,1));
    }
    .recipe-modal-img {
        max-height: 300px;
        width: auto;
        display: block;
        margin: 0 auto;
        border-radius: 8px;
    }
    .recipe-modal-title {
        color: #F28241;
        font-weight: 600;
        margin-bottom: 20px;
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
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        let currentVideoElement = null;

        $(document).on('click', '.filter-chip-row .filter-chip', function() {
            const button = $(this);
            const group = button.closest('.filter-chip-row');
            const target = group.data('target');

            if (group.data('reset-all')) {
                $('#favoriteFilterForm').find('input[type="hidden"], select').val('');
                $('.filter-chip-row').each(function() {
                    const row = $(this);
                    row.find('.filter-chip').removeClass('active');
                    row.find('.filter-chip[data-value=""]').first().addClass('active');
                });
                $('#favoriteFilterForm').submit();
                return;
            }

            group.find('.filter-chip').removeClass('active');
            button.addClass('active');
            $('#' + target).val(button.data('value'));
            $('#favoriteFilterForm').submit();
        });

        $(document).on('click', '.filter-tab', function() {
            const button = $(this);
            const panel = button.data('panel');

            if (button.data('reset-all')) {
                $('#favoriteFilterForm').find('input[type="hidden"], select').val('');
                window.location = "{{ route('favorites.index') }}";
                return;
            }

            $('.filter-tab').removeClass('active');
            button.addClass('active');
            $('.filter-section').removeClass('is-visible');
            $('.filter-section[data-panel="' + panel + '"]').addClass('is-visible');
        });

        // Cargar receta en el modal al hacer clic en "Ver"
        $('.view-recipe-btn').on('click', function() {
            const recipeId = $(this).data('recipe-id');
            const url = "{{ route('recipes.show', ':id') }}".replace(':id', recipeId);
            const pdfUrl = "{{ route('recipes.download', ':id') }}".replace(':id', recipeId);
            
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
                                `<img src="/${response.image}" class="recipe-modal-img img-fluid" alt="${response.recipe_title}">` : 
                                `<div class="text-center py-4" style="background-color: #f8f9fa; border-radius: 8px;">
                                    <i class="fas fa-image fa-5x" style="color: #F28241;"></i>
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
                            <div class="embed-responsive embed-responsive-16by9">
                                <video id="recipeVideo" controls class="w-100 rounded" style="background-color: #f8f9fa;">
                                    <source src="/${response.video}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </div>`;
                    } else if (response.video_link_type === 'direct' && response.video_direct_url) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                            <div class="embed-responsive embed-responsive-16by9">
                                <video id="recipeVideo" controls class="w-100 rounded" style="background-color: #f8f9fa;">
                                    <source src="${response.video_direct_url}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </div>`;
                    } else if (response.video_embed_url) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe
                                    class="embed-responsive-item rounded"
                                    src="${response.video_embed_url}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                    referrerpolicy="strict-origin-when-cross-origin"></iframe>
                            </div>
                        </div>`;
                    }

                    modalContent += `
                        <div class="recipe-section text-center">
                            <a href="${pdfUrl}" class="btn btn-danger" style="font-size: 1.05rem;">
                                <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
                            </a>
                        </div>`;
                    
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
