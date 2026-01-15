<!DOCTYPE html>
<html>
<head>
    <title>Cita cancelada exitosamente</title>
</head>
<body>
    <h2>Cancelacion de Cita - VNET</h2>
    <p>Estimado/a {{ $clienteNombre }},</p>
    
    <p>Su cita ha sido cancelada:</p>
    
    <ul>
        <li><strong>Fecha y Hora:</strong> {{ $fechaHora }}</li>
        <li><strong>Plan:</strong> {{ $plan }}</li>
        <li><strong>Dirección:</strong> {{ $direccion }}</li>   
        <li><strong>Contrato:</strong> {{ $contrato }}</li>
    </ul>


    <p>El motivo de la cancelacion es: {{$observation}}</p>
    
    <p>Gracias por preferirnos</p>
    
    <p>Atentamente,<br>Equipo VNET</p>
</body>
</html>