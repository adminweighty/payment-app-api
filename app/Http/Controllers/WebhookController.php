<?php

namespace App\Http\Controllers;

use App\DTOs\ZeePayRequest;
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

        if (empty($redirectUrl)) {
            return response()->json([
                'message' => 'Failed to process request',
                'status'  => 'Failed'
            ], Response::HTTP_BAD_REQUEST);
        }

        return redirect()->away($redirectUrl);
    }
}
