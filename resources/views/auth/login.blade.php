<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bienvenido a cocina con gusto</title>
  <!-- Incluir Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Anonymous+Pro" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

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
      padding-top: calc(45vh + 70px);
      gap: 4rem;
      min-height: 100vh;
    }

    /*Titulo Maquina Escribir*/
    .typewriter-container {
      position: absolute;
      top: 300px;
      right: 350px;
      width: 900px;
      height: auto;
      max-height: 500px;
      font-size: 2.3rem;
      white-space: normal;
      overflow: hidden;
      padding: 20px;
      color: rgb(75, 75, 75);
      font-weight: bold;
      letter-spacing: 0.05em;
      text-align: left;
      z-index: 10;
    }

    /* Logo */
    .logo-container {
      position: absolute;
      top: 300px;
      left: 350px;
      z-index: 10;
    }

    .logo-container img {
      height: 200px;
      width: auto;
    }


    /* Cuadro login centrado */
    .login-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 6rem 6rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 35%;
      color: #333;
      font-weight: normal;
      font-size: 1.2rem;
      box-sizing: border-box;
    }

    .login-box h2 {
      margin-top: 0;
      color: #F28241;
      text-align: center;
      margin-bottom: 1.5rem;
      font-weight: bold;
      font-size: 2rem;
    }

    .login-box input[type="email"],
    .login-box input[type="password"] {
      width: 80%;
      padding: 0.6rem;
      border-radius: 6px;
      border: 0.5px solid #d2d2d2;
      font-size: 1.3rem;
      display: block;
      margin: 0 auto 1rem;
    }

    .login-box button {
      width: 80%;
      padding: 0.6rem;
      background-color: #F28241;
      border: none;
      color: #ffffff;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1.3rem;
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

  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" alt="Foodly">
  </div>

  <div class="typewriter-container" id="typewriter">
   Bienvenido a Foodly, la red social donde las recetas se convierten en experiencias.
Descubre, comparte y cocina con gusto.
  </div> 


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
