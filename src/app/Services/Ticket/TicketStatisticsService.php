<?php

namespace App\Services\Ticket;

use App\DTOs\TicketStatisticsDto;
use App\Repositories\Contracts\TicketRepositoryInterface;
use InvalidArgumentException;

class TicketStatisticsService
{
    private const ALLOWED_PERIODS = ['day', 'week', 'month'];

    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {}

    public function getStatistics(string $period): TicketStatisticsDto
    {
        $this->validatePeriod($period);

        return $this->ticketRepository->getStatistics($period);        
    }

    private function validatePeriod(string $period): void
    {
        if (!in_array($period, self::ALLOWED_PERIODS, strict: true)) {
            throw new InvalidArgumentException(
                "Invalid period. Allowed values: " . implode(', ', self::ALLOWED_PERIODS)
            );
        }
    }
}