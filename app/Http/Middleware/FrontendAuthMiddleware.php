<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FrontendAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('frontend.login');
        }

        $user = User::find(Auth::id());

        if ($role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        } else {
            if ($user->hasRole('TRAVELLER') || $user->hasRole('SAFARI_OPERATOR')) {
                return $next($request);
            }
        }

        return redirect()->route('frontend.login');
    }
}
