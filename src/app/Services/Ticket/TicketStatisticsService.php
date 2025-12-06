<?php

namespace App\Services\Ticket;

use App\DTOs\TicketStatisticsDto;
use App\Repositories\Contracts\TicketRepositoryInterface;
use InvalidArgumentException;

/**
 * Service for retrieving ticket statistics.
 * 
 * Provides aggregated statistics about tickets for different time periods.
 */
class TicketStatisticsService
{
    /** @var string[] Allowed time periods for statistics */
    private const ALLOWED_PERIODS = ['day', 'week', 'month'];

    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {}

    /**
     * Get ticket statistics for the specified period.
     *
     * @param string $period Time period ('day', 'week', or 'month')
     * @return TicketStatisticsDto Statistics containing counts by status
     * @throws InvalidArgumentException If period is not valid
     */
    public function getStatistics(string $period): TicketStatisticsDto
    {
        $this->validatePeriod($period);

        return $this->ticketRepository->getStatistics($period);        
    }

    /**
     * Validate that the period is allowed.
     *
     * @param string $period The period to validate
     * @throws InvalidArgumentException If period is not in ALLOWED_PERIODS
     */
    private function validatePeriod(string $period): void
    {
        if (!in_array($period, self::ALLOWED_PERIODS, strict: true)) {
            throw new InvalidArgumentException(
                "Invalid period. Allowed values: " . implode(', ', self::ALLOWED_PERIODS)
            );
        }
    }
}