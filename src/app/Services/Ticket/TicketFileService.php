<?php

namespace App\Services\Ticket;

use App\Models\Ticket;

class TicketFileService
{
    public function attachFiles(Ticket $ticket, ?array $files) : void
    {
        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            $ticket->addMedia($file)
                    ->toMediaCollection('attachments');
        }
    }
}
