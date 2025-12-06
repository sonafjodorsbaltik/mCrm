<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticsResource;
use App\Services\Ticket\TicketStatisticsService;
use Illuminate\Http\Request;

class TicketStatisticsController extends Controller
{
    public function __construct(
        private TicketStatisticsService $statisticsService
    ) {}

    public function index(Request $request): StatisticsResource
    {
        $period = $request->input('period', 'day');
        
        $dto = $this->statisticsService->getStatistics($period);

        return new StatisticsResource($dto);
    }
}