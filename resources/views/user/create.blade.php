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
      padding-top: calc(36vh + 70px);
      gap: 2rem;
      min-height: 100vh;
    }

    .typewriter-container {
      position: absolute;
      top: 250px;
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
      top: 250px;
      left: 350px;
      z-index: 10;
    }

    .logo-container img {
      height: 200px;
      width: auto;
    }

    .form-box {
      top: 200px;
      background: rgba(255, 255, 255, 0.95);
      padding: 3rem 3rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 45%;
      color: #333;
      font-weight: normal;
      font-size: 1.2rem;
      box-sizing: border-box;
      overflow: hidden;
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
      margin-bottom: 1.5rem;
      font-weight: bold;
      font-size: 2rem;
    }

    .form-group {
      margin-bottom: 0;
      display: flex;
      flex-direction: column;
      min-height: 70px;
      justify-content: space-between;
    }

    .form-group label {
      color: #333;
      font-weight: 600;
      display: block;
      margin-bottom: 0.3rem;
      font-size: 1.1rem;
      height: 1.35em;
    }

    .form-box input[type="text"],
    .form-box input[type="email"],
    .form-box input[type="password"],
    .form-box input[type="date"],
    .form-box select {
      width: 100%;
      padding: 0.5rem;
      border-radius: 6px;
      border: 0.5px solid #d2d2d2;
      font-size: 1rem;
      box-sizing: border-box;
    }

    .form-box button {
      width: auto;
      padding: 0.8rem 2rem;
      background-color: #F28241;
      border: none;
      color: #ffffff;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1.1rem;
      transition: background-color 0.3s;
      grid-column: 1 / -1;
      justify-self: center;
      margin-top: 1rem;
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
    Crea tu cuenta y sé parte de nuestra comunidad.
Comparte tus recetas favoritas.
  </div>

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

      <div class="form-group form-group-full">
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

</body>
</html>
