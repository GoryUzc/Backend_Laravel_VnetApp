<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
     public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Configuración CORS para desarrollo multiplataforma
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        
        // Manejar solicitudes OPTIONS (preflight)
        if ($request->isMethod('options')) {
            return response()->json(['status' => 'ok'], 200);
        }

        return $response;
    }
}
