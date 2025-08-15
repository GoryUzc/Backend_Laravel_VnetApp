<?php

namespace App\Http\Controllers;

use App\Models\ProspectAradial;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;




class ProspectController extends Controller
{
    public function registerProspect (Request $request) {
        /**
         * Registro de prospectors solo para administradores 
         */
        $this->authorizedAdmin($request);

        /**
         * Validacion de los datos de entrada
         */
       $validated = $this->validateProspect($request);
        
        /**
         * Creacion del prospecto
         */
        $prospect = ProspectAradial::create($validated);

        return response()->json([
            'message'=> 'Prospecto creado con exito',
            'prospect' => $prospect
        ])->setStatusCode(201);
    }
    /**
     * Validacion que el usuario es un admin
     */
    private function authorizedAdmin(Request $request) {
         $user = $request->attributes->get('jwt_user');
         if ($user->role !== 'admin') {
            abourt('Unauthorized', 403);
         }
}
    /**
     * Validacion de los datos de entrada
     */
    private function validateProspect(Request $request) {
        $validator = Validator::make($request->all(), [
            'aradial_id' => 'required|string|unique:prospect_aradial,aradial_id',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document' => 'required|string|max:255',
            'document_type' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'plan' => 'required|string|max:100',
        ]);
        return $validator->validate();
    }   
}