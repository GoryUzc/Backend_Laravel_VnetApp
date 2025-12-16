<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\ProspectAradial;
use App\Models\User;
use App\Models\Role;
use App\Models\Franchises;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'role_id' => $user->role_id,
            'franchises' => $user->franchise_id,
        ];

        try {
            $token = JWT::encode($payload, $key, 'HS256');

            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => 3600,
                'id' => $user->id,
                'role_id' => $user->role_id,
                'franchise_id' => $user->franchise_id,
            ],
             200);
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
            // 'aradial_user_id' => 'required|string|unique:users,aradial_user_id',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document' => 'required|string|unique:users,document',
            'document_type' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'franchise_id' => 'required|exists:franchises,id',
            'role_id' => 'required|exists:roles,id',
            'contractor_id' => 'nullable|exists:contractors,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        
        //
        if ($request->role_id == 3){
            $request->validate([
            'legal_name' => 'required|string|max:50',
            'rif'=> 'required|string|unique:contractors,rif|max:20',
            'contractor_name'=> 'required|string|max:255',
            'contractor_phone'=> 'required|string|max:20',
            'contractor_email'=> 'required|email|unique:contractors,email|max:255',
            'franchise_id'=> 'required|exists:franchises,id',
            'address'=> 'required|string|max:250'
            ]);
        } 
        
        if ($request->role_id == 4){
        $request->validate(['contractor_id' => 'required|exists:contractors,id']);
        }


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //Si es contratista primero crear la empresa
        $contractorId = null;
        if ($request->role_id == 3){
            $contractor = Contractor::create([
            'legal_name' => $request->legal_name,
            'rif'=> $request->rif,
            'name'=> $request->contractor_name,
            'phone'=> $request->contractor_phone,
            'email'=> $request->contractor_email,
            'franchise_id'=> $request->franchise_id,
            'address'=> $request->address
            ]);
            $contractorId = $contractor->id;
           };
       
        //Crear nuevo usuario 
        $user = new User();
        // $user->aradial_user_id = $request->aradial_user_id;
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
            'id', 'branch_office')->whereRaw("NULLIF(TRIM(branch_office), '') IS NOT NULL") ->get(); 
            return response()->json([
                'message' => 'franchises list retrived successfully',
                'franchises' => $franchises
            ], 200);
    }

    public function listRoleUser(){
        $roles = Role::select(
            'id', 'name')
            -> whereIn('id', [3, 4])
            ->get(); 
            return response()->json([
                'message' => 'Roles list retrived successfully',
                'roles' => $roles
            ], 200);
    }

public function generateNumericString(){
        return substr(str_shuffle('123456789'), 0, 4);
    }

    
public function sendOtpToProspect(Request $request)
{
    $request->validate([
        'document' => 'required|string',
        'email' => 'required|email',
    ]);

    // Buscar prospecto por documento y correo
    $prospect = ProspectAradial::where('document', $request->document)
        ->where('email', $request->email)
        ->first();

    if (!$prospect) {
        return response()->json(['error' => 'Prospect no exist'], 404);
    }

    // Generar OTP
    $otp = $this->generateNumericString();

    // Guardar OTP en la tabla
    $prospect->otp = $otp;
    $prospect->save();

    // Enviar OTP por correo
    Mail::send('email.otpProspect', ['otp' => $otp, 'prospect' => $prospect], function ($message) use ($prospect) {
        $message->from(env('MAIL_FROM_ADDRESS'), 'VNET');
        $message->to($prospect->email);
        $message->subject('Codigo de Verificacion');
    });

    return response()->json([
        'message' => 'OTP sent successfully',
        'prospect_id' => $prospect->id
    ], 200);
}
    public function verifyProspect(Request $request){
    $request->validate([
        'document' => 'required|string',
        'email' => 'required|email',
        'otp' => 'required|string',
    ]);

    // Buscar prospecto por documento y correo
    $prospect = ProspectAradial::where('document', $request->document)
        ->where('email', $request->email)
        ->first();

    if (!$prospect) {
        return response()->json(['error' => 'Prospect no exist'], 404);
    }

     $key = env('JWT_SECRET');

    if (!$key) {
        return response()->json(['error' => 'Configuración JWT no disponible'], 500);
        }

    $payload = [
        'iss' => 'laravel-jwt',
        'sub' => $prospect->id,
        'iat' => time(),
        'exp' => time() + 3600, // 1 hora
        'email' => $prospect->email,
    ];

    // Verificar OTP
    if ($prospect->otp !== $request->otp) {
        return response()->json(['error' => 'OTP invalyd'], 401);
    }

    return response()->json([
        'message' => 'Prospect verified sussessfully',
        'prospect' => $prospect->only('id'),
        'token' => JWT::encode($payload, $key, 'HS256'),
        'token_type' => 'bearer',
        'expires_in' => 3600,
    ], 200);
    }
}