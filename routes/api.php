<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
return response()->json(['status' => 'API is working']);
});

Route::post('/ticket-call-back', [WebhookController::class, 'sendWebhookVeri']);
Route::post('/ticket/payment', [WebHookController::class, 'handlePaymentWebhook']);
Route::get('/events/{identifier}', [EventController::class, 'show']);

