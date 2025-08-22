<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRol
{
    public function handle(Request $request, Closure $next, ...$roles) 
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Authentication required',
                'error' => 'unauthorized'
            ], 401);
        }
        
        // Convertir role_id a entero para comparación consistente
        $userRoleId = (int)$user->role_id;
        
        if (!in_array($userRoleId, array_map('intval', $roles))) {
            return response()->json([
                'message' => 'You do not have permission to access this resource',
                'error' => 'forbidden'
            ], 403);
        }
        
        return $next($request);
    }
}
