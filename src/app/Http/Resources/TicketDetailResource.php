<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for detailed ticket representation.
 * 
 * Transforms a Ticket model into a JSON response with
 * all ticket details, customer info, and file attachments.
 */
class TicketDetailResource extends JsonResource
{
    /**
     * Transform the ticket into an array for API response.
     *
     * @param Request $request The incoming request
     * @return array Ticket data with customer and attachments
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'content' => $this->content,
            'status' => $this->status->label(),
            'status_code' => $this->status->value,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'replied_at' => $this->replied_at?->toISOString(),
            'customer' => [
                'name' => $this->customer->name,
                'email' => $this->customer->email,
                'phone' => $this->customer->phone,
            ],
            'attachments' => $this->getMedia('attachments')->map(fn($media) => [
                'id' => $media->id,
                'name' => $media->file_name,
                'size' => $media->size,
                'url' => $media->getUrl(),
            ]),
        ];
    }
}
