<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariJourneyImages extends Model
{
    use HasFactory;
    protected $fillable = ['safari_journey_id', 'image'];

    protected $appends = [
        'full_photo_url'
    ];
    public function safari_journey(): BelongsTo
    {
        return $this->belongsTo(SafariJourney::class, 'safari_journey_id');
    }

    public function getFullPhotoUrlAttribute()
    {
        return ("{$this->image}") ? url()->to('/' . "{$this->image}") : '';
    }
}
