<?php

use App\Http\Controllers\Widget\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/widget', [FeedbackController::class, 'show'])
    ->name('widget.feedback');
