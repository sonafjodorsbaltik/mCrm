<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy for User model authorization.
 * 
 * Defines who can view, create, and delete users.
 * Only admins with 'manage users' permission have access.
 */
class UserPolicy
{
    /**
     * Determine if user can view users list
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage users');
    }
    
    /**
     * Determine if user can create new users
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage users');
    }
    
    /**
     * Determine if user can delete another user
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }
        
        return $user->hasPermissionTo('manage users');
    }
}
