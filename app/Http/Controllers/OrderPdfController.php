<?php

namespace App\Http\Controllers;

use App\Models\InstallationOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderPdfController extends Controller
{
    public function downloadPdf(Request $request, $id)
    {
        // Buscar la orden con la relación necesaria para la vista (una sola consulta)
        $order = InstallationOrder::with('prospect_aradial')->findOrFail((int) $id);

        // Verificar que la orden tenga firma
        if (empty($order->signature_path)) {
            return response()->json(['error' => 'La orden no tiene firma'], 400);
        }

        // Generar PDF con opciones soportadas por DomPDF
        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
        ])->loadView('pdf.order', compact('order'))
        ->setPaper('a4');

        // Devolver como descarga
        return $pdf->download("orden_instalacion_{$order->id}.pdf");
    }

    public function previewPdf(Request $request, $id)
    {
        try {
            // Buscar la orden con la relación necesaria para la vista (una sola consulta)
            $order = InstallationOrder::with('prospect_aradial')->findOrFail((int) $id);

            // Generar PDF para previsualización
            $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            ])->loadView('pdf.order', compact('order'))
            ->setPaper('a4');

            return $pdf->stream("orden_instalacion_{$order->id}.pdf");
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Error al generar el PDF'], 500);
        }
    }
}
