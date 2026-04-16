@extends('adminlte::page')

@section('title', 'mi perfil - cocina con gusto')

@section('css')
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
<style>
.profile-field-row {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.profile-field-value {
    flex: 1;
}
.privacy-toggle {
    padding: 0 4px;
    line-height: 1;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 0.85rem;
    opacity: 0.7;
    transition: opacity 0.15s;
}
.privacy-toggle:hover {
    opacity: 1;
}
.privacy-toggle.spinning i {
    animation: spin 0.5s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@stop

@section('content')

@if($user->isAdmin())
{{-- Vista simplificada para admin --}}
<div class="row profile-page">
    <div class="col-md-4">
        <div class="card profile-card">
            <div class="card-body text-center">
                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="avatar">
                    @else
                        <div class="avatar-placeholder">
                            <i class="fas fa-user-shield"></i>
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
                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="dropdown">Cancelar</button>
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
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card profile-social mb-4">
            <div class="card-body d-flex align-items-center" style="min-height:90px;">
                <div>
                    <div class="profile-name">ADMIN</div>
                    <small class="text-muted"><i class="fas fa-shield-alt mr-1"></i>Administrador del sistema</small>
                </div>
            </div>
        </div>

        @if($isOwner)
        <div class="profile-actions mt-3">
            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#editProfileModal">
                editar perfil
            </button>
        </div>
        @endif
    </div>
</div>

@else
{{-- Vista normal para usuarios --}}
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

            </div>
        </div>
    </div>

    {{-- columna derecha --}}
    <div class="col-md-8">
        <div class="card profile-social mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="profile-name">{{ $user->name }} {{ $user->last_name }}</div>
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
                    </button>
                </div>
            </div>
        </div>
        </div>

        @php
            $anyFieldVisible = $isOwner || (
                $user->isFieldPublic('gender') ||
                $user->isFieldPublic('registration_date') ||
                $user->isFieldPublic('updated_at') ||
                $user->isFieldPublic('email') ||
                $user->isFieldPublic('phone') ||
                $user->isFieldPublic('country')
            );
        @endphp
        @if($anyFieldVisible)
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
                            @php
                                $showPersonalCol   = $isOwner || $user->isFieldPublic('gender');
                                $showAdditionalCol = $isOwner || $user->isFieldPublic('registration_date') || $user->isFieldPublic('updated_at');
                            @endphp

                            @if($showPersonalCol)
                            <div class="col-md-6">
                                <h4>Datos personales</h4>
                                @if($isOwner || $user->isFieldPublic('gender'))
                                <p class="profile-field-row">
                                    <span>Género:</span>
                                    <span class="profile-field-value">{{ $user->gender ?? 'no especificado' }}</span>
                                    @if($isOwner)
                                        <button class="privacy-toggle btn btn-sm {{ $user->isFieldPublic('gender') ? 'text-success' : 'text-secondary' }}"
                                            data-field="gender" data-visible="{{ $user->isFieldPublic('gender') ? '1' : '0' }}"
                                            title="{{ $user->isFieldPublic('gender') ? 'Público — clic para ocultar' : 'Privado — clic para publicar' }}">
                                            <i class="fas {{ $user->isFieldPublic('gender') ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>
                                    @endif
                                </p>
                                @endif
                            </div>
                            @endif

                            @if($showAdditionalCol)
                            <div class="col-md-6">
                                <h4>Información adicional</h4>
                                @if($isOwner || $user->isFieldPublic('registration_date'))
                                <p class="profile-field-row">
                                    <span>Fecha de registro:</span>
                                    <span class="profile-field-value">{{ $user->created_at->format('d/m/Y') }}</span>
                                    @if($isOwner)
                                        <button class="privacy-toggle btn btn-sm {{ $user->isFieldPublic('registration_date') ? 'text-success' : 'text-secondary' }}"
                                            data-field="registration_date" data-visible="{{ $user->isFieldPublic('registration_date') ? '1' : '0' }}"
                                            title="{{ $user->isFieldPublic('registration_date') ? 'Público — clic para ocultar' : 'Privado — clic para publicar' }}">
                                            <i class="fas {{ $user->isFieldPublic('registration_date') ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>
                                    @endif
                                </p>
                                @endif
                                @if($isOwner || $user->isFieldPublic('updated_at'))
                                <p class="profile-field-row">
                                    <span>Última actualización:</span>
                                    <span class="profile-field-value">{{ $user->updated_at->format('d/m/Y') }}</span>
                                    @if($isOwner)
                                        <button class="privacy-toggle btn btn-sm {{ $user->isFieldPublic('updated_at') ? 'text-success' : 'text-secondary' }}"
                                            data-field="updated_at" data-visible="{{ $user->isFieldPublic('updated_at') ? '1' : '0' }}"
                                            title="{{ $user->isFieldPublic('updated_at') ? 'Público — clic para ocultar' : 'Privado — clic para publicar' }}">
                                            <i class="fas {{ $user->isFieldPublic('updated_at') ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>
                                    @endif
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane" id="contact">
                        <div class="row">
                            @php
                                $showContactCol  = $isOwner || $user->isFieldPublic('email') || $user->isFieldPublic('phone');
                                $showLocationCol = $isOwner || $user->isFieldPublic('country');
                            @endphp

                            @if($showContactCol)
                            <div class="col-md-6">
                                <h4>Contacto</h4>
                                @if($isOwner || $user->isFieldPublic('email'))
                                <p class="profile-field-row">
                                    <span>Email:</span>
                                    <span class="profile-field-value">{{ $user->email }}</span>
                                    @if($isOwner)
                                        <button class="privacy-toggle btn btn-sm {{ $user->isFieldPublic('email') ? 'text-success' : 'text-secondary' }}"
                                            data-field="email" data-visible="{{ $user->isFieldPublic('email') ? '1' : '0' }}"
                                            title="{{ $user->isFieldPublic('email') ? 'Público — clic para ocultar' : 'Privado — clic para publicar' }}">
                                            <i class="fas {{ $user->isFieldPublic('email') ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>
                                    @endif
                                </p>
                                @endif
                                @if($isOwner || $user->isFieldPublic('phone'))
                                <p class="profile-field-row">
                                    <span>Teléfono:</span>
                                    <span class="profile-field-value">{{ $user->phone ?? 'no especificado' }}</span>
                                    @if($isOwner)
                                        <button class="privacy-toggle btn btn-sm {{ $user->isFieldPublic('phone') ? 'text-success' : 'text-secondary' }}"
                                            data-field="phone" data-visible="{{ $user->isFieldPublic('phone') ? '1' : '0' }}"
                                            title="{{ $user->isFieldPublic('phone') ? 'Público — clic para ocultar' : 'Privado — clic para publicar' }}">
                                            <i class="fas {{ $user->isFieldPublic('phone') ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>
                                    @endif
                                </p>
                                @endif
                            </div>
                            @endif

                            @if($showLocationCol)
                            <div class="col-md-6">
                                <h4>Ubicación</h4>
                                @if($isOwner || $user->isFieldPublic('country'))
                                <p class="profile-field-row">
                                    <span>Estado:</span>
                                    <span class="profile-field-value">{{ $user->country ?? 'no especificado' }}</span>
                                    @if($isOwner)
                                        <button class="privacy-toggle btn btn-sm {{ $user->isFieldPublic('country') ? 'text-success' : 'text-secondary' }}"
                                            data-field="country" data-visible="{{ $user->isFieldPublic('country') ? '1' : '0' }}"
                                            title="{{ $user->isFieldPublic('country') ? 'Público — clic para ocultar' : 'Privado — clic para publicar' }}">
                                            <i class="fas {{ $user->isFieldPublic('country') ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>
                                    @endif
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
        @endif

        <div class="card profile-recipes mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="m-0">{{ $isOwner ? 'Mis recetas' : 'Recetas' }}</h4>
                    <span class="text-muted">{{ $recipesCount }} publicaciones</span>
                </div>

                @if($recipes->isEmpty())
                    <div class="alert alert-light border mb-0">
                        {{ $isOwner ? 'Aun no tienes recetas publicadas.' : 'Este usuario aún no tiene recetas publicadas.' }}
                    </div>
                @elseif($isOwner)
                    {{-- Vista compacta para el dueño --}}
                    <div class="recipe-grid">
                        @foreach($recipes as $recipe)
                            <div class="recipe-item">
                                <div class="recipe-thumb">
                                    @if($recipe->image)
                                        <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->recipe_title }}">
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
                @else
                    {{-- Vista de tarjetas para visitantes --}}
                    @include('partials.recipe-ui-styles')
                    <div class="row">
                        @foreach($recipes as $recipe)
                            <div class="col-sm-6 col-md-4 mb-4">
                                @include('partials.recipe-card', [
                                    'recipe'            => $recipe,
                                    'showHoverPreview'  => true,
                                    'showFavoriteButton' => auth()->check(),
                                ])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($isOwner && !$user->isAdmin())
