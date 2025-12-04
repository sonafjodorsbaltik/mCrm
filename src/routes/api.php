<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Tickets\TicketController;
use App\Http\Controllers\Api\Tickets\TicketStatisticsController;

Route::prefix('v1')->group(function () {

    Route::post('/tickets', [TicketController::class, 'store'])
        ->name('api.tickets.store');
});


Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {


    Route::get('/tickets/statistics', [TicketStatisticsController::class, 'index'])
        ->middleware('role:admin|manager')
        ->name('api.tickets.statistics');
});
