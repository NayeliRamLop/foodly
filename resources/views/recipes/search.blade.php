@extends('layouts.public')

@section('title', 'Buscar recetas')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    @include('partials.recipe-ui-styles')
@endsection

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <h3 class="mb-0">Resultados de recetas</h3>
        @if($query !== '')
            <span class="text-muted">Busqueda: "{{ $query }}"</span>
        @endif
    </div>

    @if($recipes->count() === 0)
        <div class="alert alert-light border">
            No se encontraron recetas con ese termino.
        </div>
    @else
        <div class="row g-4">
            @foreach($recipes as $recipe)
                <div class="col-md-6 col-lg-4">
                    @include('partials.recipe-card', ['recipe' => $recipe])
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $recipes->links() }}
        </div>
    @endif
</div>

@include('partials.recipe-preview-modal', [
    'modalId' => 'guestSearchRecipeModal',
    'titleId' => 'guestSearchRecipeModalLabel',
    'bodyId' => 'guestSearchRecipeModalBody',
])
@endsection

@section('js')
@include('partials.recipe-preview-modal-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recipePreview = window.createRecipePreviewModalController({
            modalId: 'guestSearchRecipeModal',
            bodyId: 'guestSearchRecipeModalBody',
            titleId: 'guestSearchRecipeModalLabel',
            profileBaseUrl: "{{ url('/perfil') }}",
            showUrlTemplate: "{{ route('recipes.show', '__ID__') }}",
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
@endsection
