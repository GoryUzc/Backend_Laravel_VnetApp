<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;



class UserPolicy
{
 /**
     * Verificacion de Admin 
     */
    private function isAdmin(User $user): Response 
    {
        if( $user->role === 'admin')
        {
           return Response::allow();
        }
        return Response::deny('Unauthorized.');
    }
    /**
     * Crecaion de User
     */
    public function create(User $authenticatedUser): Response
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Vista de todos los Users
     */
    public function viewAny(User $authenticatedUser): Response
    {
         return $this->isAdmin($authenticatedUser);
    }

    /**
     * Consulta User 
     */
    public function view(User $authenticatedUser, User $userToBeViewed): Response
    {
       return $this->isAdmin($authenticatedUser);
    }

    /**
     * Actualiza User
     */
    public function update(User $authenticatedUser, User $userToBeUpdated): Response
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Elimina User
     */
    public function delete(User  $authenticatedUser, User $userToBeDeleted): Response
    {
       return $this->isAdmin($authenticatedUser);
    }
   
}
