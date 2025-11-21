<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $bookings = SafariBooking::query();

            if ($request->booking_id) {
                $bookings = $bookings->where('booking_id', 'LIKE', '%' . $request->booking_id . '%');
            }
            if ($request->traveler) {
                $bookings =  $bookings->whereHas('traveler', function ($query) use ($request) {
                    $query->WhereRaw(
                        "concat(first_name,' ', last_name) like '%" . trim(addslashes($request->traveler)) . "%' "
                    );
                });
            }
            if ($request->safari) {
                $bookings = $bookings->whereHas('safari', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->safari . '%');
                });
            }

            if ($request->price) {
                $bookings = $bookings->where('total_price', 'like', '%' . $request->price . '%');
            }

            if (isset($request->date)) {
                $bookings = $bookings->whereDate('created_at', $request->date);
            }

            if (isset($request->status)) {
                $bookings = $bookings->where('payment_status', $request->status);
            }

            if (isset($request->booking_status)) {
                $bookings = $bookings->where('status', $request->booking_status);
            }

            if ($request->fieldName && $request->shortBy) {
                $bookings->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $bookings = $bookings->with('safari', 'traveler')->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/booking/List', ['bookings' => $bookings]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function viewBooking(Request $request)
    {
        try {
            $booking = SafariBooking::with([
                'safari' => function ($query) {
                    $query->withAvg('safariReviews', 'rating')
                        ->with('country');
                },
                'traveler',
                'operator',
            ])->where('booking_id', $request->booking_id)->firstOrFail();

            return Inertia::render('Admin/booking/View', ['booking' => $booking]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
