<x-app-layout>
    <h1 class="page-title">Create Manager</h1>
    
    <div class="detail-card">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" 
                       value="{{ old('name') }}" 
                       class="filter-input" required>
                @error('name')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" 
                       value="{{ old('email') }}" 
                       class="filter-input" required>
                @error('email')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" 
                       class="filter-input" required>
                @error('password')
                    <span class="text-error">{{ $message }}</span>
                @enderror
                <small class="text-muted">Minimum 8 characters</small>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" 
                       id="password_confirmation" 
                       class="filter-input" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Create Manager</button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
