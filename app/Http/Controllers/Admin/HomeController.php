<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Safari;
use App\Models\User;
use App\Models\SafariBooking;
use App\Models\Payment;
use Carbon\Carbon;
use App\Rules\MatchOldPassword;
use App\Rules\NewOldPasswordNotSame;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user() && Auth::user()->role_name == 'SUPER-ADMIN') {
            return to_route('admin.dashboard');
        }
        return Inertia::render('common/SuperAdminLogin');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'regex:/(.+)@(.+)\.(.+)/i'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials) && Auth::user()->role_name == 'SUPER-ADMIN') {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard(Request $request)
    {
        $data['active_user'] = User::role('TRAVELLER')->whereActive(true)->count();
        $data['inactive_user'] = User::role('TRAVELLER')->whereActive(false)->count();
        $data['active_vendor'] = User::role('SAFARI_OPERATOR')->whereActive(true)->count();
        $data['inactive_vendor'] = User::role('SAFARI_OPERATOR')->whereActive(false)->count();
        $data['approved_safari'] = Safari::where('is_approved', 1)->count();
        $data['pending_safari'] = Safari::where('is_approved', 0)->count();
        $data['rejected_safari'] = Safari::where('is_approved', 2)->count();
        
        // Create array from January to current month only
        $currentMonth = date('n');
        $months = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $months[$i] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'total' => 0
            ];
        }
        
        // Monthly Users Data
        $userStats = User::role('TRAVELLER')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        foreach ($userStats as $month => $stat) {
            $months[$month]['total'] = $stat->total;
        }
        $data['monthly_users'] = array_values($months);
        
        // Monthly Vendors Data
        $monthsVendors = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $monthsVendors[$i] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'total' => 0
            ];
        }
        
        $vendorStats = User::role('SAFARI_OPERATOR')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        foreach ($vendorStats as $month => $stat) {
            if (isset($monthsVendors[$month])) {
                $monthsVendors[$month]['total'] = $stat->total;
            }
        }
        $data['monthly_vendors'] = array_values($monthsVendors);
        
        // Monthly Bookings Data
        $monthsBookings = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $monthsBookings[$i] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'total' => 0
            ];
        }
        
        $bookingStats = SafariBooking::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        foreach ($bookingStats as $month => $stat) {
            if (isset($monthsBookings[$month])) {
                $monthsBookings[$month]['total'] = (int)$stat->total;
            }
        }
        $data['monthly_bookings'] = array_values($monthsBookings);

        // Monthly Payments Success Data
        $monthsPaymentsSuccess = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $monthsPaymentsSuccess[$i] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'total' => 0
            ];
        }
        
        $paymentSuccessStats = Payment::where('payment_status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        foreach ($paymentSuccessStats as $month => $stat) {
            if (isset($monthsPaymentsSuccess[$month])) {
                $monthsPaymentsSuccess[$month]['total'] = $stat->total;
            }
        }
        $data['monthly_payments_success'] = array_values($monthsPaymentsSuccess);

        
        // Monthly Payments Failed Data
        $monthsPaymentsFailed = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $monthsPaymentsFailed[$i] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'total' => 0
            ];
        }
        
        $paymentFailedStats = Payment::where('payment_status','!=', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        foreach ($paymentFailedStats as $month => $stat) {
            if (isset($monthsPaymentsFailed[$month])) {
                $monthsPaymentsFailed[$month]['total'] = $stat->total ?? 0;
            }
        }
        $data['monthly_payments_failed'] = array_values($monthsPaymentsFailed);
            
        return Inertia::render('Admin/Dashboard', compact('data'));
    }

    public function adminProfile(Request $request)
    {
        $user = User::where('id', Auth::id())->first();

        if (request()->isMethod('post')) {
            $credentials = $request->validate([
                'first_name' => 'required|max:40|alpha',
                'last_name' => 'required|max:40|alpha',
                'email' =>  'required|max:255|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email,' . $user->id,
                'profile_photo' =>  $request->hasFile('profile_photo') ? 'mimes:jpeg,png,jpg,gif |max:10240' : '',
            ]);

            try {
                $user->fill($credentials);
                if ($request->input('profile_photo')) {
                    $base64Image = $request->input('profile_photo');
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                    $filename = uniqid() . time() . rand(10, 1000000) . '.jpg';
                    $filepath = 'profile_photo/' . $filename;
                    Storage::disk('public')->put($filepath, $imageData);
                    $filepath = 'storage/profile_photo/' . $filename;
                    $user->profile_photo_path = $filepath;
                }
                $user->save();

                session()->flash('success', 'Profile updated successfully');
                return redirect('admin/dashboard');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                return response()->json(['error' => 'Server Error'], 500);
            }
        }

        return Inertia::render('Admin/AdminProfile', compact('user'));
    }

    public function adminChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:8', new NewOldPasswordNotSame($request->current_password)],
            'confirm_password' => ['required_with:new_password', 'same:new_password'],
        ]);

        $request->user()->password = $request->new_password;
        $request->user()->save();

        session()->flash('success', 'Password changed successfully.');
        return $this->logout($request);
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Inertia::location(route('admin.login'));
    }
}
