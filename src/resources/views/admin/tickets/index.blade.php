<x-app-layout>
    <h1 class="page-title">All Tickets</h1>
    
    <table class="tickets-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ Str::limit($ticket->subject, 50) }}</td>
                <td>
                    <div>{{ $ticket->customer->name }}</div>
                    <small class="text-muted">{{ $ticket->customer->email }}</small>
                </td>
                <td>
                    <span class="status-badge status-{{ $ticket->status->value }}">
                        {{ $ticket->status->label() }}
                    </span>
                </td>
                <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.tickets.show', $ticket) }}">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No tickets found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination -->
    @if($tickets->hasPages())
    <div class="pagination-wrapper" style="margin-top: 30px;">
        {{ $tickets->appends(request()->query())->links() }}
    </div>
    @endif
</x-app-layout>
