<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CountryGuide;
use App\Models\FavouriteSafari;
use App\Models\Member;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Safari;
use App\Models\SafariBooking;
use App\Models\SafariReview;
use App\Models\OperatorReview;
use App\Models\SafariThingsToKnow;
use App\Models\SafariType;
use App\Models\Setting;
use App\Models\User;
use App\Models\WildLife;
use App\Notifications\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class UserController extends Controller
{
    public function notification()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $notifications->getCollection()->transform(function ($notification) {
            $notification->sender = User::find($notification->data['sender']);
            return $notification;
        });

        Auth::user()->unreadNotifications->markAsRead();


        return Inertia::render('Frontend/Auth/notification', compact('notifications'));
    }

    public function readCountNotification()
    {
        $unreadCount = Auth::user()->unreadNotifications()->count();
        return response()->json(['unread_count' => $unreadCount]);
    }


    public function deleteNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
            session()->flash('success', 'Notification deleted successfully.');
        } else {
            session()->flash('error', 'Notification not found.');
        }

        return redirect()->back();
    }


    public function myTrips(Request $request)
    {
        $query = SafariBooking::where('traveler_id', Auth::id())
            ->with('safari', 'safari.country');

        if ($request->filled('day_duration')) {
            if ($request->day_duration == 'Last 7 days') {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            } elseif ($request->day_duration == 'Last 30 days') {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            } elseif ($request->day_duration == 'Last 2 Months') {
                $query->where('created_at', '>=', Carbon::now()->subMonths(2));
            }
        }
        if ($request->filled('location')) {
            $query->whereHas('safari', function ($q) use ($request) {
                $q->where('country_id', $request->location);
            });
        }
        if ($request->filled('safari_type')) {
            $query->whereHas('safari.create_safari_type', function ($q) use ($request) {
                $q->where('safari_type_id', $request->safari_type);
            });
        }

        $bookings = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalBookingCount = (clone $query)->count();
        $countries = CountryGuide::where('status', 1)->get();
        $safariTypes = SafariType::get()->mapWithKeys(function ($item) {
            return [$item->id => $item->title];
        });

        return Inertia::render('Frontend/Auth/my-trips', [
            'bookings' => $bookings,
            'totalBookingCount' => $totalBookingCount,
            'countries' => $countries,
            'safariTypes' => $safariTypes
        ]);
    }

    public function savedSafaris(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $safaris = Safari::with('create_safari_type.type', 'country', 'seasonal_pricings')
            ->whereHas('favourite', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->addSelect(['total_price' => DB::table('seasonal_pricings')
                ->select('adult_price')
                ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                ->where('seasonal_pricings.season', 'LOW')
                ->orderBy('seasonal_pricings.id', 'asc') // or date if you want first chronologically
                ->limit(1)])
            ->withAvg('safariReviews', 'rating')
            ->where('status', 1)
            ->where('is_approved', 1)
            ->orderBy('id', 'desc')
            ->paginate(8)
            ->withQueryString();
        return Inertia::render('Frontend/Auth/saved-safaris', compact('safaris'));
    }

    public function travellerSafariDetails($slug)
    {
        try {
            if ($slug) {
                $today = Carbon::now()->toDateString();
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
                        ->orderBy('seasonal_pricings.id', 'asc') // or date if you want first chronologically
                        ->limit(1)])
                    ->withAvg('safariReviews', 'rating')
                    ->where('status', 1)
                    ->where('is_approved', 1)
                    ->first();

                $similarSafaris =  Safari::with('create_safari_type.type', 'country', 'seasonal_pricings')
                    ->where('id', '!=', $safari->id)
                    ->addSelect(['total_price' => DB::table('seasonal_pricings')
                        ->select('adult_price')
                        ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                        ->where('seasonal_pricings.season', 'LOW')
                        ->orderBy('seasonal_pricings.id', 'asc') // or date if you want first chronologically
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
                $operatorId = $safari->user_id; // or an array if multiple operators
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
                            // After pairing, reset
                            $chatRooms[$roomId]['last_user_message_time'] = null;
                        }
                    }
                }

                if (!is_null($minResponseTime)) {
                    // Just assign seconds directly
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
                $faqs = SafariThingsToKnow::where('safari_id', $safari->id)->get();
                $travellerReview = SafariReview::where('safari_id', $safari->id)->where('user_id', Auth::id())->first();

                $totalSlotsPerDay = $safari->total_slots;
                $bookings = SafariBooking::select('check_in_date')
                    ->where('safari_id', $safari->id)
                    ->get()
                    ->groupBy('check_in_date');

                $fullyBookedDates = [];

                foreach ($bookings as $date => $bookingGroup) {
                    if ($bookingGroup->count() >= $totalSlotsPerDay) {
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
                $setting = Setting::first();

                return Inertia::render('Frontend/Auth/user-safari-details', ['safari' => $safari, 'similarSafaris' => $similarSafaris, 'operatorDetail' => $operatorDetail, 'faqs' => $faqs, 'travellerReview' => $travellerReview, 'fullyBookedDates' => $fullyBookedDates, 'userBooking' => $userBooking, 'canBook' => $canBook,'setting' => $setting]);
            }
            abort(500);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function payments()
    {
        $payments = Payment::where('traveler_id', Auth::id())
            ->with('traveler', 'safariBooking', 'safariBooking.safari')
            ->paginate(8);
        return Inertia::render('Frontend/Auth/payments', compact('payments'));
    }

    public function toggleFavourite(Request $request)
    {
        try {
            $user = User::find(Auth::id());
            if ($user && $user->role_name != 'TRAVELLER') {
                session()->flash('error', 'You are not a traveller.');
                return redirect()->back();
            }
            $safari = Safari::where('id', $request->safariId)->first();
            if (!$safari) {
                session()->flash('error', 'Safari not found.');
                return redirect()->back();
            }

            $favourite = FavouriteSafari::where('user_id', $user->id)->where('safari_id', $safari->id)->first();
            if ($favourite) {
                $favourite->delete();

                session()->flash('success', 'Safari removed from favourites.');
                return redirect()->back();
            }

            FavouriteSafari::create([
                'user_id' => $user->id,
                'safari_id' => $safari->id
            ]);

            /** Send Notification */
            $receiver = User::find($safari->user_id);
            $notifyDetails["type"] = 'Favourite';
            $notifyDetails["title"] = Auth::user()->first_name . ' Saved your Safari';
            $notifyDetails["body"] = Auth::user()->full_name . ' added your safari to their favourites.';
            $notifyDetails["safariId"] = $safari->id;
            $notifyDetails["sender"] = Auth::id();
            $notify_users = $receiver;
            Notification::send($notify_users, new SendNotification($notifyDetails));

            session()->flash('success', 'Safari added to favourites.');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . " File: " . $th->getFile() . " Line: " . $th->getLine() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function safariListingReview(Request $request)
    {
        $request->validate([
            'safari_id' => 'required|exists:safaris,id',
            'rating' => 'required|integer|min:1|max:5',
            'heading' => 'required|string|max:255',
            'details' => 'required|string',
        ], [
            'safari_id.required' => 'Safari ID is required.',
            'safari_id.exists' => 'Safari not found.',
            'rating.required' => 'Rate your trip is required.',
            'heading.required' => 'Write your review.',
            'details.required' => 'The Details is required.',
        ]);
        try {
            $user = User::find(Auth::id());
            if ($user && $user->role_name != 'TRAVELLER') {
                session()->flash('error', 'You are not a traveller.');
                return redirect()->back();
            }
            $safari = Safari::where('id', $request->safari_id)->first();

            $safariReview = SafariReview::where('user_id', $user->id)->where('safari_id', $safari->id)->first();
            if ($request->ratingId) {
                $safariReview = SafariReview::find($request->ratingId);
            } else {
                $safariReview = new SafariReview();
            }

            $safariReview->user_id = $user->id;
            $safariReview->safari_id = $safari->id;
            $safariReview->rating = $request->rating;
            $safariReview->heading = $request->heading;
            $safariReview->details = $request->details;
            $safariReview->save();
            session()->flash('success', 'Review submitted successfully.');
            return redirect()->route('frontend.traveller-safari-details', ['slug' => $safari->slug]);
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . " File: " . $th->getFile() . " Line: " . $th->getLine() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function tripReview(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'safari_id' => 'required|exists:safaris,id',
            'rating' => 'required|integer|min:1|max:5',
            'heading' => 'required|string|max:255',
            'details' => 'required|string',
        ], [
            'safari_id.required' => 'Safari ID is required.',
            'safari_id.exists' => 'Safari not found.',
            'rating.required' => 'Rate your trip is required.',
            'heading.required' => 'Write your review.',
            'details.required' => 'The Details is required.',
        ]);
        try {
            $user = User::find(Auth::id());
            if ($user && $user->role_name != 'TRAVELLER') {
                session()->flash('error', 'You are not a traveller.');
                return redirect()->back();
            }
            $safari = Safari::where('id', $request->safari_id)->first();

            $safariReview = SafariReview::where('user_id', $user->id)->where('safari_id', $safari->id)->first();
            if ($safariReview) {
                $safariReview->update([
                    'rating' => $request->rating,
                    'heading' => $request->heading,
                    'details' => $request->details
                ]);
            } else {
                SafariReview::create([
                    'user_id' => $user->id,
                    'safari_id' => $safari->id,
                    'rating' => $request->rating,
                    'heading' => $request->heading,
                    'details' => $request->details
                ]);
            }
            session()->flash('success', 'Review submitted successfully.');
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . " File: " . $th->getFile() . " Line: " . $th->getLine() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function allReviews()
    {
        $operatorsWithReviews = User::role('SAFARI_OPERATOR')
            ->whereHas('safaris.safariReviews')
            ->withCount(['safaris as total_safaris', 'safaris as reviewed_safaris' => function ($query) {
                $query->whereHas('safariReviews');
            }])
            ->select('id', 'first_name', 'last_name', 'email', 'phone', 'business_name', 'business_type', 'profile_photo_path')
            ->orderBy('business_name')
            ->paginate(8);

        return Inertia::render('Frontend/Auth/review-list', compact('operatorsWithReviews'));
    }

    public function viewReview($id)
    {
        $operator = User::role('SAFARI_OPERATOR')
            ->where('id', $id)
            ->select('id', 'first_name', 'last_name', 'email', 'phone', 'business_name', 'business_type', 'profile_photo_path', 'main_destination')
            ->first();

        if (!$operator) {
            return redirect()->route('frontend.all-reviews')->with('error', 'Operator not found.');
        }

        $operator->main_destination = $operator->main_destination ? json_decode($operator->main_destination) : [];

        $reviews = SafariReview::whereHas('safari', function ($q) use ($operator) {
            $q->where('user_id', $operator->id);
        })
            ->with([
                'user:id,first_name,last_name,email,profile_photo_path',
                'safari:id,title,slug,thumbnail'
            ])
            ->select('id', 'user_id', 'safari_id', 'rating', 'heading', 'details as review_text', 'created_at')
            ->orderBy('rating', 'desc')
            ->get()
            ->groupBy('safari_id');

        $operatorReviews = OperatorReview::where('operator_id', $operator->id)
            ->where('status', 1)
            ->select('id', 'rating', 'review_text', 'source', 'created_at')
            ->orderBy('rating', 'desc')
            ->get();

        return Inertia::render('Frontend/Auth/review-details', compact('operator', 'reviews', 'operatorReviews'));
    }
}
