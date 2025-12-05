<x-app-layout>
    <a href="{{ route('admin.tickets.index') }}" class="back-link">← Back to Tickets</a>

    <h1 class="page-title">Ticket #{{ $ticket->id }}</h1>
    
    <div class="ticket-details">
        <!-- Ticket Info -->
        <div class="detail-card">
            <h3>Subject</h3>
            <p class="subject-text">{{ $ticket->subject }}</p>
            
            <h3 style="margin-top: 25px;">Message</h3>
            <p>{{ $ticket->content }}</p>
        </div>
        
        <!-- Customer Info -->
        <div class="detail-card">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $ticket->customer->name }}</p>
            <p><strong>Email:</strong> {{ $ticket->customer->email }}</p>
            <p><strong>Phone:</strong> {{ $ticket->customer->phone }}</p>
        </div>
        
        <!-- Status -->
        <div class="detail-card">
            <h3>Status</h3>
            <select id="status-select" class="status-select" data-ticket-id="{{ $ticket->id }}">
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Attachments -->
        @if($ticket->media->count() > 0)
        <div class="detail-card">
            <h3>Attachments ({{ $ticket->media->count() }})</h3>
            @foreach($ticket->media as $file)
                <a href="{{ $file->getUrl() }}" target="_blank" class="attachment-link">
                    📎 {{ $file->file_name }} ({{ number_format($file->size / 1024, 2) }} KB)
                </a>
            @endforeach
        </div>
        @endif
    </div>
    
    @push('scripts')
    <script>
        const statusSelect = document.getElementById('status-select');
        const ticketId = statusSelect.dataset.ticketId;
        
        statusSelect.addEventListener('change', async (e) => {
            const newStatus = e.target.value;
            const previousValue = e.target.querySelector('option[selected]')?.value || e.target.value;
            
            try {
                const response = await fetch(`/admin/tickets/${ticketId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✓ Status updated successfully to: ' + data.label);
                } else {
                    alert('Error updating status');
                    statusSelect.value = previousValue;
                }
            } catch (error) {
                alert('Error updating status: ' + error.message);
                console.error(error);
            }
        });
    </script>
    @endpush
</x-app-layout>
