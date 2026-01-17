<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AccomodationToStay;
use App\Mail\ContactUsSendMail;
use App\Models\Activity;
use App\Models\AvailableTag;
use App\Models\Banner;
use App\Models\CmsManagement;
use App\Models\Blog;
use App\Models\BottomBanner;
use App\Models\Category;
use App\Models\Cms;
use App\Models\Collection;
use App\Models\ContactInfo;
use App\Models\ContactUs;
use App\Models\CountryGuide;
use App\Models\Faq;
use App\Models\HelpAndSupport;
use App\Models\KeyExperience;
use App\Models\Member;
use App\Models\Message;
use App\Models\SafariType;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\NationalParkAndReserves;
use App\Models\PageMeta;
use App\Models\Region;
use App\Models\Safari;
use App\Models\SafariBooking;
use App\Models\Setting;
use App\Models\WebsiteRating;
use App\Models\WildLife;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $homeBanner = Banner::where('page_name', 'Homepage')->first();
        $cms = CmsManagement::whereIn('slug', ['what-is-tuskari', 'how-it-works', 'why-book-with-tuskari', 'trip-regions', 'operator-highlights'])
            ->get()
            ->keyBy('slug');
        $meta = PageMeta::where('page_name', 'Homepage')->first();
        $testimonial = Testimonial::where('status', true)->latest()->get();
        $bottomBanner = BottomBanner::where('slug', 'homepage')->first();
        $regions = Region::with('countryGuides')->where('status', 1)->get();

        $mostPopularDestinations = NationalParkAndReserves::where('status', 1)
            ->where('is_hidden', 0)
            ->with('country.region')
            ->where('is_most_popular', 1)
            ->orderBy('name', 'ASC')
            ->limit(8)
            ->get();

        $featuredSafaris = Safari::with('create_safari_type.type', 'country')
            ->addSelect(['total_price' => DB::table('seasonal_pricings')
                ->select('adult_price')
                ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                ->where('seasonal_pricings.season', 'LOW')
                ->orderBy('seasonal_pricings.id', 'asc')
                ->limit(1)])
            ->withAvg('safariReviews', 'rating')
            ->where('status', 1)
            ->where('is_approved', 1)
            ->where('is_featured', 1)
            ->where('is_draft', 0)
            ->latest()
            ->get();

        $countryGuides = CountryGuide::orderBy('name', 'ASC')
            ->with('parks:id,name,country_id')
            ->get()
            ->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'type' => 'country',
                    'parks' => $country->parks->map(function ($park) use ($country) {
                        return [
                            'id' => $park->id,
                            'name' => $park->name,
                            'type' => 'park',
                            'country_name' => $country->name,
                        ];
                    })
                ];
            });

        return Inertia::render('Frontend/index', ['homeBanner' => $homeBanner, 'cms' => $cms, 'testimonial' => $testimonial, 'bottomBanner' => $bottomBanner, 'destinations' => $mostPopularDestinations, 'featuredSafaris' => $featuredSafaris, 'countryGuides' => $countryGuides, 'regions' => $regions, 'meta' => $meta]);
    }

    public function safariListings(Request $request)
    {
        try {
            $query = Safari::with('create_safari_type.type', 'country', 'journeys', 'region')
                ->addSelect(['total_price' => DB::table('seasonal_pricings')
                    ->select('adult_price')
                    ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                    ->where('seasonal_pricings.season', 'LOW')
                    ->orderBy('seasonal_pricings.id', 'asc')
                    ->limit(1)])
                ->with('dateRange', 'seasonal_pricings')
                ->withAvg('safariReviews', 'rating')
                ->where('status', 1)
                ->where('is_approved', 1);

            $meta = PageMeta::where('page_name', 'Safari')->first();


            if ($request->has('location') && !empty($request->location)) {
                $locations = $request->location;
                $name = $locations['name'] ?? '';
                $type = $locations['type'] ?? '';
                if (!empty($name)) {
                    $query->where(function ($q) use ($name, $type) {
                        if ($type === 'country') {
                            $q->whereHas('country', fn($q2) => $q2->where('name', $name));
                        } elseif ($type === 'park') {
                            $q->whereHas('safari_parks.nationalParkReserve', fn($q2) => $q2->where('name', 'LIKE', "%{$name}%"));
                        } elseif ($type === 'region') {
                            $q->whereHas('region', fn($q2) => $q2->where('name', $name));
                        } else {
                            $q->whereHas('country', fn($q2) => $q2->where('name', $name))
                                ->orWhereHas('safari_parks.nationalParkReserve', fn($q3) => $q3->where('name', 'LIKE', "%{$name}%"));
                        }
                    });
                }
            }

            if ($request->has('travelers') && is_numeric($request->travelers)) {
                $travelers = (int) $request->travelers;
                if ($travelers > 0) {
                    $query->whereRaw('(no_of_adult + no_of_child) >= ?', [$travelers]);
                }
            }

            if ($request->collection_id) {
                $query->whereHas('collections', function ($q) use ($request) {
                    $q->where('collections.id', $request->collection_id);
                });
            }


            if ($request->sortByPrice == 'price_high') {
                $query->orderBy('low_season_adult_price', 'desc');
            }

            if ($request->sortByPrice == 'price_low') {
                $query->orderBy('low_season_adult_price', 'asc');
            }

            if (in_array($request->sortByRating, ['asc', 'desc'])) {
                $query->orderBy('safari_reviews_avg_rating', $request->sortByRating);
            }

            if ($request->filled('dateRange')) {
                $dateRange = $request->dateRange;
                if (!is_array($dateRange)) {
                    $dateRange = [$dateRange];
                }

                $startDate = $dateRange[0] ?? null;
                $endDate   = $dateRange[1] ?? null;

                if ($startDate && !$endDate) {
                    $endDate = $startDate;
                }

                if ($startDate && $endDate) {
                    $checkIn  = Carbon::parse($startDate)->startOfDay();
                    $checkOut = Carbon::parse($endDate)->endOfDay();

                    $query->whereHas('dateRange', function ($query) use ($checkIn, $checkOut) {
                        $query->where(function ($subQuery) use ($checkIn, $checkOut) {
                            $subQuery->whereDate('available_start_date', '<=', $checkIn)
                                ->whereDate('available_end_date', '>=', $checkOut);
                        });
                    });

                    $query->whereDoesntHave('dateRange', function ($query) use ($checkIn, $checkOut) {
                        $query->where(function ($subQuery) use ($checkIn, $checkOut) {
                            $subQuery->whereDate('blocked_start_date', '<=', $checkOut)
                                ->whereDate('blocked_end_date', '>=', $checkIn);
                        });
                    });
                }
            }

            if ($request->has('tripDurations') && is_array($request->tripDurations)) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->tripDurations as $duration) {
                        $duration = trim($duration);
                        if (Str::contains($duration, '-')) {
                            [$min, $max] = explode('-', $duration);
                            $min = is_numeric($min) ? (int) $min : null;
                            if (Str::endsWith($max, '+')) {
                                $max = (int) rtrim($max, '+');
                                if ($min !== null) {
                                    $q->orWhere('day_duration', '>=', $min);
                                } else {
                                    $q->orWhere('day_duration', '>=', $max);
                                }
                            } else {
                                $max = (int) $max;
                                if ($min !== null) {
                                    $q->orWhereBetween('day_duration', [$min, $max]);
                                }
                            }
                        } else {
                            $value = (int) $duration;
                            $q->orWhere('day_duration', $value);
                        }
                    }
                });
            }

            if ($request->filled('seasons') && is_array($request->seasons)) {
                $seasons = array_filter($request->seasons);
                if (!empty($seasons)) {
                    $query->where(function ($q) use ($seasons) {
                        foreach ($seasons as $season) {
                            $season = trim($season);
                            // Add commas around travel_season and season for exact match within comma-separated list
                            $q->orWhereRaw("CONCAT(',', REPLACE(travel_season, ' ', ''), ',') LIKE ?", ['%,' . str_replace(' ', '', $season) . ',%']);
                        }
                    });
                }
            }


            if ($request->filled('safariTypeIds') && is_array($request->safariTypeIds)) {
                $safariTypeIds = array_filter($request->safariTypeIds);

                if (!empty($safariTypeIds)) {
                    $query->whereHas('create_safari_type.type', function ($q) use ($safariTypeIds) {
                        $q->whereIn('id', $safariTypeIds);
                    });
                }
            }

            if ($request->filled('wildlifeHighlights') && is_array($request->wildlifeHighlights)) {
                $wildlifeHighlights = array_filter($request->wildlifeHighlights);

                if (!empty($wildlifeHighlights)) {
                    $query->whereHas('journeys', function ($q) use ($wildlifeHighlights) {
                        $q->where(function ($sub) use ($wildlifeHighlights) {
                            foreach ($wildlifeHighlights as $wildlifeId) {
                                $sub->orWhereJsonContains('wildlife_highlights', ['animal_id' => (string) $wildlifeId]);
                            }
                        });
                    });
                }
            }

            if ($request->filled('activities') && is_array($request->activities)) {
                $activityIds = array_filter($request->activities);
                if (!empty($activityIds)) {
                    $query->whereHas('activity', function ($q) use ($activityIds) {
                        $q->whereIn('activity_id', $activityIds);
                    });
                }
            }

            // normalize input (accept array OR comma-separated string)
            $landscapesInput = $request->landscapes ?? null;

            if ($landscapesInput) {
                $landscapes = is_array($landscapesInput)
                    ? $landscapesInput
                    : explode(',', $landscapesInput);

                $landscapes = array_values(array_filter(array_map('trim', $landscapes)));

                if (!empty($landscapes)) {
                    $query->where(function ($q) use ($landscapes) {
                        foreach ($landscapes as $landscape) {
                            $quotedPattern = '%"' . $landscape . '"%';
                            $plainPattern  = '%'  . $landscape . '%';
                            $q->orWhereRaw(
                                "(REPLACE(environment, '\\\\', '') LIKE ? OR REPLACE(environment, '\\\\', '') LIKE ?)",
                                [$quotedPattern, $plainPattern]
                            );
                        }
                    });
                }
            }

            if ($request->filled('activityLevel')) {
                $query->where('activity_level', $request->activityLevel);
            }

            if ($request->filled('numberOfAdults') && is_numeric($request->numberOfAdults)) {
                $query->where('no_of_adult', '>=', (int)$request->numberOfAdults);
            }
            if ($request->filled('numberOfChildren') && is_numeric($request->numberOfChildren)) {
                $query->where('no_of_child', '>=', (int)$request->numberOfChildren);
            }

            if ($request->filled('priceRange')) {
                $priceRange = array_filter($request->priceRange);

                if (count($priceRange) === 2) {
                    $query->whereBetween('low_season_adult_price', [(float) $priceRange[0], (float) $priceRange[1]]);
                }
            }

            if ($request->filled('availabilityTagId')) {
                $query->whereHas('tags', fn($q) => $q->where('tag_id', $request->availabilityTagId));
            }
            $safaris = $query
                ->latest()
                ->paginate(8)
                ->withQueryString();
            $safariTypes = SafariType::get();
            $experiences = KeyExperience::get(['id', 'title'])
                ->mapWithKeys(function ($item) {
                    return [$item->id => $item->title];
                });
            $activities = Activity::get();
            $countryGuides = CountryGuide::orderBy('name', 'ASC')
                ->with('parks:id,name,country_id')
                ->get()
                ->map(function ($country) {
                    return [
                        'id' => $country->id,
                        'name' => $country->name,
                        'type' => 'country',
                        'parks' => $country->parks->map(function ($park) use ($country) {
                            return [
                                'id' => $park->id,
                                'name' => $park->name,
                                'type' => 'park',
                                'country_name' => $country->name,
                            ];
                        })
                    ];
                });

            $collections = Collection::where('show_in_frontend', true)->get(['id', 'name']);
            $wildlives = WildLife::get(['id', 'name'])->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->name
                ];
            });
            $tags = AvailableTag::where('show_in_frontend', true)->get(['id', 'name']);

            return Inertia::render('Frontend/safari-listing', ['safaris' => $safaris, 'safariTypes' => $safariTypes, 'experiences' => $experiences, 'activities' => $activities, 'countryGuides' => $countryGuides, 'collections' => $collections, 'wildlives' => $wildlives, 'tags' => $tags, 'meta' => $meta]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function safariDetails($slug)
    {
        try {
            if ($slug) {
                $safari = Safari::where('slug', $slug)
                    ->with(['gallery', 'wild_lives.animal', 'seasonal_pricings', 'journeys' => function ($q) {
                        $q->with(['journey_images', 'location' => function ($q) {
                            $q->select('id', 'name');
                        }]);
                    }, 'things_to_know', 'user', 'safariReviews.user', 'country', 'dateRange'])
                    ->addSelect(['total_price' => DB::table('seasonal_pricings')
                        ->select('adult_price')
                        ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                        ->where('seasonal_pricings.season', 'LOW')
                        ->orderBy('seasonal_pricings.id', 'asc')
                        ->limit(1)])
                    ->withAvg('safariReviews', 'rating')
                    ->where('status', 1)
                    ->where('is_approved', 1)
                    ->first();

                $setting = Setting::first();

                $similarSafaris =  Safari::with('create_safari_type.type', 'country', 'seasonal_pricings')
                    ->where('id', '!=', $safari->id)
                    ->addSelect(['total_price' => DB::table('seasonal_pricings')
                        ->select('adult_price')
                        ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                        ->where('seasonal_pricings.season', 'LOW')
                        ->orderBy('seasonal_pricings.id', 'asc')
                        ->limit(1)])
                    ->withAvg('safariReviews', 'rating')
                    ->where('status', 1)
                    ->where('is_approved', 1)
                    ->where('safari_type_id', $safari->safari_type_id)
                    ->latest()
                    ->take(4)
                    ->get();

                $loggedInUserId = Auth::id();
                $members = Member::select('chat_room_id')->where('user_id', $loggedInUserId)->get();
                foreach ($members as $member) {
                    $otherMember = Member::with('chatRoom')
                        ->where('chat_room_id', $member->chat_room_id)->where('user_id', '!=', $loggedInUserId)->first();

                    if (isset($otherMember->user_id) && $otherMember->user_id == $safari->user_id) {
                        $safari->chat_room_id = $member->chat_room_id;
                    }
                }
                $operatorId = $safari->user_id;
                $allMessages = Message::orderBy('chat_room_id')
                    ->orderBy('created_at')
                    ->get()
                    ->toArray();
                $minResponseTime = null;
                $chatRooms = [];

                foreach ($allMessages as $msg) {
                    $roomId = $msg['chat_room_id'];

                    if (!isset($chatRooms[$roomId])) {
                        $chatRooms[$roomId] = [
                            'last_user_message_time' => '',
                        ];
                    }

                    if ($msg['user_id'] != $operatorId) {
                        $chatRooms[$roomId]['last_user_message_time'] = $msg['created_at'];
                    } else {
                        if ($chatRooms[$roomId]['last_user_message_time']) {
                            $userTime = strtotime($chatRooms[$roomId]['last_user_message_time']);
                            $operatorTime = strtotime($msg['created_at']);
                            $diffSeconds = $operatorTime - $userTime;
                            if ($diffSeconds > 0) {
                                if (is_null($minResponseTime) || $diffSeconds < $minResponseTime) {
                                    $minResponseTime = $diffSeconds;
                                }
                            }
                            $chatRooms[$roomId]['last_user_message_time'] = null;
                        }
                    }
                }

                if (!is_null($minResponseTime)) {
                    $safari->fastest_reply_time = $minResponseTime; // total seconds (int)
                }

                $safari->autoGenerateMessage = Auth::check() ? "New enquiry from " . Auth::user()->full_name . " about your safari: ‘{$safari->title}’." : '';
                if (!$safari) {
                    return redirect()->route('safari-listings')->with('error', 'Safari not found.');
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

                $perDateGroupLimit = $safari->per_date_group_limit;
                $bookings = SafariBooking::select('check_in_date')
                    ->where('safari_id', $safari->id)
                    ->get()
                    ->groupBy('check_in_date');

                $fullyBookedDates = [];

                foreach ($bookings as $date => $bookingGroup) {
                    if ($bookingGroup->count() >= $perDateGroupLimit) {
                        $fullyBookedDates[] = $date;
                    }
                }
                $userBooking = SafariBooking::where('safari_id', $safari->id)
                    ->where('traveler_id', Auth::id())
                    ->latest()
                    ->first();

                $canBook = true;
                if ($userBooking) {
                    if ($userBooking->status == 'CANCELLED') {
                        $canBook = true;
                    } elseif ($userBooking->check_out_date >= Carbon::now()->format('Y-m-d')) {
                        $canBook = $userBooking->status == 'COMPLETED';
                    } elseif ($userBooking->status == 'COMPLETED') {
                        $canBook = true;
                    } else {
                        $canBook = false;
                    }
                }
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
                return Inertia::render('Frontend/safari-details', ['safari' => $safari, 'similarSafaris' => $similarSafaris, 'operatorDetail' => $operatorDetail, 'fullyBookedDates' => $fullyBookedDates, 'userBooking' => $userBooking, 'canBook' => $canBook, 'setting' => $setting]);
            }
            // abort(500);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('frontend.safari-listings')->with('error', 'Safari not found.');
        }
    }

    public function story()
    {
        try {
            $cms = CmsManagement::whereIn('slug', ['what-is-tuskari', 'why-travel-with-us', 'why-tuskari-exists', 'operator-highlights'])
                ->get()
                ->keyBy('slug');
            $testimonials = Testimonial::with('user')->where('status', true)->latest()->get();

            return Inertia::render('Frontend/story', ['cms' => $cms, 'testimonials' => $testimonials]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function blog(Request $request)
    {
        try {
            $blogs = Blog::where('status', true)->paginate(6)->withQueryString();
            $blogCategories = Category::where('status', true)->get();
            $featureBlogs = Blog::where('status', true)->latest()->take(5)->get();
            $meta = PageMeta::where('page_name', 'Blog Listing')->first();
            return Inertia::render('Frontend/blog-listing', compact('blogs', 'blogCategories', 'featureBlogs', 'meta'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function blogByCategory($category)
    {
        try {
            $blogs = Blog::whereHas('category', function ($query) use ($category) {
                $query->where('title', urldecode($category));
            })->where('status', true)->paginate(6)->withQueryString();
            $blogCategories = Category::where('status', true)->get();
            $featureBlogs = Blog::where('status', true)->latest()->take(5)->get();
            $meta = PageMeta::where('page_name', 'Blog Listing')->first();
            return Inertia::render('Frontend/blog-listing', compact('blogs', 'blogCategories', 'featureBlogs', 'meta'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function blogDetails($title)
    {
        try {
            $blog = Blog::where('title', urldecode($title))->first();
            $meta = PageMeta::where('page_name', 'Blog Details')->first();
            if ($blog) {
                $blogCategories = Category::where('status', true)->get();
                $featureBlogs = Blog::where('status', true)->latest()->take(5)->get();
                return Inertia::render('Frontend/blog-details', compact('blog', 'blogCategories', 'featureBlogs', 'meta'));
            } else {
                return redirect()->route('frontend.blogs')->with('success', 'Blog not found.');
            }
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function contactUs(Request $request)
    {
        $contactInfo = ContactInfo::first();
        $faqs = Faq::where('active', true)->get();
        $faqChunks = $faqs->split(2);

        $faqPart1 = $faqChunks->get(0);
        $faqPart2 = $faqChunks->get(1);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'full_name' => 'required|string|max:50',
                'email' => 'required|email|max:255',
                'message' => 'required|string|max:1000'
            ]);

            ContactUs::create($request->all());
            $admin = User::role('SUPER-ADMIN')->first();
            Mail::to($admin->email)->queue(new ContactUsSendMail($validated));

            return redirect()->back()->with('success', 'Your message has been sent successfully.');
        }
        return Inertia::render('Frontend/contact-us', compact('contactInfo', 'faqPart1', 'faqPart2'));
    }

    public function countryGuideDetails($name)
    {
        try {
            $country = CountryGuide::where('name', 'LIKE', "%$name%")->first();
            if ($country) {
                $nationalParks = NationalParkAndReserves::with('country')
                    ->where('status', 1)
                    ->whereType('national_park')
                    ->where('country_id', $country->id)
                    ->latest()
                    ->limit(5)
                    ->get();
                $featuredSafaris = Safari::with('safariType', 'country')
                    ->where('country_id', $country->id)
                    ->addSelect(['total_price' => DB::table('seasonal_pricings')
                        ->select('adult_price')
                        ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                        ->where('seasonal_pricings.season', 'LOW')
                        ->orderBy('seasonal_pricings.id', 'asc')
                        ->limit(1)])
                    ->withAvg('safariReviews', 'rating')
                    ->where('status', 1)
                    ->where('is_approved', 1)
                    ->latest()
                    ->take(4)
                    ->get();
            }
            $meta = PageMeta::where('page_name', 'Country Guide')->first();

            return Inertia::render('Frontend/country-guide', [
                'nationalParks' => $nationalParks ?? [],
                'country' => $country,
                'featuredSafaris' => $featuredSafaris ?? [],
                'meta' => $meta
            ]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function countryGuide()
    {
        try {
            $countries = CountryGuide::select('id', 'name', 'subtitle', 'thumbnail')
                ->where('status', 1)
                ->latest()
                ->paginate(20)
                ->withQueryString();
            $meta = PageMeta::where('page_name', 'Country Guide')->first();

            return Inertia::render('Frontend/country-guides', [
                'countries' => $countries,
                'meta' => $meta
            ]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function parkReserves(Request $request)
    {
        try {
            $sort = $request->input('sort');
            $countryId = $request->input('countryId');
            $regionData = Region::with(['countryGuides:id,name,region_id'])
                ->get(['id', 'name'])
                ->map(function ($region) {
                    return [
                        'key' => $region->name,
                        'label' => $region->name,
                        'selectable' => false,
                        'children' => $region->countryGuides->map(fn($country) => [
                            'key' => $country->id,
                            'label' => $country->name,
                        ]),
                    ];
                });

            $query = NationalParkAndReserves::where('status', 1)
                ->when($sort === 'is_most_popular', fn($q) => $q->where('is_most_popular', true))
                ->when($sort === 'is_hidden_gem', fn($q) => $q->where('is_hidden_gem', true))
                ->when($countryId, fn($q) => $q->whereIn('country_id', $countryId))
                ->where('is_hidden', 0)
                ->latest()
                ->paginate(20)
                ->withQueryString();
            $meta = PageMeta::where('page_name', 'National Park')->first();

            return Inertia::render('Frontend/national-parks', [
                'parksReserves' => $query,
                'regionData' => $regionData,
                'meta' => $meta
            ]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function destinations(Request $request)
    {
        try {
            $query = NationalParkAndReserves::with('country')
                ->where('status', 1)
                ->where('is_hidden', false);

            if ($request->has('type')) {
                if ($request->type === 'national_park') {
                    $query->where('type', 'national_park');
                } elseif ($request->type === 'private_reserve') {
                    $query->where('type', 'private_reserve');
                }
            }

            $destinations = $query->latest()
                ->paginate(12)
                ->withQueryString();

            return Inertia::render('Frontend/destinations', [
                'destinations' => $destinations,
            ]);
        } catch (\Throwable $e) {
            Log::error(":: DESTINATIONS EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function privateReserve()
    {
        try {
            return Inertia::render('Frontend/private-reserve');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function safariTypes()
    {
        try {
            $safariTypes = SafariType::get();
            $footerBanner = BottomBanner::where('page_name', 'Safari Style')->first();
            $meta = PageMeta::where('page_name', 'Safari Style')->first();
            return Inertia::render('Frontend/safari-type', compact('safariTypes', 'footerBanner', 'meta'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function operators()
    {
        $banner = Banner::where('page_name', 'Operator')->first();
        $meta = PageMeta::where('page_name', 'Operator')->first();
        $cms = CmsManagement::whereIn('slug', ['why-join-tuskari', 'how-it-works', 'what-you-can-list', 'built-for-you', 'more-of-the-money-goes'])
            ->get()
            ->keyBy('slug');
        $faqs = Faq::where('active', true)->get();
        $faqChunks = $faqs->split(2);

        $faqPart1 = $faqChunks->get(0);
        $faqPart2 = $faqChunks->get(1);
        $footerBanner = BottomBanner::where('page_name', 'Operator')->first();

        return Inertia::render('Frontend/operator', ['banner' => $banner, 'cms' => $cms, 'faqPart1' => $faqPart1, 'faqPart2' => $faqPart2, 'footerBanner' => $footerBanner, 'meta' => $meta]);
    }

    public function getBanner(Request $request)
    {
        if (!$request->has('pageName') || empty($request->pageName)) {
            return response()->json([
                'message' => 'Parameter "pageName" is required.',
            ], 400);
        }

        $banner = Banner::where('page_name', $request->pageName)->first();

        return response()->json($banner);
    }
    public function getFooter()
    {
        $footerContent = CmsManagement::where('slug', 'footer')->first();
        $contactInfo = ContactInfo::first();

        return response()->json([
            'footerContent' => $footerContent,
            'contactInfo' => $contactInfo,
        ]);
    }

    public function parkDetails($name)
    {
        try {
            $park = NationalParkAndReserves::where('name', $name)->first();

            $featuredSafaris = Safari::with('safariType')
                ->addSelect(['total_price' => DB::table('seasonal_pricings')
                    ->select('adult_price')
                    ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                    ->where('seasonal_pricings.season', 'LOW')
                    ->orderBy('seasonal_pricings.id', 'asc') // or date if you want first chronologically
                    ->limit(1)])
                ->withAvg('safariReviews', 'rating')
                ->where('status', 1)
                ->where('is_approved', 1)
                ->latest()
                ->take(4)
                ->get();


            $accommodationIds = json_decode($park?->accomodation_ids) ?: [];
            $wildLifeIds = json_decode($park?->wild_lives_ids) ?: [];
            $selected_accommodations = AccomodationToStay::whereIn('id', $accommodationIds)->where('status', 1)->get();
            $selected_wild_lives = WildLife::whereIn('id', $wildLifeIds)->get();

            $faqs = Faq::where('active', true)->get();
            $faqChunks = $faqs->split(2); // returns a collection with 2 parts

            $faqPart1 = $faqChunks->get(0);
            $faqPart2 = $faqChunks->get(1);

            return Inertia::render('Frontend/national-park-details', ['park' => $park, 'accommodations' => $selected_accommodations, 'wild_lives' => $selected_wild_lives, 'featuredSafaris' => $featuredSafaris, 'faqPart1' => $faqPart1, 'faqPart2' => $faqPart2]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function termsAndConditions()
    {
        $cms = Cms::where('slug', 'terms-conditions')->first();
        return Inertia::render('Frontend/terms-and-conditions', ['cms' => $cms]);
    }

    public function privacyPolicy()
    {
        $cms = Cms::where('slug', 'privacy-policy')->first();
        return Inertia::render('Frontend/privacy-policy', ['cms' => $cms]);
    }
    public function helpAndSupport()
    {
        $faqs = HelpAndSupport::where('status', true)
            ->get()
            ->groupBy('tag')->toArray();
        $websiteRating = WebsiteRating::where('user_id', Auth::id())->first();

        return Inertia::render('Frontend/Auth/help-support', ['faqs' => $faqs, 'websiteRating' => $websiteRating]);
    }
    public function getHelpSupport(Request $request)
    {

        $support = HelpAndSupport::where('tag', $request->section)->where('status', true)->get();
        return response()->json($support);
    }
    public function genLink(Request $request)
    {

        $request->validate([
            'url' => ['required'],
            'title' => ['required'],
        ]);
        try {
            $shareButtons = \Share::page($request->url, $request->title)
                ->facebook()
                ->twitter()
                ->whatsapp()
                ->linkedIn()
                ->getRawLinks();
            return response()->json($shareButtons);
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return redirect()->back()->with('error', "Something is wrong");
        }
    }

    public function searchHelpSupport(Request $request)
    {
        $search = $request->input('searchQuery');

        $results = HelpAndSupport::where('question', 'like', "%$search%")
            ->orWhere('answer', 'like', "%$search%")
            ->get()
            ->groupBy('tag');
        return response()->json([
            'groupedSearchResults' => $results
        ]);
    }

    public function headerCountries()
    {
        $countries = CountryGuide::withCount('safaris')
            ->orderByDesc('safaris_count')
            ->take(6)
            ->get(['id', 'name', 'safaris_count']);
        return response()->json($countries);
    }

    public function headerNationalParks()
    {
        $parks = NationalParkAndReserves::whereHas('safari_parks.safari')
            ->withCount(['safari_parks as safari_count' => function ($q) {
                $q->whereHas('safari', function ($q2) {
                    $q2->where('is_approved', 1);
                });
            }])
            ->orderByDesc('safari_count')
            ->limit(6)
            ->where('is_hidden', false)
            ->get(['id', 'name', 'safari_count']);
        return response()->json($parks);
    }

    public function headerSafariType()
    {
        $types = SafariType::whereHas('create_safari_types.safari')
            ->withCount(['create_safari_types as safari_count' => function ($q) {
                $q->whereHas('safari', function ($q2) {
                    $q2->where('is_approved', 1);
                });
            }])
            ->orderByDesc('safari_count')
            ->take(6)
            ->get(['id', 'title']);
            
        return response()->json($types);
    }
    public function headerBlogCategories()
    {
        $blogCategories = Category::where('status', true)->has('blogs')->orderBy('title')->get();
        return response()->json($blogCategories);
    }

    public function getSettings()
    {
        $settings = Setting::first();
        return response()->json($settings);
    }

    public function faqs()
    {
        $faqs = Faq::where('active', true)->get();
        $faqChunks = $faqs->split(2);

        $faqPart1 = $faqChunks->get(0);
        $faqPart2 = $faqChunks->get(1);
        $meta = PageMeta::where('page_name', 'faq')->first();

        return Inertia::render('Frontend/Faq', ['faqPart1' => $faqPart1, 'faqPart2' => $faqPart2, 'meta' => $meta]);
    }

    public function ourStory()
    {
        $cms = Cms::where('slug', 'our-story')->first();
        $meta = PageMeta::where('page_name', 'Our Story')->first();

        return Inertia::render('Frontend/ourStory', ['cms' => $cms, 'meta' => $meta]);
    }

    public function howItWorks()
    {
        $cms = Cms::where('slug', 'how-it-works')->first();
        $meta = PageMeta::where('page_name', 'How It Works')->first();

        return Inertia::render('Frontend/howItWorks', ['cms' => $cms, 'meta' => $meta]);
    }

    public function whyItsDifferent()
    {
        $cms = Cms::where('slug', 'why-it-different')->first();
        $meta = PageMeta::where('page_name', 'Why It Different')->first();
        return Inertia::render('Frontend/whyItsDifferent', ['cms' => $cms, 'meta' => $meta]);
    }

    public function responsibleTravel()
    {
        $cms = Cms::where('slug', 'responsible-travel')->first();
        $meta = PageMeta::where('page_name', 'responsible-travel')->first();
        return Inertia::render('Frontend/responsibleTravel', ['cms' => $cms, 'meta' => $meta]);
    }
}
