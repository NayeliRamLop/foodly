@extends('adminlte::page')

@section('title', 'EDITAR USUARIO - COCINA CON GUSTO')

@section('content_header')
    <h1 class="m-0 text-dark" style="font-size: 2.5rem;">
        <i class="fas fa-user-edit mr-2" style="color: #F28241;"></i>EDITAR USUARIO
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
    <div class="col-md-8">
        <div class="card shadow-sm" style="border-top: 3px solid #a8c2e0;">
            <div class="card-body" style="background-color: #f8fafc;">
                <form method="POST" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" style="color: #4a5568;">
                                    <i class="fas fa-user mr-2" style="color: #F28241;"></i>NOMBRE
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" style="color: #4a5568;">
                                    <i class="fas fa-user mr-2" style="color: #F28241;"></i>APELLIDOS
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="{{ old('last_name', $user->last_name) }}" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender" style="color: #4a5568;">
                                    <i class="fas fa-venus-mars mr-2" style="color: #F28241;"></i>GÉNERO
                                </label>
                                <select id="gender" name="gender" class="form-control" required>
                                    <option value="Masculino" {{ old('gender', $user->gender) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ old('gender', $user->gender) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ old('gender', $user->gender) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" style="color: #4a5568;">
                                    <i class="fas fa-envelope mr-2" style="color: #F28241;"></i>CORREO ELECTRÓNICO
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $user->email) }}" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lada" style="color: #4a5568;">
                                    <i class="fas fa-phone mr-2" style="color: #F28241;"></i>LADA
                                </label>
                                <input type="text" class="form-control" id="lada" name="lada" maxlength="5" 
                                       value="{{ old('lada', $user->lada) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" style="color: #4a5568;">
                                    <i class="fas fa-mobile-alt mr-2" style="color: #F28241;"></i>TELÉFONO
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone" maxlength="10" 
                                       value="{{ old('phone', $user->phone) }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country" style="color: #4a5568;">
                                    <i class="fas fa-globe-americas mr-2" style="color: #F28241;"></i>PAÍS
                                </label>
                                <select id="country" name="country" class="form-control" required>
                                    <option value="Argentina" {{ old('country', $user->country) == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                                    <option value="Bolivia" {{ old('country', $user->country) == 'Bolivia' ? 'selected' : '' }}>Bolivia</option>
                                    <option value="Brasil" {{ old('country', $user->country) == 'Brasil' ? 'selected' : '' }}>Brasil</option>
                                    <option value="Chile" {{ old('country', $user->country) == 'Chile' ? 'selected' : '' }}>Chile</option>
                                    <option value="Colombia" {{ old('country', $user->country) == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                                    <option value="Costa Rica" {{ old('country', $user->country) == 'Costa Rica' ? 'selected' : '' }}>Costa Rica</option>
                                    <option value="Cuba" {{ old('country', $user->country) == 'Cuba' ? 'selected' : '' }}>Cuba</option>
                                    <option value="Ecuador" {{ old('country', $user->country) == 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                                    <option value="El Salvador" {{ old('country', $user->country) == 'El Salvador' ? 'selected' : '' }}>El Salvador</option>
                                    <option value="Guatemala" {{ old('country', $user->country) == 'Guatemala' ? 'selected' : '' }}>Guatemala</option>
                                    <option value="Honduras" {{ old('country', $user->country) == 'Honduras' ? 'selected' : '' }}>Honduras</option>
                                    <option value="México" {{ old('country', $user->country) == 'México' ? 'selected' : '' }}>México</option>
                                    <option value="Nicaragua" {{ old('country', $user->country) == 'Nicaragua' ? 'selected' : '' }}>Nicaragua</option>
                                    <option value="Panamá" {{ old('country', $user->country) == 'Panamá' ? 'selected' : '' }}>Panamá</option>
                                    <option value="Paraguay" {{ old('country', $user->country) == 'Paraguay' ? 'selected' : '' }}>Paraguay</option>
                                    <option value="Perú" {{ old('country', $user->country) == 'Perú' ? 'selected' : '' }}>Perú</option>
                                    <option value="Puerto Rico" {{ old('country', $user->country) == 'Puerto Rico' ? 'selected' : '' }}>Puerto Rico</option>
                                    <option value="República Dominicana" {{ old('country', $user->country) == 'República Dominicana' ? 'selected' : '' }}>República Dominicana</option>
                                    <option value="Uruguay" {{ old('country', $user->country) == 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
                                    <option value="Venezuela" {{ old('country', $user->country) == 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Espacio vacío para mantener el diseño -->
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-block" 
                                    style="background-color: #F28241; color: white;">
                                <i class="fas fa-save mr-2"></i> ACTUALIZAR
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('user.index') }}" class="btn btn-block btn-outline-secondary">
                                <i class="fas fa-times mr-2"></i> CANCELAR
                            </a>
                        </div>
                    </div>
                </form>
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
        border-color: #F28241;
        color: #F28241;
    }
    .btn-outline-secondary:hover {
        background-color: #F28241;
        color: white;
    }
    .form-control:focus {
        border-color: #a8c2e0;
        box-shadow: 0 0 0 0.2rem rgba(122, 156, 198, 0.25);
    }
</style>
@stop