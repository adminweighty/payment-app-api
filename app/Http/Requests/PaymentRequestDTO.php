<?php

namespace App\Http\Requests;

class PaymentRequestDTO
{
    public ?string $cardNumber;
    public ?string $eventId;
    public ?float $amount;
    public ?int $quantity;
    public ?string $names;
    public ?string $email;
    public ?string $mobileNumber;

    public ?string $methodName;

    public function __construct(array $data)
    {
        $this->cardNumber = $data['cardNumber'] ?? null;
        $this->eventId = $data['eventId'] ?? null;
        $this->amount = isset($data['amount']) ? (float) $data['amount'] : null;
        $this->quantity = isset($data['quantity']) ? (int) $data['quantity'] : null;
        $this->names = $data['names'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->methodName = $data['methodName'] ?? null;
        $this->mobileNumber = $data['mobileNumber'] ?? null;
    }
}
