<x-app-layout>
    <h1 class="page-title">Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Tickets</h3>
            <p class="stat-value" style="color: var(--accent);">{{ $stats->total }}</p>
        </div>
        
        <div class="stat-card">
            <h3>New</h3>
            <p class="stat-value status-new">{{ $stats->new }}</p>
        </div>
        
        <div class="stat-card">
            <h3>In Progress</h3>
            <p class="stat-value status-in_progress">{{ $stats->inProgress }}</p>
        </div>
        
        <div class="stat-card">
            <h3>Closed</h3>
            <p class="stat-value status-closed">{{ $stats->closed }}</p>
        </div>
    </div>
    
    <!-- Recent Tickets -->
    <h2 class="mb-20">Recent Tickets</h2>
    
    <table class="tickets-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>
                    <a href="{{ route('admin.tickets.show', $ticket) }}">
                        {{ $ticket->subject }}
                    </a>
                </td>
                <td>{{ $ticket->customer->name }}</td>
                <td>
                    <span class="status-badge status-{{ $ticket->status->value }}">
                        {{ $ticket->status->label() }}
                    </span>
                </td>
                <td>{{ $ticket->created_at->diffForHumans() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No tickets yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
