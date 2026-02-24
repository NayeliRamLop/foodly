@extends('adminlte::page')

@section('title', 'CAMBIAR CONTRASEÑA - COCINA CON GUSTO')

@section('content_header')
    <h1 class="m-0 text-dark" style="font-size: 2.5rem;">
        <i class="fas fa-key mr-2" style="color: #7a9cc6;"></i>CAMBIAR CONTRASEÑA
    </h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check-circle mr-2"></i> {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-circle mr-2"></i> Error!</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm" style="border-top: 3px solid #a8c2e0;">
            <div class="card-body" style="background-color: #f8fafc;">
                <form method="POST" action="{{ route('profile.change-password.update') }}">
                    @csrf

                    <div class="form-group">
                        <label for="current_password" style="color: #4a5568;">
                            <i class="fas fa-lock mr-2" style="color: #7a9cc6;"></i>CONTRASEÑA ACTUAL
                        </label>
                        <input id="current_password" name="current_password" type="password" 
                               class="form-control" required autofocus
                               placeholder="Ingresa tu contraseña actual">
                    </div>

                    <div class="form-group mt-4">
                        <label for="password" style="color: #4a5568;">
                            <i class="fas fa-key mr-2" style="color: #7a9cc6;"></i>NUEVA CONTRASEÑA
                        </label>
                        <input id="password" name="password" type="password" 
                               class="form-control" required
                               placeholder="Ingresa tu nueva contraseña">
                    </div>

                    <div class="form-group mt-4">
                        <label for="password_confirmation" style="color: #4a5568;">
                            <i class="fas fa-check-circle mr-2" style="color: #7a9cc6;"></i>CONFIRMAR CONTRASEÑA
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" 
                               class="form-control" required
                               placeholder="Confirma tu nueva contraseña">
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-block" 
                                    style="background-color: #7a9cc6; color: white;">
                                <i class="fas fa-save mr-2"></i> ACTUALIZAR
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('user.show', Auth::id()) }}" class="btn btn-block btn-outline-secondary">
                                <i class="fas fa-times mr-2"></i> CANCELAR
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4 shadow-sm" style="border-top: 3px solid #a8c2e0;">
            <div class="card-body" style="background-color: #f8fafc;">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: #7a9cc6;"></i> Mínimo 8 caracteres</li>
                    <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: #7a9cc6;"></i> Combina letras y números</li>
                    <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: #7a9cc6;"></i> Usa mayúsculas y minúsculas</li>
                    <li><i class="fas fa-check-circle mr-2" style="color: #7a9cc6;"></i> Considera añadir símbolos especiales</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    body {
        background-color: #f5f7fa;
    }
    .card {
        border-radius: 0.5rem;
        border: none;
    }
    .btn-outline-secondary {
        border-color: #7a9cc6;
        color: #7a9cc6;
    }
    .btn-outline-secondary:hover {
        background-color: #7a9cc6;
        color: white;
    }
    .form-control:focus {
        border-color: #a8c2e0;
        box-shadow: 0 0 0 0.2rem rgba(122, 156, 198, 0.25);
    }
</style>
@stop