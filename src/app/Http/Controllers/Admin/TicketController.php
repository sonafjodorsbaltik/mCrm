<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Enums\TicketStatusEnum;
use App\Http\Requests\Admin\UpdateTicketStatusRequest;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {}

    /**
     * Display a listing of tickets
     */
    public function index(Request $request)
    {
        // Get filters from request
        $filters = $request->only(['status', 'date_from', 'date_to']);
        
        // Handle unified search (email, phone, subject)
        if ($search = $request->get('search')) {
            $filters['search'] = $search;
        }
        
        // Get tickets with pagination (20 per page)
        $tickets = $this->ticketRepository->getWithFilters($filters)->paginate(20);
        
        // Get all statuses for filter dropdown
        $statuses = TicketStatusEnum::cases();
        
        return view('admin.tickets.index', compact('tickets', 'statuses'));
    }

    /**
     * Display a single ticket
     */
    public function show(Ticket $ticket)
    {
        // Eager load relationships
        $ticket->load(['customer', 'media']);
        
        // Get all available statuses for the dropdown
        $statuses = TicketStatusEnum::cases();
        
        return view('admin.tickets.show', compact('ticket', 'statuses'));
    }

    /**
     * Update ticket status (AJAX)
     */
    public function updateStatus(Ticket $ticket, UpdateTicketStatusRequest $request)
    {
        // Validation already passed via Form Request
        // Get validated status as Enum
        $status = $request->getStatus();
        
        // Update status via repository
        $this->ticketRepository->updateStatus($ticket->id, $status);
        
        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'status' => $status->value,
            'label' => $status->label()
        ]);
    }

    /**
     * Delete a ticket (soft delete)
     */
    public function destroy(Ticket $ticket)
    {
        // Check if user has permission to delete
        $this->authorize('delete', $ticket);
        
        // Clear all associated media files before deletion
        $ticket->clearMediaCollection();
        
        // Soft delete the ticket
        $ticket->delete();
        
        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Ticket deleted successfully');
    }
}
