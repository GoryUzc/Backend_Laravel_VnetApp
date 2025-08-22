<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProspectAradial;
use Illuminate\Auth\Access\Response;

class ProspectPolicy
{
    /**
     * Determine if the user can create prospects.
     */
    public function create(User $user): Response
    {
        return (int)$user->role_id === 1
            ? Response::allow()
            : Response::deny('Unauthorized. Admin access required.');
    }

    /**
     * Determine if the user can view any prospects.
     */
    public function viewAny(User $user): Response
    {
        $roleId = (int)$user->role_id;
        
        if (in_array($roleId, [1, 2])) {
            return Response::allow();
        }
        
        return Response::deny('Unauthorized. Admin or Supervisor access required.');
    }

    /**
     * Determine if the user can view the specified prospect.
     */
    public function view(User $user, ProspectAradial $prospect): Response
    {
        $roleId = (int)$user->role_id;
        
        if ($roleId === 1) {
            return Response::allow();
        }
        
        if ($roleId === 2 && $user->franchise_id == $prospect->franchise_id) {
            return Response::allow();
        }
        
        return Response::deny('Unauthorized. You can only view prospects from your franchise.');
    }

    /**
     * Determine if the user can update the specified prospect.
     */
    public function update(User $user, ProspectAradial $prospect): Response
    {
        return (int)$user->role_id === 1
            ? Response::allow()
            : Response::deny('Unauthorized. Admin access required.');
    }

    /**
     * Determine if the user can delete the specified prospect.
     */
    public function delete(User $user, ProspectAradial $prospect): Response
    {
        return (int)$user->role_id === 1
            ? Response::allow()
            : Response::deny('Unauthorized. Admin access required.');
    }
}