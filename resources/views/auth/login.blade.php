<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bienvenido a cocina con gusto</title>
  <!-- Incluir Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Anonymous+Pro" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/custom-public.css') }}">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Anonymous Pro', monospace;
      background-size: cover;
      background-image: url('/images/fondo-04.jpg');
      background-position: center;
      color: white;
      overflow-x: hidden;
    }

    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start; 
      padding-top: 110px;
      gap: 2rem;
      min-height: 100vh;
    }

    .hero-row {
      width: min(980px, 94vw);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      margin-top: 0.4rem;
    }

    /*Titulo Maquina Escribir*/
    .typewriter-container {
      position: static;
      width: min(860px, 92vw);
      height: auto;
      max-height: none;
      font-size: clamp(1.1rem, 1.35vw, 1.5rem);
      white-space: normal;
      overflow: hidden;
      padding: 20px;
      color: rgb(75, 75, 75);
      font-weight: bold;
      letter-spacing: 0.05em;
      text-align: center;
      z-index: 10;
    }

    .public-navbar {
      padding-top: 0.35rem !important;
      padding-bottom: 0.35rem !important;
    }

    .public-navbar .search-form {
      max-width: 520px;
    }

    .public-navbar .register-link,
    .public-navbar .btn-primary {
      font-size: 0.95rem !important;
      padding: 0.35rem 0.95rem !important;
    }

    .public-navbar .btn-primary,
    .public-navbar .btn-primary:hover,
    .public-navbar .btn-primary:active,
    .public-navbar .btn-primary:focus,
    .public-navbar .btn-primary:focus-visible {
      background-color: color-mix(in srgb, #F28241 85%, black) !important;
      border-color: color-mix(in srgb, #F28241 85%, black) !important;
      color: #ffffff !important;
      box-shadow: none !important;
    }

    /* Logo */
    .logo-container {
      position: static;
      flex: 0 0 auto;
      z-index: 10;
    }

    .logo-container img {
      height: clamp(90px, 7vw, 125px);
      width: auto;
    }


    /* Cuadro login centrado */
    .login-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 2rem 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      width: min(500px, 92vw);
      max-width: none;
      color: #333;
      font-weight: normal;
      font-size: 1.0rem;
      box-sizing: border-box;
    }

    .login-box h2 {
      margin-top: 0;
      color: #F28241;
      text-align: center;
      margin-bottom: 1.0rem;
      font-weight: bold;
      font-size: clamp(1rem, 1.5vw, 1.6rem) !important;
    }

    .login-box input[type="email"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 0.5rem;
      border-radius: 6px;
      border: 0.5px solid #d2d2d2;
      font-size: 0.85rem;
      display: block;
      margin: 0 auto 1rem;
    }

    .login-box button {
      width: 100%;
      padding: 0.4rem;
      background-color: #F28241;
      border: none;
      color: #ffffff;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s;
      display: block;
      margin: 0 auto;
    }

    .login-box button:hover {
      background-color: #d97031;
      color: #ffffff;
    }

    .links {
      text-align: center;
      margin-top: 1rem;
      font-size: 1rem;
    }

    .links a {
      color: rgba(93, 93, 93, 0.6);
      text-decoration: none;
      display: block;
      margin: 0.3rem 0;
      transition: color 0.3s;
      font-size: 1.1rem;
    }

    .links a:hover {
      color: #F28241;
    }

    @media (max-width: 1600px) {
      .hero-row {
        width: min(980px, 94vw);
      }
    }

    @media (max-width: 1200px) {
      .typewriter-container {
        width: min(760px, 92vw);
        font-size: clamp(1rem, 1.25vw, 1.3rem);
      }

      .logo-container img {
        height: 95px;
      }

      .login-box {
        width: min(500px, 92vw);
        padding: 2rem 1.6rem;
      }
    }

    @media (max-width: 992px) {
      body {
        padding-top: 130px;
        gap: 2rem;
      }

      .hero-row {
        width: 94vw;
        justify-content: center;
        gap: 0.5rem;
      }

      .logo-container {
        position: static;
      }

      .typewriter-container {
        position: static;
        width: min(92vw, 700px);
        max-height: none;
        font-size: 1.1rem;
        text-align: center;
        padding: 0.5rem 1rem;
      }

      .public-navbar .register-link,
      .public-navbar .btn-primary {
        font-size: 0.9rem !important;
        padding: 0.4rem 0.8rem !important;
      }

      .login-box {
        width: min(500px, 92vw);
        padding: 2rem 1.4rem;
      }
    }

    @media (max-width: 576px) {
      .logo-container img {
        height: 84px;
      }

      .typewriter-container {
        font-size: 1rem;
      }

      .login-box {
        width: min(500px, 92vw);
        padding: 1.6rem 1rem;
        font-size: 0.95rem;
      }

      .login-box h2 {
        font-size: 1.75rem !important;
      }

      .login-box input[type="email"],
      .login-box input[type="password"],
      .login-box button {
        width: 100%;
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg px-4 py-3 public-navbar fixed-top">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
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

  <section class="hero-row">
    <div class="logo-container">
      <img src="{{ asset('images/logo.png') }}" alt="Foodly">
    </div>

    <div class="typewriter-container" id="typewriter">
     Bienvenido a Foodly, la red social donde las recetas se convierten en experiencias.
Descubre, comparte y cocina con gusto.
    </div>
  </section>


  <div class="login-box">
    <!-- Mensaje de éxito después del registro -->
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <!-- Mensaje de error general -->
    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          {{ $error }}<br>
        @endforeach
      </div>
    @endif

    <!-- Mensaje específico para cuenta desactivada -->
    @if (session('status') == 'account-disabled')
      <div class="alert alert-warning">
        Tu cuenta ha sido desactivada. Por favor contacta al administrador.
      </div>
    @endif

    <h2>Iniciar sesión</h2>
    <form action="{{ route('login') }}" method="POST">
      @csrf

      <div class="form-group mb-3">
        <input type="email" name="email" placeholder="Email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required autofocus>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group mb-3">
        <input type="password" name="password" placeholder="Contraseña"
               class="form-control @error('password') is-invalid @enderror" required>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      
      <div class="form-group">
        <button type="submit">Ingresar</button>
      </div>
    </form>

    <div class="links">
      <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
      <a href="{{ route('user.create') }}">Registrarme</a>
    </div>
  </div>

  <script>
    const typewriterEl = document.getElementById('typewriter');
    const fullText = typewriterEl.textContent.trim();
    typewriterEl.textContent = '';
    
    let index = 0;
    function typeWriter() {
      if (index < fullText.length) {
        typewriterEl.textContent += fullText[index];
        index++;
        setTimeout(typeWriter, 50); // 50ms entre cada letra
      }
    }
    
    typeWriter();
  </script>
  <script>
    (function () {
      const form = document.querySelector('.search-form');
      if (!form) return;
      const input = form.querySelector('input[name="q"]');
      const box = form.querySelector('.search-suggest');
      if (!input || !box) return;

      const debounce = (fn, wait) => {
        let t;
        return (...args) => {
          clearTimeout(t);
          t = setTimeout(() => fn(...args), wait);
        };
      };

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
    })();
  </script>

  <!-- Incluir Bootstrap JS (opcional, solo si necesitas funcionalidades JS de Bootstrap) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
