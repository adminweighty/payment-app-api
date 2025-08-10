<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method_name',
        'payment_amount',
        'payment_description',
        'payer_paid_status',
        'status',
        'merchant_reference',
        'payment_status',
        'payment_status_one',
        'payment_status_two',
        'payment_special_code',
        'payer_names',
        'payer_email',
        'payer_mobile',
        'payer_number_of_tickets',
        'payment_currency',
    ];
}
