<!DOCTYPE html>
<html>
<head>
    <title>Cita creada con exitosamente</title>
</head>
<body>
    <h2>Confirmación de Cita - VNET</h2>
    <p>Estimado/a {{ $clienteNombre }},</p>
    
    <p>Su cita ha sido agendada exitosamente:</p>
    
    <ul>
        <li><strong>Fecha y Hora:</strong> {{ $fechaHora }}</li>
        <li><strong>Plan:</strong> {{ $plan }}</li>
        <li><strong>Dirección:</strong> {{ $direccion }}</li>   
    </ul>
    
    <p>Un técnico se presentará en la fecha y hora acordada.</p>
    
    <p>Atentamente,<br>Equipo VNET</p>
</body>
</html>