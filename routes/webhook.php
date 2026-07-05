<?php

use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle'])
    ->name('webhook.stripe');
