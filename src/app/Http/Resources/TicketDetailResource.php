<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketDetailResource extends JsonResource
{
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
