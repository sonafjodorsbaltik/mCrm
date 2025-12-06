<?php

namespace App\DTOs;

/**
 * Data Transfer Object for ticket statistics.
 * 
 * Contains aggregated ticket counts grouped by status
 * for a specific time period.
 */
readonly class TicketStatisticsDto
{
    /**
     * @param string $period Time period ('day', 'week', 'month')
     * @param int $total Total number of tickets
     * @param int $new Count of tickets with 'new' status
     * @param int $inProgress Count of tickets with 'in_progress' status
     * @param int $closed Count of tickets with 'closed' status
     */
    public function __construct(
        public string $period,
        public int $total,
        public int $new,
        public int $inProgress,
        public int $closed,
    ) {}

    /**
     * Convert DTO to array format for API responses.
     *
     * @return array{period: string, total: int, by_status: array{new: int, in_progress: int, closed: int}}
     */
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