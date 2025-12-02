<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Enums\TicketStatusEnum;

class TicketObserver
{
    public function updating(Ticket $ticket) : void
    {
        if ($ticket->isDirty('status') && $ticket->status->requiresReplyDate()) {
            $ticket->replied_at = now();
        }
    }
}
