<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProspectAradial;
use Illuminate\Auth\Access\Response;



class ProspectPolicy
{
 /**
     * Determine cual usuario pueden crear un prospecto.
     */
    public function create(User $user): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Unauthorized.');
    }

    /**
     * Determina que usuario puede ver todos los prospectos.
     */
    public function viewAny(User $user): Response
    {
        
        if ($user->role === 'admin') {
            return Response::allow();
        }

        if ($user->role === 'supervisor') {
            return Response::allow();
        }

        return Response::deny('Unauthorized.');
    }

    /**
     * Determina si el usuario puede ver un prospecto en especifico.
     */
    public function view(User $user, ProspectAradial $prospect): Response
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }

        if ($user->role === 'supervisor' && $user->branch === $prospect->branch) {
            return Response::allow();
        }

        return Response::deny('Unauthorized.');
    }

    /**
     * Determina que usuario puede actualizar un prspecto.
     */
    public function update(User $user): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Unauthorized.');
    }

    /**
     * Determina que usuario puede borrar un prospecto .
     */
    public function delete(User $user): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Unauthorized.');
    }
   
}
