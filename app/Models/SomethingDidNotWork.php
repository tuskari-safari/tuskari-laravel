<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SomethingDidNotWork extends Model
{
    protected $fillable = [
        'user_id',
        'issue_description',
        'page_url',
        'device_info',
        'screenshot',
        'resolve'
    ];

    protected $appends = [
        'screenshot_full_url'
    ];
    public function getScreenshotFullUrlAttribute()
    {
        if ($this->screenshot) {
            $path = asset($this->screenshot);
        }
        return $path;
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
