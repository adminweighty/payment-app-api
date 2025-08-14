<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
return response()->json(['status' => 'API is working']);
});

use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from Laravel!', function ($message) {
            $message->to('udeanmbano@gmail.com')  // replace with your email
            ->subject('Test Email');
        });

        return response()->json(['status' => 'Email sent successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Failed to send email',
            'error' => $e->getMessage()
        ]);
    }
});

Route::post('/ticket-call-back', [WebhookController::class, 'sendWebhookVeri']);
Route::post('/ticket/payment', [WebHookController::class, 'handlePaymentWebhook']);
Route::get('/events/{identifier}', [EventController::class, 'show']);

Route::get('/test-redirect', [WebHookController::class, 'testRedirect']);
