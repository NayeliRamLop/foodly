@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        <li class="nav-item">
            <a href="{{ auth()->check() ? route('home') : url('/') }}" class="nav-link navbar-foodly-brand">
                <img src="{{ asset(config('adminlte.logo_img')) }}" alt="{{ config('adminlte.logo_img_alt', config('app.name', 'Foodly')) }}" class="navbar-foodly-logo">
            </a>
        </li>

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')

        @if(Auth::check())
            <li class="nav-item navbar-search-always">
                <form class="search-form d-none d-lg-flex mx-auto position-relative" action="{{ route('search.global') }}" method="GET" autocomplete="off">
                    <input class="form-control form-control-navbar" type="search"
                           name="q"
                           placeholder="Buscar recetas..."
                           aria-label="Buscar recetas...">
                    <button class="btn btn-search btn-navbar" type="submit">Buscar</button>
                </form>
            </li>
            @if(request()->is('home'))
                <li class="nav-item">
                    <span class="nav-link">
                        Bienvenido, {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                    </span>
                </li>
            @endif
        @endif
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
