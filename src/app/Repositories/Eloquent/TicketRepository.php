<?php

namespace App\Repositories\Eloquent;

use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\DTOs\CreateTicketDto;
use App\Enums\TicketStatusEnum;
use Illuminate\Support\Collection;
use App\DTOs\TicketStatisticsDto;
use App\Models\Customer;
use Carbon\Carbon;

class TicketRepository implements TicketRepositoryInterface
{
    public function create(CreateTicketDto $dto): Ticket
    {
        $customer = Customer::updateOrCreate(
            ['email' => $dto->customerEmail],
            [
                'name' => $dto->customerName,
                'phone' => $dto->customerPhone,
            ]
        );

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

    public function getWithFilters(array $filters): Collection
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
        
        return $query->latest()->get();
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
