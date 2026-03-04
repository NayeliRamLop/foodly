<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer contraseña - Cocina con Gusto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Meta tag CSRF añadido para solucionar el error -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css?family=Anonymous+Pro" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 2rem 1rem;
      box-sizing: border-box;
      min-height: 100vh;
    }

    .typewriter-container {
      font-size: 3.5rem;
      white-space: nowrap;
      overflow: hidden;
      border-right: 2px solid rgba(255,255,255,0.75);
      width: 0;
      color: white;
      font-weight: bold;
      letter-spacing: 0.05em;
      max-width: 95vw;
      text-align: center;
      margin: 2rem 0;
      position: relative;
      z-index: 10;
    }

    .reset-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 450px;
      color: #333;
      font-size: 1.2rem;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1000;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .confirm-password-group {
      position: relative;
    }

    .confirm-password-group .confirm-check {
      position: absolute;
      right: 12px;
      top: 39px;
      color: #2f9e44;
      font-size: 1rem;
      font-weight: bold;
      opacity: 0;
      transition: opacity 0.2s ease;
      pointer-events: none;
    }

    .confirm-password-group.is-match input[type="password"] {
      border-color: #72bf82 !important;
      box-shadow: 0 0 0 3px rgba(56, 161, 105, 0.2) !important;
      background-color: #f7fff9;
      padding-right: 2rem;
    }

    .confirm-password-group.is-match .confirm-check {
      opacity: 1;
    }

    .confirm-password-group.is-mismatch input[type="password"] {
      border-color: #d6a0a0 !important;
      box-shadow: 0 0 0 3px rgba(185, 90, 90, 0.16) !important;
    }

    input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 1rem;
      margin-bottom: 0.25rem;
      transition: border-color 0.3s;
    }

    input[type="password"]:focus {
      border-color: #5d7b9d;
      outline: none;
      box-shadow: 0 0 0 3px rgba(93, 123, 157, 0.2);
    }

    button {
      width: 100%;
      padding: 0.8rem;
      background: linear-gradient(to right, #5d7b9d, #4a6a8a);
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1.1rem;
      margin-top: 1.5rem;
      transition: all 0.3s;
      position: relative;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    button:hover {
      background: linear-gradient(to right, #4a6a8a, #3a5169);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    button:disabled {
      background: #cccccc;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .spinner {
      display: inline-block;
      width: 1.2rem;
      height: 1.2rem;
      border: 3px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
      margin-right: 8px;
      vertical-align: middle;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .invalid-feedback {
      color: #dc3545;
      font-size: 0.9em;
      margin-top: 0.5rem;
      display: block;
    }

    .is-invalid {
      border-color: #dc3545 !important;
    }

    .success-message {
      color: #2ecc71;
      background-color: rgba(46, 204, 113, 0.1);
      padding: 1.2rem;
      border-radius: 8px;
      border-left: 4px solid #2ecc71;
      margin-bottom: 1.5rem;
      display: none;
      text-align: center;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .password-requirements {
      font-size: 0.85rem;
      color: #6c757d;
      margin-top: 0.5rem;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      color: #5d7b9d;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
      color: #3a5169;
    }
  </style>
</head>
<body>

  <div class="typewriter-container" id="typewriter"></div>

  <div class="reset-box">
    @if(session('status'))
      <div class="success-message show">
        ✓ {{ session('status') }}
      </div>
    @endif

    <div class="success-message" id="successMessage">
      ✓ Contraseña restablecida con éxito. Redirigiendo...
    </div>

    <form method="POST" action="{{ route('password.update') }}" id="resetForm">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

      <div class="form-group">
        <label for="password">Nueva contraseña</label>
        <input type="password" name="password" id="password" placeholder="Ingresa tu nueva contraseña" 
               class="form-control @error('password') is-invalid @enderror" required>
        <div class="password-requirements">
          Debe contener al menos 8 caracteres, una mayúscula, una minúscula y un número.
        </div>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="invalid-feedback" id="passwordError"></div>
      </div>

      <div class="form-group confirm-password-group" id="confirmPasswordGroup">
        <label for="password_confirmation">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" id="confirmPassword" 
               placeholder="Confirma tu nueva contraseña" class="form-control" required>
        <span class="confirm-check" aria-hidden="true">✓</span>
        <div class="invalid-feedback" id="confirmError"></div>
      </div>

      <button type="submit" id="submitBtn">
        <span id="buttonText">Restablecer contraseña</span>
      </button>

      <a href="{{ route('login') }}" class="back-link">← Volver al inicio de sesión</a>
    </form>
  </div>

  <script>
    // Efecto máquina de escribir
    const tw = document.getElementById('typewriter');
    const text = "Restablecer contraseña";
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
          }, 1000);
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
          setTimeout(typeWriter, 500);
          return;
        }
      }

      const speed = isDeleting ? 50 : 100;
      if (!isPaused) {
        setTimeout(typeWriter, speed);
      }
    }

    // Manejo del formulario con validación mejorada
    document.getElementById('resetForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const form = e.target;
      const submitBtn = document.getElementById('submitBtn');
      const buttonText = document.getElementById('buttonText');
      const originalText = buttonText.textContent;
      
      // Mostrar spinner y deshabilitar botón
      submitBtn.disabled = true;
      buttonText.innerHTML = '<span class="spinner"></span> Procesando...';
      
      // Limpiar errores anteriores
      document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        const data = await response.json();

        if (!response.ok) {
          // Mostrar errores de validación del backend
          if (data.errors) {
            for (const [field, messages] of Object.entries(data.errors)) {
              const inputField = document.getElementById(field);
              const errorElement = document.getElementById(`${field}Error`) || 
                                 inputField.nextElementSibling;
              
              if (inputField) {
                inputField.classList.add('is-invalid');
              }
              
              if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                errorElement.textContent = messages.join(' ');
              }
            }
          }
          throw new Error(data.message || 'Error al procesar la solicitud');
        }

        // Mostrar éxito y redirigir
        document.getElementById('successMessage').style.display = 'block';
        form.style.display = 'none';
        
        setTimeout(() => {
          window.location.href = "{{ route('login') }}";
        }, 3000);

      } catch (error) {
        console.error('Error:', error);
        // Mostrar error general si no hay errores específicos
        if (!document.querySelector('.invalid-feedback:not(:empty)')) {
          const errorElement = document.getElementById('passwordError') || 
                             document.createElement('div');
          errorElement.textContent = error.message;
          errorElement.className = 'invalid-feedback';
          document.getElementById('password').insertAdjacentElement('afterend', errorElement);
          document.getElementById('password').classList.add('is-invalid');
        }
      } finally {
        // Restaurar botón
        submitBtn.disabled = false;
        buttonText.textContent = originalText;
      }
    });

    // Validación frontend básica
    document.getElementById('password').addEventListener('input', validatePassword);
    document.getElementById('confirmPassword').addEventListener('input', validatePasswordConfirmation);

    function validatePassword() {
      const password = this.value;
      const confirm = document.getElementById('confirmPassword').value;
      const confirmGroup = document.getElementById('confirmPasswordGroup');
      const errorElement = document.getElementById('passwordError');
      
      // Validación de requisitos
      const hasMinLength = password.length >= 8;
      const hasUpperCase = /[A-Z]/.test(password);
      const hasLowerCase = /[a-z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      
      if (password && (!hasMinLength || !hasUpperCase || !hasLowerCase || !hasNumber)) {
        errorElement.textContent = "La contraseña no cumple con los requisitos";
        this.classList.add('is-invalid');
      } else {
        errorElement.textContent = "";
        this.classList.remove('is-invalid');
      }
      
      // Validación de coincidencia
      if (confirm && password !== confirm) {
        document.getElementById('confirmError').textContent = "Las contraseñas no coinciden";
        document.getElementById('confirmPassword').classList.add('is-invalid');
        if (confirmGroup) {
          confirmGroup.classList.remove('is-match');
          confirmGroup.classList.add('is-mismatch');
        }
      } else if (confirm && password === confirm) {
        if (confirmGroup) {
          confirmGroup.classList.remove('is-mismatch');
          confirmGroup.classList.add('is-match');
        }
      } else if (confirmGroup) {
        confirmGroup.classList.remove('is-match', 'is-mismatch');
      }
    }

    function validatePasswordConfirmation() {
      const confirm = this.value;
      const password = document.getElementById('password').value;
      const confirmGroup = document.getElementById('confirmPasswordGroup');
      const errorElement = document.getElementById('confirmError');
      
      if (password && confirm && password !== confirm) {
        errorElement.textContent = "Las contraseñas no coinciden";
        this.classList.add('is-invalid');
        if (confirmGroup) {
          confirmGroup.classList.remove('is-match');
          confirmGroup.classList.add('is-mismatch');
        }
      } else {
        errorElement.textContent = "";
        this.classList.remove('is-invalid');
        if (confirmGroup) {
          if (password && confirm && password === confirm) {
            confirmGroup.classList.remove('is-mismatch');
            confirmGroup.classList.add('is-match');
          } else {
            confirmGroup.classList.remove('is-match', 'is-mismatch');
          }
        }
      }
    }

    // Iniciar efecto máquina de escribir
    typeWriter();
  </script>
</body>
</html>