@extends('adminlte::page')

@section('title', 'Buscar')

@section('content_header')
    <h1 class="m-0 text-dark" style="font-size: 2.2rem;">Resultados</h1>
@stop

@section('content')
    @if($query === '')
        <div class="alert alert-light border">
            Escribe algo para buscar recetas o personas.
        </div>
    @else
        <div class="mb-4">
            <span class="text-muted">Busqueda:</span>
            <strong>{{ $query }}</strong>
        </div>

        @if($recipes->isEmpty() && $users->isEmpty())
            <div class="text-muted">No se encontraron resultados.</div>
        @else
            <div class="row">
                @foreach($recipes as $recipe)
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <div class="card h-100 search-card">
                            <div class="search-card-media">
                                @if($recipe->image)
                                    <img src="{{ asset('storage/'.$recipe->image) }}" alt="{{ $recipe->recipe_title }}" class="search-card-image">
                                @else
                                    <i class="fas fa-image search-card-icon"></i>
                                @endif
                            </div>
                            <div class="card-body search-card-body">
                                <div class="search-card-title">{{ $recipe->recipe_title }}</div>
                                <div class="text-muted search-card-description">{{ \Illuminate\Support\Str::limit($recipe->recipe_description, 70) }}</div>
                                <div class="text-muted small search-card-metrics">
                                    {{ $recipe->favorited_by_count }} favoritos • {{ $recipe->comments_count }} comentarios • {{ number_format($recipe->avg_rating, 1) }}★
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 text-center">
                                <a href="{{ auth()->check() ? '#' : route('login') }}"
                                   class="btn btn-sm view-recipe-btn"
                                   @auth data-recipe-id="{{ $recipe->id }}" @endauth>
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach($users as $user)
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <a href="{{ route('profile.public', $user->id) }}" class="card h-100 text-decoration-none text-dark search-card">
                            <div class="search-card-media search-user-media">
                                <div class="list-avatar search-user-avatar">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body search-card-body">
                                <div class="search-card-title">{{ $user->name }} {{ $user->last_name }}</div>
                                <div class="text-muted small search-card-subtitle">{{ $user->email }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    @auth
    <div class="modal fade" id="searchRecipeModal" tabindex="-1" role="dialog" aria-labelledby="searchRecipeModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="searchRecipeModalLabel">
                        <i class="fas fa-utensils mr-2"></i> Receta Completa
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="searchRecipeModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endauth
@stop

@section('css')
<style>
    .search-card {
        overflow: hidden;
        border: 1px solid #f2cfb4 !important;
        box-shadow: 0 12px 28px rgba(242, 130, 65, 0.12) !important;
    }

    .view-recipe-btn {
        background-color: var(--primary);
        color: #ffffff !important;
        border: none;
        border-radius: 14px !important;
        padding: 0.55rem 1.2rem !important;
        font-size: 1.05rem !important;
        font-weight: 700 !important;
    }

    .view-recipe-btn:hover {
        background-color: color-mix(in srgb, var(--primary) 85%, black);
        color: #ffffff !important;
    }

    .view-recipe-btn i,
    .view-recipe-btn span {
        color: #ffffff !important;
    }

    .search-card-media {
        height: 220px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 1rem 0;
    }

    .search-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 22px 22px 0 0;
    }

    .search-card-icon {
        font-size: 2rem;
        color: #c2c2c2;
    }

    .search-card-body {
        text-align: center;
        padding: 1rem 1.2rem 0.7rem;
    }

    .search-card-title {
        font-weight: 700;
        font-size: 1.05rem;
        line-height: 1.35;
        margin-bottom: 0.6rem;
        word-break: break-word;
        color: var(--primary);
    }

    .search-card-description {
        font-size: 0.92rem;
        line-height: 1.55;
        min-height: 3.1em;
        margin-bottom: 0.75rem;
        color: #6b7480 !important;
    }

    .search-card-metrics {
        font-size: 0.8rem;
        line-height: 1.4;
        margin-bottom: 0.35rem;
    }

    .search-user-media {
        background: linear-gradient(180deg, #fff7ef 0%, #fffdfb 100%);
    }

    .search-user-avatar {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #f1c29c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #c2c2c2;
    }

    .search-user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 576px) {
        .search-card-media {
            height: 165px;
        }

        .search-user-avatar {
            width: 72px;
            height: 72px;
        }

        .search-card-title {
            font-size: 0.9rem;
        }

        .search-card-description {
            font-size: 0.84rem;
        }
    }
</style>
@stop

@section('js')
@auth
<script>
    $(function () {
        let currentVideoElement = null;
        const profileBaseUrl = "{{ url('/perfil') }}";

        function loadRecipeById(recipeId) {
            if (currentVideoElement) {
                currentVideoElement.pause();
                currentVideoElement.currentTime = 0;
                currentVideoElement = null;
            }

            const url = "{{ route('recipes.show', ':id') }}".replace(':id', recipeId);

            $('#searchRecipeModalBody').html(`
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
                        <div class="recipe-modal-media mb-4">
                            <div class="recipe-modal-media-content">
                                ${response.image ?
                                    `<img src="/storage/${response.image}" class="recipe-modal-img img-fluid" alt="${response.recipe_title}">` :
                                    `<div class="text-center py-4" style="background-color: #f8f9fa; border-radius: 8px;">
                                        <i class="fas fa-image fa-5x" style="color: #F28241;"></i>
                                        <p class="mt-2">Sin imagen</p>
                                    </div>`
                                }
                            </div>
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
                                <video id="searchRecipeVideo" controls class="recipe-video-player">
                                    <source src="/storage/${response.video}" type="video/mp4">
                                </video>
                            </div>
                        </div>`;
                    } else if (response.video_link_type === 'direct' && response.video_direct_url) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                            <div class="recipe-video-wrap">
                                <video id="searchRecipeVideo" controls class="recipe-video-player">
                                    <source src="${response.video_direct_url}" type="video/mp4">
                                </video>
                            </div>
                        </div>`;
                    } else if (response.video_embed_url) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                            <div class="recipe-video-wrap">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item rounded"
                                        src="${response.video_embed_url}"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen
                                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                                </div>
                            </div>
                        </div>`;
                    }

                    $('#searchRecipeModalBody').html(modalContent);
                    $('#searchRecipeModalLabel').html(`<i class="fas fa-utensils mr-2"></i> ${response.recipe_title}`);

                    if (response.video || response.video_link_type === 'direct') {
                        currentVideoElement = document.getElementById('searchRecipeVideo');
                    }
                },
                error: function(xhr) {
                    $('#searchRecipeModalBody').html(`
                        <div class="alert alert-danger">
                            No se pudo cargar la receta. Error: ${xhr.status} - ${xhr.statusText}
                        </div>
                    `);
                }
            });
        }

        $('.view-recipe-btn[data-recipe-id]').on('click', function(event) {
            event.preventDefault();
            loadRecipeById(Number($(this).data('recipe-id')));
            $('#searchRecipeModal').modal('show');
        });

        $('#searchRecipeModal').on('hidden.bs.modal', function () {
            if (currentVideoElement) {
                currentVideoElement.pause();
                currentVideoElement.currentTime = 0;
                currentVideoElement = null;
            }
            $(this).find('iframe').attr('src', '');
        });
    });
</script>
@endauth
@stop
