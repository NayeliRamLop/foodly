@extends('layouts.public')

@section('title', 'Buscar recetas')

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
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="image-wrapper" style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            @if($recipe->image)
                                <img src="{{ asset('storage/'.$recipe->image) }}" class="img-fluid" alt="{{ $recipe->recipe_title }}" style="max-height: 100%; max-width: 100%; object-fit: scale-down;">
                            @else
                                <div class="text-center text-muted">
                                    <div style="font-size: 2rem;">Sin imagen</div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $recipe->recipe_title }}</h5>
                            <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($recipe->recipe_description, 120) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $recipes->links() }}
        </div>
    @endif
</div>
@endsection
