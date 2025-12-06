<?php

namespace App\Repositories\Contracts;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Enums\TicketStatusEnum;
use App\DTOs\TicketStatisticsDto;
use App\DTOs\CreateTicketDto;

/**
 * Contract for Ticket repository operations.
 * 
 * Defines methods for CRUD operations, filtering, and statistics.
 */
interface TicketRepositoryInterface
{
    /**
     * Create a new ticket.
     */
    public function create(CreateTicketDto $dto, int $customerId): Ticket;

    /**
     * Find a ticket by ID.
     */
    public function findById(int $id): ?Ticket;

    /**
     * Update ticket status.
     */
    public function updateStatus(int $id, TicketStatusEnum $status): bool;

    /**
     * Get tickets with optional filters.
     * 
     * @param array $filters Associative array of filter options
     */
    public function getWithFilters(array $filters): Builder;

    /**
     * Get ticket statistics for a time period.
     * 
     * @param string $period 'day', 'week', or 'month'
     */
    public function getStatistics(string $period): TicketStatisticsDto;
    
    /**
     * Find recent ticket by customer phone AND email.
     * 
     * @param int $hours Time window in hours
     */
    public function getRecentByPhoneOrEmail(
        string $phone, 
        string $email, 
        int $hours = 24
    ): ?Ticket;

}