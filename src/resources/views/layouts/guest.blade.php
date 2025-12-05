<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'mCRM') }}</title>

        <!-- Loft Theme -->
        <link rel="stylesheet" href="{{ asset('css/loft-theme.css') }}">
        
        <style>
            /* Guest Layout Styles */
            body {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            
            .auth-container {
                background: var(--card-light);
                border-radius: 4px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                padding: 40px;
                width: 100%;
                max-width: 450px;
                position: relative;
            }
            
            .dark-theme .auth-container {
                background: var(--card-dark);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }
            
            .auth-container h1 {
                color: var(--text-light);
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 30px;
                text-align: center;
            }
            
            .dark-theme .auth-container h1 {
                color: var(--text-dark);
            }
        </style>
    </head>
    <body>
        <!-- Theme Toggle Button -->
        <button type="button" class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
            <span id="theme-icon">○</span>
        </button>

        <div class="auth-container">
            {{ $slot }}
        </div>

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
    </body>
</html>

