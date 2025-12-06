<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Enums\TicketStatusEnum;

/**
 * Observer for Ticket model events.
 * 
 * Automatically handles side effects when ticket data changes,
 * such as setting replied_at timestamp on status changes.
 */
class TicketObserver
{
    /**
     * Handle the Ticket "updating" event.
     * 
     * Sets replied_at to current timestamp when status changes
     * to IN_PROGRESS or CLOSED.
     *
     * @param Ticket $ticket The ticket being updated
     */
    public function updating(Ticket $ticket): void
    {
        if ($ticket->isDirty('status') && $ticket->status->requiresReplyDate()) {
            $ticket->replied_at = now();
        }
    }
}
