<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\{HasSlug, SlugOptions};


class Safari extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'duration',
        'location',
        'country_id',
        'price_for_adult',
        'price_for_child',
        'price_for_pet',
        'lat',
        'long',
        'best_visit_time',
        'health_and_safety',
        'travel_access_info',
        'pace_activity_level',
        'vehicle_types',
        'travel_season',
        'status',
        'is_approved',
        'accomodation_id',
        'safari_type_id',
        'thumbnail',
        'destination',
        'environment',
        'activity_level',
        'no_of_adult',
        'no_of_child',
        'availability_tag',
        'banner_image',
        'impact',
        'today_highlights',
        'region_id',
        'national_park_id',
        'imapact_image',
        'day_duration',
        'night_duration',
        'safari_included',
        'safari_not_included',
        'add_ons',
        'number_of_groups',
        'group_type',
        'low_season_start_date',
        'low_season_end_date',
        'high_season_start_date',
        'high_season_end_date',
        'low_season_adult_price',
        'high_season_adult_price',
        'low_season_child_price',
        'high_season_child_price',
        'available_start_date',
        'available_end_date',
        'blocked_start_date',
        'blocked_end_date',
        'per_date_group_limit',
        'total_slots',
        'is_last_min_offer',
        'is_curated',
        'is_featured',
        'short_description',
        'is_draft',
        'step_saved_status',
        'booking_mode'
    ];

    protected $appends = [
        'full_thumbnail_url',
        'full_banner_image_url',
        'is_favourite'
    ];
    protected $casts = [
        'environment' => 'array',
        'step_saved_status' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function key_experiences(): BelongsToMany
    {
        return $this->belongsToMany(KeyExperience::class, 'safari_key_experiences', 'safari_id', 'key_experience_id');
    }

    public function accomodation(): BelongsTo
    {
        return $this->belongsTo(AccomodationToStay::class, 'accomodation_id');
    }

    public function safariOperator(): BelongsTo
    {
        return $this->belongsTo(SafariOperator::class, 'safari_operator_id');
    }

    public function getFullThumbnailUrlAttribute()
    {
        return ($this->thumbnail) ? url()->to('/' . "{$this->thumbnail}") : '/frontend_assets/images/safari-list-1.png';
    }

    public function getFullBannerImageUrlAttribute()
    {
        return ($this->banner_image) ? url()->to('/' . "{$this->banner_image}") : '/frontend_assets/images/safari-dtl-banner.jpg';
    }

    public function getIsFavouriteAttribute()
    {
        $user = Auth::user();
        if ($user) {
            $getIsFav = FavouriteSafari::where('safari_id', $this->id)->where('user_id', $user->id)->first();
            return !empty($getIsFav) ? 1 : 0;
        } else {
            return 0;
        }
    }
    public function safariType(): BelongsTo
    {
        return $this->belongsTo(SafariType::class, 'safari_type_id');
    }
    public function create_safari_type(): HasMany
    {
        return $this->hasMany(CreateSafariType::class, 'safari_id');
    }

    public function safariReviews(): HasMany
    {
        return $this->hasMany(SafariReview::class, 'safari_id')->latest();
    }
    public function activity(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'safari_activities', 'safari_id', 'activity_id');
    }

    public function safariImages(): HasMany
    {
        return $this->hasMany(SafariImage::class, 'safari_id');
    }

    public function wild_lives(): HasMany
    {
        return $this->hasMany(SafariWildlifeSight::class, 'safari_id');
    }

    public function journeys(): HasMany
    {
        return $this->hasMany(SafariJourney::class, 'safari_id');
    }

    public function things_to_know(): HasMany
    {
        return $this->hasMany(SafariThingsToKnow::class, 'safari_id');
    }

    public function favourite(): HasMany
    {
        return $this->hasMany(FavouriteSafari::class, 'safari_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(CountryGuide::class, 'country_id');
    }

    public function group_pricing(): HasMany
    {
        return $this->hasMany(SafariGroupPricing::class, 'safari_id');
    }

    public function discount(): HasMany
    {
        return $this->hasMany(SafariDiscount::class, 'safari_id');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(SafariGallery::class, 'safari_id');
    }

    public function dateRange(): HasMany
    {
        return $this->hasMany(SafariDate::class, 'safari_id');
    }
    public function park(): BelongsTo
    {
        return $this->belongsTo(NationalParkAndReserves::class, 'national_park_id');
    }

    public function safari_parks(): hasMany
    {
        return $this->hasMany(SafariNationalParkReserve::class, 'safari_id');
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'safari_collections', 'safari_id', 'collection_id');
    }

       public function tags(): BelongsToMany
    {
        return $this->belongsToMany(AvailableTag::class, 'safari_available_tags', 'safari_id', 'tag_id');
    }
    
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function seasonal_pricings(){
        return $this->hasMany(SeasonalPricing::class, 'safari_id');
    }

    public function isEnquiryMode(): bool
    {
        return $this->booking_mode === 'enquiry';
    }
}
