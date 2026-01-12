<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden de Instalación_{{ $order->meeting->nro_contract ?? '000' }}</title>
    <style>
        /* Configuración de Hoja Carta Horizontal */
        @page {
            size: letter landscape;
            margin: 0.8cm;
        }

        body { 
            font-family: Arial, sans-serif; 
            font-size: 8.5px; 
            line-height: 1.1; 
            color: #000; 
            margin: 0;
            padding: 0;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: -1px; }
        th, td { border: 1px solid #000; padding: 3px; vertical-align: middle; }

        /* Estilos para Símbolos de Leyenda */
        .sym { display: inline-block; border: 1px solid #000; line-height: 1; }
        .sym-line { width: 20px; border-bottom: 2px solid #000; height: 0; margin-bottom: 3px; }
        .sym-rect { width: 15px; height: 10px; }
        .sym-circle { width: 10px; height: 10px; border-radius: 50%; background: #000; }
        .sym-cross { width: 12px; height: 12px; text-align: center; font-size: 10px; font-weight: bold; line-height: 12px; }
        .sym-empty-circle { width: 10px; height: 10px; border-radius: 50%; border: 1px solid #000; }

        .header-table td { padding: 0; }
        
        /* Header con cuadrícula completa */
        .header-table td { padding: 0; }
        .logo-box { width: 20%; text-align: center; padding: 5px !important; }
        .title-box { width: 80%; }
        .main-title { 
            font-size: 13px; 
            font-weight: bold; 
            text-align: center; 
            padding: 8px; 
            border-bottom: 1px solid #000; 
        }
        .meta-info-table { border: none; width: 100%; }
        .meta-info-table td { 
            border: none; 
            border-right: 1px solid #000; 
            font-size: 8px; 
            font-weight: bold; 
            text-align: center;
            padding: 4px;
        }

        /* Layout de dos columnas para Horizontal */
        .main-container { width: 100%; margin-top: 10px; }
        .col-left { float: left; width: 30%; }
        .col-right { float: right; width: 68%; }

        /* Checklist con círculos */
        .checklist-table td { padding: 2px 4px; }
        .circle { 
            width: 10px; height: 10px; border: 1px solid #000; 
            border-radius: 50%; display: inline-block; margin-left: 5px; vertical-align: middle;
        }

        /* Estilo de secciones */
        .section-header { 
            background-color: #f2f2f2; 
            font-weight: bold; 
            text-align: center; 
            padding: 4px; 
            font-size: 9px;
            border: 1px solid #000;
        }
        .data-box { 
            border: 1px solid #000; 
            border-top: none; 
            min-height: 50px; 
            margin-bottom: 8px; 
            padding: 6px; 
            font-size: 9px;
        }

        .footer-note { 
            font-size: 7px; 
            border: 1px solid #000; 
            padding: 6px; 
            margin-top: 8px; 
            text-align: justify;
            line-height: 1.2;
        }

        /* Segunda Página */
        .page-break { page-break-before: always; }
        .croquis-container {
            border: 1px solid #000;
            height: 350px;
            width: 100%;
            margin-top: 5px;
            background-image: radial-gradient(#d0d0d0 0.5px, transparent 0.5px);
            background-size: 15px 15px; /* Cuadrícula de guía */
        }

        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="logo-box">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/image001.png'))) }}" width="100">
            </td>
            <td class="title-box">
                <div class="main-title">DEPARTAMENTO DE OPERACIONES / ORDEN DE INSTALACIÓN</div>
                <table class="meta-info-table">
                    <tr>
                        <td style="width: 25%;">Código: FOR-UDN-004</td>
                        <td style="width: 25%;">Edición: 1</td>
                        <td style="width: 25%;">Fecha: {{ $order->created_at?->format('m/Y') }}</td>
                        <td style="width: 25%; border-right: none;">Solicitud: {{ $order->created_at?->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="main-container clearfix">
    <div class="col-left">
        <table class="checklist-table">
            <tr style="background: #f2f2f2;">
                <th style="width: 15%;">Nro.</th>
                <th>Item a valorar</th>
            </tr>
            @php
                $items = [
                    1 => "Verificación puerto NAP.", 2 => "Inspección realizada.",
                    3 => "Día y hora acordados.", 4 => "Lugar ONT definido.",
                    5 => "Ruta NAP a ONT definida.", 6 => "Ruta ejecutada.",
                    7 => "Roseta instalada.", 8 => "Conector mecánico.",
                    9 => "Acoplador instalado.", 10 => "Patch Cord.",
                    11 => "ONT instalada.", 12 => "Potencias TX/RX.",
                    13 => "ONT configurada.", 14 => "Wifi configurado.",
                    15 => "Canales configuración.", 16 => "Test de velocidad.",
                    17 => "Planilla completada.", 18 => "Cliente conforme.",
                    19 => "Recomendaciones dadas.", 20 => "Imagen test enviada.",
                    21 => "Imagen ONT enviada.", 22 => "Planilla firmada.",
                    23 => "Orden entregada."
                ];
            @endphp
            @foreach($items as $n => $text)
            <tr>
                <td align="center"><strong>{{ $n }}</strong> <div class="circle"></div></td>
                <td style="font-size: 7.5px;">{{ $text }}</td>
            </tr>
            @endforeach
        </table>
        <div class="footer-note">
            <strong>Nota Importante:</strong> Toda instalación debe llevar esta lista cotejada y firmada por el Líder de Cuadrilla para tener validez ante la UDN y Corporación VNET.
        </div>
    </div>

    <div class="col-right">
        <div class="section-header">DATOS DEL CLIENTE</div>
        <div class="data-box">
            <strong>Nombre:</strong> {{ $order->prospect_aradial->name ?? '' }} {{ $order->prospect_aradial->last_name ?? '' }}<br>
            <strong>Dirección:</strong> {{ $order->prospect_aradial->address ?? 'N/A' }}<br>
            <strong>Telf:</strong> {{ $order->prospect_aradial->phone ?? '' }} | <strong>Plan:</strong> {{ $order->prospect_aradial->plan ?? '' }}
        </div>

        <div class="section-header">MATERIALES UTILIZADOS</div>
        <table>
            <tr style="background: #f2f2f2; font-size: 7.5px;">
                <th>Material</th><th style="width:10%">Cant.</th>
                <th>Material</th><th style="width:10%">Cant.</th>
            </tr>
            <tr>
                <td>ONT de 1 puerto</td><td>{{ $order->ont_puerto_1 ?? 0 }}</td>
                <td>ONT de 4 puertos</td><td>{{ $order->ont_4_puertos ?? 0 }}</td>
            </tr>
            <tr>
                <td>Conector SC/APC</td><td>{{ $order->conector_sc_pc ?? 0 }}</td>
                <td>Conector SC/UPC</td><td>{{ $order->conector_sc_upc ?? 0 }}</td>
            </tr>
            <tr>
                <td>Patch Cord SC/PC-SC/APC</td><td>{{ $order->patch_cord_scsp_scapc ?? 0 }}</td>
                <td>Canaletas (Metros)</td><td>{{ $order->canaletas ?? 0 }}</td>
            </tr>
            <tr>
                <td>Roseta</td><td>{{ $order->roseta ?? 0 }}</td>
                <td>Ramplug</td><td>{{ $order->ramplug ?? 0 }}</td>
            </tr>
            <tr>
                <td>SC/APC-Adapter</td><td>{{ $order->adapter_scapc ?? 0 }}</td>
                <td>Cable Drop (Hilos)</td><td>{{ $order->cable_drop ?? 0 }}</td>
            </tr>
        </table>

        <div class="section-header" style="margin-top: 8px;">IMPLEMENTACIÓN TÉCNICA</div>
        <table>
            <tr style="background: #f2f2f2;">
                <th>Descripción</th>
                <th>Planificación</th>
                <th>Ejecución</th>
            </tr>
            <tr><td>Potencia recibida (dBm)</td><td></td><td>{{ $order->potencia_recibida_ont ?? '' }}</td></tr>
            <tr><td>MAC ONT</td><td></td><td>{{ $order->mac_ont ?? '' }}</td></tr>
            <tr><td>Serial ONT</td><td></td><td>{{ $order->serial_ont ?? '' }}</td></tr>
            <tr><td>Puerto NAP</td><td></td><td>{{ $order->puerto_nap ?? '' }}</td></tr>
            <tr><td>Ubicación de la ONU</td><td></td><td>{{ $order->ubicacion_onu ?? '' }}</td></tr>
            <tr><td>Nro de equipos a conectar</td><td></td><td>{{ $order->nro_equipos_conectar ?? '' }}</td></tr>
            <tr><td>Coordenadas</td><td></td><td></td></tr>
            <tr><td>Puerto OLT</td><td></td><td>{{ $order->puerto_olt ?? '' }}</td></tr>
            <tr><td>Etiqueta del cliente</td><td></td><td>{{ $order->etiqueta_cliente ?? '' }}</td></tr>
            <tr><td>Router_</td><td></td><td>{{ $order->router ?? '' }}</td></tr>
            <tr><td>Otros equipos</td><td></td><td></td></tr>
            <tr><td>Detalles de instalación</td><td></td><td>{{ $order->detalles_instalacion ?? '' }}</td></tr>
        </table>

        <table style="margin-top: 5px; width: 100%;">
    <tr>
        <td style="width: 22%;"><strong>Técnico</strong></td>
        <td style="width: 15%;">{{ $order->meeting->user->name ?? '' }}</td>
        <td style="width: 18%;"><strong>Hora inicio</strong></td>
        <td style="width: 10%;">{{ $order->meeting->date_time1->format('H:i') ?? '' }}</td>
        <td style="width: 20%;"><strong>Lider de cuadrilla</strong></td>
        <td style="width: 10%;">{{ $order->meeting->user->contractor->name ?? ''}}</td>
        <td style="width: 15%;"><strong>Fecha</strong></td>
        <td style="width: 10%;">{{ $order->meeting->date_time1->format('Y/m/d') }}</td>
    </tr>
    <tr>
        <td><strong>vehículo</strong></td>
        <td></td>
        <td><strong>Hora fin</strong></td>
        <td style="width: 10%;">{{ $order->updated_at->format('H:i') ?? '' }}</td>
        <td colspan="2"><strong>Nombre del Cliente</strong></td>
        <td style="width: 10%;">{{ $order->prospect_aradial->name }}</td>
    </tr>
    <tr>
        </td>
        <td colspan="2" style="height: 45px; vertical-align: bottom; text-align: center;">
            <div style="border-top: 1px solid #000; margin: 0 10px;">
                <strong>Firma del Cliente</strong>
                @if(isset($order->signature))
                <div style="text-align: center;">
                    <img src="{{ $order->signature }}" style="width: 150px; height: auto;">
                    <p style="font-size: 6px; margin: 0;">Firma Digital del cliente</p>
                </div>
            @endif
            </div>
        </td>
    </tr>
</table>

<div style="border: 1px solid #000; margin-top: 5px; padding: 6px; font-size: 7.5px; text-align: left; line-height: 1.2;">
    La edición de este documento es responsabilidad de la Coordinación de Gestión de la Calidad. <br>
    Cualquier modificación o cambio que requiera implementar debe ser consultado formalmente ante la Gerencia de Innovación.
</div>
    </div>
</div>

<div class="page-break"></div>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="logo-box">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/image001.png'))) }}" width="90">
            </td>
            <td style="text-align: center; padding: 10px;">
                <strong style="font-size: 14px;">DEPARTAMENTO DE OPERACIONES / ORDEN DE INSTALACIÓN</strong>
            </td>
        </tr>
    </table>
</div>

<div style="margin-top: 5px;">
    <div style="border: 1px solid #000; border-bottom: none; padding: 3px; font-weight: bold; background: #eee;">Observaciones:</div>
    <div style="border: 1px solid #000; height: 100px; padding: 5px;">
    </div>
</div>

<table style="margin-top: 10px; width: 100%;">
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold; background: #eee; font-size: 8px;">
            USAR SOLO EN CASO DE QUE EL METRAJE EMPLEADO EN LA INSTALACIÓN SOBREPASE LA CANTIDAD PERMITIDA
        </td>
    </tr>
    <tr>
        <td style="width: 30%; height: 50px; vertical-align: top;">
            <strong>Metros empleados en exceso:</strong>
        </td>
        <td style="width: 35%; font-size: 7px; text-align: center;">
            La firma del cliente refleja la conformidad del empleo de metraje extra en la instalación.
        </td>
        <td style="width: 35%; text-align: center; vertical-align: bottom;">
            <div style="border-top: 1px solid #000; margin: 0 10px; padding-top: 2px;">Firma y cédula del Cliente</div>
        </td>
    </tr>
</table>

<div class="main-container clearfix" style="margin-top: 10px;">
    <div class="col-left" style="width: 25%;">
        <table class="checklist-table">
            <tr style="background: #eee;"><th colspan="2">LEYENDA</th></tr>
            <tr><td style="width: 40%;">Drop</td><td style="text-align: center;"><div class="sym-line"></div></td></tr>
            <tr><td>Tanquilla </td><td style="text-align: center; font-size: 12px;"><div class="sym sym-rect"></div></td></tr>
            <tr><td>Poste </td><td style="text-align: center;"><div class="sym sym-empty-circle"></div></td></tr>
            <tr><td>FXb</td><td style="text-align: center;"><div class="sym sym-cross"></div></td></tr>
            <tr><td>Router </td><td style="text-align: center;"><div class="sym sym-cross"></div></td></tr>
            <tr><td>ONT</td><td style="text-align: center;"><div class="sym-empty-circle"></div></td></tr>
            <tr><td>ONU</td><td style="text-align: center;"><div class="sym sym-cross"></div></td></tr>
            <tr><td>&nbsp;</td><td></td></tr>
            <tr><td>&nbsp;</td><td></td></tr>
            <tr><td>&nbsp;</td><td></td></tr>
        </table>
    </div>

    <div class="col-right" style="width: 73%; border: 1px solid #000; height: 300px; position: relative;">
        <div class="croquis-container" style="height: 100%; border: none; margin-top: 0;">
            </div>
        <div style="position: absolute; bottom: 5px; right: 10px; font-size: 8px; color: #666;">
            Use este espacio para dibujar el croquis 
        </div>
    </div>
</div>

</body>
</html>