<!DOCTYPE html>
<html>
<head>
    <title>Cita asignada con éxito</title>
</head>
<body>
    <p>Hola <strong>{{ $clienteNombre }}</strong>,</p>
    <p>Te informamos que tu cita de instalación ha sido asignada con éxito. Aquí están los detalles:</p>
    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p><strong>📅 Fecha y Hora:</strong> {{ $fechaHora }}</p>
        <p><strong>👤 Técnico Asignado:</strong> {{ $tecnicoNombre }}</p>
        <p><strong>📞 Teléfono del Técnico:</strong> {{ $telefonoTecnico }}</p>
        <p><strong>📍 Dirección:</strong> {{ $direccion }}</p>
    </div>
    <p>El técnico se pondrá en contacto contigo si es necesario. Por favor, asegúrate de estar disponible en el horario programado.</p>
    <p>Gracias por confiar en nosotros.</p>
    <p>Atentamente,<br>Equipo VNET</p>
</body>
</html>
