<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZeePayRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
class WebhookController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function sendWebhookVeri(Request $request)
    {
        $payload = $request->getContent();

        if (empty($payload)) {
            return response()->json([
                'message' => 'Failed, Please contact SmatPay',
                'status' => 'Failed'
            ], Response::HTTP_BAD_REQUEST);
        }

        Log::info("Received XML Payload: {$payload}");

        $zeePayRequest = new ZeePayRequest();
        $zeePayRequest->body = $payload;

        $redirectUrl = $this->transactionService->saveIveriRequest($zeePayRequest);


        return $redirectUrl;
    }

    public function handlePaymentWebhook(Request $request)
    {
        $token = $request->header('X-Webhook-Secret');

        if ($token !== env('PAYMENT_WEBHOOK_SECRET')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Payment gateway sends JSON — capture it
        $payload = $request->all();

        return $this->transactionService->createPayment($payload);
    }
    public function testRedirect(Request $request)
    {
        // Example: Redirect to Google for testing
        $redirectUrl = 'https://www.google.com';

        return redirect()->away($redirectUrl);
    }
}
