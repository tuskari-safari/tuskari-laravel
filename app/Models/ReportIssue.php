<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportIssue extends Model
{
    protected $fillable = ['user_id', 'safari_id', 'description', 'issue_type', 'resolve'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class);
    }
}
