<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

/**
 * Policy for Ticket model authorization.
 * 
 * Defines who can delete tickets based on permissions.
 */
class TicketPolicy
{
    /**
     * Determine if the user can delete the ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('delete tickets');
    }
    
    /**
     * Determine if the user can permanently delete the ticket.
     * Only admins can force delete (if using soft deletes).
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }
}
