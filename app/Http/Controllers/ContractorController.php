<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContractorController extends Controller
{
    use AuthorizesRequests;

    /**
     * List all contractors (admin and supervisors only)
     */
    public function registerContractor(Request $request)
    {
        $this->authorize('viewAny', Contractor::class);
        $user = $request->user();

        if ($user->role_id == 1) { // Admin
            $contractors = Contractor::with('users')->get();
        } elseif ($user->role_id == 2) { // Supervisor
            $contractors = Contractor::with('users')
                ->where('franchise_id', $user->franchise_id)
                ->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

         $contractor = Contractor::create($request->all());

        return response()->json([
            'message' => 'Contractor created successfully',
            'contractor' => $contractor->load('users')
        ], 201);
    }

    /**
     * Register a new contractor
     */
    public function listContractor(Request $request)
    {
        $this->authorize('create', Contractor::class);
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'legal_name' => 'required|string|max:255',
            'rif' => 'required|string|unique:contractors,rif|max:20',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:contractors,email|max:255',
            'franchise_id' => 'required|exists:franchises,id',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Supervisors can only create contractors in their own franchise
        if ($user->role_id == 2 && $request->franchise_id != $user->franchise_id) {
            return response()->json([
                'message' => 'You cannot create contractors in other franchises'
            ], 403);
        }
    }
       
    /**
     * Show contractor details
     */
    public function detailsContractor(Request $request, $id)
    {
        $contractor = Contractor::with('users')->findOrFail($id);
        $this->authorize('view', $contractor);

        return response()->json([
            'message' => 'Contractor details retrieved successfully',
            'contractor' => $contractor
        ], 200);
    }

    /**
     * Update a contractor
     */
    public function updateContractor(Request $request, $id)
    {
        $contractor = Contractor::findOrFail($id);
        $this->authorize('update', $contractor);

        $validator = Validator::make($request->all(), [
            'legal_name' => 'sometimes|required|string|max:255',
            'rif' => 'sometimes|required|string|max:20|unique:contractors,rif,'.$id,
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255|unique:contractors,email,'.$id,
            'franchise_id' => 'sometimes|required|exists:franchises,id',
            'address' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = $request->user();
        // Supervisors can only update contractors in their own franchise
        if ($user->role_id == 2 && $request->has('franchise_id') && $request->franchise_id != $user->franchise_id) {
            return response()->json([
                'message' => 'You cannot move contractors to other franchises'
            ], 403);
        }

        $contractor->update($request->all());

        return response()->json([
            'message' => 'Contractor updated successfully',
            'contractor' => $contractor->fresh()->load('users')
        ], 200);
    }

    /**
     * Delete a contractor
     */
    public function deleteContractor(Request $request, $id)
    {
        $contractor = Contractor::findOrFail($id);
        $this->authorize('delete', $contractor);

        // Verify no associated users exist
        if ($contractor->users()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete a contractor with associated users'
            ], 400);
        }

        $contractor->delete();

        return response()->json([
            'message' => 'Contractor deleted successfully'
        ], 200);
    }

    //List contractors to process for register (public)
    public function lisForRegistration(){
        $contractors = Contractor::select(
            'id', 'legal_name')->get(); 
            return response()->json([
                'message' => 'Contractors list retrived successfully',
                'contractors' => $contractors
            ], 200);
    }
}