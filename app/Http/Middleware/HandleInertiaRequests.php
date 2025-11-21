<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => [
                    'id' => Auth::id() ?? null,
                    'first_name' => Auth::user()->first_name ?? null,
                    'role' => Auth::user()->role_name ?? null,
                    'full_name' => Auth::user()->full_name ?? null,
                    'email' => Auth::user()->email ?? null,
                    'profile_photo_url' => Auth::user()->profile_photo_url ?? null,
                    'business_name' => Auth::user()->business_name ?? null,
                    'about_operation' => Auth::user()->about_operation ?? null,
                    'no_of_employees' => Auth::user()->no_of_employees ?? null,
                    'business_type' => Auth::user()->business_type ?? null,
                    'business_logo' => Auth::user()->operator_logo_full_url ?? null,
                    'is_approved' => Auth::user()->is_approved ?? null,
                    'why_choose_us' => Auth::user()->why_choose_us ?? null,
                    'business_years' => Auth::user()->business_years ?? null,
                    'address' => Auth::user()->address ?? null,
                ]
            ],

            'previousUrl' => url()->previous(),
            'baseUrl' => url(),
            'currentUrl' => $request->path(),
            'isLogin' => Auth::user() && (Auth::user()->role_name == 'TRAVELLER' || Auth::user() && Auth::user()->role_name == 'SAFARI_OPERATOR') ? true : false,
            'appName' => config('app.name'),
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'info' => fn() => $request->session()->get('info'),
                'warning' => fn() => $request->session()->get('warning'),
            ],
        ]);
    }
}
