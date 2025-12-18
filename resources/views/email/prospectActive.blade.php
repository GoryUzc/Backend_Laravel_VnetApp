<!DOCTYPE html>
<html>
<head>
    <title>Conexion exitosa</title>
</head>
<body>
    <h2>Conexion exitosa - VNET</h2>
    <p>Estimado/a {{ $clienteNombre }},</p>
    
    <p>Su conexion ha sido establecida exitosamente:</p>
    
    <ul>
        <li><strong>Plan:</strong> {{ $plan }}</li>
        <li><strong>Dirección:</strong> {{ $direccion }}</li>   
        <li><strong>Contrato:</strong> {{ $contrato }}</li>
    </ul>
    
    <p>Cualquier eventualidad por favor comunicarse con atencion al cliente.</p>
    <p>Estamos para servirle.</p>
    
    <p>Atentamente,<br>Equipo VNET</p>
</body>
</html>