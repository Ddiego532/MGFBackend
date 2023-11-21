<!DOCTYPE html>
<html>
<head>
    <style>
        /* Estilos CSS en línea para el correo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Hola, {{$nombre}}!</h1>
        <p>El motivo de este correo es dar aviso de su confirmación del pago de subasta.</p>
        <p>A continuación, se detalla el monto y el Nro de la subasta.</p>
        <p>Nro Subasta: {{$nro}}</p>
        <p>Total: ${{$total}}</p>
        <p>Atentamente,<br><strong>MyGreenFashion</strong></p>
        <img src="{{ asset('images/maxlogo.png') }}" alt="Ejemplo de Imagen">
    </div>
</body>
</html>
