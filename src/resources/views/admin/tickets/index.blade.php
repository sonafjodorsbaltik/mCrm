<x-app-layout>
    <h1 class="page-title">All Tickets</h1>
    
    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    {{-- Filters Form --}}
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="filters-form">
        <div class="filters-grid">
            <!-- Status Filter -->
            <div class="filter-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="filter-input">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" 
                                {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div class="filter-group">
                <label for="date_from">From Date</label>
                <input type="text" name="date_from" id="date_from" 
                       value="{{ request('date_from') }}" 
                       placeholder="YYYY-MM-DD"
                       class="filter-input">
            </div>

            <!-- Date To -->
            <div class="filter-group">
                <label for="date_to">To Date</label>
                <input type="text" name="date_to" id="date_to" 
                       value="{{ request('date_to') }}" 
                       placeholder="YYYY-MM-DD"
                       class="filter-input">
            </div>

            <!-- Search -->
            <div class="filter-group">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" 
                       value="{{ request('search') }}" 
                       placeholder="Email, phone, or subject..."
                       class="filter-input">
            </div>

            <!-- Actions -->
            <div class="filter-actions">
                <button type="submit" class="btn-primary">Filter</button>
                <a href="{{ route('admin.tickets.index') }}" class="btn-secondary">Reset</a>
            </div>
        </div>
    </form>
    
    {{-- Tickets Table --}}
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
                    
                    @can('delete', $ticket)
                        <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-delete" onclick="if(confirm('Delete ticket #{{ $ticket->id }}?')) { this.closest('form').submit(); }">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No tickets found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{-- Pagination --}}
    @if($tickets->hasPages())
    <div class="pagination-wrapper">
        {{ $tickets->appends(request()->query())->links('pagination::loft') }}
    </div>
    @endif
</x-app-layout>
