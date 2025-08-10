<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IveriResult extends Model
{
    protected $table = 'iveri_result';

    protected $primaryKey = 'id';

    public $timestamps = false; // No created_at/updated_at columns

    protected $fillable = [
        'amount',
        'application_id',
        'currency',
        'electronic_commerce_indicator',
        'expiry_date',
        'jwt',
        'merchant_data',
        'merchant_reference',
        'pan',
        'result_code',
        'result_description',
        'three_d_secure_request_id',
        'three_d_secure_veres_enrolled',
        'card_security_code',
        'result_response',
        'three_d_secure_ds_trans_id',
        'card_holder_authentication_data',
        'card_holder_authentication_id',
        'encrypted_pan',
        'result_response_auth',
    ];
}
