@php
    $guestRecipeRedirect = old('redirect_to', session('guest_recipe_redirect_to', route('home')));
    $guestRecipeReturn = old('return_to', url()->current());
    $guestRecipeTab = old('auth_modal_tab', session('guest_recipe_modal_tab', 'login'));
@endphp

<div class="modal fade" id="guestRecipeAuthModal" tabindex="-1" aria-labelledby="guestRecipeAuthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content guest-auth-modal">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="guestRecipeAuthModalLabel">Para ver esta receta necesitas una cuenta</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any() && old('auth_modal_tab'))
                    <div class="alert alert-danger mb-3">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <ul class="nav nav-pills justify-content-center mb-4 guest-auth-tabs" id="guestRecipeAuthTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $guestRecipeTab === 'login' ? 'active' : '' }}" id="guest-login-tab" data-bs-toggle="pill" data-bs-target="#guest-login-pane" type="button" role="tab">
                            Iniciar sesión
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $guestRecipeTab === 'register' ? 'active' : '' }}" id="guest-register-tab" data-bs-toggle="pill" data-bs-target="#guest-register-pane" type="button" role="tab">
                            Crear cuenta
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade {{ $guestRecipeTab === 'login' ? 'show active' : '' }}" id="guest-login-pane" role="tabpanel">
                        <form method="POST" action="{{ route('login') }}" class="guest-auth-form">
                            @csrf
                            <input type="hidden" name="redirect_to" class="guest-redirect-input" value="{{ $guestRecipeRedirect }}">
                            <input type="hidden" name="return_to" class="guest-return-input" value="{{ $guestRecipeReturn }}">
                            <input type="hidden" name="auth_modal_tab" value="login">

                            <div class="form-group">
                                <label for="guest_login_email">Correo electrónico</label>
                                <input type="email" id="guest_login_email" name="email" class="form-control" value="{{ old('auth_modal_tab') === 'login' ? old('email') : '' }}" required>
                            </div>

                            <div class="form-group">
                                <label for="guest_login_password">Contraseña</label>
                                <input type="password" id="guest_login_password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Entrar y ver receta</button>
                        </form>
                    </div>

                    <div class="tab-pane fade {{ $guestRecipeTab === 'register' ? 'show active' : '' }}" id="guest-register-pane" role="tabpanel">
                        <form method="POST" action="{{ route('guest.recipe-auth.register') }}" class="guest-auth-form guest-auth-register-form">
                            @csrf
                            <input type="hidden" name="redirect_to" class="guest-redirect-input" value="{{ $guestRecipeRedirect }}">
                            <input type="hidden" name="return_to" class="guest-return-input" value="{{ $guestRecipeReturn }}">
                            <input type="hidden" name="auth_modal_tab" value="register">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_name">Nombre</label>
                                        <input type="text" id="guest_register_name" name="name" class="form-control" value="{{ old('auth_modal_tab') === 'register' ? old('name') : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_last_name">Apellidos</label>
                                        <input type="text" id="guest_register_last_name" name="last_name" class="form-control" value="{{ old('auth_modal_tab') === 'register' ? old('last_name') : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_gender">Género</label>
                                        <select id="guest_register_gender" name="gender" class="form-control" required>
                                            <option value="">Selecciona género</option>
                                            @foreach(['Masculino', 'Femenino', 'Otro'] as $gender)
                                                <option value="{{ $gender }}" {{ old('auth_modal_tab') === 'register' && old('gender') === $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_email">Correo electrónico</label>
                                        <input type="email" id="guest_register_email" name="email" class="form-control" value="{{ old('auth_modal_tab') === 'register' ? old('email') : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_phone">Teléfono</label>
                                        <input type="text" id="guest_register_phone" name="phone" class="form-control" value="{{ old('auth_modal_tab') === 'register' ? old('phone') : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_country">Estado</label>
                                        <select id="guest_register_country" name="country" class="form-control" required>
                                            <option value="">Selecciona estado</option>
                                            @foreach(['Aguascalientes','Baja California','Baja California Sur','Campeche','Chiapas','Chihuahua','Ciudad de México','Coahuila','Colima','Durango','Estado de México','Guanajuato','Guerrero','Hidalgo','Jalisco','Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla','Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas'] as $state)
                                                <option value="{{ $state }}" {{ old('auth_modal_tab') === 'register' && old('country') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_date">Fecha de registro</label>
                                        <input type="date" id="guest_register_date" name="registration_date" class="form-control" value="{{ old('auth_modal_tab') === 'register' ? old('registration_date') : now()->toDateString() }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_register_password">Contraseña</label>
                                        <input type="password" id="guest_register_password" name="password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="guest_register_password_confirmation">Confirmar contraseña</label>
                                        <input type="password" id="guest_register_password_confirmation" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .guest-auth-tabs .nav-link {
        background: #ffffff !important;
        color: var(--primary) !important;
        border: 1px solid #f2cfb4 !important;
        border-radius: 14px !important;
        padding: 0.55rem 1.2rem !important;
        font-weight: 700 !important;
    }

    .guest-auth-tabs .nav-link.active,
    .guest-auth-tabs .show > .nav-link {
        background: var(--primary) !important;
        color: #ffffff !important;
        border-color: var(--primary) !important;
    }

    .guest-auth-tabs .nav-link:hover {
        background: #fff7ef !important;
        color: var(--primary) !important;
    }

    .guest-auth-tabs .nav-link.active:hover,
    .guest-auth-tabs .show > .nav-link:hover {
        background: color-mix(in srgb, var(--primary) 85%, black) !important;
        color: #ffffff !important;
    }

    #guestRecipeAuthModal .modal-header .close {
        color: #ffffff !important;
        font-size: 1.6rem !important;
        font-weight: 700 !important;
        line-height: 1 !important;
        opacity: 1 !important;
        text-shadow: none !important;
        background: transparent !important;
        border: 0 !important;
        padding: 0 !important;
        min-height: auto !important;
    }
</style>
