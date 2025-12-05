<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Ticket\TicketStatisticsService;
use App\Repositories\Contracts\TicketRepositoryInterface;

class DashboardController extends Controller
{
    public function __construct(
        private TicketStatisticsService $statisticsService,
        private TicketRepositoryInterface $ticketRepository
    ) {}

    public function index()
    {
        // Get statistics for the last week
        $stats = $this->statisticsService->getStatistics('week');
        
        // Get 5 most recent tickets
        $recentTickets = $this->ticketRepository->getWithFilters([])
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentTickets'));
    }
}
