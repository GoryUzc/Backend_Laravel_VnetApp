<?php

namespace App\Http\Controllers;

use App\Models\ProspectAradial;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
     * Consult existence in prospect from aradial 
     */
    public function getConsultFromAradial($document, $type_document) {

        try {
            if (empty($document)) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Document parameter is required'
                ], 400);
            }
            Log::info("Consulting prospect in API from Aradial with document: " . $document);
            
            $response = Http::timeout(30)
            ->withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get(env('IP_CONSULT')."/api/v2/client/V/{$document}"); 
            Log::info( print_r($response, true) );
            if ($response->successful()) {
                $externalData = $response->json();
                Log::info( print_r($externalData, true) );
                $transformedData = $this->transformerAradialData($externalData['data'], $type_document);
                $prospect = $this->syncProspectToLocalData($transformedData);
                $contractIds = $this->extractsContractIds($externalData['data']);
                return response()->json([
                    'status' => 'success',
                    'prospect' => $prospect->id,
                    'email' => $prospect->email,
                    'contract_ids' => $contractIds,
                    'existInAradial' => true,
                ], 200);
            } else {
                Log::warning("Failed to retrieve prospect from Aradial API for document: ", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json([
                    'status'=> 'error',
                    'message' => 'Failed to retrieve prospect from Aradial API',
                    'external_status'=> $response->status(),
                ], 404);
            }
        }catch (\Exception $e) {
            Log::error("Error consulting prospect from Aradial API: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error: ' . $e->getMessage()
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

    private function transformerAradialData($data, $type_document) {
        return [
            'aradial_id' => $data['id'] ?? null,
            'name' => $data['name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'document' => $data['doc'] ?? null,
            'document_type' => $type_document ?? null,
            'phone' => $data['contracts'][0]['cellphone'] ?? null,
            'address' => $data['contracts'][0]['address'] ?? null,
            'city' => $data['sucursal'] ?? null,
            'email' => $data['email'] ?? null,
            'plan' => $data['contracts'][0]['services'][0]['package_name'] ?? null,
            'franchise_id' => $data['contracts'][0]['franchise_id'] ?? null,
            'status_red' => $data['contracts'][0]['services'][0]['service_status'] ?? null,
        ];
    }

    private function syncProspectToLocalData($data) {

        $prospect = ProspectAradial::where('document', $data['document'])->first();

        if ($prospect){

            $prospect->update($data);
            Log::info("Prospect update locally with document:" . $data['document']);
        } else {
            $prospect = ProspectAradial::create($data);
            Log::info("Prospect created locally with document:" . $data['document']);
        }
        
        return $prospect;
    }

    private function extractsContractIds ($data): array {

        if (!isset($data['contracts']) || !is_array($data['contracts'])) {
            return [];
        }

        return array_column($data['contracts'], 'contract_id');
    }
}