<?php

namespace App\Repositories\Contracts;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use App\Enums\TicketStatusEnum;
use App\DTOs\TicketStatisticsDto;
use App\DTOs\CreateTicketDto;

interface TicketRepositoryInterface
{
    public function create(CreateTicketDto $dto): Ticket;

    public function findById(int $id): ?Ticket;

    public function updateStatus(int $id, TicketStatusEnum $status): bool;

    public function getWithFilters(array $filters): Collection;

    public function getStatistics(string $period): TicketStatisticsDto;
    
    public function getRecentByPhoneOrEmail(
        string $phone, 
        string $email, 
        int $hours = 24
    ): ?Ticket;

}