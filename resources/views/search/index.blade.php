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
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div style="height: 160px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                @if($recipe->image)
                                    <img src="{{ asset('storage/'.$recipe->image) }}" alt="{{ $recipe->recipe_title }}" style="max-height: 100%; max-width: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-image" style="font-size: 2rem; color: #c2c2c2;"></i>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="font-weight-bold">{{ $recipe->recipe_title }}</div>
                                <div class="text-muted small">
                                    @if($recipe->user)
                                        <a href="{{ route('profile.public', $recipe->user->id) }}">{{ $recipe->user->name }}</a>
                                    @else
                                        Administrador
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-outline-secondary">Ver recetas</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach($users as $user)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('profile.public', $user->id) }}" class="card h-100 text-decoration-none text-dark">
                            <div class="card-body d-flex align-items-center gap-2">
                                <div class="list-avatar">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $user->name }} {{ $user->last_name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
@stop
