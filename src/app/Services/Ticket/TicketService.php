<?php

namespace App\Services\Ticket;

use App\DTOs\CreateTicketDto;
use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Services\Customer\CustomerService;
use App\Services\Customer\RateLimitService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * Service for managing ticket creation and related business logic.
 * 
 * Orchestrates the ticket creation process including:
 * - Rate limit validation
 * - Customer creation/update
 * - File attachment handling
 */
class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private CustomerService $customerService,
        private RateLimitService $rateLimitService,
        private TicketFileService $ticketFileService
    ) {}

    /**
     * Create a new ticket from the submitted data.
     * 
     * Performs the following steps in a database transaction:
     * 1. Validates rate limit (1 ticket per 24 hours per customer)
     * 2. Creates or updates the customer record
     * 3. Creates the ticket with 'new' status
     * 4. Attaches any uploaded files
     *
     * @param CreateTicketDto $dto Data transfer object containing ticket and customer info
     * @return Ticket The newly created ticket
     * @throws TooManyRequestsHttpException If rate limit exceeded (429)
     */
    public function createTicket(CreateTicketDto $dto): Ticket
    {
        if (!$this->rateLimitService->canSubmitTicket($dto->customerPhone, $dto->customerEmail)) {
            throw new TooManyRequestsHttpException(
                retryAfter: 86400,
                message: __('validation.rate_limit_ticket')
            );
        }

        return DB::transaction(function () use ($dto) {

            $customer = $this->customerService->findOrCreate($dto);

            $ticket = $this->ticketRepository->create($dto, $customer->id);

            $this->ticketFileService->attachFiles($ticket, $dto->files);

            return $ticket;
        });
    }
}