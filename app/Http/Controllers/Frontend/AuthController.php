<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Safari;
use App\Models\SafariBooking;
use App\Rules\MatchOldPassword;
use App\Rules\NewOldPasswordNotSame;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::now()->toDateString();
        $savedSafaris = Safari::with('create_safari_type.type', 'country')
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
            ->latest()
            ->limit(4)
            ->get();
        $latestBookings = SafariBooking::where('traveler_id', Auth::id())
            ->with('safari', 'safari.country')
            ->latest()
            ->limit(8)
            ->get();
        $count = [];
        $counts = SafariBooking::where('traveler_id', Auth::id())
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $count = [
            'active'    => $counts['ACTIVE'] ?? 0,
            'completed' => $counts['COMPLETED'] ?? 0,
            'cancelled' => $counts['CANCELLED'] ?? 0,
        ];
        return Inertia::render('Frontend/Auth/dashboard', compact('savedSafaris', 'latestBookings', 'count'));
    }

    public function travellerProfile(Request $request)
    {
        $profile = Auth::user();
        if ($request->isMethod('post')) {
            $request->validate([
                'full_name' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
                'email' =>  'required|max:255|regex:/(.+)@(.+)\.(.+)/i|email|unique:users,email,' . Auth::id(),
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
            $profile->save();
            return redirect()->route('frontend.traveller-profile')->with('success', 'Profile updated successfully');
        }

        $payments = Payment::where('traveler_id', Auth::id())
            ->with('traveler', 'safariBooking', 'safariBooking.safari')
            ->limit(8)
            ->get();
        return Inertia::render('Frontend/Auth/travellerProfile', compact('profile', 'payments'));
    }

    public function travellerChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:8', new NewOldPasswordNotSame($request->current_password)],
            'confirm_password' => ['required_with:new_password', 'same:new_password'],
        ]);

        $user = Auth::user();
        $user->password = $request->new_password;
        $user->save();
        return redirect()->route('frontend.traveller-profile')->with('success', 'Password updated successfully');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('frontend.login');
    }
}
