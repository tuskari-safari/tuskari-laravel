<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class BottomBanner extends Model
{
    use HasSlug;
    protected $fillable = [
        'slug',
        'page_name',
        'title',
        'subtitle',
        'thumbnail',
    ];
    protected $appends = ['full_image'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('page_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getFullImageAttribute(): string
    {
        return ("{$this->thumbnail}") ? url()->to('/' . "{$this->thumbnail}") : '';
    }
}
