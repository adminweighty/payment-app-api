<?php

namespace App\Services;

use App\DTO\PaymentRequestDTO;
use App\Models\Event;
use App\Models\IveriResult;
use App\Models\Payment;
use App\Models\IveriCredential;
use App\Models\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TransactionService
{
    public function saveIveriRequest($zeePayRequest)
    {
        $params = $this->decodeQueryString($zeePayRequest->body ?? '');
        $redirectUrl = '';
        $encryptedPan = '';
        $authenticationFailed = '';

        $ivObjectResult = IveriResult::where('merchant_reference', $params['MerchantReference'] ?? null)->get();

        if ($ivObjectResult->isNotEmpty()) {
            $encryptedPan = Crypt::decryptString($ivObjectResult[0]->enrypted_pan);

            $ivObject = $ivObjectResult[0];
            $ivObject->three_d_secure_request_id = $params['ThreeDSecure_RequestID'] ?? null;
            $ivObject->electronic_commerce_indicator = $params['ElectronicCommerceIndicator'] ?? null;
            $ivObject->three_d_secure_veres_enrolled = $params['ThreeDSecure_VEResEnrolled'] ?? null;
            $ivObject->result_code = $params['ResultCode'] ?? null;
            $ivObject->result_description = $params['ResultDescription'] ?? null;
            $ivObject->application_id = $params['ApplicationID'] ?? null;
            $ivObject->merchant_reference = $params['MerchantReference'] ?? null;
            $ivObject->amount = isset($params['Amount']) ? (float)$params['Amount'] : null;
            $ivObject->currency = $params['Currency'] ?? null;
            $ivObject->jwt = $params['JWT'] ?? null;
            $ivObject->pan = $params['PAN'] ?? null;
            $ivObject->expiry_date = $params['ExpiryDate'] ?? null;
            $ivObject->merchant_data = $params['MerchantData'] ?? null;
            $ivObject->card_security_code = $params['CardSecurityCode'] ?? null;
            $ivObject->result_response = $zeePayRequest->body ?? '';
            $ivObject->three_d_secure_ds_trans_id = $params['ThreeDSecure_DSTransID'] ?? null;
            $ivObject->card_holder_authentication_id = $params['CardHolderAuthenticationID'] ?? null;
            $ivObject->card_holder_authentication_data = $params['CardHolderAuthenticationData'] ?? null;
            $ivObject->enrypted_pan = '';

            $ivObject->save();

            $transactions = Payment::where('merchant_reference', $ivObject->merchant_reference)
                ->whereNull('wallet_name')
                ->first();

            if (empty($ivObject->card_holder_authentication_data)) {
                $authenticationFailed = 'Authentication could not be completed';
            }

            if ($ivObject->result_code == '0' && $transactions && empty($authenticationFailed)) {
                $iveriCred = IveriCredential::first();

                $resultAmount = (int)$ivObject->amount;
                $requestBody = [
                    'Version' => '2.0',
                    'CertificateID' => '{' . $iveriCred->iveri_certificate_id . '}',
                    'ProductType' => 'Enterprise',
                    'ProductVersion' => 'WebAPI',
                    'Direction' => 'Request',
                    'Transaction' => [
                        'ApplicationID' => $ivObject->application_id,
                        'Command' => 'Debit',
                        'Mode' => 'Test',
                        'Amount' => $resultAmount,
                        'ExpiryDate' => $ivObject->expiry_date,
                        'MerchantReference' => $ivObject->merchant_reference,
                        'Currency' => $ivObject->currency,
                        'PAN' => $encryptedPan,
                        'ThreeDSecure_ProtocolVersion' => '2.2.0',
                        'CardHolderAuthenticationID' => $ivObject->card_holder_authentication_id,
                        'CardHolderAuthenticationData' => $ivObject->card_holder_authentication_data,
                        'ElectronicCommerceIndicator' => $ivObject->electronic_commerce_indicator,
                        'ThreeDSecure_DSTransID' => $ivObject->three_d_secure_ds_trans_id,
                        'ThreeDSecure_AuthenticationType' => '01',
                        'ThreeDSecure_VEResEnrolled' => $ivObject->three_d_secure_veres_enrolled,
                    ]
                ];

                $response = Http::post($iveriCred->iveri_base_url, $requestBody);
                $bodyResponse = $response->body();
                $ivObject->result_response_auth = $bodyResponse;
                $ivObject->save();

                $json = json_decode($bodyResponse, true);

                $result = $json['Transaction']['Result']['Code'] ?? null;
                $resultCode = $json['Transaction']['Result']['Description'] ?? null;
                $resultDescription = $json['Transaction']['Result']['AcquirerDescription'] ?? 'Unknown';

                if ($transactions && $result == '0') {
                    $transactions->status = true;
                    $transactions->payer_paid_status = 1;
                    $transactions->payment_status = 'Paid';
                    $transactions->payment_status_one = $ivObject->result_code;
                    $transactions->payment_status_two = $ivObject->result_description;
                    $transactions->save();
                } else {
                    $transactions->status = true;
                    $transactions->transaction_paid = -1;
                    $transactions->payment_status = 'Failed';
                    $transactions->payment_status_one = $ivObject->result_code;
                    $transactions->payment_status_two = $resultDescription;
                    $transactions->save();
                }
            } else {
                $transactions->status = true;
                $transactions->transaction_paid = -1;
                $transactions->payment_status = 'Failed';
                $transactions->payment_status_one = $authenticationFailed ?: $ivObject->result_code;
                $transactions->payment_status_two = $authenticationFailed ?: $ivObject->result_description;
                $transactions->save();
            }

            if ($transactions->status) {
                $event = Event::findOrFail($transactions->payment_special_code);
                // Generate tickets
                $tickets = [];
                for ($i = 0; $i < $transactions->payer_number_of_tickets; $i++) {
                    $ticketNumber = $event->special_code . '-' . $transactions->id . '-' . strtoupper(Str::random(6));
                    $ticket = Ticket::create([
                        'payment_id' => $transactions->id,
                        'ticket_number' => $ticketNumber,
                    ]);
                    $tickets[] = $ticketNumber;
                }
// Send confirmation email
                Mail::to($transactions->payer_email)
                    ->send(new \App\Mail\TicketConfirmation($tickets, $transactions, $event));
                $redirectUrl = config('app.payment_endpoint') . 'success?ticketReferences=' . $transactions->merchant_reference;
            } else {
                $redirectUrl = config('app.payment_endpoint') . '/failed?ticketReferences=' . $transactions->merchant_reference;
            }
        }

        return $redirectUrl;
    }

    public function createPayment(array $data)
    {
        $dto = new PaymentRequestDTO($data);
        $guid = (string)Str::uuid();
        $payment = Payment::create([
            'payment_method_name' => $dto->methodName,
            'payment_special_code' => $dto->eventId,
            'payment_amount' => $dto->amount,
            'payer_number_of_tickets' => $dto->quantity,
            'payer_names' => $dto->names,
            'payer_email' => $dto->email,
            'payer_mobile' => $dto->mobileNumber,
            'status' => false,
            'payment_currency' => 'USD',
            'payer_paid_status' => 0,
            'merchant_reference' => $guid ?? null,
            'payment_status' => '',
            'payment_status_one' => '',
            'payment_status_two' => ''
        ]);

        //create iveri record
        $ivObjectResult = IveriResult::where('merchant_reference',$payment->merchant_reference)->first();

        if ($ivObjectResult) {
            // Update existing record
            $ivObjectResult->encrypted_pan = $dto->cardNumber;
            $ivObjectResult->save();
        } else {
            // Create new record
            IveriResult::create([
                'encrypted_pan'     => $dto->cardNumber,
                'merchant_reference' => $payment->merchant_reference
            ]);
        }
        $iveriCred = IveriCredential::first();

        // Send back the response directly from the service
        return response()->json([
            'status' => 'success',
            'applicationID' => $iveriCred->iveri_application_id,
            'merchantReference' => $payment->merchant_reference,
        ], 200);
    }

    function decodeQueryString(string $query): array
    {
        $pairs = explode('&', $query);
        $result = [];

        foreach ($pairs as $pair) {
            $parts = explode('=', $pair, 2);

            $key = urldecode($parts[0] ?? '');
            $value = urldecode($parts[1] ?? '');

            $result[$key] = $value;
        }

        return $result;
    }

}
