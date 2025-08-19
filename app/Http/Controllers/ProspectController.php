<?php

namespace App\Http\Controllers;

use App\Models\ProspectAradial;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Policies\UserProspectPolicy;




class ProspectController extends Controller
{
       public function registerProspect(Request $request)
    {
        // Autorización con Policy
        $this->authorize('create', ProspectAradial::class);

        // Validación de los datos del prospecto
        $validated = $this->validateProspect($request);
        
        // Creación
        $prospect = ProspectAradial::create($validated);

        return response()->json([
            'message' => 'Prospecto creado con éxito',
            'prospect' => $prospect
        ])->setStatusCode(201);
    }

    public function listProspects(Request $request)
    {
        // ✅ AUTORIZACIÓN CON POLICY
        $this->authorize('viewAny', ProspectAradial::class);

        // Lógica específica según rol
        $user = $request->user(); // Asumiendo que Auth::user() está disponible
        
        if ($user->role === 'admin') {
            return response()->json(ProspectAradial::all(), 200);
        }
        
        if ($user->role === 'supervisor') {
            return response()->json(
                ProspectAradial::where('branch', $user->branch)->get(), 
                200
            );
        }
    }

    public function prospectDetails(Request $request, $id)
    {
        // ✅ AUTORIZACIÓN CON POLICY
        $prospect = ProspectAradial::findOrFail($id);
        $this->authorize('view', $prospect);

        return response()->json($prospect, 200);
    }

    public function updateProspect(Request $request, $id)
    {
        // ✅ AUTORIZACIÓN CON POLICY
        $prospect = ProspectAradial::findOrFail($id);
        $this->authorize('update', $prospect);

        $validated = $this->validateProspect($request);
        $prospect->update($validated);

        return response()->json([
            'message' => 'Prospecto actualizado con éxito',
            'prospect' => $prospect
        ], 200);
    }

    public function deleteProspect(Request $request, $id)
    {
        // ✅ AUTORIZACIÓN CON POLICY
        $prospect = ProspectAradial::findOrFail($id);
        $this->authorize('delete', $prospect);

        $prospect->delete();

        return response()->json([
            'message' => 'Prospecto eliminado con éxito'
        ], 200);
    }

    private function validateProspect(Request $request)
    {
        return Validator::make($request->all(), [
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
        ])->validate();
    }
}