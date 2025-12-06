<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for ticket list representation.
 * 
 * Used for listing tickets with minimal data.
 * For full ticket details, use TicketDetailResource.
 */
class TicketResource extends JsonResource
{
    /**
     * Transform the ticket into a compact array for lists.
     *
     * @param Request $request The incoming request
     * @return array Ticket summary with id, subject, status, and customer name
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'status' => $this->status->label(),
            'status_code' => $this->status->value,
            'created_at' => $this->created_at->toISOString(),
            'customer_name' => $this->customer->name,
        ];
    }
}