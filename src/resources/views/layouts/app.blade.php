<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') - mCRM Admin</title>

        <!-- Loft Theme + Admin Theme -->
        <link rel="stylesheet" href="{{ asset('css/loft-theme.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
        
        <!-- Flatpickr Date Picker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        
        @stack('styles')
    </head>
    <body>
        <!-- Theme Toggle Button -->
        <button type="button" class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
            <span id="theme-icon">○</span>
        </button>

        <!-- Admin Header -->
        <header class="admin-header">
            <div class="container">
                <h1>mCRM Admin</h1>
                
                <nav class="admin-nav">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.tickets.index') }}">Tickets</a>
                    
                    @can('viewAny', App\Models\User::class)
                        <a href="{{ route('admin.users.index') }}">Users</a>
                    @endcan
                    
                    <a href="{{ route('admin.profile.edit') }}">Profile</a>
                </nav>
                
                <div class="user-menu">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </header>
        
        <!-- Custom Delete Confirmation Modal -->
        <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
            <div style="background: white; padding: 30px; border-radius: 8px; max-width: 400px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <h2 style="margin: 0 0 15px 0; color: #333;">Confirm Deletion</h2>
                <p id="deleteMessage" style="margin: 0 0 25px 0; color: #666;"></p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button id="deleteCancelBtn" style="padding: 10px 30px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Cancel</button>
                    <button id="deleteConfirmBtn" style="padding: 10px 30px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Delete</button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="admin-main">
            {{ $slot }}
        </main>
        
        <script>
            // Custom Modal Delete Confirmation
            let formToSubmit = null;
            const modal = document.getElementById('deleteModal');
            const messageEl = document.getElementById('deleteMessage');
            const confirmBtn = document.getElementById('deleteConfirmBtn');
            const cancelBtn = document.getElementById('deleteCancelBtn');

            // Show modal
            function showDeleteModal(form, message) {
                formToSubmit = form;
                messageEl.textContent = message;
                modal.style.display = 'flex';
            }

            // Hide modal
            function hideDeleteModal() {
                modal.style.display = 'none';
                formToSubmit = null;
            }

            // Confirm button
            confirmBtn.addEventListener('click', () => {
                if (formToSubmit) {
                    // Save form reference before hideModal clears it
                    const form = formToSubmit;
                    hideDeleteModal();
                    // Remove class to prevent recursion
                    form.classList.remove('delete-confirm');
                    // Click the submit button
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.click();
                    } else {
                        form.submit();
                    }
                }
            });

            // Cancel button
            cancelBtn.addEventListener('click', hideDeleteModal);

            // Click outside to close
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    hideDeleteModal();
                }
            });

            // Intercept form submissions
            document.addEventListener('submit', (e) => {
                const form = e.target;
                if (form.classList.contains('delete-confirm')) {
                    e.preventDefault();
                    const message = form.dataset.confirmMessage || 'Are you sure?';
                    showDeleteModal(form, message);
                }
            });
        </script>
        
        <!-- Theme Toggle Script -->
        <script>
            // Theme Toggle
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                themeIcon.textContent = '●';
            }

            // Toggle on click
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-theme');
                const isDark = document.body.classList.contains('dark-theme');
                themeIcon.textContent = isDark ? '●' : '○';
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        </script>
        
        <!-- Flatpickr Date Picker -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            // Initialize Flatpickr on date inputs
            document.addEventListener('DOMContentLoaded', function() {
                const dateInputs = document.querySelectorAll('input[name="date_from"], input[name="date_to"]');
                
                dateInputs.forEach(input => {
                    flatpickr(input, {
                        dateFormat: 'Y-m-d',
                        allowInput: true,
                        locale: 'en', // Force English
                        theme: document.body.classList.contains('dark-theme') ? 'dark' : 'light'
                    });
                });
            });
        </script>
        
        @stack('scripts')
    </body>
</html>
