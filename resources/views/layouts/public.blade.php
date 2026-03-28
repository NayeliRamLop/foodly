<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Cocina con Gusto')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tipografía -->
    <link href="https://fonts.googleapis.com/css2?family=Helvetica+Neue:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-public.css') }}">

    <style>
        main {
            padding-top: 60px;
        }
    </style>

    @yield('css')
</head>
<body>

<!-- ================= NAVBAR PÚBLICO ================= -->
<nav class="navbar navbar-expand-lg px-4 py-3 public-navbar fixed-top">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ auth()->check() ? route('home') : url('/') }}">
        <img src="{{ asset('images/logo.png') }}" alt="Foodly" height="34">
    </a>

    <form class="search-form d-none d-lg-flex mx-auto position-relative" action="{{ route('recipes.search') }}" method="GET" autocomplete="off">
        <input type="text" name="q" class="form-control" placeholder="Buscar recetas..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-search">Buscar</button>
        <div class="search-suggest d-none"></div>
    </form>

    <div class="nav-actions d-flex align-items-center gap-3">
      @guest
        <a href="{{ route('user.create') }}" class="nav-link fw-medium register-link">
          Registrarse
        </a>

        <a href="{{ route('login') }}" class="btn btn-primary px-4">
          Iniciar sesion
        </a>
      @else
        <a href="{{ route('home') }}" class="btn btn-primary px-4">
          Ir a inicio
        </a>

        <form method="POST" action="{{ route('logout') }}" class="m-0">
          @csrf
          <button type="submit" class="btn btn-outline-secondary px-4">
            Cerrar sesion
          </button>
        </form>
      @endguest
    </div>
</nav>

<!-- ================= CONTENIDO ================= -->
<main>
    @yield('content')
</main>

@guest
    @include('partials.guest-recipe-auth-modal')
@endguest

<!-- ================= FOOTER OPCIONAL ================= -->
<footer class="text-center py-4 text-muted small">
    © {{ date('Y') }} Cocina con Gusto · Todos los derechos reservados
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('js')

<script>
  (function () {
    const forms = document.querySelectorAll('.search-form');
    if (!forms.length) return;

    const debounce = (fn, wait) => {
      let t;
      return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), wait);
      };
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
          const imageUrl = item.image ? `/storage/${item.image}` : '';
          const imageHtml = imageUrl
            ? `<img src="${imageUrl}" alt="" class="suggest-thumb">`
            : `<div class="suggest-thumb placeholder"></div>`;
          return `
            <button type="button" class="suggest-item" data-value="${item.recipe_title}" data-id="${item.id}">
              ${imageHtml}
              <span class="suggest-text">${item.recipe_title}</span>
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
          const resp = await fetch(`{{ route('recipes.suggest') }}?q=${encodeURIComponent(value)}`);
          if (!resp.ok) return;
          const data = await resp.json();
          render(data);
        } catch (err) {
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
        const recipeId = target.getAttribute('data-id');
        if (!recipeId) return;
        if (@json(auth()->check())) {
          window.location.href = `{{ route('recipes.index') }}?open_recipe=${recipeId}`;
          return;
        }
        if (window.openGuestRecipeAuthModal) {
          window.openGuestRecipeAuthModal(`{{ route('home') }}?open_recipe=${recipeId}`);
        }
      });
    });
  })();
</script>

@guest
<script>
  (function () {
    const modalEl = document.getElementById('guestRecipeAuthModal');
    if (!modalEl) return;

    const redirectInputs = modalEl.querySelectorAll('.guest-redirect-input');
    const returnInputs = modalEl.querySelectorAll('.guest-return-input');
    const currentUrl = window.location.href;

    const setModalTarget = (redirectUrl) => {
      redirectInputs.forEach((input) => {
        input.value = redirectUrl || `{{ route('home') }}`;
      });

      returnInputs.forEach((input) => {
        input.value = currentUrl;
      });
    };

    window.openGuestRecipeAuthModal = (redirectUrl) => {
      setModalTarget(redirectUrl);
      const instance = new bootstrap.Modal(modalEl);
      instance.show();
    };

    document.addEventListener('click', (event) => {
      const trigger = event.target.closest('.js-recipe-auth-trigger');
      if (!trigger) return;

      event.preventDefault();
      window.openGuestRecipeAuthModal(trigger.getAttribute('data-redirect-url'));
    });

    if (@json(session('guest_recipe_open_modal', false)) || @json((bool) old('auth_modal_tab'))) {
      setModalTarget(@json(old('redirect_to', session('guest_recipe_redirect_to', route('home')))));
      const instance = new bootstrap.Modal(modalEl);
      instance.show();
    }
  })();
</script>
@endguest

<script>
  (function () {
    const hideModal = (modalEl) => {
      if (!modalEl) return;

      if (window.bootstrap && window.bootstrap.Modal) {
        const instance = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
        instance.hide();
        return;
      }

      if (window.jQuery && typeof window.jQuery(modalEl).modal === 'function') {
        window.jQuery(modalEl).modal('hide');
      }
    };

    document.addEventListener('click', (event) => {
      const closeButton = event.target.closest('.modal .close, .modal [data-dismiss="modal"], .modal [data-bs-dismiss="modal"]');
      if (!closeButton) return;

      event.preventDefault();
      hideModal(closeButton.closest('.modal'));
    });
  })();
</script>

</body>
</html>
