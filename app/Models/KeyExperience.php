<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeyExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'thumbnail',
        'subtitle',
        'image'
    ];
    protected $appends = [
        'thumbnail_url',
        'image_url'
    ];

    public function getThumbnailUrlAttribute()
    {
        return ("{$this->thumbnail}") ? url()->to('/' . "{$this->thumbnail}") : asset('admin_assets/demo.png');
    }

    public function getImageUrlAttribute()
    {
        return ("{$this->image}") ? url()->to('/' . "{$this->image}") : asset('admin_assets/demo.png');
    }

    public function safari_key_experience(): HasMany
    {
        return $this->hasMany(SafariKeyExperience::class);
    }
}
