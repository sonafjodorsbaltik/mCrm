<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticsResource;
use App\Services\Ticket\TicketStatisticsService;
use Illuminate\Http\Request;

/**
 * API Controller for ticket statistics.
 * 
 * Provides aggregated ticket statistics for authenticated users.
 * Requires auth:sanctum and admin|manager role.
 */
class TicketStatisticsController extends Controller
{
    public function __construct(
        private TicketStatisticsService $statisticsService
    ) {}

    /**
     * Get ticket statistics for a specified period.
     * 
     * @param Request $request Request with optional 'period' param (day, week, month)
     * @return StatisticsResource Statistics data
     */
    public function index(Request $request): StatisticsResource
    {
        $period = $request->input('period', 'day');
        
        $dto = $this->statisticsService->getStatistics($period);

        return new StatisticsResource($dto);
    }
}