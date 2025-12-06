<?php

namespace App\Repositories\Eloquent;

use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\DTOs\CreateTicketDto;
use App\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\DTOs\TicketStatisticsDto;
use Carbon\Carbon;

/**
 * Eloquent implementation of TicketRepository.
 * 
 * Handles all database operations for Ticket model including
 * CRUD operations, filtering, and statistics.
 */
class TicketRepository implements TicketRepositoryInterface
{
    /**
     * Create a new ticket.
     *
     * @param CreateTicketDto $dto Ticket data (subject, content)
     * @param int $customerId ID of the associated customer
     * @return Ticket The newly created ticket with 'new' status
     */
    public function create(CreateTicketDto $dto, int $customerId): Ticket
    {
        return Ticket::create([
            'customer_id' => $customerId,
            'subject' => $dto->subject,
            'content' => $dto->content,
            'status' => TicketStatusEnum::NEW,
        ]);
    }

    /**
     * Find a ticket by ID.
     *
     * @param int $id Ticket ID
     * @return Ticket|null The ticket or null if not found
     */
    public function findById(int $id): ?Ticket
    {
        return Ticket::find($id);
    }

    /**
     * Update ticket status.
     * 
     * Note: TicketObserver will automatically set replied_at
     * when status changes to IN_PROGRESS or CLOSED.
     *
     * @param int $id Ticket ID
     * @param TicketStatusEnum $status New status
     * @return bool True if updated successfully, false if ticket not found
     */
    public function updateStatus(int $id, TicketStatusEnum $status): bool
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return false;
        }

        $ticket->status = $status;

        return $ticket->save();
    }

    /**
     * Get tickets with optional filters.
     * 
     * Supports filtering by:
     * - status: exact match
     * - date_from/date_to: date range
     * - email/phone: partial match on customer
     * - search: unified search across subject, email, phone
     *
     * @param array $filters Associative array of filters
     * @return Builder Query builder for further chaining (pagination, etc.)
     */
    public function getWithFilters(array $filters): Builder
    {
        $query = Ticket::with('customer');
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['email'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('email', 'like', '%' . $filters['email'] . '%');
            });
        }
        
        if (isset($filters['phone'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('phone', 'like', '%' . $filters['phone'] . '%');
            });
        }
        
        // Unified search across email, phone, and subject
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('email', 'like', "%{$search}%")
                                   ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }
        
        return $query->latest();
    }

    /**
     * Get ticket statistics for a time period.
     * 
     * Uses efficient SQL COUNT with GROUP BY.
     *
     * @param string $period Time period ('day', 'week', 'month')
     * @return TicketStatisticsDto Statistics with counts by status
     */
    public function getStatistics(string $period): TicketStatisticsDto
    {
        $startDate = match($period) {
            'day' => Carbon::today(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            default => Carbon::today(),
        };
        
        // Use SQL COUNT with GROUP BY for better performance
        // Instead of loading all tickets into memory
        $statusCounts = Ticket::where('created_at', '>=', $startDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        
        return new TicketStatisticsDto(
            period: $period,
            total: $statusCounts->sum(),
            new: $statusCounts->get(TicketStatusEnum::NEW->value, 0),
            inProgress: $statusCounts->get(TicketStatusEnum::IN_PROGRESS->value, 0),
            closed: $statusCounts->get(TicketStatusEnum::CLOSED->value, 0),
        );
    }
    
    /**
     * Find recent ticket by customer phone AND email.
     * 
     * Used for rate limiting - checks if customer submitted
     * a ticket within the specified time window.
     *
     * @param string $phone Customer phone in E.164 format
     * @param string $email Customer email address
     * @param int $hours Time window in hours (default 24)
     * @return Ticket|null Most recent ticket or null if none found
     */
    public function getRecentByPhoneOrEmail(
        string $phone, 
        string $email, 
        int $hours = 24
    ): ?Ticket {
        return Ticket::whereHas('customer', function ($query) use ($phone, $email) {
                // Check if BOTH phone AND email match (prevent bypass)
                $query->where('phone', $phone)
                      ->where('email', $email);
            })
            ->where('created_at', '>=', Carbon::now()->subHours($hours))
            ->latest()
            ->first();
    }
    
}
