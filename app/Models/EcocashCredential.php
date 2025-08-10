<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcocashCredential extends Model
{
    protected $table = 'ecocash_credentials';

    protected $primaryKey = 'id';

    public $timestamps = false; // Using custom timestamp fields

    protected $fillable = [
        'ecocash_base_url',
        'ecocash_api_key',
        'ecocash_payment_endpoint',
        'ecocash_transaction_status_endpoint',
        'ecocash_created_at',
        'ecocash_reversal_endpoint',
        'ecocash_transaction_merchant_code',
        'ecocash_transaction_merchant_number',
        'ecocash_transaction_merchant_pin',
        'ecocash_transaction_password',
        'ecocash_transaction_username',
        'ecocash_updated_at',
    ];

    protected $casts = [
        'ecocash_created_at' => 'datetime',
        'ecocash_updated_at' => 'datetime',
    ];
}
