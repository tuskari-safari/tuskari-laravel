<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SafariJourney extends Model
{
    use HasFactory;
    protected $fillable = ['safari_id', 'day_number', 'heading', 'subtext', 'included', 'wildlife_location', 'wildlife_highlights', 'status', 'accommodation', 'meal', 'today_highlights','no_accommodation_included'];

    public function safari(): BelongsTo
    {
        return $this->belongsTo(Safari::class, 'safari_id');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'safari_journey_activities', 'safari_journey_id', 'activity_id');
    }

    public function journey_images(): HasMany
    {
        return $this->hasMany(SafariJourneyImages::class, 'safari_journey_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(NationalParkAndReserves::class, 'wildlife_location');
    }
}
