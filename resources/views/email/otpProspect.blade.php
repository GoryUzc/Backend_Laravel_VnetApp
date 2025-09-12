<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verificación Agenda VNET</title>
</head>
<body>
    <h2>Hola {{ $prospect->name }},</h2>
    <p>Tu código para verificación es:</p>
    <h1 style="color: #2d3748;">{{ $otp }}</h1>
    <p>Por favor, ingresa este código en la aplicación para continuar con tu proceso.</p>
    <br>
    <p>Gracias,<br>Equipo VNET</p>
</body>
</html>