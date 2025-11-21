<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccomodationToStay extends Model
{

    protected $fillable = [
        'title',
        'description',
        'types',
        'image',
        'status'
    ];
    protected $casts = [
        'types' => 'array',
        'status' => 'string'
    ];
    protected $appends = [
        'full_photo_url'
    ];

    public function getFullPhotoUrlAttribute()
    {
        return ("{$this->image}") ? url()->to('/' . "{$this->image}") : '';
    }
    
    public function safari(): HasMany
    {
        return $this->hasMany(Safari::class, 'accomodation_id');
    }
}
