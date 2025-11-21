<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Auth extends Middleware
{
    /**
     * Overwrite the method you want to customize
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return $request->expectsJson() ? null : route('admin.login');
        }

        return $request->expectsJson() ? null : route('frontend.login');
    }
}
