<!DOCTYPE html>
<html>
<head>
    <title>Cambio de Contraseña</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1>¡Hola, {{$nombre}}!</h1>
        <p>Este es un correo electrónico de recuperaacion de contraseña.</p>
        <p>A continuación, pulse el boton para poder cambiar su contraseña actual.</p>
        <p>Atentamente,<br><strong>MyGreenFashion</strong></p>
        <a style="text-decoration: none" href="https://www.ejemplo.com/pagina-aleatoria?email={{$email}}" target="_blank">
            <button style="display: block;width: 100%;padding: 10px;background-color: #333;color: #fff;border: none;border-radius: 5px;cursor: pointer;font-size: 16px;transition: background-color 0.3s;">Cambiar Contraseña</button>
        </a>
        <img src="{{ asset('images/maxlogo.png') }}" alt="Ejemplo de Imagen">
    </div>
</body>
</html>
