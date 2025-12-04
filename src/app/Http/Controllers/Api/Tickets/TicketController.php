<?php

namespace App\Http\Controllers\Api\Tickets;

use App\DTOs\CreateTicketDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketDetailResource;
use App\Services\Ticket\TicketService;

class TicketController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    public function store(StoreTicketRequest $request)
    {
        $dto = CreateTicketDto::fromArray($request->validated());
        $ticket = $this->ticketService->createTicket($dto);

        return new TicketDetailResource($ticket);
    }
}