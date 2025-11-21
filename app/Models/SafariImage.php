<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariImage extends Model
{
    use HasFactory;

    protected $fillable = ['safari_id', 'images', 'thumbnail'];
    protected $appends = [
        'full_image_url',
        'full_thumbnail_url'
    ];
    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class);
    }

    public function getFullImageUrlAttribute()
    {
        return ($this->images) ? url()->to('/' . "{$this->images}") : 'frontend_assets/images/safa-dtl-mini-1.jpg';
    }
    public function getFullThumbnailUrlAttribute()
    {
        return ($this->thumbnail) ? url()->to('/' . "{$this->thumbnail}") : '';
    }
}
