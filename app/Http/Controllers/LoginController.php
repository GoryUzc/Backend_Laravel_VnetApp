<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Iniciar sesión y devolver token JWT
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $key = env('JWT_SECRET');

        if (!$key) {
            return response()->json(['error' => 'Configuración JWT no disponible'], 500);
        }

        $payload = [
            'iss' => 'laravel-jwt',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 3600, // 1 hora
            'name' => $user->name,
            'last_name' => $user->last_name,
            'role_id' => $user->role_id,
            'franchise_id' => $user->franchise_id,
            'email' => $user->email,
        ];

        try {
            $token = JWT::encode($payload, $key, 'HS256');

            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => 3600,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'franchise_id' => $user->franchise_id,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al generar token JWT: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

   /**
 * Cerrar sesión (invalidar token en el cliente)
 */
    public function logout(Request $request)
{
    // En JWT stateless, el logout se maneja en el cliente
    // El servidor solo confirma que el cliente debe eliminar el token
    
    return response()->json([
        'message' => 'Session closed. Please delete the token from your local storage.'
    ], 
200);
}

    /**
     * Registrar un nuevo usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aradial_user_id' => 'required|string|unique:users,aradial_user_id',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document' => 'required|string|unique:users,document',
            'document_type' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'franchise_id' => 'required|exists:franchises,id',
            'role_id' => 'required|exists:roles,id',
            'contractor_id' => 'nullable|exists:users,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = new User();
        $user->aradial_user_id = $request->aradial_user_id;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->document = $request->document;
        $user->document_type = $request->document_type;
        $user->phone = $request->phone;
        $user->franchise_id = $request->franchise_id;
        $user->role_id = $request->role_id;
        $user->contractor_id = $request->contractor_id;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'user' => $user->only('id', 'name', 'email', 'role_id', 'franchise_id')
        ], 201);
    }
}