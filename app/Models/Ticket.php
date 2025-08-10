<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'payment_id',
        'ticket_number',
    ];

    public function transaction()
    {
        return $this->belongsTo(Payment::class);
    }
}
