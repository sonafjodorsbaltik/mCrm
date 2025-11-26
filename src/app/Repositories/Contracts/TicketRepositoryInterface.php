<?php

namespace App\Repositories\Contracts;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use App\Enums\TicketStatusEnum;

interface TicketRepositoryInterface
{
    public function create(array $attributes): Ticket;

    public function findById(int $id): ?Ticket;

    public function updateStatus(int $id, TicketStatusEnum $status): bool;

    public function getWithFilters(array $filters): Collection;

    public function getStatistics(string $period): array;
    
    public function getRecentByPhoneOrEmail(
        string $phone, 
        string $email, 
        int $hours = 24
    ): ?Ticket;

}