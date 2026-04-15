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
    <script>
        (function () {
            const forms = document.querySelectorAll('.search-form');
            if (!forms.length) return;

            const debounce = (fn, wait) => {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn(...args), wait);
                };
            };

            const resolveMediaUrl = (value) => {
                const source = String(value ?? '').trim();
                if (!source) {
                    return '';
                }

                if (/^(https?:)?\/\//i.test(source) || source.startsWith('data:') || source.startsWith('blob:')) {
                    return source;
                }

                return `/${source.replace(/^\/+/, '')}`;
            };

            forms.forEach((form) => {
                const input = form.querySelector('input[name="q"]');
                const box = form.querySelector('.search-suggest');
                if (!input || !box) return;

                const render = (items) => {
                    if (!items.length) {
                        box.classList.add('d-none');
                        box.innerHTML = '';
                        return;
                    }

                    box.innerHTML = items.map((item) => {
                        const imageUrl = item.image ? resolveMediaUrl(item.image) : '';
                        const imageHtml = imageUrl
                            ? `<img src="${imageUrl}" alt="" class="suggest-thumb">`
                            : `<div class="suggest-thumb placeholder"></div>`;
                        const kind = item.type === 'user' ? 'Persona' : 'Receta';

                        return `
                            <button type="button" class="suggest-item" data-type="${item.type}" data-id="${item.id}" data-value="${item.label}">
                                ${imageHtml}
                                <span class="suggest-meta">
                                    <span class="suggest-text">${item.label}</span>
                                    <span class="suggest-kind">${kind}</span>
                                </span>
                            </button>
                        `;
                    }).join('');

                    box.classList.remove('d-none');
                };

                const fetchSuggestions = debounce(async () => {
                    const value = input.value.trim();
                    if (value.length < 1) {
                        render([]);
                        return;
                    }

                    try {
                        const resp = await fetch(`{{ route('search.global.suggest') }}?q=${encodeURIComponent(value)}`);
                        if (!resp.ok) return;
                        const data = await resp.json();
                        render(data);
                    } catch (error) {
                        render([]);
                    }
                }, 200);

                input.addEventListener('input', fetchSuggestions);
                document.addEventListener('click', (event) => {
                    if (!form.contains(event.target)) {
                        render([]);
                    }
                });

                box.addEventListener('click', (event) => {
                    const target = event.target.closest('.suggest-item');
                    if (!target) return;

                    render([]);

                    const type = target.getAttribute('data-type');
                    const id = target.getAttribute('data-id');
                    const value = target.getAttribute('data-value') || '';

                    if (type === 'user' && id) {
                        window.location.href = `{{ url('/perfil') }}/${id}`;
                        return;
                    }

                    window.location.href = `{{ route('search.global') }}?q=${encodeURIComponent(value)}`;
                });
            });
        })();
    </script>
@stop
