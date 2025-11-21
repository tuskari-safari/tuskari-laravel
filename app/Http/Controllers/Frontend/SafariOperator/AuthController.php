<?php

namespace App\Http\Controllers\Frontend\SafariOperator;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CountryGuide;
use App\Models\OperatorBank;
use App\Models\OperatorReview;
use App\Models\Safari;
use App\Models\SafariBooking;
use App\Models\SafariReview;
use App\Models\SafariType;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use App\Rules\MatchOldPassword;
use App\Rules\NewOldPasswordNotSame;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function operatorDashboard()
    {
        $today = Carbon::now()->toDateString();
        $mySafaris = Safari::with('create_safari_type.type', 'country')
            ->where('user_id', Auth::id())
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
        $count['listingCount'] = Safari::where('status', 1)->where('is_approved', 1)->where('user_id', Auth::id())->count('id');
        $count['upcomingBookingCount'] = SafariBooking::where('operator_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->whereDate('check_in_date', '>', Carbon::today())
            ->count();
        $count['completedBookingCount'] = SafariBooking::where('operator_id', Auth::id())->where('status', 'COMPLETED')->count();
        $bookings = SafariBooking::with('safari', 'safari.country')->where('operator_id', Auth::id())->latest()->limit(8)->get();

        return Inertia::render('Frontend/Auth/operator-dashboard', ['mySafaris' => $mySafaris, 'count' => $count, 'bookings' => $bookings]);
    }


    public function operatorBookings(Request $request)
    {
        $today = Carbon::today();

        $bookingsQuery = SafariBooking::with([
            'safari' => function ($query) {
                $query->withAvg('safariReviews', 'rating');
            },
            'safari.activity',
            'traveler',
        ])
            ->where('operator_id', Auth::id())
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('safari', function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->location, function ($query) use ($request) {
                $query->whereHas('safari', function ($q) use ($request) {
                    $q->where('country_id', $request->location);
                });
            })
            ->when($request->safari_type, function ($query) use ($request) {
                $query->whereHas('safari.create_safari_type', function ($q) use ($request) {
                    $q->where('safari_type_id', $request->safari_type);
                });
            })
            ->when($request->day_duration, function ($query) use ($request) {
                if ($request->day_duration == 'Last 7 days') {
                    $query->where('created_at', '>=', Carbon::now()->subDays(7));
                } elseif ($request->day_duration == 'Last 30 days') {
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                } elseif ($request->day_duration == 'Last 2 Months') {
                    $query->where('created_at', '>=', Carbon::now()->subMonths(2));
                }
            })
            ->when($request->status, function ($query) use ($request, $today) {
                switch ($request->status) {
                    case 'completed':
                        $query->where('status', 'COMPLETED');
                        break;

                    case 'ongoing':
                        $query->whereDate('check_in_date', $today)
                            ->where('status', 'ACTIVE');
                        break;

                    case 'upcoming':
                        $query->whereDate('check_in_date', '>', $today)
                            ->whereIn('status', ['ACTIVE', 'PENDING']);
                        break;

                    case 'all':
                        break;

                    default:
                        $query->where('status', $request->status);
                        break;
                }
            });

        $bookings = $bookingsQuery->latest()->paginate(15);

        $totalBookingCount = (clone $bookingsQuery)->count();
        $countries = CountryGuide::where('status', 1)->get();
        $safariTypes = SafariType::get()->mapWithKeys(function ($item) {
            return [$item->id => $item->title];
        });

        return Inertia::render('Frontend/Auth/operator-bookings', [
            'bookings' => $bookings,
            'totalBookingCount' => $totalBookingCount,
            'countries' => $countries,
            'safariTypes' => $safariTypes
        ]);
    }

    public function operatorBookingStatusChange(Request $request)
    {
        $booking = SafariBooking::find($request->id);
        $booking->status = 'COMPLETED';
        $booking->completion_date = Carbon::now();
        $booking->save();
        session()->flash('success', 'Booking status successfully changed.');
        return redirect()->back();
    }


    public function operatorEarnings(Request $request)
    {
        $operatorBanks = OperatorBank::get();
        $this->updateWalletBalance();
        $wallet = Wallet::where('operator_id', Auth::id())->first();

        $earningsQuery = null;
        if ($wallet) {
            $earningsQuery = $wallet->transactions()->with('booking.traveler:id,first_name,last_name,profile_photo_path');

            if ($request->filter) {
                switch ($request->filter) {
                    case 'Last 7 days':
                        $earningsQuery->where('created_at', '>=', Carbon::now()->subDays(7));
                        break;
                    case 'Last 30 days':
                        $earningsQuery->where('created_at', '>=', Carbon::now()->subDays(30));
                        break;
                    case 'Last 2 Months':
                        $earningsQuery->where('created_at', '>=', Carbon::now()->subMonths(2));
                        break;
                }
            }
            $earnings = $earningsQuery->latest()->paginate(20);
        } else {
            $earnings = collect([]);
        }

        $withdrawalRequests = WithdrawalRequest::where('operator_id', Auth::id())
            ->with('operatorBank')
            ->latest()
            ->paginate(10);

        return Inertia::render('Frontend/Auth/operator-earnings', [
            'operatorBanks' => $operatorBanks,
            'wallet' => $wallet,
            'withdrawalRequests' => $withdrawalRequests,
            'earnings' => $earnings
        ]);
    }

    public function operatorMyProfile(Request $request)
    {
        $profile = User::where('id', Auth::id())->with('country')->first();
        $profile->main_destination = $profile->main_destination ? json_decode($profile->main_destination) : [];
        if ($request->isMethod('post')) {
            $request->validate([
                'full_name' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
                'email' =>  'required|max:255|regex:/(.+)@(.+)\.(.+)/i|email|unique:users,email,' . Auth::id(),
                'business_name' => 'required',
                'phone' => ['required', 'regex:/^\+?[0-9]{1,4}?[0-9]{6,14}$/', 'unique:users,phone,' . Auth::id(), 'max:15'],
                'country_id' => 'required|exists:countries,id',
                'country_code' => 'nullable',
                'main_destination' => 'nullable|array',
                'main_destination.*'   => 'string',
                'business_type' => 'required',
                'business_years' => 'required',
                'website_link' => 'nullable|url',
                'instagram_link' => 'nullable|url',
                'about_operation' => 'required|MAX:1000',
                'profilePhoto' => ['nullable', 'mimes:jpeg,png,jpg|max:10240']
            ]);
            if ($request->hasFile('profilePhoto')) {
                $file = $request->file('profilePhoto');
                $path = 'profile_photo';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $profile->profile_photo_path = $final_image_url;
            }
            $name = explode(' ', $request->full_name);
            $profile->first_name = $name[0];
            $profile->last_name = array_key_exists(1, $name) ? $name[1] : '';
            $profile->email = $request->email;
            $profile->business_name = $request->business_name;
            $profile->business_years = $request->business_years;
            $profile->phone = $request->phone;
            $profile->country_id = $request->country_id;
            $profile->main_destination = $request->main_destination ? json_encode($request->main_destination) : $profile->main_destination;
            $profile->country_code = $request->country_code;
            $profile->business_type = $request->business_type;
            $profile->website_link = $request->website_link;
            $profile->instagram_link = $request->instagram_link;
            $profile->about_operation = $request->about_operation;
            $profile->save();
            return redirect()->route('frontend.operator-my-profile')->with('success', 'Profile updated successfully');
        }

        $countries = Country::get(['id', 'name'])
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->name];
            });
        $countryCodes = Country::get(['id', 'name', 'phonecode'])
            ->map(function ($item) {
                return [
                    'label' => $item->name . ' (+' . $item->phonecode . ')',
                    'value' => $item->phonecode,
                ];
            })
            ->values();

        return Inertia::render('Frontend/Auth/operator-my-profile', compact('profile', 'countries', 'countryCodes'));
    }

    public function operatorChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:8', new NewOldPasswordNotSame($request->current_password)],
            'confirm_password' => ['required_with:new_password', 'same:new_password'],
        ]);

        $user = User::find(Auth::id());
        $user->password = $request->new_password;
        $user->save();
        return redirect()->route('frontend.operator-my-profile')->with('success', 'Password updated successfully');
    }

    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'registration_certificate' => 'nullable|mimes:pdf|max:2048',
            'tourism_operating_license' => 'nullable|mimes:pdf|max:2048',
            'insurance' => 'nullable|mimes:pdf|max:2048',
        ]);
        $user = User::find(Auth::id());
        if ($request->hasFile('registration_certificate')) {
            $file2 = $request->file('registration_certificate');
            $path2 = 'registration_certificate_pdf';
            $final_pdf_url = ImageHelper::customSaveImage($file2, $path2);
            $user->registration_certificate = $final_pdf_url;
        }
        if ($request->hasFile('tourism_operating_license')) {
            $file2 = $request->file('tourism_operating_license');
            $path2 = 'tourism_operating_license_pdf';
            $final_pdf_url = ImageHelper::customSaveImage($file2, $path2);
            $user->tourism_operating_license = $final_pdf_url;
        }
        if ($request->hasFile('insurance')) {
            $file2 = $request->file('insurance');
            $path2 = 'insurance_pdf';
            $final_pdf_url = ImageHelper::customSaveImage($file2, $path2);
            $user->insurance = $final_pdf_url;
        }
        $user->save();
        return redirect()->route('frontend.operator-my-profile')->with('success', 'Documents uploaded successfully');
    }

    public function operatorBookingDetails(Request $request)
    {
        $booking = SafariBooking::where('operator_id', Auth::id())->with([
            'safari' => function ($query) {
                $query->withAvg('safariReviews', 'rating');
            },
            'safari.activity',
            'operator',
            'traveler',
        ])->find($request->safariBookingId)->toArray();
        return response()->json($booking);
    }

    private function updateWalletBalance()
    {
        $bookings = SafariBooking::where('payment_status', 'confirmed')
            ->whereBetween('check_in_date', [
                Carbon::today()->addDay(),
                Carbon::today()->addDays(7)
            ])
            ->with('operator')
            ->get();
        foreach ($bookings as $booking) {
            $wallet = Wallet::where('operator_id', $booking->operator_id)->first();

            $existingTransaction = WalletTransaction::where('booking_id', $booking->id)
                ->where('status', 'completed')
                ->first();

            if (!$existingTransaction) {
                $wallet->settleFunds($booking->pay_to_operator);
                WalletTransaction::where('booking_id', $booking->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                    ]);
            }
        }
    }

    public function operatorAllReviews()
    {
        $operator = User::role('SAFARI_OPERATOR')
            ->where('id', Auth::id())
            ->select('id', 'first_name', 'last_name', 'email', 'phone', 'business_name', 'business_type', 'profile_photo_path', 'main_destination')
            ->first();

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

        return Inertia::render('Frontend/Auth/operator-review-details', compact('operator', 'reviews', 'operatorReviews'));
    }
}
