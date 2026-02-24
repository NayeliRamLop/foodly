<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña - Cocina con Gusto</title>
    <style>
        body {
            font-family: 'Anonymous Pro', monospace;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #1565c0;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header h2 {
            margin: 10px 0 0;
            font-size: 22px;
            font-weight: normal;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .button {
            display: inline-block;
            background-color: #1565c0;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0d3c78;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .url {
            word-break: break-all;
            color: #1565c0;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
            margin: 15px 0;
        }
        .expire-notice {
            color: #d32f2f;
            font-weight: bold;
            margin: 15px 0;
        }
        .signature {
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍳 Cocina con Gusto</h1>
        <h2>Restablecer contraseña</h2>
    </div>
    
    <div class="content">
        <p>¡Hola!</p>
        
        <p>Recibiste este correo porque solicitaste restablecer la contraseña de tu cuenta en <strong>Cocina con Gusto</strong>.</p>
        
        <p>Haz clic en el siguiente botón para completar el proceso:</p>
        
        <div style="text-align: center;">
            <a href="{{ $actionUrl }}" class="button">Restablecer contraseña</a>
        </div>
        
        <p>Si el botón no funciona, copia y pega esta URL en tu navegador:</p>
        <div class="url">{{ $displayableActionUrl }}</div>
        
        <div class="expire-notice">
            ⏳ Este enlace expirará en {{ $count }} minutos.
        </div>
        
        <p>Si no solicitaste este cambio, puedes ignorar este mensaje con seguridad.</p>
        
        <p class="signature">¡Gracias por ser parte de Cocina con Gusto!</p>
    </div>
    
    <div class="footer">
        <p>© {{ $currentYear }} Cocina con Gusto. Todos los derechos reservados.</p>
        <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
    </div>
</body>
</html>