<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SafariType extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'thumbnail',
        'button_text'
    ];

    protected $appends = [
        'thumbnail_url'
    ];

    public function getThumbnailUrlAttribute()
    {
        return ("{$this->thumbnail}") ? url()->to('/' . "{$this->thumbnail}") : asset('admin_assets/demo.png');
    }

    public function create_safari_types(): HasMany
    {
        return $this->hasMany(CreateSafariType::class);
    }
}
