<?php

namespace App\Http\Controllers;

use App\Models\ProspectAradial;
use Illuminate\Http\Request;
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
       
        $validated = $this->validateProspect($request);
        if(is_object($validated ) && $validated->fails()) {
            return response()->json($validated->errors(), 400);
        }
        $prospect = ProspectAradial::create($request->all());

        return response()->json([
            'message' => 'Prospect created successfully',
            'prospect' => $prospect
        ], 201);
    }

    /**
     * List all prospects (admin and supervisors only)
     */
    public function listProspects(Request $request)
    {
        $user = $request->user();

        $prospects = match((int)$user->role_id) {
            1 => ProspectAradial::get(),
            2 => ProspectAradial::with('franchise')
                    ->where('franchise_id', $user->franchise_id)
                    ->get(),
            default => null
        };

        if (!$prospects) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Prospects retrieved successfully',
            'prospects' => $prospects
        ], 200);
    }

    /**
     * Get prospect details (admin and supervisors of the same franchise)
     */
    public function prospectDetails(Request $request, $id)
    {
        $prospect = ProspectAradial::where('id' , $id)->first();
       if(empty($prospect)) {
            return response()->json([
            'message' => 'Prospect no exist'
        ], 404);
        }
        return response()->json([
            'message' => 'Prospect details retrieved successfully',
            'prospect' => $prospect
        ], 200);

    }


    /**
     * Update a prospect (admin only)
     */
    public function updateProspect(Request $request, $id)
    { 
        $prospect = ProspectAradial::where('id' , $id)->first();
       
        if(empty($prospect)) {
            return response()->json([
            'message' => 'Prospect no exist'
        ], 404);
        }

        $validated = $this->validateProspect($request, $prospect);
        
        if(is_object($validated ) && $validated->fails()) {
            return response()->json($validated->errors(), 422);
        };

        $validated = ProspectAradial::where(['id' => $id])->update(request()->all());

        return response()->json([
            'message' => 'Prospect update successfully',
            'prospect' => $validated
        ], 201);
     }
    

    /**
     * Delete a prospect (admin only)
     */
    public function deleteProspect(Request $request, $id)
    {
         $prospect = ProspectAradial::where('id' , $id)->first();
       
        if(empty($prospect)) {
            return response()->json([
            'message' => 'Prospect no exist'
        ], 404);
    }

        $prospect->delete();

        return response()->json([
            'message' => 'Prospect deleted successfully'
        ], 200);
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
            'last_name' => 'required|string|max:255',
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


        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator;
        }
Log::info(print_r($validator->validate(),true));
        return $validator->validate();
    }
}