<div class="profile-actions">
    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#editProfileModal">
        editar perfil
    </button>
    <form action="{{ route('user.destroy', $user->id) }}" method="POST" id="deleteAccountForm">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-outline-danger btn-lg" id="deleteAccountBtn">
            eliminar cuenta
        </button>
    </form>
</div>
@endif

@endif {{-- @else (no admin) --}}
@stop

@section('js')
@parent

@if($isOwner)
{{-- Modal editar perfil --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Editar perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.perfil.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any() && (session('open_edit_modal') || $errors->has('current_password') || $errors->has('name') || $errors->has('email')))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size:.75rem; letter-spacing:.05em;">Información personal</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Apellido</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Género</label>
                            <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="">No especificado</option>
                                @foreach(['Masculino','Femenino','No binario','Prefiero no decirlo'] as $g)
                                    <option value="{{ $g }}" {{ old('gender', $user->gender) === $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Teléfono</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>País / Estado</label>
                            <input type="text" name="country" class="form-control"
                                value="{{ old('country', $user->country) }}">
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size:.75rem; letter-spacing:.05em;">Cambiar contraseña <small class="text-muted font-weight-normal">(dejar en blanco para no cambiar)</small></h6>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Contraseña actual</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nueva contraseña</label>
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" autocomplete="new-password">
                            @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>Confirmar contraseña</label>
                            <input type="password" name="new_password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-abrir modal si hay errores de validación
@if($errors->any() || session('open_edit_modal'))
$(document).ready(function() { $('#editProfileModal').modal('show'); });
@endif

// Confirmar eliminación de cuenta
document.getElementById('deleteAccountBtn').addEventListener('click', function() {
    if (window.confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) {
        document.getElementById('deleteAccountForm').submit();
    }
});
</script>
@endif

@if($isOwner)
<script>
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.privacy-toggle');
    if (!btn) return;

    var field = btn.dataset.field;
    var currentlyVisible = btn.dataset.visible === '1';
    var newVisible = !currentlyVisible;

    btn.classList.add('spinning');

    fetch('{{ route('user.privacy.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ field: field, visible: newVisible }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.ok) {
            btn.dataset.visible = newVisible ? '1' : '0';
            var icon = btn.querySelector('i');
            if (newVisible) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                btn.classList.remove('text-secondary');
                btn.classList.add('text-success');
                btn.title = 'Público — clic para ocultar';
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                btn.classList.remove('text-success');
                btn.classList.add('text-secondary');
                btn.title = 'Privado — clic para publicar';
            }
        }
    })
    .catch(function() {})
    .finally(function() { btn.classList.remove('spinning'); });
});
</script>
@endif

