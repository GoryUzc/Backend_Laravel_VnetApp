<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine if the user is an admin.
     */
    private function isAdmin(User $user): Response
    {
        
        if ((int)$user->role_id === 1) {
            return Response::allow();
        }
        return Response::deny('Unauthorized. Admin access required.');
    }

    /**
     * Determine if the user can create new users.
     */
    public function create(User $authenticatedUser): Response
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $authenticatedUser): Response
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Determine if the user can view the specified user.
     */
    public function view(User $authenticatedUser, User $userToBeViewed): Response
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Determine if the user can update the specified user.
     */
    public function update(User $authenticatedUser, User $userToBeUpdated): Response
    {
        return $this->isAdmin($authenticatedUser);
    }

    /**
     * Determine if the user can delete the specified user.
     */
    public function delete(User $authenticatedUser, User $userToBeDeleted): Response
    {
        if ((int)$authenticatedUser->role_id !== 1) {
            return Response::deny('Unauthorized. Admin access required.');
        }
        
        if ($authenticatedUser->id === $userToBeDeleted->id) {
            return Response::deny('You cannot delete your own account.');
        }
        
        return Response::allow();
    }
}