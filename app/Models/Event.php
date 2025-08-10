<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Specify the table if not the plural of model name (optional here)
    protected $table = 'events';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'date',
        'time',
        'venue',
        'price',
        'special_code',
        'event_image_url'
    ];

    // If you want to cast date fields automatically
    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];
}
