<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CountryGuide extends Model
{
    protected $casts = [
        'status' => 'string',
    ];

    protected $fillable = [
        'region',
        'name',
        'slug',
        'subtitle',
        'banner_image',
        'content_details',
        'thumbnail',
        'unique_experience_title',
        'unique_experience_desc',
        'unique_experience',
        'bottom_banner',
        'middle_sec_title',
        'middle_sec_subtitle',
        'faqs',
        'status',
    ];
    protected $appends = [
        'thumbnail_url',
        'banner_image_url'
    ];

    public function getThumbnailUrlAttribute()
    {
        return ($this->thumbnail) ? url()->to('/' . $this->thumbnail) : 'frontend_assets/images/safari-list-1.png';
    }
    public function getBannerImageUrlAttribute()
    {
        return ($this->banner_image) ? url()->to('/' . $this->banner_image) : 'frontend_assets/images/safari-list-1.png';
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region', 'id');
    }

    public function parks(): HasMany
    {
        return $this->hasMany(NationalParkAndReserves::class, 'country_id', 'id');
    }

    public function safaris(): HasMany
    {
        return $this->hasMany(Safari::class, 'country_id', 'id');
    }
}
