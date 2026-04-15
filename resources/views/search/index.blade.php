@extends('adminlte::page')

@section('title', 'Buscar')

@section('content')
<div class="container py-5 search-results-page">
    @if($query === '')
        <div class="alert alert-light border">
            Escribe algo para buscar recetas o personas.
        </div>
    @elseif($recipes->isEmpty() && $users->isEmpty())
        <div class="alert alert-light border">
            No se encontraron resultados.
        </div>
    @else
        @if($recipes->isNotEmpty())
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <h3 class="mb-0">Resultados de recetas</h3>
                <span class="text-muted">Busqueda: "{{ $query }}"</span>
            </div>

                <div class="row g-4">
                    @foreach($recipes as $recipe)
                        <div class="col-md-6 col-lg-4">
                            @include('partials.recipe-card', ['recipe' => $recipe, 'showHoverPreview' => false, 'showFavoriteButton' => auth()->check()])
                        </div>
                    @endforeach
                </div>
        @endif

        @if($users->isNotEmpty())
            <div class="search-users-block{{ $recipes->isNotEmpty() ? ' mt-5' : '' }}">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <h3 class="mb-0">Resultados de personas</h3>
                    <span class="text-muted">
                        @if($recipes->isEmpty())
                            Busqueda: "{{ $query }}"
                        @else
                            {{ $users->count() }} resultado{{ $users->count() === 1 ? '' : 's' }}
                        @endif
                    </span>
                </div>

                <div class="row g-4">
                    @foreach($users as $user)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('profile.public', $user->id) }}" class="card h-100 text-decoration-none text-dark search-user-card">
                                <div class="search-user-media">
                                    <div class="list-avatar search-user-avatar">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body search-user-body">
                                    <div class="search-user-title">{{ $user->name }} {{ $user->last_name }}</div>
                                    <div class="text-muted small search-user-subtitle">{{ $user->email }}</div>
                                </div>
                                <div class="card-footer search-user-footer">
                                    <span class="btn view-recipe-btn search-user-action">Ver perfil</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

</div>
@stop

@include('partials.recipe-preview-modal', [
    'modalId' => 'searchRecipeModal',
    'titleId' => 'searchRecipeModalLabel',
    'bodyId' => 'searchRecipeModalBody',
])

@section('css')
@include('partials.recipe-ui-styles')
<style>
    .search-results-page {
        max-width: none;
    }

    .search-results-page .recipe-card .image-wrapper {
        height: 200px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        position: relative;
        overflow: hidden;
        padding: 0;
    }

    .search-results-page .recipe-card .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center center;
        display: block;
    }

    .search-results-page .recipe-card .img-placeholder {
        height: 200px;
        background-color: #f8f9fa;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        border-bottom: 1px solid #f1c29c;
    }

    .search-user-card {
        overflow: hidden;
        border: 1px solid #f2cfb4 !important;
        box-shadow: 0 12px 28px rgba(242, 130, 65, 0.12) !important;
        border-radius: 18px !important;
        background: linear-gradient(180deg, #fff9f4 0%, #ffffff 100%) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .search-user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 34px rgba(87, 52, 20, 0.14) !important;
        border-color: #efc29f !important;
    }

    .search-user-media {
        height: 270px;
        background: linear-gradient(180deg, #fff2e6 0%, #fffdfb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        border-bottom: 1px solid #f5dfcf;
    }

    .search-user-avatar {
        width: 156px;
        height: 156px;
        border-radius: 50%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #f1c29c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #c2c2c2;
        box-shadow: 0 10px 20px rgba(87, 52, 20, 0.08);
    }

    .search-user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .search-user-body {
        min-height: 158px;
        padding: 1rem 1rem 0.7rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .search-user-title {
        font-size: 1.12rem;
        color: var(--primary);
        margin-bottom: 0.7rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .search-user-subtitle {
        font-size: 0.95rem;
        line-height: 1.55;
        color: #6d7680 !important;
    }

    .search-user-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        background: transparent;
        padding: 0 1rem 1.1rem;
        border: 0;
    }

    .search-user-action {
        pointer-events: none;
    }

    @media (max-width: 576px) {
        .search-user-media {
            height: 230px;
        }

        .search-user-avatar {
            width: 118px;
            height: 118px;
        }

        .search-user-title {
            font-size: 1rem;
        }

        .search-user-subtitle {
            font-size: 0.9rem;
        }
    }
</style>
@stop

@section('js')
@include('partials.recipe-preview-modal-script')
@include('partials.favorite-toggle-card-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recipePreview = window.createRecipePreviewModalController({
            modalId: 'searchRecipeModal',
            bodyId: 'searchRecipeModalBody',
            titleId: 'searchRecipeModalLabel',
            profileBaseUrl: "{{ url('/perfil') }}",
            showUrlTemplate: "{{ route('recipes.show', '__ID__') }}",
            renderFooterActions: () => '',
            isGuest: @json(!auth()->check()),
            loginUrl: "{{ route('login') }}",
            registerUrl: "{{ route('user.create') }}",
            profilePromptUrl: "{{ route('user.create') }}?intent=profile#crear-cuenta",
            logoUrl: "{{ asset('images/logo.png') }}",
        });

        document.querySelectorAll('.view-recipe-btn[data-recipe-id]').forEach((button) => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                if (recipePreview) {
                    recipePreview.open(this.getAttribute('data-recipe-id'));
                }
            });
        });
    });
</script>
@stop
