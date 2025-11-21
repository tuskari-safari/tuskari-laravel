<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariKeyExperience extends Model
{
    use HasFactory;
    protected $fillable = ['safari_id', 'key_experience_id'];

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class, 'safari_id');
    }

    public function key_experience(): BelongsTo
    {
        return $this->belongsTo(KeyExperience::class, 'key_experience_id');
    }
}
