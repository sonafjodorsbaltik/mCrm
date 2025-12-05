<?php

namespace App\Repositories\Eloquent;

use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\DTOs\CreateTicketDto;
use App\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\DTOs\TicketStatisticsDto;
use App\Models\Customer;
use Carbon\Carbon;

class TicketRepository implements TicketRepositoryInterface
{
    public function create(CreateTicketDto $dto): Ticket
    {
        // Customer should already exist, created by CustomerService
        $customer = Customer::where('email', $dto->customerEmail)->firstOrFail();
        
        return Ticket::create([
            'customer_id' => $customer->id,
            'subject' => $dto->subject,
            'content' => $dto->content,
            'status' => TicketStatusEnum::NEW,
        ]);
    }

    public function findById(int $id): ?Ticket
    {
        return Ticket::find($id);
    }

    public function updateStatus(int $id, TicketStatusEnum $status): bool
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return false;
        }

        $ticket->status = $status;

        return $ticket->save();
    }

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

    public function getStatistics(string $period): TicketStatisticsDto
    {
        $startDate = match($period) {
            'day' => Carbon::today(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            default => Carbon::today(),
        };
        
        $tickets = Ticket::where('created_at', '>=', $startDate)->get();
        
        return new TicketStatisticsDto(
            period: $period,
            total: $tickets->count(),
            new: $tickets->where('status', TicketStatusEnum::NEW)->count(),
            inProgress: $tickets->where('status', TicketStatusEnum::IN_PROGRESS)->count(),
            closed: $tickets->where('status', TicketStatusEnum::CLOSED)->count(),
        );
    }
    
    public function getRecentByPhoneOrEmail(
        string $phone, 
        string $email, 
        int $hours = 24
    ): ?Ticket {
        return Ticket::whereHas('customer', function ($query) use ($phone, $email) {
                $query->where('phone', $phone)
                      ->orWhere('email', $email);
            })
            ->where('created_at', '>=', Carbon::now()->subHours($hours))
            ->latest()
            ->first();
    }
    
}
