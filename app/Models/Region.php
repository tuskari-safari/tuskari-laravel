<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image', 'status'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return ($this->image) ? url()->to('/' . $this->image) : null;
    }

    public function countryGuides(): HasMany
    {
        return $this->hasMany(CountryGuide::class, 'region', 'id');
    }
}
