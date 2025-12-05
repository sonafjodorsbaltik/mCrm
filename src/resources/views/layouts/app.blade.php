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
        
        <!-- Main Content -->
        <main class="admin-main">
            {{ $slot }}
        </main>
        
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
