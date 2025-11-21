<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NationalParkAndReserves extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'short_description', 'title', 'location', 'lat', 'long', 'country_id', 'subtitle', 'description', 'category', 'best_visit_time', 'wild_lives_ids', 'accomodation_ids', 'traveler_tips', 'unique_experience', 'impact', 'status', 'banner_image', 'middle_banner_image', 'thumbnail', 'impact_image', 'is_most_popular', 'is_hidden_gem', 'wildlife_highlights_title', 'wildlife_highlights_desc', 'faqs', 'title_2', 'is_hidden'];

    protected $appends = [
        'full_banner_url',
        'full_middle_banner_image',
        'full_thumbnail_url',
        'full_impact_image_url'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function getFullBannerUrlAttribute()
    {
        return ($this->banner_image) ? url()->to('/' . "{$this->banner_image}") : '';
    }
    public function getFullMiddleBannerImageAttribute()
    {
        return ($this->middle_banner_image) ? url()->to('/' . "{$this->middle_banner_image}") : '';
    }

    public function getFullThumbnailUrlAttribute()
    {
        return ($this->thumbnail) ? url()->to('/' . "{$this->thumbnail}") : '';
    }
    public function getFullImpactImageUrlAttribute()
    {
        return ($this->impact_image) ? url()->to('/' . "{$this->impact_image}") : '';
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(CountryGuide::class, 'country_id');
    }

    public function safari(): HasMany
    {
        return $this->hasMany(Safari::class, 'national_park_id');
    }

    public function safari_parks(): HasMany
    {
        return $this->hasMany(SafariNationalParkReserve::class, 'national_park_reserve_id');
    }

}
