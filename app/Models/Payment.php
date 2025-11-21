<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['booking_id', 'traveler_id', 'transaction_id', 'payment_date', 'amount', 'payment_intent_id', 'payment_method', 'payment_status', 'payment_details', 'refund_details'];

    public function safariBooking(): BelongsTo
    {
        return $this->belongsTo(SafariBooking::class, 'booking_id');
    }

    public function traveler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traveler_id');
    }
}
