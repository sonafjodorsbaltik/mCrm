<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us</title>
    
    <!-- Shared Loft Theme -->
    <link rel="stylesheet" href="{{ asset('css/loft-theme.css') }}">
    
    <style>
        /* Widget-specific styles */
        body {
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .widget-container {
            background: var(--card-light);
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            position: relative;
            transition: all 0.3s ease;
        }

        .dark-theme .widget-container {
            background: var(--card-dark);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .widget-header {
            margin-bottom: 30px;
            padding-right: 60px;
        }

        .widget-header h1 {
            color: var(--text-light);
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .dark-theme .widget-header h1 {
            color: var(--text-dark);
        }

        .widget-header p {
            color: var(--text-secondary-light);
            font-size: 14px;
            line-height: 1.5;
        }

        .dark-theme .widget-header p {
            color: var(--text-secondary-dark);
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .widget-container {
                padding: 24px;
            }

            .widget-header {
                padding-right: 50px;
            }

            .widget-header h1 {
                font-size: 22px;
            }

            .theme-toggle {
                width: 28px;
                height: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="widget-container">
        <!-- Theme Toggle Button -->
        <button type="button" class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
            <span id="theme-icon">○</span>
        </button>

        <div class="widget-header">
            <h1>Get in Touch</h1>
            <p>Fill out the form and we'll get back to you shortly</p>
        </div>

        <div id="alert" class="alert"></div>

        <form id="feedback-form" enctype="multipart/form-data" novalidate>
            @csrf
            
            <div class="form-group" data-field="name">
                <label for="name">Your Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" required>
                <span class="error-message"></span>
            </div>

            <div class="form-group" data-field="email">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required>
                <span class="error-message"></span>
            </div>

            <div class="form-group" data-field="phone">
                <label for="phone">Phone <span class="required">*</span></label>
                <input type="tel" id="phone" name="phone" placeholder="+380501234567" required>
                <small>Format: +380501234567 (E.164)</small>
                <span class="error-message"></span>
            </div>

            <div class="form-group" data-field="subject">
                <label for="subject">Subject <span class="required">*</span></label>
                <input type="text" id="subject" name="subject" required>
                <span class="error-message"></span>
            </div>

            <div class="form-group" data-field="content">
                <label for="content">Message <span class="required">*</span></label>
                <textarea id="content" name="content" required></textarea>
                <span class="error-message"></span>
            </div>

            <div class="form-group" data-field="files">
                <label for="files">Attach Files</label>
                <div class="file-input-wrapper">
                    <label for="files" class="file-input-btn">Choose Files</label>
                    <input type="file" id="files" name="files[]" multiple>
                    <div class="file-names" id="file-names">No files selected</div>
                </div>
                <small>You can attach multiple files</small>
                <span class="error-message"></span>
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Submit Request</span>
            </button>
        </form>
    </div>

    <script>
        const form = document.getElementById('feedback-form');
        const alertBox = document.getElementById('alert');
        const submitBtn = form.querySelector('.submit-btn');
        const btnText = submitBtn.querySelector('.btn-text');
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        // Получаем CSRF токен из meta тега
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Theme Toggle
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
            themeIcon.textContent = '●';
        }

        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            themeIcon.textContent = isDark ? '●' : '○';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        // File input handler
        const fileInput = document.getElementById('files');
        const fileNamesDiv = document.getElementById('file-names');

        fileInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (files.length === 0) {
                fileNamesDiv.textContent = 'No files selected';
            } else if (files.length === 1) {
                fileNamesDiv.textContent = files[0].name;
            } else {
                fileNamesDiv.textContent = `${files.length} files selected`;
            }
        });

        // Form Submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Очищаем предыдущие ошибки
            clearErrors();
            hideAlert();

            // Disable button
            submitBtn.disabled = true;
            btnText.textContent = 'Sending...';

            // Собираем данные формы
            const formData = new FormData(form);

            try {
                const response = await fetch('/api/v1/tickets', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Success
                    showAlert('success', 'Request submitted successfully! We will contact you shortly.');
                    form.reset();
                    
                    // Auto-hide message after 5 seconds
                    setTimeout(() => hideAlert(), 5000);
                } else if (response.status === 422) {
                    // Validation errors
                    showValidationErrors(data.errors);
                    showAlert('error', 'Please fix the errors in the form.');
                } else if (response.status === 429) {
                    // Rate limit
                    showAlert('error', data.message || 'You have already submitted a request recently. Please try later.');
                } else {
                    // Other errors
                    showAlert('error', data.message || 'An error occurred while submitting. Please try later.');
                }
            } catch (error) {
                // Network error
                showAlert('error', 'Connection error. Please check your internet and try again.');
                console.error('Network error:', error);
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                btnText.textContent = 'Submit Request';
            }
        });

        function showAlert(type, message) {
            alertBox.className = `alert alert-${type} show`;
            alertBox.textContent = message;
        }

        function hideAlert() {
            alertBox.className = 'alert';
        }

        function showValidationErrors(errors) {
            for (const [field, messages] of Object.entries(errors)) {
                const formGroup = document.querySelector(`[data-field="${field}"]`);
                if (formGroup) {
                    formGroup.classList.add('has-error');
                    const errorSpan = formGroup.querySelector('.error-message');
                    if (errorSpan) {
                        errorSpan.textContent = messages[0]; // Show first error
                    }
                }
            }
        }

        function clearErrors() {
            document.querySelectorAll('.form-group.has-error').forEach(group => {
                group.classList.remove('has-error');
                const errorSpan = group.querySelector('.error-message');
                if (errorSpan) {
                    errorSpan.textContent = '';
                }
            });
        }
    </script>
</body>
</html>
