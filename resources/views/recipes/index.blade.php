@extends('adminlte::page')

@section('title', 'RECETAS')

@section('content_header')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-recipes-header">
        <h1 class="m-0 page-title-my-recipes">RECETAS</h1>
        <a href="{{ route('favorites.index') }}" class="btn view-recipe-btn">
            <i class="fas fa-heart mr-1"></i> Mis favoritos
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid px-0 my-recipes-page">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card filter-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('recipes.index') }}" id="recipeIndexFilterForm">
                    <input type="hidden" name="category_id"         id="ri_filter_category_id"         value="{{ $selectedCategory }}">
                    <input type="hidden" name="brand"               id="ri_filter_brand"               value="{{ $selectedFilters['brand'] ?? '' }}">
                    <input type="hidden" name="dish_type"           id="ri_filter_dish_type"           value="{{ $selectedFilters['dish_type'] ?? '' }}">
                    <input type="hidden" name="daily_category"      id="ri_filter_daily_category"      value="{{ $selectedFilters['daily_category'] ?? '' }}">
                    <input type="hidden" name="special_occasion"    id="ri_filter_special_occasion"    value="{{ $selectedFilters['special_occasion'] ?? '' }}">
                    <input type="hidden" name="baking_category"     id="ri_filter_baking_category"     value="{{ $selectedFilters['baking_category'] ?? '' }}">
                    <input type="hidden" name="seasonality"         id="ri_filter_seasonality"         value="{{ $selectedFilters['seasonality'] ?? '' }}">
                    <input type="hidden" name="preparation_method"  id="ri_filter_preparation_method"  value="{{ $selectedFilters['preparation_method'] ?? '' }}">

                    @php
                        $riActiveKey = 'all';
                        if ($selectedCategory) $riActiveKey = 'category_id';
                        elseif (!empty($selectedFilters['brand']))              $riActiveKey = 'brand';
                        elseif (!empty($selectedFilters['dish_type']))          $riActiveKey = 'dish_type';
                        elseif (!empty($selectedFilters['daily_category']))     $riActiveKey = 'daily_category';
                        elseif (!empty($selectedFilters['special_occasion']))   $riActiveKey = 'special_occasion';
                        elseif (!empty($selectedFilters['baking_category']))    $riActiveKey = 'baking_category';
                        elseif (!empty($selectedFilters['seasonality']))        $riActiveKey = 'seasonality';
                        elseif (!empty($selectedFilters['preparation_method'])) $riActiveKey = 'preparation_method';
                    @endphp

                    <div class="filter-tab-row mb-3">
                        <button type="button" class="filter-tab filter-tab-reset {{ $riActiveKey === 'all' ? 'active' : '' }}" data-panel="all" data-reset-all="true">Todas</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'brand' ? 'active' : '' }}" data-panel="brand">Recetas con...</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'dish_type' ? 'active' : '' }}" data-panel="dish_type">Tipo de platillo</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'daily_category' ? 'active' : '' }}" data-panel="daily_category">Para todos los dias</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'special_occasion' ? 'active' : '' }}" data-panel="special_occasion">Ocasion especial</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'baking_category' ? 'active' : '' }}" data-panel="baking_category">Reposteria y panaderia</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'seasonality' ? 'active' : '' }}" data-panel="seasonality">Temporalidad</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'preparation_method' ? 'active' : '' }}" data-panel="preparation_method">Metodos de preparacion</button>
                        <button type="button" class="filter-tab {{ $riActiveKey === 'category_id' ? 'active' : '' }}" data-panel="category_id">Categorias</button>
                    </div>

                    <div class="filter-section {{ $riActiveKey === 'category_id' ? 'is-visible' : '' }}" data-panel="category_id">
                        <div class="filter-chip-row" data-target="ri_filter_category_id" data-group="single">
                            @foreach($categories as $category)
                                <button type="button" class="filter-chip {{ (string) $selectedCategory === (string) $category->id ? 'active' : '' }}" data-value="{{ $category->id }}">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-section {{ $riActiveKey === 'brand' ? 'is-visible' : '' }}" data-panel="brand">
                        <div class="filter-chip-row" data-target="ri_filter_brand" data-group="single">
                            @foreach($brands as $brand)
                                <button type="button" class="filter-chip {{ ($selectedFilters['brand'] ?? '') === $brand ? 'active' : '' }}" data-value="{{ $brand }}">
                                    {{ $brand }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach ([
                        'dish_type'          => 'ri_filter_dish_type',
                        'daily_category'     => 'ri_filter_daily_category',
                        'special_occasion'   => 'ri_filter_special_occasion',
                        'baking_category'    => 'ri_filter_baking_category',
                        'seasonality'        => 'ri_filter_seasonality',
                        'preparation_method' => 'ri_filter_preparation_method',
                    ] as $panel => $target)
                        <div class="filter-section {{ $riActiveKey === $panel ? 'is-visible' : '' }}" data-panel="{{ $panel }}">
                            <div class="filter-chip-row" data-target="{{ $target }}" data-group="single">
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

        @if($recipes->isEmpty())
            <div class="empty-my-recipes-state">
                <div class="empty-my-recipes-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>No se encontraron recetas</h3>
                <p>Prueba con otro filtro o vuelve a "Todas".</p>
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
            'modalId' => 'recipesIndexModal',
            'titleId' => 'recipesIndexModalLabel',
            'bodyId'  => 'recipesIndexModalBody',
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
            box-shadow: 0 12px 28px rgba(0,0,0,0.06);
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
            box-shadow: 0 8px 18px rgba(255,143,67,0.22);
        }
        .filter-section {
            display: none;
            margin-top: 0.25rem;
        }
        .filter-section.is-visible {
            display: block;
        }
        @media (max-width: 768px) {
            .filter-tab, .filter-chip {
                padding: 0.65rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
@stop

@section('js')
    @include('partials.recipe-preview-modal-script')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('recipeIndexFilterForm');
        if (!filterForm) return;

        const filterInputs = Array.from(filterForm.querySelectorAll('input[type="hidden"]'));
        const clearFilters = () => filterInputs.forEach(input => { input.value = ''; });

        filterForm.querySelectorAll('.filter-tab').forEach(function (button) {
            button.addEventListener('click', function () {
                const panel = this.getAttribute('data-panel');
                if (this.hasAttribute('data-reset-all')) {
                    window.location.href = "{{ route('recipes.index') }}";
                    return;
                }
                filterForm.querySelectorAll('.filter-tab').forEach(function (tab) { tab.classList.remove('active'); });
                this.classList.add('active');
                filterForm.querySelectorAll('.filter-section').forEach(function (section) {
                    section.classList.toggle('is-visible', section.getAttribute('data-panel') === panel);
                });
            });
        });

        filterForm.querySelectorAll('.filter-chip-row .filter-chip').forEach(function (button) {
            button.addEventListener('click', function () {
                const row = this.closest('.filter-chip-row');
                const targetId = row.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                if (!targetInput) return;
                clearFilters();
                targetInput.value = this.getAttribute('data-value') || '';
                filterForm.submit();
            });
        });

        document.querySelectorAll('.recipe-card').forEach(function (card) {
            card.addEventListener('mouseenter', function () {
                const embed = card.querySelector('.recipe-embed-preview iframe');
                if (embed) {
                    const embedSrc = embed.getAttribute('data-src');
                    if (embedSrc && embed.getAttribute('src') !== embedSrc) {
                        embed.setAttribute('src', embedSrc);
                    }
                }
                const video = card.querySelector('.recipe-video-preview');
                if (!video) return;
                const runLoop = function () {
                    video.currentTime = 0;
                    const p = video.play();
                    if (p && typeof p.catch === 'function') p.catch(function () {});
                    const id = window.setTimeout(function () {
                        video.pause();
                        video.currentTime = 0;
                        if (card.matches(':hover')) runLoop();
                    }, 2000);
                    card.dataset.previewLoopTimeoutId = String(id);
                };
                runLoop();
            });

            card.addEventListener('mouseleave', function () {
                const id = Number(card.dataset.previewLoopTimeoutId || 0);
                if (id) { window.clearTimeout(id); delete card.dataset.previewLoopTimeoutId; }
                const video = card.querySelector('.recipe-video-preview');
                if (video) { video.pause(); video.currentTime = 0; }
                const embed = card.querySelector('.recipe-embed-preview iframe');
                if (embed) embed.setAttribute('src', '');
            });
        });

        const recipePreview = window.createRecipePreviewModalController({
            modalId: 'recipesIndexModal',
            bodyId:  'recipesIndexModalBody',
            titleId: 'recipesIndexModalLabel',
            profileBaseUrl:   "{{ url('/perfil') }}",
            showUrlTemplate:  "{{ route('recipes.show', '__ID__') }}",
            isGuest: false,
            getRecipeIds: function () {
                return Array.from(document.querySelectorAll('.view-recipe-btn[data-recipe-id]'))
                    .map(function (btn) { return btn.getAttribute('data-recipe-id'); });
            },
            loginUrl:         "{{ route('login') }}",
            registerUrl:      "{{ route('user.create') }}",
            profilePromptUrl: "{{ route('user.create') }}",
            logoUrl:          "{{ asset('images/logo.png') }}",
        });

        document.querySelectorAll('.view-recipe-btn[data-recipe-id]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (recipePreview) recipePreview.open(this.getAttribute('data-recipe-id'));
            });
        });

        // Auto-abrir modal desde notificación (?open_recipe=ID)
        var openRecipeId = new URLSearchParams(window.location.search).get('open_recipe');
        if (openRecipeId && recipePreview) {
            recipePreview.open(parseInt(openRecipeId, 10));
            // Limpiar el param de la URL sin recargar
            history.replaceState(null, '', window.location.pathname);
        }
    });
    </script>
@stop