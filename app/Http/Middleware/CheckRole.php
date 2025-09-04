<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
    
        Log::info("paso 1");
        $user = Auth::user();
        
        if (!$user) {

            return response()->json([
                'message' => 'Authentication required',
                'error' => 'unauthorized'
            ], 401);
        }
        
        
        $userRoleId = (int)$user->role_id;
        
        $allowedRoles = array_map('intval', $allowedRoles);
        
        // 4. COMPARAR CON LOS ROLES PERMITIDOS
        if (!in_array($userRoleId, $allowedRoles)) {
            return response()->json([
                'message' => 'You do not have permission to access this resource',
                'error' => 'forbidden',
                'user_role' => $userRoleId,
                'required_roles' => $allowedRoles
            ], 403);
        }

        return $next($request);
    }
}
