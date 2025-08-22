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
     * Registra un solo usuario (solo admin)
     */
       public function registerUser(Request $request)
    {
        // Autorización con Policy
        $this->authorize('create', User::class);
        
        // Validación de datos Usurios
        $validated = $this->validateUser($request);
        
        //hashear contraseña
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
    
        return response()->json([
            'message'=> 'User created successfully',
        'user'=> $user],
            201 );
    }

    /**
     *listar usuarios 
     */
    public function listUser(Request $request)
    {
        // Autorizacion con Policy
        $this->authorize('viewAny', User::class); 
     $validated = $this->validateUser($request);

        return response()->json([
        'message' => 'Users retrieved successfully',
        'users' => User::all()
        ], 200);
    }

    /**
     * Detalles de usuario
     */
    public function detailsUser(Request $request, $id)
    {
        // Autorización con Policy
        $userToBeViewed = User::findOrFail($id);
        $this->authorize('view', $userToBeViewed);

        return response()->json([
        'message' => 'User retrieved successfully',
        'user' => $userToBeViewed
        ], 200);
    }

    public function updateUser(Request $request, $id)
    {
        // Autorización con Policy
        $userToBeUpdated = User::findOrFail($id);
        $this->authorize('update', $userToBeUpdated);

        $validated = $this->validateUser($request, $userToBeUpdated);

         if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); // No modificar contraseña
        }

        $userToBeUpdated->update($validated);

        return response()->json([
            'message' => 'User successfully updated.',
            'userToBeUpdated' => $userToBeUpdated->refresh()
        ], 200);
        
    }

    public function deleteUser(Request $request, $id)
    {
        // Autorización con Policy
        $userToBeDeleted = User::findOrFail($id);
        $this->authorize('delete', $userToBeDeleted);

        $userToBeDeleted->delete();

        return response()->json([
            'message' => 'User successfully deleted.'
        ], 200);
        
    }

 private function validateUser(Request $request, $user = null)
{
    $rules = [
        'aradial_user_id' => [
            'required', 'string',
            Rule::unique('users', 'aradial_user_id')->ignore($user?->id)
        ],
        'name' => 'required|string|max:50',
        'last_name' => 'required|string|max:50',
        'document' => [
            'required', 'string', 'max:20',
            Rule::unique('users', 'document')->ignore($user?->id)
        ],
        'document_type' => 'required|string|max:20',
        'phone' => 'required|string|max:20',
        'franchise_id' =>  [
            'required', 'string',
            Rule::unique('users', 'franchies_id')->ignore($user?->id)
        ],
        'role_id' => [
            'required', 'string',
            Rule::unique('users', 'role_id')->ignore($user?->id)
        ],
        'contractors_id' =>  [
            'required', 'string',
            Rule::unique('users', 'contractor_id')->ignore($user?->id)
        ],
        'email' => [
            'required', 'email',
            Rule::unique('users', 'email')->ignore($user?->id)
        ],
        'password' => $user ? 'nullable|string|min:8' : 'required|string|min:8',
    ];

    return Validator::make($request->all(), $rules)->validate();
    }
}