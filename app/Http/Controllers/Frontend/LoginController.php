<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\SendForgotPasswordOtp;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Mail};
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::check()) {
            $role = Auth::user()->role_name;

            $routes = [
                'TRAVELLER' => 'frontend.dashboard',
                'SAFARI_OPERATOR' => 'frontend.operator-dashboard'
            ];

            if (isset($routes[$role])) {
                return redirect()->route($routes[$role]);
            }

            Auth::logout();
            return redirect()->route('frontend.login')->withErrors([
                'email' => 'Your account role is not authorized.'
            ]);
        }
        if ($request->isMethod('POST')) {
            $request->validate([
                'role' => 'required|in:TRAVELLER,SAFARI_OPERATOR',
                'email' => ['required', 'email', 'regex:/(.+)@(.+)\.(.+)/i', 'exists:users,email'],
                'password' => 'required'
            ], [
                'email.exists' => 'The provided email does not exist in our system.',
            ]);

            try {
                $credentials = $request->only('email', 'password');

                if (Auth::attempt($credentials)) {
                    $user = Auth::user();
                    if ($user->active == 0) {
                        Auth::logout();
                        return back()->withErrors([
                            'email' => 'This user is not active.',
                        ])->onlyInput('email');
                    }

                    if ($user->role_name === $request->role) {
                        if ($request->session()->has('safari_booking') && $user->role_name === 'TRAVELLER') {
                            return redirect()->route('frontend.checkout');
                        }

                        $routes = [
                            'TRAVELLER' => 'frontend.dashboard',
                            'SAFARI_OPERATOR' => 'frontend.operator-dashboard'
                        ];

                        return redirect()->route($routes[$user->role_name])
                            ->with('success', 'You are logged in successfully.');
                    }

                    Auth::logout();
                }

                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            } catch (\Throwable $e) {
                Log::error(" :: EXCEPTION :: " . $e->getFile() . " : " . $e->getLine() . " : " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Frontend/log-in');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'full_name' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
                'email' => ['required', 'email', 'regex:/(.+)@(.+)\.(.+)/i', 'unique:users,email', 'max:50'],
                'password' => 'required|min:8',
                'confirm_password' => 'required|min:8|same:password',
                'terms' => 'accepted'
            ], [
                'email.unique' => 'The email has already been taken.',
                'terms.accepted' => 'You must accept the terms and conditions.',
            ]);

            try {
                $user = new User();
                $name = explode(' ', $request->full_name);
                $user->first_name = $name[0];
                $user->last_name = array_key_exists(1, $name) ? $name[1] : '';
                $user->email = $request->email;
                $user->password = $request->password;
                $user->save();
                $user->assignRole('TRAVELLER');
                Auth::login($user);

                if ($request->session()->has('safari_booking')) {
                    return redirect()->route('frontend.checkout');
                }

                return redirect()->route('frontend.dashboard')->with('success', 'Registration successful. You are now logged in.');
            } catch (\Throwable $th) {
                Auth::logout();
                Log::error(" :: EXCEPTION :: " . $th->getFile() . " : " . $th->getLine() . " : " . $th->getMessage() . "\n" . $th->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Frontend/register');
    }

    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'email' => ['required', 'email', 'regex:/(.+)@(.+)\.(.+)/i', 'max:255'],
            ]);

            $token = rand(1000, 9999);

            $get_user = User::where('email', $request->email)->whereHas('roles', function ($q) {
                $q->where('name', '<>', 'SUPER-ADMIN');
            })->first();

            if (!$get_user) {
                return back()->withErrors([
                    'email' => 'Please enter your registered email',
                ]);
            }

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            $data['code'] = $token;

            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($request->email)->queue(new SendForgotPasswordOtp($data));
            }

            session()->flash('success', 'OTP sent successfully.');
            session()->put('forgot_password_email', $request->email);
            return redirect()->route('frontend.otp-validation');
        }
        return Inertia::render('Frontend/forgot-password');
    }
    public function otpValidation(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'otp' => 'required'
            ]);

            $get_otp = DB::table('password_reset_tokens')->where('token', $request->otp)->where('email', $request->email)->first();

            if (!$get_otp) {
                return back()->withErrors([
                    'otp' => 'Please enter valid OTP',
                ]);
            }
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            session()->flash('success', 'OTP successfully validate.');
            return to_route('frontend.reset-password');
        }
        return Inertia::render('Frontend/otp-verification')->with('email', session()->get('forgot_password_email'));
    }
    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password'
            ]);

            $user = User::where('email', session()->get('forgot_password_email'))->first();
            if ($user) {
                $user->password = $request->password;
                $user->save();
                session()->flash('success', 'Password successfully changed.');
            } else {
                session()->flash('error', 'Something went wrong. Please try again.');
            }

            $request->session()->forget('forgot_password_email');
            return to_route('frontend.login');
        }
        return Inertia::render('Frontend/reset-password');
    }

    public function safariOperatorRegisterOne(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'full_name' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
                'email' => ['required', 'email', 'regex:/(.+)@(.+)\.(.+)/i', 'unique:users,email', 'max:50'],
                'phone' => 'required|regex:/^\+?[0-9]{10,15}$/|unique:users,phone',
                'business_name' => 'required|string|max:255',
                'country_code' => 'nullable',
                'country_id' => 'required|exists:countries,id',

            ], [
                'email.unique' => 'The email has already been taken.',
                'business_name.required' => 'The safari Company name field is required.',
                'country_id.required' => 'The Country field is required.',

            ]);
            return redirect()->route('frontend.safari-operator-register-step-two');
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

        return Inertia::render('Frontend/safari-operator-register-one', ['countries' => $countries, 'countryCodes' => $countryCodes]);
    }

    public function safariOperatorRegisterTwo(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validatedData = $request->validate([
                'main_destination'     => 'required|array',
                'main_destination.*'   => 'string',
                'business_type'        => 'required',
                'is_operate_directly'  => 'required',
                'website_link'         => 'nullable|url',
                'instagram_link'       => 'nullable|url',
                'about_operation'      => 'nullable|string|max:1000',
                'password'             => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                'confirm_password' => 'required|string|min:8|same:password',
                'terms'                => 'accepted',
            ], [
                'business_type.required' => 'How Many Employees Do You Currently Have?',
                'is_operate_directly.required' => 'How Many Years Have You Been Operating?'
            ]);

            try {
                $operator = new User();
                $name = explode(' ', $request->full_name);

                $operator->first_name = $name[0];
                $operator->last_name = array_key_exists(1, $name) ? $name[1] : '';
                $operator->business_name = $request->business_name;
                $operator->email = $request->email;
                $operator->phone = $request->phone;
                $operator->country_code = $request->country_code;
                $operator->country_id = $request->country_id;
                $operator->main_destination = json_encode($validatedData['main_destination']);
                $operator->business_type = $validatedData['business_type'];
                $operator->is_operate_directly = $validatedData['is_operate_directly'];
                $operator->website_link = $validatedData['website_link'] ?? null;
                $operator->instagram_link = $validatedData['instagram_link'] ?? null;
                $operator->about_operation = $validatedData['about_operation'];
                $operator->password = $validatedData['password'];

                $operator->save();
                $operator->assignRole('SAFARI_OPERATOR');

                Auth::login($operator);
                session()->flash('success', 'Registration successfully completed. You are now logged in as a Safari Operator.');
                return redirect()->route('frontend.operator-dashboard');
            } catch (\Throwable $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Frontend/safari-operator-register-two');
    }
}
