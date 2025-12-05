<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Widget\FeedbackController;
use Illuminate\Support\Facades\Route;

// Widget (public)
Route::get('/widget', [FeedbackController::class, 'show'])->name('widget.feedback');

// Admin Panel (protected: auth + role:admin|manager)
Route::middleware(['auth', 'role:admin|manager'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
});

// Redirect root to admin dashboard (for authenticated users)
Route::redirect('/', '/admin/dashboard')->middleware('auth');

require __DIR__.'/auth.php';

