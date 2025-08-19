<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para manejar el inicio de sesión y registro de usuarios.
 */
class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $payload = [
            'iss' => "laravel-jwt", // Emisor
            'sub' => $user->id,     // ID de usuario
            'iat' => time(),        // Tiempo de emisión
            'exp' => time() + 60 * 60, // Expira en 1 hora
            'name' => $user->name,  // Nombre del usuario
            'role' => $user->role, // Rol del usuario
        ];

        try {
            $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

            return response()->json(['token' => $jwt]);
        } catch (\Exception $e) {
            Log::error('Error al generar token JWT: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'document' => 'required|unique:users',
            'document_type' => 'required',
            'phone' => 'required',
            'branch' => 'required',
            'role' => [
                'required',
                'string',
                'in:admin,contractor,supervisor'
            ],
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = new User;
        $user->name = request()->name;
        $user->last_name = request()->last_name;
        $user->document = request()->document;
        $user->document_type = request()->document_type;
        $user->phone = request()->phone;
        $user->branch = request()->branch;
        $user->role = request()->role;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->save();

        return response()->json($user, 201);
    }
}
