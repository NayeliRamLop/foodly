<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Crear usuario - Cocina con Gusto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Anonymous+Pro" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/app-theme.css') }}">
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
      overflow-y: auto;
    }

    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start; 
      padding-top: 110px;
      padding-bottom: 50px;
      gap: 2.5rem;
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

    .typewriter-container {
      position: static;
      width: min(600px, 92vw);
      min-height: 3.2em;
      height: auto;
      max-height: none;
      font-size: clamp(1.1rem, 1.35vw, 1.5rem);
      white-space: normal;
      overflow: hidden;
      padding: 14px;
      color: rgb(75, 75, 75);
      font-weight: bold;
      letter-spacing: 0.04em;
      text-align: center;
      z-index: 10;
    }

    /* Logo */
    .logo-container {
      position: static;
      flex: 0 0 auto;
      z-index: 10;
    }

    .logo-container img {
      height: clamp(90px, 7vw, 118px);
      width: auto;
    }

    .form-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 2.3rem 2.3rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      width: min(68vw, 680px);
      color: #333;
      font-weight: normal;
      font-size: 1rem;
      box-sizing: border-box;
      overflow: visible;
    }

    .form-box form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-box .form-group-full {
      grid-column: 1 / -1;
       width: 50%;
       justify-self: center;
    }

    .form-box h2 {
      margin-top: 0;
      color: #F28241;
      text-align: center;
      margin-bottom: 1rem;
      font-weight: bold;
      font-size: clamp(1rem, 1.5vw, 1.6rem) !important;
    }

    .form-group {
      margin-bottom: 0;
      display: flex;
      flex-direction: column;
      min-height: 58px;
      justify-content: space-between;
      position: relative;
    }

    .form-group label {
      color: #414141;
      font-weight: 600;
      display: block;
      margin-bottom: 0.25rem;
      font-size: 1rem;
      height: 1.25em;
    }

    .form-box input[type="text"],
    .form-box input[type="email"],
    .form-box input[type="password"],
    .form-box input[type="date"],
    .form-box select {
      width: 100%;
      padding: 0.5rem;
      border-radius: 6px;
      border: 1px solid #b9bec6;
      font-size: 0.85rem;
      box-sizing: border-box;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
      outline: none;
    }

    .form-box input[type="text"]:hover,
    .form-box input[type="email"]:hover,
    .form-box input[type="password"]:hover,
    .form-box input[type="date"]:hover,
    .form-box select:hover {
      border-color: #a8afb9;
    }

    .form-box input[type="text"]:focus,
    .form-box input[type="email"]:focus,
    .form-box input[type="password"]:focus,
    .form-box input[type="date"]:focus,
    .form-box select:focus {
      border-color: #949daa;
      box-shadow: 0 0 0 0.12rem rgba(120, 130, 145, 0.18);
    }

    .password-confirm-group .confirm-check {
      position: absolute;
      right: 10px;
      top: 34px;
      color: #2f9e44;
      font-size: 0.95rem;
      font-weight: bold;
      opacity: 0;
      transition: opacity 0.2s ease;
      pointer-events: none;
    }

    .password-confirm-group.is-match input {
      border-color: #72bf82 !important;
      box-shadow: 0 0 0 0.12rem rgba(56, 161, 105, 0.2) !important;
      background-color: #f7fff9;
      padding-right: 2rem;
    }

    .password-confirm-group.is-match .confirm-check {
      opacity: 1;
    }

    .password-confirm-group.is-mismatch input {
      border-color: #d6a0a0 !important;
      box-shadow: 0 0 0 0.12rem rgba(185, 90, 90, 0.16) !important;
    }

    .form-box button {
      width: auto;
      padding: 0.65rem 1.6rem;
      background-color: #F28241;
      border: none;
      color: #ffffff;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s;
      grid-column: 1 / -1;
      justify-self: center;
      margin-top: 0.8rem;
    }

    .form-box button:hover {
      background-color: #d97031;
      color: #ffffff;
    }

    .invalid-feedback {
      color: red;
      font-size: 0.875em;
      margin-top: -0.5rem;
      margin-bottom: 0.5rem;
      display: block;
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
      color: rgba(93, 93, 93, 0.6);
      font-weight: normal;
      text-decoration: none;
      font-size: 1.1rem;
      transition: color 0.3s;
    }

    .login-link a:hover {
      color: #F28241;
      text-decoration: none;
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

      .form-box {
        padding: 2.5rem 2rem;
      }
    }

    @media (max-width: 992px) {
      body {
        padding-top: 130px;
        padding-bottom: 50px;
        gap: 1.5rem;
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
        font-size: 1.45rem;
        text-align: center;
        padding: 0.5rem 1rem;
      }

      .form-box {
        max-width: 92%;
        padding: 2rem 1rem;
      }

      .form-box form {
        grid-template-columns: 1fr;
      }

      .form-box .form-group-full {
        width: 100%;
      }
    }

    @media (max-width: 576px) {
      .logo-container img {
        height: 100px;
      }

      .typewriter-container {
        font-size: 1.15rem;
      }

      .form-box h2 {
        font-size: 1.75rem !important;
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
      Crea tu cuenta y sé parte de nuestra comunidad. Comparte tus recetas favoritas.
    </div>
  </section>

  <div class="form-box">
    <h2>Crear cuenta</h2>
    
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

      <div class="form-group">
        <label for="lada">Lada</label>
        <input type="text" id="lada" name="lada" maxlength="5" placeholder="Lada" value="{{ old('lada') }}" />
      </div>

      <div class="form-group">
        <label for="phone">Teléfono</label>
        <input type="text" id="phone" name="phone" maxlength="10" placeholder="Número" value="{{ old('phone') }}" />
      </div>

      <div class="form-group">
        <label for="state">Estado</label>
        <select id="state" name="state" required>
          <option value="" disabled {{ old('state') ? '' : 'selected' }}>Selecciona estado</option>
          <option value="Aguascalientes" {{ old('state')=='Aguascalientes' ? 'selected' : '' }}>Aguascalientes</option>
          <option value="Baja California" {{ old('state')=='Baja California' ? 'selected' : '' }}>Baja California</option>
          <option value="Baja California Sur" {{ old('state')=='Baja California Sur' ? 'selected' : '' }}>Baja California Sur</option>
          <option value="Campeche" {{ old('state')=='Campeche' ? 'selected' : '' }}>Campeche</option>
          <option value="Chiapas" {{ old('state')=='Chiapas' ? 'selected' : '' }}>Chiapas</option>
          <option value="Chihuahua" {{ old('state')=='Chihuahua' ? 'selected' : '' }}>Chihuahua</option>
          <option value="Ciudad de México" {{ old('state')=='Ciudad de México' ? 'selected' : '' }}>Ciudad de México</option>
          <option value="Coahuila" {{ old('state')=='Coahuila' ? 'selected' : '' }}>Coahuila</option>
          <option value="Colima" {{ old('state')=='Colima' ? 'selected' : '' }}>Colima</option>
          <option value="Durango" {{ old('state')=='Durango' ? 'selected' : '' }}>Durango</option>
          <option value="Estado de México" {{ old('state')=='Estado de México' ? 'selected' : '' }}>Estado de México</option>
          <option value="Guanajuato" {{ old('state')=='Guanajuato' ? 'selected' : '' }}>Guanajuato</option>
          <option value="Guerrero" {{ old('state')=='Guerrero' ? 'selected' : '' }}>Guerrero</option>
          <option value="Hidalgo" {{ old('state')=='Hidalgo' ? 'selected' : '' }}>Hidalgo</option>
          <option value="Jalisco" {{ old('state')=='Jalisco' ? 'selected' : '' }}>Jalisco</option>
          <option value="Michoacán" {{ old('state')=='Michoacán' ? 'selected' : '' }}>Michoacán</option>
          <option value="Morelos" {{ old('state')=='Morelos' ? 'selected' : '' }}>Morelos</option>
          <option value="Nayarit" {{ old('state')=='Nayarit' ? 'selected' : '' }}>Nayarit</option>
          <option value="Nuevo León" {{ old('state')=='Nuevo León' ? 'selected' : '' }}>Nuevo León</option>
          <option value="Oaxaca" {{ old('state')=='Oaxaca' ? 'selected' : '' }}>Oaxaca</option>
          <option value="Puebla" {{ old('state')=='Puebla' ? 'selected' : '' }}>Puebla</option>
          <option value="Querétaro" {{ old('state')=='Querétaro' ? 'selected' : '' }}>Querétaro</option>
          <option value="Quintana Roo" {{ old('state')=='Quintana Roo' ? 'selected' : '' }}>Quintana Roo</option>
          <option value="San Luis Potosí" {{ old('state')=='San Luis Potosí' ? 'selected' : '' }}>San Luis Potosí</option>
          <option value="Sinaloa" {{ old('state')=='Sinaloa' ? 'selected' : '' }}>Sinaloa</option>
          <option value="Sonora" {{ old('state')=='Sonora' ? 'selected' : '' }}>Sonora</option>
          <option value="Tabasco" {{ old('state')=='Tabasco' ? 'selected' : '' }}>Tabasco</option>
          <option value="Tamaulipas" {{ old('state')=='Tamaulipas' ? 'selected' : '' }}>Tamaulipas</option>
          <option value="Tlaxcala" {{ old('state')=='Tlaxcala' ? 'selected' : '' }}>Tlaxcala</option>
          <option value="Veracruz" {{ old('state')=='Veracruz' ? 'selected' : '' }}>Veracruz</option>
          <option value="Yucatán" {{ old('state')=='Yucatán' ? 'selected' : '' }}>Yucatán</option>
          <option value="Zacatecas" {{ old('state')=='Zacatecas' ? 'selected' : '' }}>Zacatecas</option>
        </select>
      </div>

      <div class="form-group">
        <label for="registration_date">Fecha de Registro</label>
        <input type="date" id="registration_date" name="registration_date" value="{{ old('registration_date') }}" required />
      </div>

      <div class="form-group form-group-full">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />
      </div>

      <div class="form-group form-group-full password-confirm-group" id="passwordConfirmGroup">
        <label for="password_confirmation">Confirmar Contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required />
        <span class="confirm-check" aria-hidden="true">✓</span>
      </div>

      <button type="submit">Crear cuenta</button>
    </form>

    <div class="login-link">
      <a href="{{ route('login') }}">Regresar a login</a>
    </div>
  </div>

  <script>
    (function () {
      const passwordInput = document.getElementById('password');
      const confirmInput = document.getElementById('password_confirmation');
      const confirmGroup = document.getElementById('passwordConfirmGroup');

      if (!passwordInput || !confirmInput || !confirmGroup) return;

      const syncPasswordState = () => {
        const passwordValue = passwordInput.value;
        const confirmValue = confirmInput.value;

        confirmGroup.classList.remove('is-match', 'is-mismatch');
        confirmInput.setCustomValidity('');

        if (!confirmValue) return;

        if (passwordValue === confirmValue) {
          confirmGroup.classList.add('is-match');
          return;
        }

        confirmGroup.classList.add('is-mismatch');
        confirmInput.setCustomValidity('Las contraseñas no coinciden');
      };

      passwordInput.addEventListener('input', syncPasswordState);
      confirmInput.addEventListener('input', syncPasswordState);
      confirmInput.addEventListener('blur', syncPasswordState);
    })();
  </script>
  <script>
    const typewriterEl = document.getElementById('typewriter');
    const fullText = typewriterEl.textContent.trim();
    typewriterEl.style.minHeight = `${typewriterEl.offsetHeight}px`;
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
        window.location.href = "{{ route('login') }}";
      });
    })();
  </script>

</body>
</html>
