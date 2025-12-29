<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContractorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Register a new contractor (admin and supervisors)
     */
    public function registerContractor(Request $request)
    {
        $authUser = $request->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $rules = [
            'legal_name' => 'required|string|max:255',
            'rif' => 'required|string|unique:contractors,rif|max:20',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:contractors,email|max:255',
            'franchise_id' => 'required|exists:franchises,id',
            'address' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
         }

         $validated = $validator->validated();

        // Supervisors can only create contractors in their own franchise
        if ($authUser->role_id == 2 && $validated['franchise_id'] != $authUser->franchise_id) {
            return response()->json([
                'message' => 'You cannot create contractors in other franchises'
            ], 403);
        }

        $contractor = Contractor::create($validated);

        return response()->json([
            'message' => 'Contractor created successfully',
            'contractor' => $contractor->load('users')
        ], 201);
    }

    /**
     * List all contractors (admin and supervisors only)
     */
    public function listContractor(Request $request)
    {
        $authUser = $request->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($authUser->role_id == [1, 2]) { // Admin
            $contractors = Contractor::with('users')->get();
        } elseif ($authUser->role_id == 2) { // Supervisor
            $contractors = Contractor::with('users')
                ->where('franchise_id', $authUser->franchise_id)
                ->get();
                // ->paginate(20);
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'message' => 'Contractors retrieved successfully',
            'contractors' => $contractors
        ], 200);
    }

    /**
     * Show contractor details
     */
    public function detailsContractor(Request $request, $id)
    {
        $contractor = Contractor::with('users')->find($id);
        if (!$contractor) {
            return response()->json([
                'message' => 'Contractor not found'
            ], 404);
        }
    
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
        $authUser = $request->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $contractor = Contractor::find($id);
        if (!$contractor) {
            return response()->json([
                'message' => 'Contractor not found'
            ], 404);
        }

        // Supervisors can only update contractors in their own franchise
        if ($authUser->role_id == 2 && $contractor->franchise_id != $authUser->franchise_id) {
            return response()->json([
                'message' => 'You cannot update contractors in other franchises'
            ], 403);
        }

        $rules = [
            'legal_name' => 'sometimes|required|string|max:255',
            'rif' => 'sometimes|required|string|max:20|unique:contractors,rif,' . $id,
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255|unique:contractors,email,' . $id,
            'franchise_id' => 'sometimes|required|exists:franchises,id',
            'address' => 'sometimes|required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        $validated = validator()->$validator;

        // Supervisors can only create contractors in their own franchise
        if ($authUser->role_id == 2 && $validated['franchise_id'] != $authUser->franchise_id) {
            return response()->json([
                'message' => 'You cannot create contractors in other franchises'
            ], 403);
        }

        $contractor->update($validated);

        return response()->json([
            'message' => 'Contractor updated successfully',
            'contractor' => $contractor->fresh()->load('users')
        ], 200);
    }

    /**
     * Delete a contractor
     */
    public function deleteContractor(Request $request, $id) {
        $authUser = $request->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $contractor = Contractor::find($id);
        if (!$contractor) {
            return response()->json([
                'message' => 'Contractor not found'
            ], 404);
        }

        // Supervisors can only delete contractors in their own franchise
        if ($authUser->role_id == 2 && $contractor->franchise_id != $authUser->franchise_id) {
            return response()->json([
                'message' => 'You cannot delete contractors in other franchises'
            ], 403);
        }

        // Verify no associated users exist
        if ($contractor->users()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete a contractor with associated users'
            ], 400);
        }

        $delete = $contractor->delete();
        if ($delete) {
            return response()->json([
            'message' => 'Contractor deleted successfully'
             ], 200);
        } else {
            return response()->json([
            'error' => 'Failed to delete contractor'
            ], 500);
        }
    }

    //List contractors to process for register (public)
    public function listForRegister(){
        $contractors = Contractor::select(
            'id', 'legal_name')->get(); 
            return response()->json([
                'message' => 'Contractors list retrieved successfully',
                'contractors' => $contractors
            ], 200);
    }

    // List de workers of contractor 
    public function listworkerscontractor(Request $request){
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if (!$user->contractor){
            return response()->json(['message' => 'The user is not associated with any contractor'], 404);
        }
        $workers = $user->contractor->users()->where('role_id', 4)->get();
        return response()->json([
            'message' => 'Workers retrieved successfully',
            'workers' => $workers
        ], 200);
    }
}
