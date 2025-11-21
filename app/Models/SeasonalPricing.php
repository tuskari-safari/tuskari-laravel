<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonalPricing extends Model
{
    protected $fillable = [
        'safari_id',
        'season',
        'available_start_date',
        'available_end_date',
        'blocked_start_date',
        'blocked_end_date',
        'adult_price',
        'child_price',
    ];

    public function safari()
    {
        return $this->belongsTo(Safari::class);
    }
}
