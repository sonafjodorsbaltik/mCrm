<?php

namespace App\Services\Customer;

use App\Repositories\Contracts\TicketRepositoryInterface;

/**
 * Service for enforcing ticket submission rate limits.
 * 
 * Prevents spam by limiting ticket submissions to one per 24 hours
 * per customer (identified by phone AND email).
 */
class RateLimitService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {}

    /**
     * Check if a customer can submit a new ticket.
     * 
     * Returns false if the customer (matching both phone AND email)
     * has already submitted a ticket within the last 24 hours.
     *
     * @param string $phone Customer's phone number in E.164 format
     * @param string $email Customer's email address
     * @return bool True if submission allowed, false if rate limited
     */
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