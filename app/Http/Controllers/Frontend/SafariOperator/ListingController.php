<?php

namespace App\Http\Controllers\Frontend\SafariOperator;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\{Activity, CountryGuide, CreateSafariType, NationalParkAndReserves, Region, Safari, SafariActivity, SafariDate, SafariDiscount, SafariGallery, SafariGroupPricing, SafariJourney, SafariJourneyImages, SafariThingsToKnow, SafariType, SafariWildlifeSight, User, WildLife, SafariNationalParkReserve, SafariAvailableTag, AvailableTag, SeasonalPricing};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Inertia\Inertia;

class ListingController extends Controller
{
    public function safariManageListing(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $safaris = Safari::with('create_safari_type.type', 'country')
            ->addSelect(['total_price' => DB::table('seasonal_pricings')
                ->select('adult_price')
                ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                ->where('seasonal_pricings.season', 'LOW')
                ->orderBy('seasonal_pricings.id', 'asc')
                ->limit(1)])
            ->withAvg('safariReviews', 'rating')
            ->when($request->status === 'draft', function ($query) {
                $query->where('is_draft', 1);
            })
            ->where('user_id', Auth::id())
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Frontend/Auth/safari-manage-listing', [
            'safaris' => $safaris,
        ]);
    }

    public function createListing(Request $request)
    {
        if ($request->isMethod('post')) {

            $dateRange = $request->input('dateRange');

            if (is_string($dateRange)) {
                $dateRange = json_decode($dateRange, true);
            }

            $request->merge(['dateRange' => $dateRange]);

            $formStep = $request->input('formStep');

            if ($formStep > 0) {
                $this->validateSection($request, $formStep);
                $savedSafari = $this->saveStepData($request, $formStep);

                if ($savedSafari === 'redirect_to_listing') {
                    return redirect()->route('frontend.safari-manage-listing')->with('success', 'All steps completed! Safari saved successfully.');
                } elseif ($savedSafari) {
                    return redirect()->route('frontend.safari-edit-listing', ['safari_id' => $savedSafari])->with('success', 'Safari saved successfully.');
                }
            }

            $request->validate([
                /** String inputs */
                'title' => 'required|string|max:80',
                'region_id' => 'required|integer|exists:regions,id',
                'country_id' => 'required|integer|exists:country_guides,id',
                'activityLevel' => 'required|string|max:255',
                'description' => 'required|string|max:1500',
                'special_description' => 'required|string',
                'seasons' => 'required|array',
                'safariIncluded' => 'required|string|max:1500',
                'safariNotIncluded' => 'required|string|max:1500',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'location_type' => 'required|string',
                'national_park_id' => $request->location_type == 'National_Park_Reserve' ? 'required' : 'nullable',
                'location' => 'nullable|string|max:255',
                'company_name' => 'required|string|max:80',
                'operator_description' => 'required|string|max:400',
                'business_years' => 'nullable|max:255',
                'operator_type' => 'required|string|max:255',
                'availabilityTag' => 'nullable',
                'why_choose_us' => 'required|string|max:500',

                /** Number inputs */
                'numberOfAdults' => 'required|integer|min:1',
                'numberOfChildren' => 'nullable|integer',
                'numberOfGroups' => 'required|integer|min:1',
                'day_duration' => 'required|integer|min:1',
                'night_duration' => 'required|integer|min:1',
                'perDateGroupLimit' => 'nullable|integer|min:1',

                /** Arrays */
                'safariType' => 'required|array',
                'safariType.*' => 'required',
                'activities' => 'required|array',
                'environment' => 'required|array',
                'gallery_images' => 'required|array',
                'gallery_images.*.file' => 'required|mimes:jpeg,png,jpg,gif,webp|max:10240',
                'national_parks' => 'required|array',
                'national_parks.*' => 'required',

                'dateRange' => 'required|array',
                'dateRange.*.lowSeasonAvailableDates' => 'required',
                'dateRange.*.lowSeasonBlockedDates' => 'nullable',
                'dateRange.*.lowSeasonAdultPrice' => 'required|numeric|min:0',
                'dateRange.*.lowSeasonChildPrice' => 'nullable|numeric|min:0',
                'dateRange.*.highSeasonAvailableDates' => 'required',
                'dateRange.*.highSeasonBlockedDates' => 'nullable',
                'dateRange.*.highSeasonAdultPrice' => 'required|numeric|min:0',
                'dateRange.*.highSeasonChildPrice' => 'nullable|numeric|min:0',

                /** Nested arrays */
                'days' => 'required|array',
                'days.*.heading' => 'required|string|max:100',
                'days.*.subtext' => 'required|string|max:1500',
                'days.*.today_highlights' => 'required',
                'days.*.no_accommodation_included' => 'nullable',
                'days.*.accommodation' => 'required_if:days.*.no_accommodation_included,0|max:100',
                'days.*.image' => 'required_if:days.*.no_accommodation_included,0|array',
                'days.*.meals' => 'required',
                'days.*.wildlife_location' => 'required|max:500',
                'days.*.animals' => 'required|array',
                'days.*.animals.*.animal_id' => 'required|max:255',
                'days.*.animals.*.description' => 'nullable|string',

                'thingsToKnows' => 'required|array',
                'thingsToKnows.*.heading' => 'required|string|max:150',
                'thingsToKnows.*.description' => 'required|string|max:1000',

                'discounts' => 'nullable|array',
                'discounts.*.person_type' => 'nullable|string|max:255',
                'discounts.*.count' => 'nullable|integer|min:0',
                'discounts.*.season' => 'nullable|string|max:255',
                'discounts.*.net_price' => 'nullable|numeric|min:0',

                /** File inputs */
                'thumbnail' => 'required|file|image|max:10240|mimes:png,jpg',
                'operator_logo' => 'nullable|file|image|max:10240',
                'address' => 'required|string|max:100',
            ], [
                'title.required' => 'The title is required.',
                'season.required' => 'Please select a season.',
                'region_id.required' => 'Please select a region.',
                'national_park_id.required' => 'Please select a national park.',
                'country_id.required' => 'Country is required.',
                'country_id.exists' => 'Selected country does not exist.',
                'special_description.required' => 'What is special about this safari field is required.',

                'dateRange.required' => 'Please select a date range.',
                'dateRange.*.lowSeasonAdultPrice.required' => 'Low season adult price is required.',
                'dateRange.*.lowSeasonChildPrice.required' => 'Low season child price is required.',
                'dateRange.*.highSeasonAdultPrice.required' => 'High season adult price is required.',
                'dateRange.*.highSeasonChildPrice.required' => 'High season child price is required.',
                'dateRange.*.lowSeasonAvailableDates.required' => 'Low season Available dates are required.',
                'dateRange.*.highSeasonAvailableDates.required' => 'Hign season Available dates are required.',

                'numberOfAdults.required' => 'Number of adults is required.',
                'numberOfChildren.required' => 'Number of children is required.',
                'numberOfGroups.required' => 'Number of groups is required.',

                'gallery_images.required' => 'Gallery images are required.',
                'gallery_images.*.file.required' => 'Please upload an image for each gallery.',
                'gallery_images.*.file.mimes' => 'The gallery image must be a file of type: jpeg, png, jpg, gif, webp.',
                'gallery_images.*.file.max' => 'The gallery image may not be greater than 10MB.',

                'animals.*.animal_id.required' => 'Please enter the wildlife species name.',
                'animals.*.probability.required' => 'Please select the sightings likelihood.',

                'days.*.heading.required' => 'Each day needs a heading.',
                'days.*.heading.max' => 'Each day heading must be less than 100 characters.',
                'days.*.accommodation.max' => 'Each day accommodation must be less than 255 characters.',
                'days.*.subtext.required' => 'Each day needs a description.',
                'days.*.today_highlights.required' => 'Each day requires today`s highlights.',
                'days.*.meals.required' => 'Please enter the meals for each day.',
                'days.*.wildlife_location.required' => 'Please enter the wildlife location for each day.',
                'days.*.wildlife_location.max' => 'Each day wildlife location must be less than 500 characters.',
                'days.*.animals.*.animal_id.required' => 'Please select an animal for each day',
                'days.*.accommodation.required_if' => 'Accommodation is required when no accommodation is not selected.',
                'days.*.image.required_if' => 'Image is required when no accommodation is not selected.',

                'days.*.image.*.file.mimes' => 'The gallery image must be a file of type: jpeg, png, jpg, gif, webp.',
                'days.*.image.*.file.max' => 'The gallery image must not be greater than 10MB.',

                'thingsToKnows.*.heading.required' => 'Each "thing to know" needs a title.',
                'thingsToKnows.*.description.required' => 'Each "thing to know" needs a description.',

                'thumbnail.required' => 'Please upload a thumbnail image for the package.',
                'thumbnail.image' => 'The thumbnail must be an image.',
                'thumbnail.max' => 'The thumbnail image must not be greater than 10MB.',
                'operator_logo.max' => 'The operator logo image must not be greater than 10MB.',
            ]);
            DB::beginTransaction();

            try {
                $user = User::find(Auth::id());
                $safariId = $request->input('safari_id');

                // Get existing safari or create new one
                $safari = $safariId ? Safari::find($safariId) : new Safari();
                if (!$safari->exists) {
                    $safari->user_id = $user->id;
                }
                $safari->title = $request->title;
                $safari->region_id = $request->region_id;
                $safari->national_park_id = $request->location_type == 'National_Park_Reserve' ? $request->national_park_id : null;
                $safari->country_id = $request->country_id;
                $safari->activity_level = $request->activityLevel;
                $safari->description = $request->description;
                $safari->short_description = $request->special_description;
                $safari->travel_season = implode(', ', $request->seasons);
                $safari->safari_included = $request->safariIncluded;
                $safari->safari_not_included = $request->safariNotIncluded;
                $safari->lat = $request->latitude;
                $safari->long = $request->longitude;
                $safari->location = $request->location;
                $safari->no_of_adult = $request->numberOfAdults;
                $safari->no_of_child = $request->numberOfChildren ?? 0;
                $safari->number_of_groups = $request->numberOfGroups;
                $safari->day_duration = $request->day_duration;
                $safari->night_duration = $request->night_duration;
                $safari->per_date_group_limit = $request->perDateGroupLimit;
                $safari->environment = $request->environment ? json_encode($request->environment) : NULL;
                $safari->is_draft = 0;
                $safari->save();
                SafariAvailableTag::create([
                    'tag_id' => $request->availabilityTag,
                    'safari_id' => $safari->id,
                ]);

                if ($request->dateRange) {
                    SafariDate::where('safari_id', $safari->id)->delete();
                    SeasonalPricing::where('safari_id', $safari->id)->delete();

                    foreach ($request->dateRange as $dateRange) {
                        // --- SafariDate save ---
                        $seasons = [
                            'low' => [
                                'available' => $dateRange['lowSeasonAvailableDates'] ?? [],
                                'blocked' => $dateRange['lowSeasonBlockedDates'] ?? []
                            ],
                            'high' => [
                                'available' => $dateRange['highSeasonAvailableDates'] ?? [],
                                'blocked' => $dateRange['highSeasonBlockedDates'] ?? []
                            ]
                        ];

                        foreach ($seasons as $season => $dates) {
                            // Normalize single-date case → make it an array
                            $availableDates = is_array($dates['available'])
                                ? $dates['available']
                                : [$dates['available']];
                            $blockedDates = is_array($dates['blocked'])
                                ? $dates['blocked']
                                : [$dates['blocked']];

                            if (!empty($availableDates[0])) {
                                SafariDate::create([
                                    'safari_id' => $safari->id,
                                    'available_start_date' => Carbon::parse($availableDates[0])->toDateString(),
                                    'available_end_date' => isset($availableDates[1]) ? Carbon::parse($availableDates[1])->toDateString() : null,
                                    'blocked_start_date' => !empty($blockedDates[0]) ? Carbon::parse($blockedDates[0])->toDateString() : null,
                                    'blocked_end_date' => !empty($blockedDates[1]) ? Carbon::parse($blockedDates[1])->toDateString() : null,
                                ]);
                            }
                        }

                        // --- SeasonalPricing save ---
                        $lowAvailable = is_array($dateRange['lowSeasonAvailableDates'])
                            ? $dateRange['lowSeasonAvailableDates']
                            : [$dateRange['lowSeasonAvailableDates']];
                        $lowBlocked = is_array($dateRange['lowSeasonBlockedDates'])
                            ? $dateRange['lowSeasonBlockedDates']
                            : [$dateRange['lowSeasonBlockedDates']];

                        $highAvailable = is_array($dateRange['highSeasonAvailableDates'])
                            ? $dateRange['highSeasonAvailableDates']
                            : [$dateRange['highSeasonAvailableDates']];
                        $highBlocked = is_array($dateRange['highSeasonBlockedDates'])
                            ? $dateRange['highSeasonBlockedDates']
                            : [$dateRange['highSeasonBlockedDates']];

                        // Low Season
                        if (!empty($lowAvailable[0])) {
                            SeasonalPricing::create([
                                'safari_id' => $safari->id,
                                'season' => 'low',
                                'available_start_date' => Carbon::parse($lowAvailable[0])->toDateString(),
                                'available_end_date' => isset($lowAvailable[1]) ? Carbon::parse($lowAvailable[1])->toDateString() : null,
                                'blocked_start_date' => !empty($lowBlocked[0]) ? Carbon::parse($lowBlocked[0])->toDateString() : null,
                                'blocked_end_date' => !empty($lowBlocked[1]) ? Carbon::parse($lowBlocked[1])->toDateString() : null,
                                'adult_price' => $dateRange['lowSeasonAdultPrice'] ?? 0,
                                'child_price' => $dateRange['lowSeasonChildPrice'] ?? 0,
                            ]);
                        }

                        // High Season
                        if (!empty($highAvailable[0])) {
                            SeasonalPricing::create([
                                'safari_id' => $safari->id,
                                'season' => 'high',
                                'available_start_date' => Carbon::parse($highAvailable[0])->toDateString(),
                                'available_end_date' => isset($highAvailable[1]) ? Carbon::parse($highAvailable[1])->toDateString() : null,
                                'blocked_start_date' => !empty($highBlocked[0]) ? Carbon::parse($highBlocked[0])->toDateString() : null,
                                'blocked_end_date' => !empty($highBlocked[1]) ? Carbon::parse($highBlocked[1])->toDateString() : null,
                                'adult_price' => $dateRange['highSeasonAdultPrice'] ?? 0,
                                'child_price' => $dateRange['highSeasonChildPrice'] ?? 0,
                            ]);
                        }
                    }
                }

                /** Save national parks and reserve */
                if ($request->national_parks) {
                    foreach ($request->national_parks as $parkId) {
                        $createSafariType = SafariNationalParkReserve::create([
                            'safari_id' => $safari->id,
                            'national_park_reserve_id' => $parkId
                        ]);
                    }
                }

                /** Save safari type */
                if ($request->safariType) {
                    foreach ($request->safariType as $safariTypeId) {
                        $createSafariType = CreateSafariType::create([
                            'safari_id' => $safari->id,
                            'safari_type_id' => $safariTypeId
                        ]);
                    }
                }

                /** Save activity */
                if ($request->activities) {
                    foreach ($request->activities as $activityId) {
                        $createSafariActivity = SafariActivity::create([
                            'safari_id' => $safari->id,
                            'activity_id' => $activityId
                        ]);
                    }
                }

                /** Save day by day */
                if ($request->days) {
                    foreach ($request->days as $key => $day) {

                        $highlights = [];
                        if (isset($day['animals'])) {
                            foreach ($day['animals'] as $animal) {
                                if (empty($animal['animal_id']) && empty($animal['description'])) {
                                    continue;
                                }
                                $highlights[] = [
                                    'animal_id' => (string) $animal['animal_id'],
                                    'description' => $animal['description'] ?? '',
                                ];
                            }
                        }

                        $safariJourney = new SafariJourney();
                        $safariJourney->safari_id = $safari->id;
                        $safariJourney->heading = $day['heading'];
                        $safariJourney->subtext = $day['subtext'];
                        $safariJourney->today_highlights = $day['today_highlights'] ?? '';
                        $safariJourney->no_accommodation_included = $day['no_accommodation_included'] ?? false;
                        $safariJourney->accommodation = $day['accommodation'];
                        $safariJourney->meal = $day['meals'] ?? '';
                        $safariJourney->wildlife_location = $day['wildlife_location'] ?? '';
                        $safariJourney->wildlife_highlights = !empty($highlights) ? json_encode($highlights) : null;
                        $safariJourney->save();
                        $submittedImages = $day['image'] ?? [];
                        if ($submittedImages) {
                            foreach ($submittedImages as $image) {
                                $file = $image['file'] ??  null;
                                if (!$file) {
                                    continue;
                                }

                                $safariJourneyImage = new SafariJourneyImages();
                                $safariJourneyImage->safari_journey_id = $safariJourney->id;
                                $final_image_url = ImageHelper::customSaveImage($file, 'safari_journey_images');
                                $safariJourneyImage->image = $final_image_url;
                                $safariJourneyImage->save();
                            }
                        }
                    }
                }

                /** Save things To Knows */
                if ($request->thingsToKnows) {
                    foreach ($request->thingsToKnows as $index => $item) {
                        SafariThingsToKnow::create([
                            'safari_id' => $safari->id,
                            'heading' => $item['heading'],
                            'description' => $item['description']
                        ]);
                    }
                }
                /** Save Discount */
                if ($request->discounts) {
                    foreach ($request->discounts as $index => $item) {
                        if ($item['person_type'] == null) {
                            continue;
                        }
                        SafariGroupPricing::create([
                            'safari_id' => $safari->id,
                            'person_type' => $item['person_type'],
                            'count' => $item['count'] ?? 0,
                            'season' => $item['season'],
                            'net_price' => $item['net_price'] ?? 0
                        ]);
                    }
                }

                /** Save Gallery image */
                if ($request->gallery_images) {
                    foreach ($request->gallery_images as $image) {
                        $final_image_url = ImageHelper::customSaveImage($image['file'], 'safari_gallery_images');
                        $safariImage = new SafariGallery();
                        $safariImage->safari_id = $safari->id;
                        $safariImage->files = $final_image_url;
                        $safariImage->type = 'image';
                        $safariImage->save();
                    }
                }

                /** Save thumbnail */
                if ($request->hasFile('thumbnail')) {
                    $final_image_url = ImageHelper::customSaveImage($request->file('thumbnail'), 'safari_thumbnail');
                    $safari->thumbnail = $final_image_url;
                    $safari->save();
                }

                /** Save operator additional details */
                $user->business_name = $request->company_name;
                $user->about_operation = $request->operator_description;
                $user->business_type = $request->operator_type;
                $user->address = $request->address;
                $user->why_choose_us = $request->why_choose_us;
                $user->business_years = $request->business_years;
                $user->save();

                /** Save operator logo */
                if ($request->hasFile('operator_logo')) {
                    $final_image_url = ImageHelper::customSaveImage($request->file('operator_logo'), 'safari_operator_logo');
                    $user->business_logo = $final_image_url;
                    $user->save();
                }
                DB::commit();

                return redirect()->route('frontend.safari-manage-listing')->with('success', 'Safari has been created successfully. Please wait for the admin approval.');
            } catch (\Exception $e) {
                Log::error("File: " . $e->getFile() . " Line: " . $e->getLine() . " - " . $e->getMessage() . "\n" . $e->getTraceAsString());
                DB::rollBack();
                abort(500);
            }
        }

        $regions = Region::get(['id', 'name'])
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->name];
            });

        $wildlives = WildLife::get(['id', 'name'])->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->name
            ];
        });
        $allNationalParkReserves = NationalParkAndReserves::where('status', true)->get(['id', 'name'])->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        });


        $safariTypes = SafariType::get(['id', 'title']);
        $activities = Activity::get(['id', 'title', 'icon']);
        $safariAvailableTags = AvailableTag::where('show_in_frontend', 1)->get(['id', 'name'])->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->name
            ];
        });

        return Inertia::render('Frontend/Auth/create-new-listing', compact('regions', 'wildlives', 'allNationalParkReserves', 'safariTypes', 'activities', 'safariAvailableTags'));
    }

    private function saveStepData(Request $request, $formStep, $safariId = null)
    {
        $user = User::find(Auth::id());
        $safari = $safariId ? Safari::find($safariId) : new Safari();

        if (!$safari->exists) {
            $safari->user_id = $user->id;
            $safari->is_draft = 1;
        }

        DB::beginTransaction();

        try {
            switch ($formStep) {
                case 1:
                    $safari->title = $request->title;
                    $safari->region_id = $request->region_id;
                    $safari->country_id = $request->country_id;
                    $safari->travel_season = implode(', ', $request->seasons ?? []);
                    $safari->day_duration = $request->day_duration;
                    $safari->night_duration = $request->night_duration;
                    $safari->save();

                    // Save thumbnail
                    if ($request->hasFile('thumbnail')) {
                        $final_image_url = ImageHelper::customSaveImage($request->file('thumbnail'), 'safari_thumbnail');
                        $safari->thumbnail = $final_image_url;
                        $safari->save();
                    }

                    // Save national parks
                    if ($request->national_parks) {
                        $incomingParks = $request->national_parks;
                        SafariNationalParkReserve::where('safari_id', $safari->id)->whereNotIn('national_park_reserve_id', $incomingParks)->delete();
                        foreach ($incomingParks as $parkId) {
                            SafariNationalParkReserve::updateOrCreate(
                                ['safari_id' => $safari->id, 'national_park_reserve_id' => $parkId],
                                []
                            );
                        }
                    }

                    // Save safari types
                    if ($request->safariType) {
                        $incomingIds = $request->safariType;
                        CreateSafariType::where('safari_id', $safari->id)->whereNotIn('safari_type_id', $incomingIds)->delete();
                        foreach ($incomingIds as $safariTypeId) {
                            CreateSafariType::updateOrCreate(
                                ['safari_id' => $safari->id, 'safari_type_id' => $safariTypeId],
                                []
                            );
                        }
                    }

                    break;

                case 2: // Safari Overview
                    $safari->description = $request->description;
                    $safari->short_description = $request->special_description;
                    $safari->save();
                    break;

                case 3: // Safari Itinerary
                    if ($request->days) {
                        $existingJourneys = $safari->journeys()->with('journey_images')->get();
                        $processedJourneyIds = [];
                        
                        foreach ($request->days as $index => $day) {
                            $safariJourney = isset($existingJourneys[$index])
                                ? $existingJourneys[$index]
                                : new SafariJourney();

                            $highlights = [];
                            if (isset($day['animals'])) {
                                foreach ($day['animals'] as $animal) {
                                    if (empty($animal['animal_id']) && empty($animal['description'])) {
                                        continue;
                                    }
                                    $highlights[] = [
                                        'animal_id' => (string) $animal['animal_id'],
                                        'description' => $animal['description'] ?? '',
                                    ];
                                }
                            }

                            $safariJourney->safari_id = $safari->id;
                            $safariJourney->heading = $day['heading'] ?? '';
                            $safariJourney->meal = $day['meals'] ?? '';
                            $safariJourney->subtext = $day['subtext'] ?? '';
                            $safariJourney->today_highlights = $day['today_highlights'] ?? '';
                            $safariJourney->wildlife_location = $day['wildlife_location'] ?? '';
                            $safariJourney->no_accommodation_included = $day['no_accommodation_included'] ?? false;
                            $safariJourney->accommodation = $day['accommodation'] ?? '';
                            $safariJourney->wildlife_highlights = !empty($highlights) ? json_encode($highlights) : null;
                            $safariJourney->save();
                            
                            $processedJourneyIds[] = $safariJourney->id;

                            $existingImages = $safariJourney->journey_images;
                            $submittedImages = $day['image'] ?? [];
                            $submittedPaths = [];

                            foreach ($submittedImages as $image) {
                                if (!empty($image['file'])) {
                                    $imagePath = ImageHelper::customSaveImage($image['file'], 'safari_journey_images');
                                    SafariJourneyImages::create([
                                        'safari_journey_id' => $safariJourney->id,
                                        'image' => $imagePath,
                                    ]);
                                    $submittedPaths[] = $imagePath;
                                } elseif (!empty($image['preview']) && str_contains($image['preview'], '/storage/')) {
                                    $url = $image['preview'];
                                    $path = parse_url($url, PHP_URL_PATH);
                                    $relativePath = ltrim($path, '/');
                                    $submittedPaths[] = $relativePath;

                                    if (!$existingImages->contains('image', $relativePath)) {
                                        SafariJourneyImages::create([
                                            'safari_journey_id' => $safariJourney->id,
                                            'image' => $relativePath,
                                        ]);
                                    }
                                }
                            }

                            // Delete images that were removed
                            foreach ($existingImages as $img) {
                                if (!in_array($img->image, $submittedPaths)) {
                                    if (file_exists($img->image)) {
                                        @unlink($img->image);
                                    }
                                    $img->delete();
                                }
                            }
                        }
                        
                        // Delete journeys that were removed
                        $journeysToDelete = $existingJourneys->whereNotIn('id', $processedJourneyIds);
                        foreach ($journeysToDelete as $journey) {
                            // Delete associated images first
                            foreach ($journey->journey_images as $img) {
                                if (file_exists($img->image)) {
                                    @unlink($img->image);
                                }
                                $img->delete();
                            }
                            $journey->delete();
                        }
                    }
                    break;

                case 4: // What's Included/Not Included
                    $safari->safari_included = $request->safariIncluded;
                    $safari->safari_not_included = $request->safariNotIncluded;
                    $safari->save();
                    break;

                case 5: // Activities
                    if ($request->activities) {
                        $incomingActivities = $request->activities;
                        SafariActivity::where('safari_id', $safari->id)->whereNotIn('activity_id', $incomingActivities)->delete();
                        foreach ($incomingActivities as $activityId) {
                            SafariActivity::updateOrCreate(
                                ['safari_id' => $safari->id, 'activity_id' => $activityId],
                                []
                            );
                        }
                    }
                    break;

                case 6: // Ecosystems/Landscapes
                    $safari->environment = $request->environment ? json_encode($request->environment) : null;
                    $safari->save();
                    break;

                case 7: // Activity Level
                    $safari->activity_level = $request->activityLevel;
                    $safari->save();
                    break;

                case 8: // Traveller Group & Capacity
                    $safari->no_of_adult = $request->numberOfAdults;
                    $safari->no_of_child = $request->numberOfChildren ?? 0;
                    $safari->number_of_groups = $request->numberOfGroups;
                    // $safari->group_type = $request->groupType;
                    $safari->save();
                    break;

                case 9: // Availability Calendar

                    $safari->per_date_group_limit = $request->perDateGroupLimit;
                    $safari->save();

                    if ($request->availabilityTag) {
                        $findSafariAvailableTag = SafariAvailableTag::where('safari_id', $safari->id)->first();

                        if ($findSafariAvailableTag) {
                            $findSafariAvailableTag->tag_id = $findSafariAvailableTag->tag_id;
                            $findSafariAvailableTag->save();
                        } else {

                            SafariAvailableTag::create([
                                'tag_id' => $request->availabilityTag[0],
                                'safari_id' => $safari->id,
                            ]);
                            //    dd(true);
                        }
                    }

                    if ($request->dateRange) {
                        SafariDate::where('safari_id', $safari->id)->delete();
                        SeasonalPricing::where('safari_id', $safari->id)->delete();

                        foreach ($request->dateRange as $dateRange) {
                            // --- SafariDate save ---
                            $seasons = [
                                'low' => [
                                    'available' => $dateRange['lowSeasonAvailableDates'] ?? [],
                                    'blocked' => $dateRange['lowSeasonBlockedDates'] ?? []
                                ],
                                'high' => [
                                    'available' => $dateRange['highSeasonAvailableDates'] ?? [],
                                    'blocked' => $dateRange['highSeasonBlockedDates'] ?? []
                                ]
                            ];

                            foreach ($seasons as $season => $dates) {
                                // Normalize single-date case → make it an array
                                $availableDates = is_array($dates['available'])
                                    ? $dates['available']
                                    : [$dates['available']];
                                $blockedDates = is_array($dates['blocked'])
                                    ? $dates['blocked']
                                    : [$dates['blocked']];

                                if (!empty($availableDates[0])) {
                                    SafariDate::create([
                                        'safari_id' => $safari->id,
                                        'available_start_date' => Carbon::parse($availableDates[0])->toDateString(),
                                        'available_end_date' => isset($availableDates[1]) ? Carbon::parse($availableDates[1])->toDateString() : null,
                                        'blocked_start_date' => !empty($blockedDates[0]) ? Carbon::parse($blockedDates[0])->toDateString() : null,
                                        'blocked_end_date' => !empty($blockedDates[1]) ? Carbon::parse($blockedDates[1])->toDateString() : null,
                                    ]);
                                }
                            }

                            // --- SeasonalPricing save ---
                            $lowAvailable = is_array($dateRange['lowSeasonAvailableDates'])
                                ? $dateRange['lowSeasonAvailableDates']
                                : [$dateRange['lowSeasonAvailableDates']];
                            $lowBlocked = is_array($dateRange['lowSeasonBlockedDates'])
                                ? $dateRange['lowSeasonBlockedDates']
                                : [$dateRange['lowSeasonBlockedDates']];

                            $highAvailable = is_array($dateRange['highSeasonAvailableDates'])
                                ? $dateRange['highSeasonAvailableDates']
                                : [$dateRange['highSeasonAvailableDates']];
                            $highBlocked = is_array($dateRange['highSeasonBlockedDates'])
                                ? $dateRange['highSeasonBlockedDates']
                                : [$dateRange['highSeasonBlockedDates']];

                            // Low Season
                            if (!empty($lowAvailable[0])) {
                                SeasonalPricing::create([
                                    'safari_id' => $safari->id,
                                    'season' => 'low',
                                    'available_start_date' => Carbon::parse($lowAvailable[0])->toDateString(),
                                    'available_end_date' => isset($lowAvailable[1]) ? Carbon::parse($lowAvailable[1])->toDateString() : null,
                                    'blocked_start_date' => !empty($lowBlocked[0]) ? Carbon::parse($lowBlocked[0])->toDateString() : null,
                                    'blocked_end_date' => !empty($lowBlocked[1]) ? Carbon::parse($lowBlocked[1])->toDateString() : null,
                                    'adult_price' => $dateRange['lowSeasonAdultPrice'] ?? 0,
                                    'child_price' => $dateRange['lowSeasonChildPrice'] ?? 0,
                                ]);
                            }

                            // High Season
                            if (!empty($highAvailable[0])) {
                                SeasonalPricing::create([
                                    'safari_id' => $safari->id,
                                    'season' => 'high',
                                    'available_start_date' => Carbon::parse($highAvailable[0])->toDateString(),
                                    'available_end_date' => isset($highAvailable[1]) ? Carbon::parse($highAvailable[1])->toDateString() : null,
                                    'blocked_start_date' => !empty($highBlocked[0]) ? Carbon::parse($highBlocked[0])->toDateString() : null,
                                    'blocked_end_date' => !empty($highBlocked[1]) ? Carbon::parse($highBlocked[1])->toDateString() : null,
                                    'adult_price' => $dateRange['highSeasonAdultPrice'] ?? 0,
                                    'child_price' => $dateRange['highSeasonChildPrice'] ?? 0,
                                ]);
                            }
                        }
                    }

                    // Save discounts
                    if ($request->discounts) {
                        SafariGroupPricing::where('safari_id', $safari->id)->delete();
                        foreach ($request->discounts as $index => $item) {
                            if ($item['person_type'] == null) {
                                continue;
                            }
                            SafariGroupPricing::create([
                                'safari_id' => $safari->id,
                                'person_type' => $item['person_type'],
                                'count' => $item['count'] ?? 0,
                                'season' => $item['season'],
                                'net_price' => $item['net_price'] ?? 0
                            ]);
                        }
                    }
                    break;

                case 10: // Map Location
                    $safari->lat = $request->latitude;
                    $safari->long = $request->longitude;
                    $safari->location = $request->location;
                    $safari->national_park_id = $request->location_type == 'National_Park_Reserve' ? $request->national_park_id : null;
                    $safari->save();
                    break;

                case 11: // Gallery
                    if ($request->gallery_images) {
                        foreach ($request->gallery_images as $image) {
                            $final_image_url = ImageHelper::customSaveImage($image['file'], 'safari_gallery_images');
                            $safariImage = new SafariGallery();
                            $safariImage->safari_id = $safari->id;
                            $safariImage->files = $final_image_url;
                            $safariImage->type = 'image';
                            $safariImage->save();
                        }
                    }
                    break;

                case 12: // Things to Know/FAQ
                    $incomingItems = $request->thingsToKnows ?? [];

                    foreach ($incomingItems as $item) {
                        if (empty($item['heading']) && empty($item['description'])) {
                            continue;
                        }
                        if (!empty($item['id'])) {
                            $model = SafariThingsToKnow::where('id', $item['id'])
                                ->where('safari_id', $safari->id)
                                ->first();

                            if ($model) {
                                $model->update([
                                    'heading' => $item['heading'],
                                    'description' => $item['description'],
                                ]);
                                $processedIds[] = $model->id;
                            }
                        } else {
                            $model = SafariThingsToKnow::create([
                                'safari_id' => $safari->id,
                                'heading' => $item['heading'],
                                'description' => $item['description'],

                            ]);
                            $processedIds[] = $model->id;
                        }
                    }
                    SafariThingsToKnow::where('safari_id', $safari->id)
                        ->whereNotIn('id', $processedIds)
                        ->delete();
                    break;

                case 13:
                    $user->business_name = $request->company_name;
                    $user->about_operation = $request->operator_description;
                    $user->business_type = $request->operator_type;
                    $user->address = $request->address;
                    $user->why_choose_us = $request->why_choose_us;
                    $user->business_years = $request->business_years;
                    $user->save();

                    if ($request->hasFile('operator_logo')) {
                        $final_image_url = ImageHelper::customSaveImage($request->file('operator_logo'), 'safari_operator_logo');
                        $user->business_logo = $final_image_url;
                        $user->save();
                    }
                    break;
            }
            // Update step saved status
            $stepStatus = $safari->step_saved_status ?? [];
            $stepStatus[$formStep] = true;
            $safari->step_saved_status = $stepStatus;
            $safari->save();

            DB::commit();

            // Check if all steps are completed and redirect

            $requiredSteps = range(1, 13);
            $allStepsCompleted = true;
            foreach ($requiredSteps as $step) {
                if (!isset($stepStatus[$step]) || !$stepStatus[$step]) {
                    $allStepsCompleted = false;
                    break;
                }
            }

            if ($allStepsCompleted) {
                return 'redirect_to_listing';
            }

            return $safari->id;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateSection(Request $request, $formStep)
    {
        switch ($formStep) {
            case 1: // Basic Information
                $request->validate([
                    'title' => 'required|string|max:80',
                    'region_id' => 'required|integer|exists:regions,id',
                    'country_id' => 'required|integer|exists:country_guides,id',
                    'national_parks' => 'required|array',
                    'safariType' => 'required|array',
                    'seasons' => 'required|array',
                    'thumbnail' => $request->thumbnail ? 'required|file|image|max:10240|mimes:png,jpg' : 'nullable',
                    'day_duration' => 'required|integer|min:1',
                    'night_duration' => 'required|integer|min:1',
                ], [
                    'title.required' => 'The title is required.',
                    'region_id.required' => 'Please select a region.',
                    'country_id.required' => 'Country is required.',
                    'country_id.exists' => 'Selected country does not exist.',
                    'national_parks.required' => 'Please select at least one national park.',
                    'safariType.required' => 'Please select at least one safari type.',
                    'seasons.required' => 'Please select at least one season.',
                    'thumbnail.max' => 'The thumbnail image must not be greater than 10MB.',
                    'day_duration.required' => 'Day duration is required.',
                    'night_duration.required' => 'Night duration is required.',
                ]);
                break;

            case 2: // Safari Overview
                $request->validate([
                    'description' => 'required|string|max:1500',
                    'special_description' => 'required|string',
                ], [
                    'description.required' => 'Safari description is required.',
                    'description.max' => 'Description must not exceed 600 characters.',
                    'special_description.required' => 'What is special about this safari field is required.',
                ]);
                break;

            case 3: // Safari Itinerary
                $request->validate([
                    'days' => 'required|array',
                    'days.*.heading' => 'required|string|max:100',
                    'days.*.subtext' => 'required|string|max:1500',
                    'days.*.today_highlights' => 'required',
                    'days.*.no_accommodation_included' => 'nullable',
                    'days.*.accommodation' => 'required_if:days.*.no_accommodation_included,0|max:100',
                    'days.*.image' => 'required_if:days.*.no_accommodation_included,0|array',
                    'days.*.meals' => 'required',
                    'days.*.wildlife_location' => 'required|max:500',
                    'days.*.animals' => 'required|array',
                    'days.*.animals.*.animal_id' => 'required|max:255',
                    'days.*.animals.*.description' => 'nullable|string',
                ], [
                    'days.required' => 'Safari itinerary is required.',
                    'days.*.heading.required' => 'Each day needs a heading.',
                    'days.*.heading.max' => 'Each day heading must be less than 100 characters.',
                    'days.*.subtext.required' => 'Each day needs a description.',
                    'days.*.today_highlights.required' => 'Each day requires today\'s highlights.',
                    'days.*.accommodation.max' => 'Each day accommodation must be less than 100 characters.',
                    'days.*.meals.required' => 'Please enter the meals for each day.',
                    'days.*.accommodation.required_if' => 'Accommodation is required when no accommodation is not selected.',
                    'days.*.image.required_if' => 'Image is required when no accommodation is not selected.',
                    'days.*.wildlife_location.required' => 'Please enter the wildlife location for each day.',
                    'days.*.wildlife_location.max' => 'Each day wildlife location must be less than 500 characters.',
                    'days.*.animals.required' => 'Please select animals for each day.',
                    'days.*.animals.*.animal_id.required' => 'Please select an animal for each day',
                ]);
                break;

            case 4: // What's Included/Not Included
                $request->validate([
                    'safariIncluded' => 'required|string|max:1500',
                    'safariNotIncluded' => 'required|string|max:1500',
                ], [
                    'safariIncluded.required' => 'Please specify what is included in the safari.',
                    'safariNotIncluded.required' => 'Please specify what is not included in the safari.',
                ]);
                break;

            case 5: // Activities
                $request->validate([
                    'activities' => 'required|array',
                ], [
                    'activities.required' => 'Please select at least one activity.',
                ]);
                break;

            case 6: // Ecosystems/Landscapes
                $request->validate([
                    'environment' => 'required|array',
                ], [
                    'environment.required' => 'Please select at least one environment type.',
                ]);
                break;

            case 7: // Activity Level
                $request->validate([
                    'activityLevel' => 'required|string|max:255',
                ], [
                    'activityLevel.required' => 'Please select an activity level.',
                ]);
                break;

            case 8: // Traveller Group & Capacity
                $request->validate([
                    'numberOfAdults' => 'required|integer|min:1',
                    'numberOfChildren' => 'nullable|integer',
                    'numberOfGroups' => 'required|integer|min:1',
                    // 'groupType' => 'required|string',
                ], [
                    'numberOfAdults.required' => 'Number of adults is required.',
                    'numberOfAdults.min' => 'At least 1 adult is required.',
                    'numberOfGroups.required' => 'Number of groups is required.',
                    'numberOfGroups.min' => 'At least 1 group is required.',
                    'groupType.required' => 'Please select a group type.',
                ]);
                break;

            case 9: // Availability Calendar
                $request->validate([
                    'dateRange' => 'required|array',
                    'dateRange.*.lowSeasonAvailableDates' => 'required',
                    'dateRange.*.lowSeasonBlockedDates' => 'nullable',
                    'dateRange.*.lowSeasonAdultPrice' => 'required|numeric|min:0',
                    'dateRange.*.lowSeasonChildPrice' => 'nullable|numeric|min:0',
                    'dateRange.*.highSeasonAvailableDates' => 'required',
                    'dateRange.*.highSeasonBlockedDates' => 'nullable',
                    'dateRange.*.highSeasonAdultPrice' => 'required|numeric|min:0',
                    'dateRange.*.highSeasonChildPrice' => 'nullable|numeric|min:0',

                    'perDateGroupLimit' => 'nullable|integer|min:1',
                    'availabilityTag' => 'nullable',
                ], [
                    'dateRange.required' => 'Please select a date range.',
                    'dateRange.*.lowSeasonAdultPrice.required' => 'Low season adult price is required.',
                    'dateRange.*.lowSeasonChildPrice.required' => 'Low season child price is required.',
                    'dateRange.*.highSeasonAdultPrice.required' => 'High season adult price is required.',
                    'dateRange.*.highSeasonChildPrice.required' => 'High season child price is required.',
                    'dateRange.*.lowSeasonAvailableDates.required' => 'Low season Available dates are required.',
                    'dateRange.*.highSeasonAvailableDates.required' => 'Hign season Available dates are required.',
                ]);
                break;

            case 10: // Map Location
                $request->validate([
                    'location_type' => 'required|string',
                    'national_park_id' => $request->location_type == 'National_Park_Reserve' ? 'required' : 'nullable',
                    'location' => 'nullable|string|max:255',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                ], [
                    'location_type.required' => 'Please select a location type.',
                    'national_park_id.required' => 'Please select a national park.',
                ]);
                break;

            case 11: // Gallery
                $request->validate([
                    'gallery_images' => 'nullable|array',
                    'gallery_images.*.file' => 'nullable|mimes:jpeg,png,jpg,gif,webp|max:10240',
                ], [
                    'gallery_images.*.file.mimes' => 'The gallery image must be a file of type: jpeg, png, jpg, gif, webp.',
                    'gallery_images.*.file.max' => 'The gallery image must not be greater than 10MB.',
                ]);
                break;

            case 12: // Things to Know/FAQ
                $request->validate([
                    'thingsToKnows' => 'required|array',
                    'thingsToKnows.*.heading' => 'required|string|max:150',
                    'thingsToKnows.*.description' => 'required|string|max:1000',
                ], [
                    'thingsToKnows.required' => 'Please add at least one thing to know.',
                    'thingsToKnows.*.heading.required' => 'Each "thing to know" needs a title.',
                    'thingsToKnows.*.heading.max' => 'Each "thing to know" title must be less than 150 characters.',
                    'thingsToKnows.*.description.required' => 'Each "thing to know" needs a description.',
                    'thingsToKnows.*.description.max' => 'Each "thing to know" description must be less than 1000 characters.',
                ]);
                break;

            case 13: // Operator Info
                $request->validate([
                    'company_name' => 'required|string|max:80',
                    'operator_description' => 'required|string|max:400',
                    'business_years' => 'nullable|string|max:255',
                    'address' => 'required|string|max:100',
                    'why_choose_us' => 'required|string|max:500',
                    'operator_logo' => 'nullable|file|image|max:10240',
                ], [
                    'company_name.required' => 'Company name is required.',
                    'company_name.max' => 'Company name must not exceed 80 characters.',
                    'operator_description.required' => 'Operator description is required.',
                    'operator_description.max' => 'Operator description must not exceed 400 characters.',
                    'address.required' => 'Address is required.',
                    'address.max' => 'Address must not exceed 100 characters.',
                    'why_choose_us.required' => 'Why choose us field is required.',
                    'why_choose_us.max' => 'Why choose us must not exceed 500 characters.',
                    'operator_logo.max' => 'The operator logo must not be greater than 10MB.',
                ]);
                break;
        }
    }

    public function editListing(Request $request, $id)
    {
        $safari = Safari::where('id', $id)
            ->with(
                'create_safari_type',
                'activity',
                'journeys.journey_images',
                'group_pricing',
                'things_to_know',
                'discount',
                'gallery',
                'wild_lives',
                'dateRange',
                'safari_parks',
                'tags',
                'seasonal_pricings'
            )
            ->first();

        $safari->environment = $safari->environment ? json_decode($safari->environment) : [];
        $safari->travel_season = $safari->travel_season ? array_map('trim', explode(',', $safari->travel_season)) : '';
        if ($request->isMethod('post')) {

            $dateRange = $request->input('dateRange');

            if (is_string($dateRange)) {
                $dateRange = json_decode($dateRange, true);
            }

            $request->merge(['dateRange' => $dateRange]);
            $formStep = $request->input('formStep');
            if ($formStep > 0) {
                $this->validateSection($request, $formStep);
                $savedSafari = $this->saveStepData($request, $formStep, $safari->id);

                if ($savedSafari === 'redirect_to_listing') {
                    return redirect()->route('frontend.safari-manage-listing')->with('success', 'All steps completed! Safari saved successfully.');
                } elseif ($savedSafari) {
                    return redirect()->route('frontend.safari-edit-listing', ['safari_id' => $savedSafari])->with('success', 'Safari saved successfully.');
                }
            }

            $request->validate([
                /** String inputs */
                'title' => 'required|string|max:80',
                'region_id' => 'required|integer|exists:regions,id',
                'country_id' => 'required|integer|exists:country_guides,id',
                'activityLevel' => 'required|string|max:255',
                'description' => 'required|string|max:1500',
                'special_description' => 'required|string',
                'seasons' => 'required|array',
                'safariIncluded' => 'required|max:1500',
                'safariNotIncluded' => 'required|max:1500',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'location_type' => 'required',
                'national_park_id' => $request->location_type == 'National_Park_Reserve' ? 'required' : 'nullable',
                'location' => 'nullable|string|max:255',
                'company_name' => 'required|string|max:80',
                'operator_description' => 'required|string|max:400',
                'operator_type' => 'required|string|max:255',
                'availabilityTag' => 'nullable',
                'business_years' => 'nullable|max:255',
                'why_choose_us' => 'required|string|max:500',

                /** Number inputs */
                'numberOfAdults' => 'required|integer|min:1',
                'numberOfChildren' => 'nullable|integer',
                'numberOfGroups' => 'required|integer|min:1',
                'day_duration' => 'required|integer|min:1',
                'night_duration' => 'required|integer|min:1',
                'perDateGroupLimit' => 'nullable|integer',

                /** Arrays */
                'safariType' => 'required|array',
                'safariType.*' => 'required',
                'national_parks' => 'required|array',
                'national_parks.*' => 'required',
                'activities' => 'required|array',
                'environment' => 'required|array',
                'gallery_images' => $request->hasFile('gallery_images')
                    ? 'required|array'
                    : 'nullable|array',
                'gallery_images.*.file' => 'mimes:jpeg,png,jpg,svg|image|max:10240',

                /** Nested arrays */
                'dateRange' => 'required|array',
                'dateRange.*.lowSeasonAvailableDates' => 'required',
                'dateRange.*.lowSeasonBlockedDates' => 'nullable',
                'dateRange.*.lowSeasonAdultPrice' => 'required|numeric|min:0',
                'dateRange.*.lowSeasonChildPrice' => 'nullable|numeric|min:0',
                'dateRange.*.highSeasonAvailableDates' => 'required',
                'dateRange.*.highSeasonBlockedDates' => 'nullable',
                'dateRange.*.highSeasonAdultPrice' => 'required|numeric|min:0',
                'dateRange.*.highSeasonChildPrice' => 'nullable|numeric|min:0',

                'days' => 'required|array',
                'days.*.heading' => 'required|string|max:100',
                'days.*.subtext' => 'required|max:1500',
                'days.*.wildlife_location' => 'required|max:500',
                'days.*.meals' => 'required',
                'days.*.today_highlights' => 'required',
                'days.*.no_accommodation_included' => 'nullable',
                'days.*.accommodation' => 'required_if:days.*.no_accommodation_included,0|max:100',
                'days.*.image' => 'required_if:days.*.no_accommodation_included,0|array',
                'days.*.animals' => 'required|array',
                'days.*.animals.*.animal_id' => 'required|max:255',
                'days.*.animals.*.description' => 'nullable|string',
                'thingsToKnows' => 'required|array',
                'thingsToKnows.*.heading' => 'required|string|max:500',
                'thingsToKnows.*.description' => 'required|string|max:1000',

                'discounts' => 'nullable|array',
                'discounts.*.person_type' => 'nullable|string|max:255',
                'discounts.*.count' => 'nullable|integer|min:0',
                'discounts.*.season' => 'nullable|string|max:255',
                'discounts.*.net_price' => 'nullable|numeric|min:0',

                /** File inputs */
                'thumbnail' => $request->hasFile('thumbnail') ? 'mimes:png,jpg|image|max:10240' : '',
                'operator_logo' => 'nullable|mimes:jpeg,png,jpg,svg|image|max:10240',
            ], [
                'title.required' => 'The title is required.',
                'season.required' => 'Please select a season.',
                'region_id.required' => 'Please select a region.',
                'national_park_id.required' => 'Please select a national park.',
                'special_description.required' => 'What is special about this safari field is required.',
                'country_id.required' => 'Country is required.',
                'country_id.exists' => 'Selected country does not exist.',
                'numberOfAdults.required' => 'Number of adults is required.',
                'numberOfChildren.required' => 'Number of children is required.',
                'numberOfGroups.required' => 'Number of groups is required.',
                'gallery_images.*.file.mimes' => 'The gallery image must be a file of type: jpeg, png, jpg, gif, webp.',
                'gallery_images.*.file.max' => 'The gallery image must not be greater than 10MB.',
                'dateRange.required' => 'Please select a date range.',
                'dateRange.*.lowSeasonAdultPrice.required' => 'Low season adult price is required.',
                'dateRange.*.lowSeasonChildPrice.required' => 'Low season child price is required.',
                'dateRange.*.highSeasonAdultPrice.required' => 'High season adult price is required.',
                'dateRange.*.highSeasonChildPrice.required' => 'High season child price is required.',
                'dateRange.*.lowSeasonAvailableDates.required' => 'Low season Available dates are required.',
                'dateRange.*.highSeasonAvailableDates.required' => 'Hign season Available dates are required.',

                'days.*.heading.required' => 'Each day needs a heading.',
                'days.*.heading.max' => 'Each day heading must be less than 100 characters.',
                'days.*.subtext.required' => 'Each day needs a description.',
                'days.*.animals.*.animal_id.required' => 'Please select an animal for each day',
                'days.*.accommodation.required_if' => 'Accommodation is required when no accommodation is not selected.',
                'days.*.image.required_if' => 'Image is required when no accommodation is not selected.',
                'days.*.meals.required' => 'Each day needs a meals.',
                'days.*.wildlife_location.required' => 'Please enter the wildlife location for each day.',
                'days.*.wildlife_location.max' => 'Each day wildlife location must be less than 500 characters.',
                'days.*.today_highlights.required' => 'Each day requires today`s highlights.',

                'thingsToKnows.*.heading.required' => 'Each "thing to know" needs a heading.',
                'thingsToKnows.*.heading.max' => 'Each "thing to know" heading must be less than 255 characters.',
                'thingsToKnows.*.description.required' => 'Each "thing to know" needs a description.',
                'thumbnail.required' => 'Please upload a thumbnail image for the package.',
                'thumbnail.image' => 'The thumbnail must be an image.',
                'thumbnail.max' => 'The thumbnail image must not be greater than 10MB.',
                'operator_logo.max' => 'The operator logo must not be greater than 10MB.',
            ]);

            DB::beginTransaction();

            try {
                $user = User::find(Auth::id());
                $safari->update([
                    'title' => $request->title,
                    'region_id' => $request->region_id,
                    'country_id' => $request->country_id,
                    'activity_level' => $request->activityLevel,
                    'description' => $request->description,
                    'short_description' => $request->special_description,
                    'travel_season' => implode(', ', $request->seasons),
                    'safari_included' => $request->safariIncluded,
                    'safari_not_included' => $request->safariNotIncluded,
                    'lat' => $request->latitude,
                    'long' => $request->longitude,
                    'location' => $request->location,
                    'no_of_adult' => $request->numberOfAdults,
                    'no_of_child' => $request->numberOfChildren ?? 0,
                    'number_of_groups' => $request->numberOfGroups,
                    'day_duration' => $request->day_duration,
                    'night_duration' => $request->night_duration,
                    'per_date_group_limit' => $request->perDateGroupLimit,
                    'is_draft' => 0,
                    'environment' => $request->environment ? json_encode($request->environment) : NULL,
                ]);

                if ($request->availabilityTag) {
                    $findSafariAvailableTag = SafariAvailableTag::where('safari_id', $safari->id)->first();
                    if ($findSafariAvailableTag) {
                        $findSafariAvailableTag->tag_id = $findSafariAvailableTag->tag_id;
                        $findSafariAvailableTag->save();
                    } else {
                        SafariAvailableTag::create([
                            'tag_id' => $request->availabilityTag[0],
                            'safari_id' => $safari->id,
                        ]);
                    }
                }

                if ($request->dateRange) {
                    SafariDate::where('safari_id', $safari->id)->delete();
                    SeasonalPricing::where('safari_id', $safari->id)->delete();

                    foreach ($request->dateRange as $dateRange) {
                        // --- SafariDate save ---
                        $seasons = [
                            'low' => [
                                'available' => $dateRange['lowSeasonAvailableDates'] ?? [],
                                'blocked' => $dateRange['lowSeasonBlockedDates'] ?? []
                            ],
                            'high' => [
                                'available' => $dateRange['highSeasonAvailableDates'] ?? [],
                                'blocked' => $dateRange['highSeasonBlockedDates'] ?? []
                            ]
                        ];

                        foreach ($seasons as $season => $dates) {
                            // Normalize single-date case → make it an array
                            $availableDates = is_array($dates['available'])
                                ? $dates['available']
                                : [$dates['available']];
                            $blockedDates = is_array($dates['blocked'])
                                ? $dates['blocked']
                                : [$dates['blocked']];

                            if (!empty($availableDates[0])) {
                                SafariDate::create([
                                    'safari_id' => $safari->id,
                                    'available_start_date' => Carbon::parse($availableDates[0])->toDateString(),
                                    'available_end_date' => isset($availableDates[1]) ? Carbon::parse($availableDates[1])->toDateString() : null,
                                    'blocked_start_date' => !empty($blockedDates[0]) ? Carbon::parse($blockedDates[0])->toDateString() : null,
                                    'blocked_end_date' => !empty($blockedDates[1]) ? Carbon::parse($blockedDates[1])->toDateString() : null,
                                ]);
                            }
                        }

                        // --- SeasonalPricing save ---
                        $lowAvailable = is_array($dateRange['lowSeasonAvailableDates'])
                            ? $dateRange['lowSeasonAvailableDates']
                            : [$dateRange['lowSeasonAvailableDates']];
                        $lowBlocked = is_array($dateRange['lowSeasonBlockedDates'])
                            ? $dateRange['lowSeasonBlockedDates']
                            : [$dateRange['lowSeasonBlockedDates']];

                        $highAvailable = is_array($dateRange['highSeasonAvailableDates'])
                            ? $dateRange['highSeasonAvailableDates']
                            : [$dateRange['highSeasonAvailableDates']];
                        $highBlocked = is_array($dateRange['highSeasonBlockedDates'])
                            ? $dateRange['highSeasonBlockedDates']
                            : [$dateRange['highSeasonBlockedDates']];

                        // Low Season
                        if (!empty($lowAvailable[0])) {
                            SeasonalPricing::create([
                                'safari_id' => $safari->id,
                                'season' => 'low',
                                'available_start_date' => Carbon::parse($lowAvailable[0])->toDateString(),
                                'available_end_date' => isset($lowAvailable[1]) ? Carbon::parse($lowAvailable[1])->toDateString() : null,
                                'blocked_start_date' => !empty($lowBlocked[0]) ? Carbon::parse($lowBlocked[0])->toDateString() : null,
                                'blocked_end_date' => !empty($lowBlocked[1]) ? Carbon::parse($lowBlocked[1])->toDateString() : null,
                                'adult_price' => $dateRange['lowSeasonAdultPrice'] ?? 0,
                                'child_price' => $dateRange['lowSeasonChildPrice'] ?? 0,
                            ]);
                        }

                        // High Season
                        if (!empty($highAvailable[0])) {
                            SeasonalPricing::create([
                                'safari_id' => $safari->id,
                                'season' => 'high',
                                'available_start_date' => Carbon::parse($highAvailable[0])->toDateString(),
                                'available_end_date' => isset($highAvailable[1]) ? Carbon::parse($highAvailable[1])->toDateString() : null,
                                'blocked_start_date' => !empty($highBlocked[0]) ? Carbon::parse($highBlocked[0])->toDateString() : null,
                                'blocked_end_date' => !empty($highBlocked[1]) ? Carbon::parse($highBlocked[1])->toDateString() : null,
                                'adult_price' => $dateRange['highSeasonAdultPrice'] ?? 0,
                                'child_price' => $dateRange['highSeasonChildPrice'] ?? 0,
                            ]);
                        }
                    }
                }

                /** Save safari type */
                if ($request->safariType) {
                    $incomingIds = $request->safariType;
                    CreateSafariType::where('safari_id', $safari->id)->whereNotIn('safari_type_id', $incomingIds)->delete();
                    foreach ($incomingIds as $safariTypeId) {
                        CreateSafariType::updateOrCreate(
                            ['safari_id' => $safari->id, 'safari_type_id' => $safariTypeId],
                            []
                        );
                    }
                }

                /** Save activity */
                if ($request->activities) {
                    $incomingActivities = $request->activities;
                    SafariActivity::where('safari_id', $safari->id)->whereNotIn('activity_id', $incomingActivities)->delete();
                    foreach ($incomingActivities as $activityId) {
                        SafariActivity::updateOrCreate(
                            ['safari_id' => $safari->id, 'activity_id' => $activityId],
                            []
                        );
                    }
                }

                /** Save national park & reserve */
                if ($request->national_parks) {
                    $incomingParks = $request->national_parks;
                    SafariNationalParkReserve::where('safari_id', $safari->id)->whereNotIn('national_park_reserve_id', $incomingParks)->delete();
                    foreach ($incomingParks as $parkId) {
                        SafariNationalParkReserve::updateOrCreate(
                            ['safari_id' => $safari->id, 'national_park_reserve_id' => $parkId],
                            []
                        );
                    }
                }

                /** Save day by day */
                if ($request->days) {
                    $existingJourneys = $safari->journeys()->with('journey_images')->get();
                    $processedJourneyIds = [];
                    
                    foreach ($request->days as $index => $day) {
                        $safariJourney = isset($existingJourneys[$index])
                            ? $existingJourneys[$index]
                            : new SafariJourney();

                        $highlights = [];
                        if (isset($day['animals'])) {
                            foreach ($day['animals'] as $animal) {
                                if (empty($animal['animal_id']) && empty($animal['description'])) {
                                    continue;
                                }
                                $highlights[] = [
                                    'animal_id' => (string) $animal['animal_id'],
                                    'description' => $animal['description'] ?? '',
                                ];
                            }
                        }

                        $safariJourney->safari_id = $safari->id;
                        $safariJourney->heading = $day['heading'] ?? '';
                        $safariJourney->meal = $day['meals'] ?? '';
                        $safariJourney->subtext = $day['subtext'] ?? '';
                        $safariJourney->today_highlights = $day['today_highlights'] ?? '';
                        $safariJourney->wildlife_location = $day['wildlife_location'] ?? '';
                        $safariJourney->no_accommodation_included = $day['no_accommodation_included'] ?? false;
                        $safariJourney->accommodation = $day['accommodation'] ?? '';
                        $safariJourney->wildlife_highlights = !empty($highlights) ? json_encode($highlights) : null;
                        $safariJourney->save();
                        
                        $processedJourneyIds[] = $safariJourney->id;

                        $existingImages = $safariJourney->journey_images;
                        $submittedImages = $day['image'] ?? [];
                        $submittedPaths = [];

                        foreach ($submittedImages as $image) {
                            if (!empty($image['file'])) {
                                $imagePath = ImageHelper::customSaveImage($image['file'], 'safari_journey_images');
                                SafariJourneyImages::create([
                                    'safari_journey_id' => $safariJourney->id,
                                    'image' => $imagePath,
                                ]);
                                $submittedPaths[] = $imagePath;
                            } elseif (!empty($image['preview']) && str_contains($image['preview'], '/storage/')) {
                                $url = $image['preview'];
                                $path = parse_url($url, PHP_URL_PATH);
                                $relativePath = ltrim($path, '/');
                                $submittedPaths[] = $relativePath;

                                if (!$existingImages->contains('image', $relativePath)) {
                                    SafariJourneyImages::create([
                                        'safari_journey_id' => $safariJourney->id,
                                        'image' => $relativePath,
                                    ]);
                                }
                            }
                        }

                        // Delete images that were removed
                        foreach ($existingImages as $img) {
                            if (!in_array($img->image, $submittedPaths)) {
                                if (file_exists($img->image)) {
                                    @unlink($img->image);
                                }
                                $img->delete();
                            }
                        }
                    }
                    
                    // Delete journeys that were removed
                    $journeysToDelete = $existingJourneys->whereNotIn('id', $processedJourneyIds);
                    foreach ($journeysToDelete as $journey) {
                        // Delete associated images first
                        foreach ($journey->journey_images as $img) {
                            if (file_exists($img->image)) {
                                @unlink($img->image);
                            }
                            $img->delete();
                        }
                        $journey->delete();
                    }
                }

                /** Save wild life highlight */
                $processedIds = [];

                if ($request->has('animals') && is_array($request->animals)) {
                    $incomingIds = array_column($request->animals, 'animal_id');
                    SafariWildlifeSight::where('safari_id', $safari->id)->whereNotIn('animal_id', $incomingIds)->delete();
                    foreach ($incomingIds as $safariTypeId) {
                        SafariWildlifeSight::updateOrCreate(['safari_id' => $safari->id, 'animal_id' => $safariTypeId], [
                            'probability' => $request->animals[array_search($safariTypeId, $incomingIds)]['probability'] ?? null,
                            'note' => $request->animals[array_search($safariTypeId, $incomingIds)]['description'] ?? null,
                        ]);
                    }
                }

                /** Save things To Knows */
                $incomingItems = $request->thingsToKnows ?? [];

                foreach ($incomingItems as $item) {
                    if (empty($item['heading']) && empty($item['description'])) {
                        continue;
                    }
                    if (!empty($item['id'])) {
                        $model = SafariThingsToKnow::where('id', $item['id'])
                            ->where('safari_id', $safari->id)
                            ->first();

                        if ($model) {
                            $model->update([
                                'heading' => $item['heading'],
                                'description' => $item['description'],
                            ]);
                            $processedIds[] = $model->id;
                        }
                    } else {
                        $model = SafariThingsToKnow::create([
                            'safari_id' => $safari->id,
                            'heading' => $item['heading'],
                            'description' => $item['description'],

                        ]);
                        $processedIds[] = $model->id;
                    }
                }
                SafariThingsToKnow::where('safari_id', $safari->id)
                    ->whereNotIn('id', $processedIds)
                    ->delete();

                /** Save Discount */
                if ($request->discounts) {
                    SafariGroupPricing::where('safari_id', $safari->id)->delete();
                    foreach ($request->discounts as $item) {
                        if (isset($item['id'])) {
                            SafariGroupPricing::create([
                                'safari_id' => $safari->id,
                                'person_type' => $item['person_type'],
                                'count' => $item['count'] ?? 0,
                                'season' => $item['season'],
                                'net_price' => $item['net_price'] ?? 0
                            ]);
                        } else {
                            if ($item['person_type'] == null) {
                                continue;
                            }
                            SafariGroupPricing::create([
                                'safari_id' => $safari->id,
                                'person_type' => $item['person_type'],
                                'count' => $item['count'] ?? 0,
                                'season' => $item['season'],
                                'net_price' => $item['net_price'] ?? 0
                            ]);
                        }
                    }
                }

                /** Save Gallery image */
                if ($request->gallery_images) {
                    foreach ($request->gallery_images as $image) {
                        $final_image_url = ImageHelper::customSaveImage($image['file'], 'safari_gallery_images');
                        $safariImage = new SafariGallery();
                        $safariImage->safari_id = $safari->id;
                        $safariImage->files = $final_image_url;
                        $safariImage->type = 'image';
                        $safariImage->save();
                    }
                }

                /** remove gallery image */
                if ($request->removeGalleryImageIds) {
                    $safariImages = SafariGallery::whereIn('id', $request->removeGalleryImageIds)->get();
                    foreach ($safariImages as $safariImage) {
                        if (file_exists($safariImage->files)) {
                            @unlink($safariImage->files);
                        }
                        $safariImage->delete();
                    }
                }

                /** Save thumbnail */

                if ($request->hasFile('thumbnail')) {
                    if (file_exists($safari->thumbnail)) {
                        @unlink($safari->thumbnail);
                    }

                    $final_image_url = ImageHelper::customSaveImage($request->file('thumbnail'), 'safari_thumbnail');
                    $safari->thumbnail = $final_image_url;
                    $safari->save();
                }

                /** Save operator additional details */
                $user->business_name = $request->company_name;
                $user->about_operation = $request->operator_description;
                $user->business_type = $request->operator_type;
                $user->address = $request->address;
                $user->why_choose_us = $request->why_choose_us;
                $user->business_years = $request->business_years;
                $user->save();

                /** Save operator logo */
                if ($request->hasFile('operator_logo')) {
                    if (file_exists($user->business_logo)) {
                        @unlink($user->business_logo);
                    }
                    $final_image_url = ImageHelper::customSaveImage($request->file('operator_logo'), 'safari_operator_logo');
                    $user->business_logo = $final_image_url;
                    $user->save();
                }
                DB::commit();

                return redirect()->route('frontend.safari-manage-listing')->with('success', 'Safari updated successfully.');
            } catch (\Exception $e) {
                Log::error("File: " . $e->getFile() . " Line: " . $e->getLine() . " - " . $e->getMessage() . "\n" . $e->getTraceAsString());
                DB::rollBack();
                abort(500);
            }
        }

        $regions = Region::get(['id', 'name'])
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->name];
            });

        $wildlives = WildLife::get(['id', 'name'])
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => trim($item->name)
                ];
            });

        $allNationalParkReserves = NationalParkAndReserves::where('status', true)->get(['id', 'name'])->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        });

        $safariTypes = SafariType::get(['id', 'title']);
        $activities = Activity::get(['id', 'title', 'icon']);

        $safari->days = $safari->journeys->map(function ($journey) {
            return [
                'heading' => $journey->heading,
                'subtext' => $journey->subtext,
                'no_accommodation_included' => $journey->no_accommodation_included == 1 ? true : false,
                'accommodation' => $journey->accommodation,
                'today_highlights' => $journey->today_highlights,
                'meals' => $journey->meal,
                'wildlife_location' => $journey->wildlife_location,
                'image' => $journey->journey_images->map(function ($img) {
                    return [
                        'preview' => $img->full_photo_url,
                    ];
                }),
                'animals' => json_decode($journey->wildlife_highlights)

            ];
        });

        $safari->discount = $safari->group_pricing->map(function ($item) {
            return [
                'person_type' => $item->person_type,
                'count' => $item->count,
                'season' => $item->season,
                'net_price' => $item->net_price,
            ];
        });

        $safari->gallery = $safari->gallery->map(function ($item) {
            return [
                'preview' => $item->full_image_url,
            ];
        });

        $safari->things_to_know = $safari->things_to_know->map(function ($item) {
            return [
                'heading' => $item->heading,
                'description' => $item->description,
            ];
        });
        
        // Add national parks data
        $safari->national_parks = $safari->safari_parks->pluck('national_park_reserve_id')->toArray();
        
        $safariAvailableTags = AvailableTag::where('show_in_frontend', 1)->get(['id', 'name'])->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->name
            ];
        });
        return Inertia::render('Frontend/Auth/create-new-listing', compact('regions', 'wildlives', 'allNationalParkReserves', 'safariTypes', 'activities', 'safari', 'safariAvailableTags'));
    }

    public function deleteListing($safari_id)
    {
        $safari = Safari::where('id', $safari_id)->first();
        if (!$safari) {
            return redirect()->route('frontend.safari-manage-listing')->with('error', 'Safari not found.');
        }
        $safariImages = SafariGallery::where('safari_id', $safari_id)->get();
        $safariJourneys = SafariJourney::where('safari_id', $safari_id)->get();
        foreach ($safariJourneys as $safariJourney) {
            $safariJourneyImages = SafariJourneyImages::where('safari_journey_id', $safariJourney->id)->get();
            foreach ($safariJourneyImages as $safariJourneyImage) {
                if (file_exists($safariJourneyImage->image)) {
                    @unlink($safariJourneyImage->image);
                }
                $safariJourneyImage->delete();
            }
            $safariJourney->delete();
        }

        foreach ($safariImages as $safariImage) {
            if (file_exists($safariImage->files)) {
                @unlink($safariImage->files);
            }
            $safariImage->delete();
        }
        if (file_exists($safari->thumbnail)) {
            @unlink($safari->thumbnail);
        }
        $safari->delete();
        return redirect()->route('frontend.safari-manage-listing')->with('success', 'Safari deleted successfully.');
    }

    public function getCountries(Request $request)
    {
        $countries = CountryGuide::where('region', $request->region_id)->get(['id', 'name']);
        return response()->json($countries);
    }

    public function operatorSafariDetails($slug)
    {
        $today = Carbon::now()->toDateString();
        $safari = Safari::where('slug', $slug)
            ->with([
                'gallery',
                'wild_lives.animal',
                'journeys' => function ($q) {
                    $q->with(['journey_images', 'location' => function ($q) {
                        $q->select('id', 'name');
                    }]);
                },
                'things_to_know',
                'user',
                'safariReviews.user'
            ])
            ->addSelect(['total_price' => DB::table('seasonal_pricings')
                ->select('adult_price')
                ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                ->where('seasonal_pricings.season', 'LOW')
                ->orderBy('seasonal_pricings.id', 'asc')
                ->limit(1)])
            ->withAvg('safariReviews', 'rating')
            ->where('status', 1)
            ->first();

        foreach ($safari->journeys as $journey) {
            if (!empty($journey->wildlife_highlights)) {
                $highlights = collect(json_decode($journey->wildlife_highlights, true))
                    ->map(function ($highlight) {
                        $animal = WildLife::find($highlight['animal_id']);
                        return [
                            'animal_id'   => $highlight['animal_id'],
                            'animal_name' => $animal ? $animal->name : null,
                            'description' => $highlight['description'] ?? null,
                            'image'       => $animal ? $animal->full_thumbnail_url : null
                        ];
                    });
                $journey->wildlife_highlights = $highlights;
            }
        }

        foreach ($safari->wild_lives as $wildlife) {
            $animal = WildLife::find($wildlife->animal_id);

            if ($animal) {
                $wildlife->animal = $animal->toArray();

                $level = strtolower($wildlife->probability ?? 'low');
                switch ($level) {
                    case 'high':
                        $wildlife->probability_label = 90;
                        break;
                    case 'medium':
                        $wildlife->probability_label = 60;
                        break;
                    case 'low':
                    default:
                        $wildlife->probability_label = 20;
                        break;
                }
            } else {
                $wildlife->animal = null;
                $wildlife->probability_label = 0;
            }
        }

        $operatorDetail = User::where('id', $safari->user_id)
            ->first();

        $faqs = SafariThingsToKnow::where('safari_id', $safari->id)->get();

        return Inertia::render('Frontend/Auth/operator-safari-details', compact('safari', 'operatorDetail', 'faqs'));
    }

    public function getNationalParkLocations(Request $request)
    {
        $allNationalParkReserves = NationalParkAndReserves::where('id', $request->park_id)->first(['id', 'lat', 'location', 'long']);
        return response()->json($allNationalParkReserves);
    }


    public function duplicateSafari(Request $request)
    {
        DB::beginTransaction();
        try {
            $originalSafari = Safari::where('id', $request->safari_id)
                ->with(
                    'create_safari_type',
                    'activity',
                    'journeys.journey_images',
                    'group_pricing',
                    'things_to_know',
                    'discount',
                    'gallery',
                    'wild_lives',
                    'dateRange',
                    'safari_parks',
                    'seasonal_pricings'
                )
                ->first();

            $newSafari = $originalSafari->replicate();
            $newSafari->title = $originalSafari->title . ' (Copy)';
            $newSafari->created_at = now();
            $newSafari->updated_at = now();
            
            // Handle thumbnail duplication with null check
            if ($originalSafari->thumbnail) {
                $final_image_url = ImageHelper::customSaveDuplicateImage($originalSafari->thumbnail, 'safari_thumbnail', $newSafari->id);
                $newSafari->thumbnail = $final_image_url;
            }
            $newSafari->save();

            // Duplicate safari dates
            foreach ($originalSafari->dateRange as $date) {
                $newSafari->dateRange()->create([
                    'available_start_date' => $date->available_start_date,
                    'available_end_date' => $date->available_end_date,
                    'blocked_start_date' => $date->blocked_start_date,
                    'blocked_end_date' => $date->blocked_end_date
                ]);
            }

            // Duplicate safari types
            foreach ($originalSafari->create_safari_type as $type) {
                $newSafari->create_safari_type()->create([
                    'safari_type_id' => $type->safari_type_id
                ]);
            }

            // Duplicate tags
            if ($originalSafari->tags && $originalSafari->tags->isNotEmpty()) {
                $tagIds = $originalSafari->tags->pluck('id')->toArray();
                $newSafari->tags()->attach($tagIds);
            }

            // Duplicate national parks
            foreach ($originalSafari->safari_parks as $park) {
                $newSafari->safari_parks()->create([
                    'national_park_reserve_id' => $park->national_park_reserve_id
                ]);
            }

            // Duplicate seasonal pricing
            foreach ($originalSafari->seasonal_pricings as $pricing) {
                $newSafari->seasonal_pricings()->create([
                    'season' => $pricing->season,
                    'available_start_date' => $pricing->available_start_date,
                    'available_end_date' => $pricing->available_end_date,
                    'blocked_start_date' => $pricing->blocked_start_date,
                    'blocked_end_date' => $pricing->blocked_end_date,
                    'adult_price' => $pricing->adult_price,
                    'child_price' => $pricing->child_price
                ]);
            }

            //Duplicate Activities
            $activityIds = $originalSafari->activity->pluck('id')->toArray();
            $newSafari->activity()->attach($activityIds);

            //duplicate group pricing (Tiered Pricing/Discount)
            if ($originalSafari->group_pricing && $originalSafari->group_pricing->isNotEmpty()) {
                foreach ($originalSafari->group_pricing as $pricing) {
                    $newSafari->group_pricing()->create([
                        'person_type' => $pricing->person_type,
                        'count' => $pricing->count,
                        'season' => $pricing->season,
                        'net_price' => $pricing->net_price
                    ]);
                }
            }

            //duplicate things to know
            if ($originalSafari->things_to_know && $originalSafari->things_to_know->isNotEmpty()) {
                foreach ($originalSafari->things_to_know as $thing) {
                    $newSafari->things_to_know()->create($thing->only(['heading', 'description']));
                }
            }

            //duplicate discounts
            if ($originalSafari->discount && $originalSafari->discount->isNotEmpty()) {
                foreach ($originalSafari->discount as $discount) {
                    $newSafari->discount()->create([
                        'person' => $discount->person,
                        'count' => $discount->count,
                        'discount_type' => $discount->discount_type,
                        'discount' => $discount->discount
                    ]);
                }
            }

            //duplicate jouneys and images
            if ($originalSafari->journeys && $originalSafari->journeys->isNotEmpty()) {
                foreach ($originalSafari->journeys as $journey) {
                    $newJourney = $newSafari->journeys()->create([
                        'heading' => $journey->heading,
                        'subtext' => $journey->subtext,
                        'accommodation' => $journey->accommodation,
                        'no_accommodation_included' => $journey->no_accommodation_included,
                        'meal' => $journey->meal,
                        'wildlife_location' => $journey->wildlife_location,
                        'wildlife_highlights' => $journey->wildlife_highlights,
                        'today_highlights' => $journey->today_highlights
                    ]);

                    foreach ($journey->journey_images as $img) {
                        $final_image_url = ImageHelper::customSaveDuplicateImage($img->image, 'safari_journey_images', $newJourney->id);
                        if ($final_image_url) {
                            $safariImage = new SafariJourneyImages();
                            $safariImage->safari_journey_id = $newJourney->id;
                            $safariImage->image = $final_image_url;
                            $safariImage->save();
                        }
                    }
                }
            }
            //duplicate gallery images
            if ($originalSafari->gallery && $originalSafari->gallery->isNotEmpty()) {
                foreach ($originalSafari->gallery as $img) {
                    $final_image_url = ImageHelper::customSaveDuplicateImage($img->files, 'safari_gallery_images', $newSafari->id);
                    if ($final_image_url) {
                        $safariImage = new SafariGallery();
                        $safariImage->safari_id = $newSafari->id;
                        $safariImage->files = $final_image_url;
                        $safariImage->type = 'image';
                        $safariImage->save();
                    }
                }
            }
            DB::commit();
            return redirect()->route('frontend.safari-manage-listing')->with('success', 'Safari duplicated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: " . $e->getFile() . " Line: " . $e->getLine() . " - " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
