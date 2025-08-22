<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Verificar si el token está presente
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'message' => 'Token no proporcionado',
                'error' => 'unauthorized'
            ], 401);
        }

        // 2. Obtener la clave secreta
        $key = env('JWT_SECRET');
        
        if (!$key) {
            Log::error('JWT_SECRET no está configurado en el entorno');
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => 'server_error'
            ], 500);
        }

        try {
            // 3. Decodificar el token
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            
            // 4. Obtener el usuario del token
            $userId = $decoded->sub;
            
            // 5. Cargar el usuario desde la base de datos
            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado',
                    'error' => 'unauthorized'
                ], 401);
            }
            
            // 6. Autenticar al usuario en la aplicación
            Auth::login($user);
            
            // 7. Agregar el usuario decodificado a la solicitud para uso posterior
            $request->attributes->add(['user' => $user]);
            
            // 8. Continuar con la solicitud
            return $next($request);
            
        } catch (\Firebase\JWT\ExpiredException $e) {
            Log::warning('Token JWT expirado', ['exception' => $e]);
            return response()->json([
                'message' => 'Token expirado',
                'error' => 'token_expired'
            ], 401);
            
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            Log::warning('Firma JWT inválida', ['exception' => $e]);
            return response()->json([
                'message' => 'Token inválido',
                'error' => 'invalid_token'
            ], 401);
            
        } catch (\Exception $e) {
            Log::error('Error al procesar token JWT', ['exception' => $e]);
            return response()->json([
                'message' => 'Error al procesar el token',
                'error' => 'token_error'
            ], 401);
        }
    }
}
