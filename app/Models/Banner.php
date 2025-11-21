<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'page_name',
        'title',
        'short_description',
        'thumbnail',
    ];
    
    protected $appends = ['full_image'];

    public function getFullImageAttribute(): string
    {
        return $this->thumbnail ? url($this->thumbnail) : null;
    }

}
