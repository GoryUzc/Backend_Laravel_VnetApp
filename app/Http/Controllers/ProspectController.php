<?php

namespace App\Http\Controllers;

use App\Models\ProspectAradial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProspectController extends Controller
{
    use AuthorizesRequests;


       public function registerProspect(Request $request)
    {
        // Autorización con Policy
        $this->authorize('create', ProspectAradial::class);
       
        $user = $request->user();
        
        // Validación de los datos del prospecto
        $validated = $this->validateProspect($request);
        if (!$validated){
            return response()->json(['message'=>'Validation error'], 422);
        }
    }

    public function listProspects(Request $request)
    {
        // ✅ AUTORIZACIÓN CON POLICY
        $this->authorize('viewAny', ProspectAradial::class);

        // Lógica específica según rol
        $user = $request->user(); 

        if ($user->role === 'admin') {
            return response()->json(ProspectAradial::all(), 200);
        } elseif ($user->role === 'supervisor') {
            return response()->json(
                ProspectAradial::where('franchise_id', $user->franchise_id)->get(), 
                200
            );
        } else {
                return response()->json(['message'=>'Unathorized'], 403);
            }
    }

    public function detailsProspect(Request $request, $id)
    {
        // Autorización con Policy
        $prospect = ProspectAradial::findOrFail($id);
        $this->authorize('view', $prospect);

        return response()->json($prospect, 200);
    }

    public function updatedProspect(Request $request, $id)
    {
        // Autorización con Policy
        $prospect = ProspectAradial::findOrFail($id);
        $this->authorize('update', $prospect);

        $validated = $this->validateProspect($request, $prospect);
        $prospect->update($validated);

        return response()->json([
            'message' => 'Prospecto actualizado con éxito',
            'prospect' => $prospect
        ], 200);
    }

    public function deleteProspect(Request $request, $id)
    {
        // Autorización con Policy
        $prospect = ProspectAradial::findOrFail($id);
        $this->authorize('delete', $prospect);

        $prospect->delete();

        return response()->json([
            'message' => 'Prospecto eliminado con éxito'
        ], 200);
    }

    private function validateProspect(Request $request, $prospect = null)
    {
       $rules = [
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
        ];
        // Si es una actualización, ignorar la unicidad del aradial_id del prospecto actual
        if ($prospect) {
            $rules['aradial_id'] .= ",aradial_id,{$prospect->id}";
        } else {
            $rules['aradial_id'] .= "|unique:prsopect_aradial,aradial_id";
        }
        return Validator::make($request->all(), $rules)->validate();
    }
}