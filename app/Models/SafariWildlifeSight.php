<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariWildlifeSight extends Model
{
    protected $fillable = ['safari_id', 'animal_id', 'probability', 'note'];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(WildLife::class, 'animal_id', 'id');
    }

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class, 'safari_id', 'id');
    }
}
