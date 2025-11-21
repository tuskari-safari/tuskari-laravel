<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariNationalParkReserve extends Model
{
    protected $fillable = [
        'safari_id',
        'national_park_reserve_id',
    ];

    public function nationalParkReserve(): BelongsTo
    {
        return $this->belongsTo(NationalParkAndReserves::class, 'national_park_reserve_id');
    }

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class, 'safari_id');
    }
}
