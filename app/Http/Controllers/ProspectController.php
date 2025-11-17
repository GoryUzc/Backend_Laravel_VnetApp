<?php

namespace App\Http\Controllers;

use App\Models\ProspectAradial;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProspectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Register a new prospect
     */
    public function registerProspect(Request $request)
    {
        $validator = $this->validateProspect($request);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            $prospect = ProspectAradial::create($validatedData);

            return response()->json([
                'message' => 'Prospect created successfully',
                'prospect' => $prospect
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all prospects (admin and supervisors only)
     */
    public function listProspects(Request $request)
    {
        $user = $request->user();

        try {

        $prospects = match((int)$user->role_id) {
            1 => ProspectAradial::get(),
            2, 3, 4 => ProspectAradial::with('franchise')
                    ->where('franchise_id', $user->franchise_id)
                    ->get(),
            default => null
        };
            return response()->json([
                'message' => 'Prospects retrieved successfully',
                'prospects' => $prospects
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get prospect details
     */
    public function prospectDetails(Request $request, $id)
    {
        $prospect = ProspectAradial::find($id);

        if (!$prospect) {
            return response()->json([
                'message' => 'Prospect does not exist'
            ], 404);
        }

        return response()->json([
            'message' => 'Prospect details retrieved successfully',
            'prospect' => $prospect
        ], 200);
    }

    /**
     * Update a prospect
     */
    public function updateProspect(Request $request, $id)
    {
        $prospect = ProspectAradial::find($id);

        if (!$prospect) {
            return response()->json([
                'message' => 'Prospect does not exist'
            ], 404);
        }

        $validator = $this->validateProspect($request, $prospect);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            $prospect->update($validatedData);

            return response()->json([
                'message' => 'Prospect updated successfully',
                'prospect' => $prospect
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a prospect
     */
    public function deleteProspect(Request $request, $id)
    {
        $prospect = ProspectAradial::find($id);

        if (!$prospect) {
            return response()->json([
                'message' => 'Prospect does not exist'
            ], 404);
        }

        try {
            $prospect->delete();

            return response()->json([
                'message' => 'Prospect deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Consult existence in prospect_aradial 

public function consultProspectAradial(Request $request, $document)
{
    try {
        // Busca el prospecto por ID
        $prospect = ProspectAradial::find($document, 'document');

        // Si llega aquí, el prospecto existe
        return response()->json([
            'status' => 'Prospect exists',
        ], 200);

    } catch (ModelNotFoundException $e) {
        // Si no se encuentra el prospecto
        return response()->json([
            'error' => 'Prospect not found',
        ], 404);

    } catch (\Exception $e) {
        // Para cualquier otro error inesperado
        return response()->json([
            'error' => 'Internal Server Error: ' . $e->getMessage()
        ], 500);
    }
}



    /**
     * Validate prospect data
     */
    private function validateProspect(Request $request, $prospect = null)
    {
        $rules = [
            'aradial_id' => [
                'required',
                'string',
                Rule::unique('prospect_aradial', 'aradial_id')->ignore($prospect?->aradial_id, 'aradial_id')
            ],
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'document' => 'required|string|max:255',
            'document_type' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'plan' => 'required|string|max:100',
            'franchise_id' => 'required|exists:franchises,id',
            'status_red' => 'nullable|string|max:50',
        ];

        return Validator::make($request->all(), $rules);
    }
}