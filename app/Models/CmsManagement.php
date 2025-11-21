<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CmsManagement extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'tag',
        'slug',
        'title',
        'subtitle',
        'thumbnail',
        'features',
        'short_description',
        'text_content',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('tag')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }


    protected $appends = [
        'thumbnail_full_url',
    ];

    public function getThumbnailFullUrlAttribute()
    {
        return $this->thumbnail ? asset($this->thumbnail) : null;
    }
}
