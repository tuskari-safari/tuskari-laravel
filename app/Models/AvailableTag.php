<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableTag extends Model
{
     protected $fillable = [
        'name',
        'show_in_frontend'
    ];

    protected $casts = [
        'show_in_frontend' => 'boolean'
    ];

    public function safariTags()
    {
        return $this->hasMany(SafariAvailableTag::class);
    }

    public function safaris()
    {
        return $this->belongsToMany(Safari::class, 'safari_available_tags', 'tag_id', 'safari_id');
    }
}
