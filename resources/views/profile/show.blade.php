@extends('adminlte::page')

@section('title', 'mi perfil - cocina con gusto')

@section('content_header')
<h1 class="profile-title">
    <i class="fas fa-user-circle"></i>
   Mi perfil
</h1>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@stop

@section('content')
<div class="row profile-page">

    {{-- columna izquierda --}}
    <div class="col-md-4">
        <div class="card profile-card">
            <div class="card-body text-center">

                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="avatar">
                    @else
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif

                    @if($isOwner)
                    <div class="dropdown mt-3">
                        <button class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-camera"></i> Cambiar foto
                        </button>

                        <div class="dropdown-menu dropdown-menu-right p-3">
                            <form action="{{ route('user.avatar.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <label class="d-block mb-2">
                                    Subir nueva foto
                                    <input type="file" name="avatar" class="form-control-file mt-1">
                                </label>

                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-success">Guardar</button>
                                    <button type="button" id="cancel-upload" class="btn btn-sm btn-secondary">Cancelar</button>
                                </div>
                            </form>

                            @if($user->avatar)
                                <hr>
                                <form action="{{ route('user.avatar.delete', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger w-100">Eliminar foto</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <p class="member-date">
                    <i class="fas fa-calendar-alt"></i>
                    Miembro desde {{ $user->created_at->format('d/m/Y') }}
                </p>

            </div>
        </div>
    </div>

    {{-- columna derecha --}}
    <div class="col-md-8">
        <div class="card profile-social mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="profile-name">{{ $user->name }} {{ $user->last_name }}</div>
                    <div class="profile-meta">
                        Miembro desde {{ $user->created_at->format('d/m/Y') }}
                    </div>
                </div>
                <div class="profile-right">
                    @if(!$isOwner)
                        <div class="profile-follow">
                            <form action="{{ route('profile.follow', $user->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary">
                                    {{ $isFollowing ? 'Dejar de seguir' : 'Seguir' }}
                                </button>
                            </form>
                        </div>
                    @endif
                    <div class="profile-stats">
                        <button type="button" class="stat-button" data-toggle="modal" data-target="#followersModal">
                            <span class="stat-value">{{ $followersCount }}</span>
                            <span class="stat-label">Seguidores</span>
                        </button>
                        <button type="button" class="stat-button" data-toggle="modal" data-target="#followingModal">
                            <span class="stat-value">{{ $followingCount }}</span>
                            <span class="stat-label">Siguiendo</span>
                        </button>
                        <div>
                            <span class="stat-value">{{ $likesTotal }}</span>
                            <span class="stat-label">Likes</span>
                        </div>
                    <button type="button" class="stat-button" data-toggle="modal" data-target="#ratingsModal">
                        <span class="stat-value">{{ number_format($starsAverage ?? 0, 1) }}</span>
                        <span class="stat-label">Estrellas</span>
                        <div class="small text-muted">
                            5★ {{ $commentsRating5 }} | 1★ {{ $commentsRating1 }}
                        </div>
                    </button>
                </div>
            </div>
        </div>
        </div>

        <div class="card profile-info">

            <div class="card-header p-0">
                <ul class="nav nav-tabs profile-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#personal">
                            Información personal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contact">
                            Información de contacto
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    <div class="tab-pane active" id="personal">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Datos personales</h4>
                                <p><span>Nombre:</span> {{ $user->name }}</p>
                                <p><span>Apellido:</span> {{ $user->last_name }}</p>
                                <p><span>Género:</span> {{ $user->gender ?? 'no especificado' }}</p>
                            </div>

                            <div class="col-md-6">
                                <h4>Información adicional</h4>
                                <p><span>Fecha de registro:</span> {{ $user->created_at->format('d/m/Y') }}</p>
                                <p><span>Última actualización:</span> {{ $user->updated_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="contact">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Contacto</h4>
                                <p><span>Email:</span> {{ $user->email }}</p>
                                <p><span>Teléfono:</span> {{ $user->phone ?? 'no especificado' }}</p>
                            </div>

                            <div class="col-md-6">
                                <h4>Ubicación</h4>
                                <p><span>Estado:</span> {{ $user->country ?? 'no especificado' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="card profile-recipes mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="m-0">Mis recetas</h4>
                    <span class="text-muted">{{ $recipesCount }} publicaciones</span>
                </div>

                @if($recipes->isEmpty())
                    <div class="alert alert-light border mb-0">
                        Aun no tienes recetas publicadas.
                    </div>
                @else
                    <div class="recipe-grid">
                        @foreach($recipes as $recipe)
                            <div class="recipe-item">
                                <div class="recipe-thumb">
                                    @if($recipe->image)
                                        <img src="{{ asset('storage/'.$recipe->image) }}" alt="{{ $recipe->recipe_title }}">
                                    @else
                                        <i class="fas fa-image" style="font-size: 2rem; color: #c2c2c2;"></i>
                                    @endif
                                </div>
                                <div class="recipe-body">
                                    <div class="recipe-title">{{ $recipe->recipe_title }}</div>
                                    <div class="recipe-stats">
                                        <span><i class="fas fa-heart"></i> {{ $recipe->favorited_by_count }}</span>
                                        <span><i class="fas fa-star"></i> {{ number_format($recipe->ratings_avg_rating ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($isOwner)
<div class="profile-actions">
    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-lg">editar perfil</a>
    <a href="{{ route('profile.change-password') }}" class="btn btn-warning btn-lg">cambiar contraseña</a>
    <button class="btn btn-outline-danger btn-lg" data-toggle="modal" data-target="#deleteAccountModal">
        eliminar cuenta
    </button>
</div>
@endif
@stop

@section('js')
@parent
<div class="modal fade" id="ratingsModal" tabindex="-1" role="dialog" aria-labelledby="ratingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ratingsModalLabel">Comentarios por estrellas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($comments->isEmpty())
                    <div class="list-empty">Aun no hay comentarios.</div>
                @else
                    <div class="comment-filters mb-3">
                        <button type="button" class="comment-filter active" data-rating="">Todos ({{ $comments->count() }})</button>
                        <button type="button" class="comment-filter" data-rating="5">5★ ({{ $commentsRating5 }})</button>
                        <button type="button" class="comment-filter" data-rating="4">4★ ({{ $comments->where('rating', 4)->count() }})</button>
                        <button type="button" class="comment-filter" data-rating="3">3★ ({{ $comments->where('rating', 3)->count() }})</button>
                        <button type="button" class="comment-filter" data-rating="2">2★ ({{ $comments->where('rating', 2)->count() }})</button>
                        <button type="button" class="comment-filter" data-rating="1">1★ ({{ $commentsRating1 }})</button>
                    </div>
                    <div class="comment-list" id="ratingsList" data-comments='@json($comments)'>
                        @foreach($comments as $comment)
                            <div class="comment-item" data-rating="{{ $comment['rating'] }}">
                                <div class="comment-meta">
                                    <span class="comment-user">{{ $comment['name'] }} {{ $comment['last_name'] }}</span>
                                    <span class="comment-date">{{ $comment['created_at'] }}</span>
                                </div>
                                <div class="comment-rating">{{ str_repeat('★', $comment['rating']) }}{{ str_repeat('☆', 5 - $comment['rating']) }}</div>
                                <div class="comment-text">{{ $comment['comment'] }}</div>
                                <div class="text-muted small mt-1">Receta: {{ $comment['recipe_title'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '#ratingsModal .comment-filter', function() {
    const button = $(this);
    const rating = button.data('rating');
    const list = $('#ratingsList');
    const raw = list.data('comments');
    if (!raw) return;

    const items = JSON.parse(raw);
    $('#ratingsModal .comment-filter').removeClass('active');
    button.addClass('active');

    const ratingNumber = rating ? parseInt(rating, 10) : null;
    const filtered = ratingNumber ? items.filter((item) => item.rating === ratingNumber) : items;
    const html = filtered.length
        ? filtered.map((item) => `
            <div class="comment-item" data-rating="${item.rating}">
                <div class="comment-meta">
                    <span class="comment-user">${item.name} ${item.last_name}</span>
                    <span class="comment-date">${item.created_at}</span>
                </div>
                <div class="comment-rating">${'★'.repeat(item.rating)}${'☆'.repeat(5 - item.rating)}</div>
                <div class="comment-text">${item.comment}</div>
                <div class="text-muted small mt-1">Receta: ${item.recipe_title}</div>
            </div>
        `).join('')
        : `<div class="text-muted small">No hay comentarios con ese filtro.</div>`;

    list.html(html);
});
</script>
@stop

@section('js')
@parent
<!-- Modales de seguidores -->
<div class="modal fade" id="followersModal" tabindex="-1" role="dialog" aria-labelledby="followersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followersModalLabel">Seguidores</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($followers->isEmpty())
                    <div class="list-empty">Aun no tiene seguidores.</div>
                @else
                    <div class="list-grid">
                        @foreach($followers as $follower)
                            <a class="list-item" href="{{ route('profile.public', $follower->id) }}">
                                <div class="list-avatar">
                                    @if($follower->avatar)
                                        <img src="{{ $follower->avatar_url }}" alt="{{ $follower->name }}">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div class="list-name">{{ $follower->name }} {{ $follower->last_name }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="followingModal" tabindex="-1" role="dialog" aria-labelledby="followingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followingModalLabel">Siguiendo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($following->isEmpty())
                    <div class="list-empty">No sigue a nadie aun.</div>
                @else
                    <div class="list-grid">
                        @foreach($following as $followed)
                            <a class="list-item" href="{{ route('profile.public', $followed->id) }}">
                                <div class="list-avatar">
                                    @if($followed->avatar)
                                        <img src="{{ $followed->avatar_url }}" alt="{{ $followed->name }}">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div class="list-name">{{ $followed->name }} {{ $followed->last_name }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
