<?php

namespace App\Http\Controllers\Api\Tickets;

use App\DTOs\CreateTicketDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketDetailResource;
use App\Services\Ticket\TicketService;

/**
 * API Controller for ticket operations.
 * 
 * Handles public ticket creation endpoint.
 */
class TicketController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    /**
     * Create a new ticket from widget form submission.
     * 
     * @param StoreTicketRequest $request Validated ticket data
     * @return TicketDetailResource The created ticket
     */
    public function store(StoreTicketRequest $request): TicketDetailResource
    {
        $dto = CreateTicketDto::fromArray($request->validated());
        $ticket = $this->ticketService->createTicket($dto);

        return new TicketDetailResource($ticket);
    }
}