<x-app-layout>
    <h1 class="page-title">User Management</h1>
    
    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
    @endif
    
    <div class="mb-20">
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            + Create Manager
        </a>
    </div>
    
    <table class="tickets-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                        <span class="status-badge status-{{ $role->name }}">
                            {{ ucfirst($role->name) }}
                        </span>
                    @endforeach
                </td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>
                    @can('delete', $user)
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-delete" onclick="if(confirm('Delete user {{ $user->name }}?')) { this.closest('form').submit(); }">
                                Delete
                            </button>
                        </form>
                        @else
                        <span class="text-muted">You</span>
                        @endif
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
