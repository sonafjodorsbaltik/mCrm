<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use Illuminate\Http\UploadedFile;

/**
 * Service for handling file attachments on tickets.
 * 
 * Uses Spatie Media Library to attach files to tickets.
 */
class TicketFileService
{
    /**
     * Attach uploaded files to a ticket.
     * 
     * Files are stored in the 'attachments' media collection.
     *
     * @param Ticket $ticket The ticket to attach files to
     * @param UploadedFile[]|null $files Array of uploaded files or null
     */
    public function attachFiles(Ticket $ticket, ?array $files): void
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
