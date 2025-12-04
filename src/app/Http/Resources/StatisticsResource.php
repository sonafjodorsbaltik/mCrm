<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'period' => $this->period,
            'total' => $this->total,
            'by_status' => [
                'new' => $this->new,
                'in_progress' => $this->inProgress,
                'closed' => $this->closed,
            ],
        ];
    }
}
