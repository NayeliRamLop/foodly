<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Crear usuario - Cocina con Gusto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Anonymous+Pro" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Anonymous Pro', monospace;
      background-image: url('https://images.unsplash.com/photo-1551218808-94e220e084d2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
      background-size: cover;
      background-position: center;
      color: white;
      overflow-x: hidden;
    }

    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 6rem;
      box-sizing: border-box;
    }

    .typewriter-container {
      font-size: 3.5rem;
      white-space: nowrap;
      overflow: hidden;
      border-right: 2px solid rgba(255,255,255,0.75);
      width: 0;
      font-weight: bold;
      letter-spacing: 0.05em;
      max-width: 95vw;
      text-align: center;
      margin-bottom: 4rem;
      color: white;
    }

    .form-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 420px;
      color: #333;
      font-size: 1.2rem;
      margin: 2rem auto 3rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-box input[type="text"],
    .form-box input[type="email"],
    .form-box input[type="password"],
    .form-box input[type="date"],
    .form-box select {
      width: 100%;
      padding: 0.5rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
      margin-bottom: 0.5rem;
      box-sizing: border-box;
    }

    .form-box .phone-input {
      display: flex;
      gap: 0.5rem;
    }
    .form-box .phone-input input.lada {
      width: 70px;
      padding: 0.5rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
      text-align: center;
    }
    .form-box .phone-input input.phone-number {
      flex-grow: 1;
      padding: 0.5rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .form-box button {
      width: 100%;
      padding: 0.6rem;
      background-color: #1565c0;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1.1rem;
      margin-top: 1rem;
    }

    .form-box button:hover {
      background-color: #0d3c78;
    }

    .invalid-feedback {
      color: red;
      font-size: 0.875em;
      margin-top: -0.5rem;
      margin-bottom: 0.5rem;
    }

    .error-list {
      padding-left: 1rem;
      margin-top: 0.5rem;
      color: red;
      font-size: 0.9rem;
    }

    .login-link {
      margin-top: 1rem;
      text-align: center;
    }

    .login-link a {
      color: #1565c0;
      font-weight: bold;
      text-decoration: none;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg px-4 py-3 public-navbar fixed-top">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
      <img src="{{ asset('images/logo.png') }}" alt="Foodly" height="34">
      <span>FOODLY</span>
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

  <div class="typewriter-container" id="typewriter">Crear usuario   </div>

  <div class="form-box">
    @if ($errors->any())
      <div class="invalid-feedback" style="display:block;">
        <ul class="error-list">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('user.store') }}">
      @csrf

      <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required />
      </div>

      <div class="form-group">
        <label for="last_name">Apellidos</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required />
      </div>

      <div class="form-group">
        <label for="gender">Género</label>
        <select id="gender" name="gender" required>
          <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Selecciona género</option>
          <option value="Masculino" {{ old('gender')=='Masculino' ? 'selected' : '' }}>Masculino</option>
          <option value="Femenino" {{ old('gender')=='Femenino' ? 'selected' : '' }}>Femenino</option>
          <option value="Otro" {{ old('gender')=='Otro' ? 'selected' : '' }}>Otro</option>
        </select>
      </div>

      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required />
      </div>

      <div class="form-group phone-input">
        <div>
          <label for="lada">Lada</label>
          <input type="text" id="lada" name="lada" maxlength="5" placeholder="Lada" value="{{ old('lada') }}" />
        </div>
        <div style="flex-grow:1;">
          <label for="phone">Teléfono</label>
          <input type="text" id="phone" name="phone" maxlength="10" placeholder="Número" value="{{ old('phone') }}" />
        </div>
      </div>

      <div class="form-group">
        <label for="country">País</label>
        <select id="country" name="country" required>
          <option value="" disabled {{ old('country') ? '' : 'selected' }}>Selecciona país</option>
          <option value="Argentina" {{ old('country')=='Argentina' ? 'selected' : '' }}>Argentina</option>
          <option value="Bolivia" {{ old('country')=='Bolivia' ? 'selected' : '' }}>Bolivia</option>
          <option value="Brasil" {{ old('country')=='Brasil' ? 'selected' : '' }}>Brasil</option>
          <option value="Chile" {{ old('country')=='Chile' ? 'selected' : '' }}>Chile</option>
          <option value="Colombia" {{ old('country')=='Colombia' ? 'selected' : '' }}>Colombia</option>
          <option value="Costa Rica" {{ old('country')=='Costa Rica' ? 'selected' : '' }}>Costa Rica</option>
          <option value="Cuba" {{ old('country')=='Cuba' ? 'selected' : '' }}>Cuba</option>
          <option value="Ecuador" {{ old('country')=='Ecuador' ? 'selected' : '' }}>Ecuador</option>
          <option value="El Salvador" {{ old('country')=='El Salvador' ? 'selected' : '' }}>El Salvador</option>
          <option value="Guatemala" {{ old('country')=='Guatemala' ? 'selected' : '' }}>Guatemala</option>
          <option value="Honduras" {{ old('country')=='Honduras' ? 'selected' : '' }}>Honduras</option>
          <option value="México" {{ old('country')=='México' ? 'selected' : '' }}>México</option>
          <option value="Nicaragua" {{ old('country')=='Nicaragua' ? 'selected' : '' }}>Nicaragua</option>
          <option value="Panamá" {{ old('country')=='Panamá' ? 'selected' : '' }}>Panamá</option>
          <option value="Paraguay" {{ old('country')=='Paraguay' ? 'selected' : '' }}>Paraguay</option>
          <option value="Perú" {{ old('country')=='Perú' ? 'selected' : '' }}>Perú</option>
          <option value="Puerto Rico" {{ old('country')=='Puerto Rico' ? 'selected' : '' }}>Puerto Rico</option>
          <option value="República Dominicana" {{ old('country')=='República Dominicana' ? 'selected' : '' }}>República Dominicana</option>
          <option value="Uruguay" {{ old('country')=='Uruguay' ? 'selected' : '' }}>Uruguay</option>
          <option value="Venezuela" {{ old('country')=='Venezuela' ? 'selected' : '' }}>Venezuela</option>
        </select>
      </div>

      <div class="form-group">
        <label for="registration_date">Fecha de Registro</label>
        <input type="date" id="registration_date" name="registration_date" value="{{ old('registration_date') }}" required />
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirmar Contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required />
      </div>

      <button type="submit">Crear cuenta</button>
    </form>

    <div class="login-link">
      <a href="{{ route('login') }}">Regresar a login</a>
    </div>
  </div>

  <script>
    const tw = document.getElementById('typewriter');
    const text = "Crear usuario   ";
    const extraSpaces = 3;
    let i = 0;
    let isDeleting = false;
    let isPaused = false;

    function typeWriter() {
      let displayText = "";

      if (!isDeleting) {
        if (i <= text.length) {
          displayText = text.substring(0, i);
        } else if (i <= text.length + extraSpaces) {
          displayText = text + " ".repeat(i - text.length);
        }

        tw.textContent = displayText;
        tw.style.width = displayText.length + 'ch';
        tw.style.borderRight = '2px solid rgba(255,255,255,0.75)';
        i++;

        if (i > text.length + extraSpaces) {
          isPaused = true;
          setTimeout(() => {
            isPaused = false;
            isDeleting = true;
            typeWriter();
          }, 1500);
          return;
        }

      } else {
        i--;
        if (i >= 0) {
          if (i <= text.length) {
            displayText = text.substring(0, i);
          } else {
            displayText = text + " ".repeat(i - text.length);
          }

          tw.textContent = displayText;
          tw.style.width = displayText.length + 'ch';
          tw.style.borderRight = '2px solid rgba(255,255,255,0.75)';
        } else {
          isDeleting = false;
          setTimeout(typeWriter, 700);
          return;
        }
      }

      const speed = isDeleting ? 80 : 150;
      if (!isPaused) {
        setTimeout(typeWriter, speed);
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

</body>
</html>
