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
            abort( 403,'Unauthorized');
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

        /**
         * Lista de prospectos 
         */

    public function listProspects(Request $request) {
        /**
         * Validacion de usuarios 
        */
        $user = $request->attributes->get('jwt_user');

        if ($user->role === 'admin') {
            return response()->json(ProspectAradial::all(),
             200
            );
        }
         if ($user->role === 'supervisor') {
            return response()->json(
                ProspectAradial::where('branch', $user->branch)->get(), 
                200
            );
            
        }
        return response()->json([
            'error'=> 'Unauthorized',
            'message' => 'User not authorized to view this resource'
        ],
        403);
    }

    /**
     * Detalles de un prospecto
     */
    public function prospectDetails(Request $request, $id) {
        /**
         * Validacion de usuario 
         */
        $user = $request->attributes->get('jwt_user');
        
        if ($user->role === 'admin' || $user->role === 'supervisor') {
            $prospect = ProspectAradial::find($id);
            if (!$prospect) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'Prospect not found'
                ], 404);
            }
            return response()->json($prospect, 200);
        }
        return response()->json([
            'error'=> 'Unauthorized',
            'message' => 'User not authorized to view this resource'
        ], 403);
    }

    /**
     * Actualizar prospecto
     */
    public function updateProspect(Request $request, $id) {
        /**
         * Validacion de usuario 
         */
        $user = $request->attributes->get('jwt_user');
        
        if ($user->role !== 'admin') {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'User not authorized to update this resource'
            ], 403);
        }
        
        $prospect = ProspectAradial::find($id);
        if (!$prospect) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Prospect not found'
            ], 404);
        }

        $validated = $this->validateProspect($request);
        $prospect->update($validated);

        return response()->json([
            'message' => 'Prospect updated successfully',
            'prospect' => $prospect
        ], 200);
    }

    /**
     * Eliminar prospecto
     */
    public function deleteProspect(Request $request, $id) {
        /**
         * Validacion de usuario 
         */
        $user = $request->attributes->get('jwt_user');
        
        if ($user->role !== 'admin') {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'User not authorized to delete this resource'
            ], 403);
        }
        
        $prospect = ProspectAradial::find($id);
        if (!$prospect) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Prospect not found'
            ], 404);
        }

        $prospect->delete();

        return response()->json([
            'message' => 'Prospect deleted successfully'
        ], 200);
    }
}