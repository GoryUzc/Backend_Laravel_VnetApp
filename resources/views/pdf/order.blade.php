<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden de Instalación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header .logo {
            text-align: left;
            width: 20%;
        }
        .header .title {
            text-align: center;
            width: 60%;
        }
        .header .title h1 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header .title h2 {
            margin: 0;
            font-size: 12px;
            color: #333;
        }
        .header .info {
            text-align: right;
            width: 20%;
            font-size: 9px;
        }
        .section {
            margin-bottom: 15px;
        }
        .two-columns {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 8px;
        }
        .field {
            margin-bottom: 4px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 140px;
        }
        .signature-area {
            margin-top: 25px;
        }
        .signature-box {
            border: 1px solid #000;
            height: 60px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
    </style>
</head>
<body>

<div class="header">
    <table>
        <tr>
            <td class="logo">
                @php
                    $path = public_path('images/image001.png');
                    $data = file_get_contents($path);
                    $base64 = 'data:image/png;base64,' . base64_encode($data);
                @endphp
                <img src="{{ $base64 }}" alt="VNET" height="80">
            </td>
            <td class="title">
                <h1>ORDEN DE INSTALACIÓN</h1>
                <h2>FIBRA ÓPTICA</h2>
            </td>
            <td class="info">
                <div><strong>N° Orden:</strong> {{ $order->id }}</div>
                <div><strong>Fecha:</strong> {{ $order->created_at?->format('d/m/Y') ?? 'N/A' }}</div>
                <div><strong>Hora:</strong> {{ $order->created_at?->format('H:i') ?? 'N/A' }}</div>
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="field"><span class="label">Nombre del Cliente:</span> {{ trim(($order->prospect_aradial->name ?? '') . ' ' . ($order->prospect_aradial->last_name ?? '')) ?: 'N/A' }}</div>
    <div class="field"><span class="label">Dirección:</span> {{ $order->prospect_aradial->address ?? 'N/A' }}</div>
    <div class="field"><span class="label">Teléfono:</span> {{ $order->prospect_aradial->phone ?? 'N/A' }}</div>
</div>

<div class="two-columns">
    <div class="col">
        <div class="section">
            <strong>DATOS TECNICOS DEL CLIENTE</strong>
            <div class="field"><span class="label">PPoE Usuario:</span> {{ $order->ppoe_user ?? 'N/A' }}</div>
            <div class="field"><span class="label">PPoE Contraseña:</span> {{ $order->ppoe_password ?? 'N/A' }}</div>
            <div class="field"><span class="label">MAC ONT:</span> {{ $order->mac_ont ?? 'N/A' }}</div>
            <div class="field"><span class="label">Serial ONT:</span> {{ $order->serial_ont ?? 'N/A' }}</div>
            <div class="field"><span class="label">Potencia Recibida:</span> {{ $order->potencia_recibida_ont ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col">
        <div class="section">
            <strong>MATERIALES DE INSTALACION</strong>
            <div class="field"><span class="label">ONT Puerto 1:</span> {{ $order->ont_puerto_1 ?? 0 }}</div>
            <div class="field"><span class="label">Conector SC PC:</span> {{ $order->conector_sc_pc ?? 0 }}</div>
            <div class="field"><span class="label">Patch Cord:</span> {{ $order->patch_cord_scsp_scapc ?? 0 }}</div>
            <div class="field"><span class="label">Roseta:</span> {{ $order->roseta ?? 0 }}</div>
            <div class="field"><span class="label">Adapter SCAPC:</span> {{ $order->adapter_scapc ?? 0 }}</div>
            <div class="field"><span class="label">ONT 4 Puertos:</span> {{ $order->ont_4_puertos ?? 0 }}</div>
            <div class="field"><span class="label">Conector SC UPC:</span> {{ $order->conector_sc_upc ?? 0 }}</div>
            <div class="field"><span class="label">Canaletas:</span> {{ $order->canaletas ?? 0 }}</div>
            <div class="field"><span class="label">Ramplug:</span> {{ $order->ramplug ?? 0 }}</div>
            <div class="field"><span class="label">Cable Drop:</span> {{ $order->cable_drop ?? 0 }}</div>
            <div class="field"><span class="label">Hilos:</span> {{ $order->hilos ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="section">
    <strong>DETALLES DE LA INSTALACIÓN</strong>
    <div class="field">{{ $order->detalles_instalacion ?? 'N/A' }}</div>
</div>

<div class="signature-box">
    @if($order->signature_path)
        @php
            $sigPath = public_path($order->signature_path);
            if (file_exists($sigPath)) {
                $sigData = file_get_contents($sigPath);
                $sigBase64 = 'data:image/png;base64,' . base64_encode($sigData);
            } else {
                $sigBase64 = null;
            }
        @endphp
        @if($sigBase64)
            <img src="{{ $sigBase64 }}" height="50" style="margin-top:5px;">
        @endif
    @endif
</div>

<div class="footer">
    Documento válido con firma del cliente.<br>
    Generado el: {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>
</html>