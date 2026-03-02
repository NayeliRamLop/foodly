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

    <!-- CSS global -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

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
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
        <img src="{{ asset('images/logo.png') }}" alt="Foodly" height="34">
    </a>

    <form class="search-form d-none d-lg-flex mx-auto position-relative" action="{{ route('recipes.search') }}" method="GET" autocomplete="off">
        <input type="text" name="q" class="form-control" placeholder="Buscar recetas..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-search">Buscar</button>
        <div class="search-suggest d-none"></div>
    </form>

    <div class="nav-actions d-flex align-items-center gap-3">
        <a href="{{ route('user.create') }}" class="nav-link fw-medium register-link">
            Registrarse
        </a>

        <a href="{{ route('login') }}" class="btn btn-primary px-4">
            Iniciar sesion
        </a>
    </div>
</nav>

<!-- ================= CONTENIDO ================= -->
<main>
    @yield('content')
</main>

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
            <button type="button" class="suggest-item" data-value="${item.recipe_title}">
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
        input.value = target.getAttribute('data-value') || '';
        render([]);
        form.submit();
      });
    });
  })();
</script>

</body>
</html>
