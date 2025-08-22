<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contractor;
use Illuminate\Auth\Access\Response;

class ContractorPolicy
{
    /**
     * Creación de contratistas: Admin, Supervisores y contratistas.
     */
    public function create(User $user): Response
    {
        // Convertir a entero
        $roleId = (int)$user->role_id;
        
        // 1 = admin, 2 = supervisor, 3 = contractor
        if (in_array($roleId, [1, 2, 3])) {
            return Response::allow();
        }
        
        return Response::deny('Unauthorized.');
    }

    /**
     * Vista de todos los contratistas: Admin y Supervisor.
     */
    public function viewAny(User $user): Response
    {
        $roleId = (int)$user->role_id;
        
        if (in_array($roleId, [1, 2])) {
            return Response::allow();
        }
        
        return Response::deny('Unauthorized.');
    }

    /**
     * Consulta de contratista: 
     * - Admin: puede ver todos
     * - Supervisor: solo los de su franquicia
     * - Contractor: solo su propia empresa
     */
    public function view(User $user, Contractor $contractor): Response
    {
        $roleId = (int)$user->role_id;
        
        if ($roleId === 1) {
            return Response::allow();
        }
        
        if ($roleId === 2 && $user->franchise_id == $contractor->franchise_id) {
            return Response::allow();
        }
        
        if ($roleId === 3 && $user->contractor_id == $contractor->id) {
            return Response::allow();
        }
        
        return Response::deny('Unauthorized.');
    }

    /**
     * Actualización de contratista: 
     * - Admin: puede actualizar todos
     * - Supervisor: solo los de su franquicia
     * - Contractor: solo su propia empresa
     */
    public function update(User $user, Contractor $contractor): Response
    {
        $roleId = (int)$user->role_id;
        
        if ($roleId === 1) {
            return Response::allow();
        }
        
        if ($roleId === 2 && $user->franchise_id == $contractor->franchise_id) {
            return Response::allow();
        }
        
        if ($roleId === 3 && $user->contractor_id == $contractor->id) {
            return Response::allow();
        }
        
        return Response::deny('Unauthorized.');
    }

    /**
     * Eliminación de contratista: 
     * Solo Admin puede eliminar contractors
     */
    public function delete(User $user, Contractor $contractor): Response
    {
        $roleId = (int)$user->role_id;
        
        if ($roleId === 1) {
            return Response::allow();
        }
        
        return Response::deny('Unauthorized.');
    }
}