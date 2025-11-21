<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariAvailableTag extends Model
{
    protected $fillable = [
        'tag_id',
        'safari_id',
    ];

    public function tags()
    {
        return $this->belongsTo(AvailableTag::class);
    }

    public function safari()
    {
        return $this->belongsTo(Safari::class);
    }
}
