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

class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private CustomerService $customerService,
        private RateLimitService $rateLimitService,
        private TicketFileService $ticketFileService
    ) {}

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

            $ticket = $this->ticketRepository->create([
                'customer_id' => $customer->id,
                'subject' => $dto->subject,
                'content' => $dto->content,
                'status' => TicketStatusEnum::NEW,
            ]);

            $this->ticketFileService->attachFiles($ticket, $dto->files);

            return $ticket;
        });
    }
}