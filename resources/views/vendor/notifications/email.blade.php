<x-mail::message>
{{-- Header con Logo --}}
<x-mail::header>
    <div style="text-align: center; margin-bottom: 25px;">
        <a href="{{ url('/') }}">
            @if(config('app.logo'))
                <img src="{{ asset('storage/' . config('app.logo')) }}" alt="{{ config('app.name') }}" style="height: 60px;">
            @else
                <h1 style="color: #2563eb; margin: 0; font-size: 24px;">{{ config('app.name') }}</h1>
            @endif
        </a>
    </div>
</x-mail::header>

{{-- Línea decorativa --}}
<div style="height: 4px; background: linear-gradient(to right, #2563eb, #1e40af); margin-bottom: 25px;"></div>

{{-- Saludo --}}
<h1 style="color: #1e40af; font-size: 20px; font-weight: 600; margin-bottom: 20px;">
    ¡Hola, {{ $user->name ?? 'Usuario' }}!
</h1>

{{-- Contenido principal --}}
<div style="color: #333; line-height: 1.6; margin-bottom: 25px;">
    <p>Recibiste este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.</p>
    
    <p>Por favor haz clic en el siguiente botón para crear una nueva contraseña:</p>
</div>

{{-- Botón de acción --}}
@isset($actionText)
<x-mail::button :url="$actionUrl" color="primary" style="background-color: #2563eb; border-radius: 8px; padding: 12px 24px; font-weight: 600;">
    {{ $actionText }}
</x-mail::button>
@endisset

{{-- Tiempo de expiración --}}
<div style="margin-top: 20px; color: #666; font-size: 14px;">
    <p><strong>Importante:</strong> Este enlace expirará en {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutos.</p>
</div>

{{-- Advertencia de seguridad --}}
<div style="margin-top: 30px; padding: 15px; background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px;">
    <p style="color: #666; margin: 0; font-size: 14px;">
        <strong>Seguridad:</strong> Si no solicitaste este cambio, por favor ignora este mensaje y considera 
        <a href="{{ route('password.request') }}" style="color: #2563eb;">cambiar tu contraseña</a> 
        como medida de precaución.
    </p>
</div>

{{-- Footer --}}
<x-mail::footer>
    <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 30px; text-align: center; color: #6b7280; font-size: 12px;">
        <p style="margin-bottom: 5px;">© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        
        <div style="margin-top: 10px;">
            <a href="{{ url('/') }}" style="color: #2563eb; text-decoration: none; margin: 0 10px;">Inicio</a>
            <a href="{{ route('privacy.policy') }}" style="color: #2563eb; text-decoration: none; margin: 0 10px;">Política de Privacidad</a>
            <a href="{{ route('contact') }}" style="color: #2563eb; text-decoration: none; margin: 0 10px;">Contacto</a>
        </div>
    </div>
</x-mail::footer>

{{-- Subcopy para clientes de email que no soportan botones --}}
@isset($actionText)
<x-slot:subcopy>
    <div style="margin-top: 30px; font-size: 12px; color: #6b7280; line-height: 1.5;">
        <p>Si tienes problemas con el botón, copia y pega esta URL en tu navegador:</p>
        <a href="{{ $actionUrl }}" style="color: #2563eb; word-break: break-all; text-decoration: none;">
            {{ $displayableActionUrl }}
        </a>
    </div>
</x-slot:subcopy>
@endisset
</x-mail::message>