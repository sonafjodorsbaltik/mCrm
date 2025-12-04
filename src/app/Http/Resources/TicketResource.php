<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
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