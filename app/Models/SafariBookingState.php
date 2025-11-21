<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariBookingState extends Model
{
     protected $fillable = [
        'booking_id',
        'traveler_id',
        'state',
    ];
}
