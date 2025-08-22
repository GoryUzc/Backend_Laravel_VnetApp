<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Register a new user (admin only)
     */
    public function registerUser(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $this->validateUser($request);
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->only('id', 'name', 'email', 'role_id', 'franchise_id', 'contractor_id')
        ], 201);
    }

    /**
     * List all users (admin only)
     */
    public function listUser(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with(['franchise', 'role', 'contractor'])->get();

        return response()->json([
            'message' => 'Users retrieved successfully',
            'users' => $users
        ], 200);
    }

    /**
     * Get user details (admin only)
     */
    public function detailsUser(Request $request, $id)
    {
        $user = User::with(['franchise', 'role', 'contractor'])->findOrFail($id);
        $this->authorize('view', $user);

        return response()->json([
            'message' => 'User details retrieved successfully',
            'user' => $user
        ], 200);
    }

    /**
     * Update a user (admin only)
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $validated = $this->validateUser($request, $user);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->refresh()->load(['franchise', 'role', 'contractor'])
        ], 200);
    }

    /**
     * Delete a user (admin only)
     */
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    /**
     * Validate user data
     */
    private function validateUser(Request $request, $user = null)
    {
        $rules = [
            'aradial_user_id' => [
                'required', 
                'string',
                Rule::unique('users', 'aradial_user_id')->ignore($user?->id)
            ],
            'name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'document' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('users', 'document')->ignore($user?->id)
            ],
            'document_type' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'franchise_id' => 'required|exists:franchises,id',
            'role_id' => 'required|exists:roles,id',
            'contractor_id' => 'nullable|exists:contractors,id',
            'email' => [
                'required', 
                'email',
                Rule::unique('users', 'email')->ignore($user?->id)
            ],
            'password' => $user ? 'nullable|string|min:8' : 'required|string|min:8',
        ];

        return Validator::make($request->all(), $rules)->validate();
    }
}