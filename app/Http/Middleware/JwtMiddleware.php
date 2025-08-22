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
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'message' => 'Token not provided',
                'error' => 'unauthorized'
            ], 401);
        }

        $key = env('JWT_SECRET');
        
        if (!$key) {
            Log::error('JWT_SECRET not configured');
            return response()->json([
                'message' => 'Server configuration error',
                'error' => 'server_error'
            ], 500);
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->sub;
            
            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                    'error' => 'unauthorized'
                ], 401);
            }
            
            Auth::login($user);
            $request->attributes->add(['user' => $user]);
            
            return $next($request);
            
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json([
                'message' => 'Token expired',
                'error' => 'token_expired'
            ], 401);
            
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return response()->json([
                'message' => 'Invalid token signature',
                'error' => 'invalid_token'
            ], 401);
            
        } catch (\Exception $e) {
            Log::error('JWT processing error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Token processing error',
                'error' => 'token_error'
            ], 401);
        }
    }
}