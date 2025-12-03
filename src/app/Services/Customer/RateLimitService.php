<?php

namespace App\Services\Customer;

use App\Repositories\Contracts\TicketRepositoryInterface;

class RateLimitService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {}

    public function canSubmitTicket(string $phone, string $email): bool
    {
        $recentTicket = $this->ticketRepository->getRecentByPhoneOrEmail(
            phone: $phone,
            email: $email,
            hours: 24
        );
        
        return $recentTicket === null;
    }
}