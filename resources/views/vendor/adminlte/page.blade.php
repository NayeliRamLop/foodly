@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.main-sidebar');
            const sidebarLinks = document.querySelectorAll('.main-sidebar .nav-link[href]:not([href="#"])');
            const toggler = document.querySelector('[data-widget="pushmenu"]');

            const closeSidebar = function () {
                document.body.classList.remove('sidebar-open');
                document.body.classList.remove('sidebar-collapse');
                document.body.classList.remove('sidebar-is-opening');
            };

            sidebarLinks.forEach((link) => {
                link.addEventListener('click', function () {
                    closeSidebar();
                });
            });

            if (sidebar) {
                sidebar.addEventListener('mouseleave', function () {
                    closeSidebar();
                });
            }

            document.addEventListener('click', function (event) {
                const clickedInsideSidebar = sidebar && sidebar.contains(event.target);
                const clickedToggler = toggler && toggler.contains(event.target);

                if (!clickedInsideSidebar && !clickedToggler) {
                    closeSidebar();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('click', function (event) {
            const closeButton = event.target.closest('.modal .close, .modal [data-dismiss="modal"], .modal [data-bs-dismiss="modal"]');

            if (!closeButton) {
                return;
            }

            event.preventDefault();

            const modalElement = closeButton.closest('.modal');

            if (!modalElement) {
                return;
            }

            if (window.bootstrap && window.bootstrap.Modal) {
                const instance = window.bootstrap.Modal.getInstance(modalElement) || new window.bootstrap.Modal(modalElement);
                instance.hide();
                return;
            }

            if (window.jQuery && typeof window.jQuery(modalElement).modal === 'function') {
                window.jQuery(modalElement).modal('hide');
            }
        });
    </script>
@stop
