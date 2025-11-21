<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_deposit_percentage',
        'auto_balance_duration_days',
        'platform_fee',
        'stripe_processing_fee',
    ];

    protected $casts = [
        'first_deposit_percentage' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'stripe_processing_fee' => 'decimal:2',
    ];
}