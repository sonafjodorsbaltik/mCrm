<?php

namespace App\DTOs;

readonly class TicketStatisticsDto
{
    public function __construct(
        public string $period,
        public int $total,
        public int $new,
        public int $inProgress,
        public int $closed,
    ) {}

    public function toArray(): array 
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