<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'safari_id',
        'available_start_date',
        'available_end_date',
        'blocked_start_date',
        'blocked_end_date',
    ];

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class);
    }
}
