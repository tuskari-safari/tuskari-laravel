<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WildLife extends Model
{
    protected $fillable = [
        'name',
        'subtitle',
        'thumbnail',

    ];
    protected $appends = ['full_thumbnail_url'];

    public function getFullThumbnailUrlAttribute()
    {
        return ($this->thumbnail) ? url()->to('/' . "{$this->thumbnail}") : null;
    }

    public function safari_wildlife_sights()
    {
        return $this->hasMany(SafariWildlifeSight::class, 'animal_id');
    }
}
