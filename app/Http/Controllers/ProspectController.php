<?php

namespace App\Http\Controllers;

use App\Models\ProspectAradial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProspectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Register a new prospect (admin only)
     */
    public function registerProspect(Request $request)
    {
        $this->authorize('create', ProspectAradial::class);
        
        $validated = $this->validateProspect($request);
        $prospect = ProspectAradial::create($validated);

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
        $this->authorize('viewAny', ProspectAradial::class);
        $user = $request->user();

        $prospects = match((int)$user->role_id) {
            1 => ProspectAradial::with('franchise')->get(),
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
        $prospect = ProspectAradial::with('franchise')->findOrFail($id);
        $this->authorize('view', $prospect);

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
        $prospect = ProspectAradial::with('franchise')->findOrFail($id);
        $this->authorize('update', $prospect);

        $validated = $this->validateProspect($request, $prospect);
        $prospect->update($validated);

        return response()->json([
            'message' => 'Prospect updated successfully',
            'prospect' => $prospect->refresh()
        ], 200);
    }

    /**
     * Delete a prospect (admin only)
     */
    public function deleteProspect(Request $request, $id)
    {
        $prospect = ProspectAradial::findOrFail($id);
        $this->authorize('delete', $prospect);

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

        return Validator::make($request->all(), $rules)->validate();
    }
}