@if(!$isOwner)
@include('partials.recipe-preview-modal', [
    'modalId'   => 'recipePreviewModal',
    'titleId'   => 'recipePreviewTitle',
    'bodyId'    => 'recipePreviewBody',
])
@include('partials.recipe-preview-modal-script')
<script>
(function() {
    var recipePreview = window.createRecipePreviewModalController({
        modalId:         'recipePreviewModal',
        titleId:         'recipePreviewTitle',
        bodyId:          'recipePreviewBody',
        csrfToken:       '{{ csrf_token() }}',
        showUrlTemplate: '{{ route('recipes.show', '__ID__') }}',
    });

    // Abrir modal al pulsar "Ver receta"
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.view-recipe-btn');
        if (btn && recipePreview) recipePreview.open(btn.getAttribute('data-recipe-id'));
    });

    // Favoritos AJAX
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-favorite');
        if (!btn) return;
        var url = btn.dataset.favoriteUrl;
        if (!url) return;
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            btn.classList.toggle('active', data.favorited);
            btn.title = data.favorited ? 'Quitar de favoritos' : 'Agregar a favoritos';
        })
        .catch(function() {});
    });
})();
</script>
@endif

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
(function() {
    // Parse once from the raw attribute (before jQuery caches it as an object)
    var ratingsData = null;

    $('#ratingsModal').on('show.bs.modal', function() {
        var raw = document.getElementById('ratingsList')
                    ? document.getElementById('ratingsList').getAttribute('data-comments')
                    : null;
        if (raw) {
            try { ratingsData = JSON.parse(raw); } catch(e) { ratingsData = []; }
        }
    });

    $(document).on('click', '#ratingsModal .comment-filter', function() {
        if (!ratingsData) return;

        var button = $(this);
        var rating = button.attr('data-rating');

        $('#ratingsModal .comment-filter').removeClass('active');
        button.addClass('active');

        var ratingNumber = rating ? parseInt(rating, 10) : null;
        var filtered = ratingNumber
            ? ratingsData.filter(function(item) { return item.rating === ratingNumber; })
            : ratingsData;

        var html = filtered.length
            ? filtered.map(function(item) {
                var stars = '★'.repeat(item.rating) + '☆'.repeat(5 - item.rating);
                return '<div class="comment-item" data-rating="' + item.rating + '">'
                    + '<div class="comment-meta">'
                    + '<span class="comment-user">' + item.name + ' ' + item.last_name + '</span>'
                    + '<span class="comment-date">' + item.created_at + '</span>'
                    + '</div>'
                    + '<div class="comment-rating">' + stars + '</div>'
                    + '<div class="comment-text">' + item.comment + '</div>'
                    + '<div class="text-muted small mt-1">Receta: ' + item.recipe_title + '</div>'
                    + '</div>';
            }).join('')
            : '<div class="text-muted small">No hay comentarios con ese filtro.</div>';

        $('#ratingsList').html(html);
    });
})();
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
