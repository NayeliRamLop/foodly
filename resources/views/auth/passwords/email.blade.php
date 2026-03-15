<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar contraseña - Cocina con Gusto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Anonymous+Pro" rel="stylesheet">

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
      justify-content: center;
      min-height: 100vh;
      padding: 2rem;
      box-sizing: border-box;
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
      margin-bottom: 2rem;
      position: fixed;
      top: 20%;
      left: 50%;
      transform: translateX(-50%);
    }

    .reset-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 3rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 500px;
      color: #333;
      font-size: 1.2rem;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    input[type="email"] {
      width: 100%;
      padding: 0.8rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }

    button {
      width: 100%;
      padding: 0.8rem;
      background-color: #1565c0;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1.2rem;
      margin-top: 1.5rem;
      transition: background-color 0.3s;
      position: relative;
    }

    button:hover {
      background-color: #0d3c78;
    }

    button:disabled {
      background-color: #cccccc;
      cursor: not-allowed;
    }

    .spinner {
      display: inline-block;
      width: 1.2rem;
      height: 1.2rem;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
      margin-right: 8px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .invalid-feedback {
      color: red;
      font-size: 0.9em;
      margin-top: 0.5rem;
    }

    .success-message {
      color: #2ecc71;
      background-color: rgba(46, 204, 113, 0.1);
      padding: 1.5rem;
      border-radius: 8px;
      border-left: 4px solid #2ecc71;
      margin-bottom: 1.5rem;
      display: none;
      text-align: center;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      color: #1565c0;
      text-decoration: none;
      font-size: 1.1rem;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="typewriter-container" id="typewriter"></div>

  <div class="reset-box">
    <div class="success-message" id="successMessage">
      ✓ ¡Listo! El enlace de recuperación ha sido enviado a tu correo.
    </div>

    <form method="POST" action="{{ route('password.email') }}" id="resetForm">
      @csrf

      <div class="form-group">
        <input type="email" name="email" placeholder="Correo electrónico"
               class="@error('email') is-invalid @enderror"
               value="{{ old('email') }}" required autofocus>
        <div class="invalid-feedback" id="emailError">
          @error('email') {{ $message }} @enderror
        </div>
      </div>

      <button type="submit" id="submitBtn">
        <span id="buttonText">Enviar enlace de recuperación</span>
      </button>

      <a href="{{ route('login') }}" class="back-link">← Volver al inicio de sesión</a>
    </form>
  </div>

  <script>
    // Efecto máquina de escribir
    const tw = document.getElementById('typewriter');
    const text = "Recuperar contraseña";
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

    // Manejo del formulario
    document.getElementById('resetForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const form = e.target;
      const submitBtn = document.getElementById('submitBtn');
      const buttonText = document.getElementById('buttonText');
      const originalText = buttonText.textContent;
      
      // Mostrar spinner y deshabilitar botón
      submitBtn.disabled = true;
      buttonText.innerHTML = '<span class="spinner"></span> Enviando...';
      
      // Limpiar errores anteriores
      document.getElementById('emailError').textContent = '';

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const data = await response.json();

        if (!response.ok) {
          // Mostrar errores de validación del backend
          if (data.errors) {
            for (const [field, messages] of Object.entries(data.errors)) {
              const errorElement = document.getElementById(`${field}Error`);
              if (errorElement) {
                errorElement.innerHTML = messages.join('<br>');
              }
            }
          }
          throw new Error(data.message || 'El correo que ingresaste no está registrado');
        }

        // Mostrar éxito y redirigir
        document.getElementById('successMessage').style.display = 'block';
        form.style.display = 'none';
        
        setTimeout(() => {
          window.location.href = "{{ route('login') }}";
        }, 3000);

      } catch (error) {
        console.error('Error:', error);
        // Mostrar error específico para correo no registrado
        if (error.message.includes("correo no registrado") || error.message.includes("email not found")) {
          document.getElementById('emailError').textContent = "Este correo no está registrado";
        } else {
          document.getElementById('emailError').textContent = error.message || "Ocurrió un problema al enviar el correo";
        }
      } finally {
        // Restaurar botón
        submitBtn.disabled = false;
        buttonText.textContent = originalText;
      }
    });

    // Iniciar efecto máquina de escribir
    typeWriter();
  </script>
</body>
</html>