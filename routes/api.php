<?php

use App\Http\Controllers\LeadWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/leads', LeadWebhookController::class)
    ->middleware('throttle:60,1')
    ->name('webhooks.leads');
