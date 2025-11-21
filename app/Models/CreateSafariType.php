<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreateSafariType extends Model
{
    protected $fillable = [
        'safari_id',
        'safari_type_id',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(SafariType::class, 'safari_type_id');
    }

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class, 'safari_id');
    }
}
