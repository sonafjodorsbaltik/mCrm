<x-app-layout>
    <h1 class="page-title">Change Password</h1>
    
    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="detail-card">
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" 
                       id="current_password" 
                       class="filter-input" required>
                @error('current_password')
                    <span class="text-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" 
                       class="filter-input" required>
                @error('password')
                    <span class="text-error">{{ $message }}</span>
                @enderror
                <small class="text-muted">Minimum 8 characters</small>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" 
                       id="password_confirmation" 
                       class="filter-input" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</x-app-layout>
