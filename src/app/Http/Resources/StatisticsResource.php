<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for ticket statistics.
 * 
 * Transforms TicketStatisticsDto into a JSON response
 * with aggregated ticket counts by status.
 */
class StatisticsResource extends JsonResource
{
    /**
     * Transform the statistics DTO into an array.
     *
     * @param Request $request The incoming request
     * @return array Statistics with period and status counts
     */
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
