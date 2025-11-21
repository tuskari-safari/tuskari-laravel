<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\{HasSlug, SlugOptions};

class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['title', 'slug', 'status','order'];
    protected $casts = [
        'status' => 'string',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }
}
