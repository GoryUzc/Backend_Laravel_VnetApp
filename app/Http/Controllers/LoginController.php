<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\User;
use App\Models\Role;
use App\Models\Franchises;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    //Inicio de sesion retorna token 
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

   //Final de sesion, invalida Token 
    public function logout(Request $request)
{
    // En JWT stateless, el logout se maneja en el cliente
    // El servidor solo confirma que el cliente debe eliminar el token
    
    return response()->json([
        'message' => 'Session closed. Please delete the token from your local storage.'
    ], 
200);
}

    //Registro de nuevos usuarios 
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
        
        //
        if ($request->role_id == 3){
            $rules = array_merge( [
                'legal_name' => 'requiered|string|max:50',
                'rif'=> 'required|string|unique:contractors,rif|max:20',
                'contractor_name'=> 'requered|string|max:20',
                'contractor_phone'=> 'required|string|max:20',
                'contractor_email'=> 'required|email|max:20|unique:contractors,email',
                'franchise_id'=> 'required|exist:franchise_id',
                'address'=> 'required|string|max:250',
            ]);
        } 

        if ($request->role_id == 4){
            $rules = ['contractor_id'] = 'required|exist:contractors, id';
        }

         $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //Si es contratista primero crear la empresa
        $contractorId = null;
        if ($request->role_id == 3){
            $contractor = Contractor::create([
            'legal_name' => $request->legal_name,
            'rif'=> $request->rif,
            'contractor_name'=> $request->name,
            'contractor_phone'=> $request->phone,
            'contractor_email'=> $request->email,
            'franchise_id'=> $request->franchise_id,
            'address'=> $request->address
            ]);
            $contractorId = $contractor->id;
           };
       
        //Crear nuevo usuario 
        $user = new User();
        $user->aradial_user_id = $request->aradial_user_id;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->document = $request->document;
        $user->document_type = $request->document_type;
        $user->phone = $request->phone;
        $user->franchise_id = $request->franchise_id;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        //asignar contractor_id 
        if ($request->role_id == 3){
            $user->contractor_id = $contractorId;
        }else if ($request->role_id == 4){
            $user->contractor_id = $request->contractor_id;
        }
        $user->save();

        return response()->json([
            'message' => 'Usuario registed successfully',
            'user' => $user->load ('contractor')->only(
                'id', 'name', 'email', 'role_id', 'contractor_id'
            ), 
            'contractor' => $user->contractor ? $user->contractor->only(
                'id', 'legal_name', 'name', 'email'
            ) : null
            ], 201);
    }
    public function listFranchisesUser(){
        $franchises = Franchises::select(
            'id', 'branch_oficce')->get(); 
            return response()->json([
                'message' => 'Franchises list retrived successfully',
                'Frachises' => $franchises
            ], 200);
    }

    public function listRoleUser(){
        $roles = Role::select(
            'id', 'name')->get(); 
            return response()->json([
                'message' => 'Roles list retrived successfully',
                'Roles' => $roles
            ], 200);
    }
}