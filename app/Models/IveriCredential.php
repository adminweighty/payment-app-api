<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IveriCredential extends Model
{
    protected $table = 'iveri_credentials';

    protected $primaryKey = 'id';

    public $timestamps = false; // Because you use custom timestamp fields

    protected $fillable = [
        'iveri_application_id',
        'iveri_certificate_id',
        'iveri_created_at',
        'iveri_merchant_profile_id',
        'iveri_updated_at',
        'iveri_base_url',
        'iveri_credential_type_name',
    ];

    protected $casts = [
        'iveri_created_at' => 'datetime',
        'iveri_updated_at' => 'datetime',
    ];
}
