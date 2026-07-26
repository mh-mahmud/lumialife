<?php

use App\Http\Controllers\SteadfastWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/steadfast/webhook', [SteadfastWebhookController::class, 'handle'])
    ->middleware('throttle:120,1')
    ->name('steadfast.webhook');
