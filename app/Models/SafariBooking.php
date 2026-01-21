<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SafariBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'safari_id',
        'traveler_id',
        'operator_id',
        'total_price',
        'check_in_date',
        'check_out_date',
        'duration',
        'no_of_adults',
        'no_of_children',
        'status',
        'payment_status',
        'adult_price',
        'children_price',
        'completion_date',
        'platform_fee',
        'stripe_fee',
        'additional_fee',
        'pay_to_operator',
        'admin_commission',
        'deposit_amount',
        'next_deposit_amount',
        'next_deposit_date',
        'payment_method_id',
        'payment_type',
        'is_full_paid',
        'cancel_reason',
        'refund_amount',
        'cancelled_at',
        'is_enquiry',
        'chat_room_id',
        'enquiry_status',
        'quoted_total_price',
        'quoted_net_price',
        'quoted_at',
        'traveler_notes',
    ];

    protected $casts = [
        'is_enquiry' => 'boolean',
        'quoted_at' => 'datetime',
        'quoted_total_price' => 'decimal:2',
        'quoted_net_price' => 'decimal:2',
    ];

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class, 'safari_id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    public function traveler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traveler_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function scopeEnquiries($query)
    {
        return $query->where('is_enquiry', true);
    }

    public function scopeRegularBookings($query)
    {
        return $query->where('is_enquiry', false);
    }

    public function scopePendingEnquiries($query)
    {
        return $query->enquiries()->where('enquiry_status', 'pending');
    }

    public function scopeQuotedEnquiries($query)
    {
        return $query->enquiries()->where('enquiry_status', 'quoted');
    }
}
