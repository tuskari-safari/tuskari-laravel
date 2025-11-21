<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariReview extends Model
{
    protected $fillable = [
        'safari_id',
        'user_id',
        'heading',
        'details',
        'rating',
        'status',
        'username',
        'user_image',
    ];

    protected $appends = [
        'user_full_image_url',
    ];

    public function getUserFullImageUrlAttribute()
    {
        return asset($this->user_image);
    }
    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class, 'safari_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
