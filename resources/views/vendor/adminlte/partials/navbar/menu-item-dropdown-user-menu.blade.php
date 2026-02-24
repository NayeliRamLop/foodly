@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )

@if (config('adminlte.use_route_url', false))
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item">
    {{-- Botón simple de logout --}}
    <a class="nav-link text-danger" 
       href="#"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       title="Cerrar sesión"
       data-toggle="tooltip">
        <i class="fas fa-fw fa-sign-out-alt"></i>
        <span class="d-none d-md-inline-block">{{ __('Cerrar sesión') }}</span>
    </a>

    {{-- Formulario de logout oculto --}}
    <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
        @if(config('adminlte.logout_method'))
            {{ method_field(config('adminlte.logout_method')) }}
        @endif
        @csrf
    </form>
</li>

@push('js')
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush