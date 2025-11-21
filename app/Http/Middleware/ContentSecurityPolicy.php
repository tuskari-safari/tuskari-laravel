<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // CDN whitelist and trusted domains - no brackets, just URLs separated by spaces
        $cdnHosts = [
            "https://cdn.jsdelivr.net",
            "https://maxcdn.bootstrapcdn.com",
            "https://cdn.datatables.net",
            "https://use.fontawesome.com",
            "https://code.jquery.com",
            "https://cdnjs.cloudflare.com",
            "https://cdn.ckeditor.com",
            "https://fonts.googleapis.com",
            "https://fonts.gstatic.com",
            "https://js.stripe.com",
            "https://static.cloudflareinsights.com",
            "http://127.0.0.1:5173", // Dev localhost
            "https://api.iconify.design",
            "https://api.simplesvg.com",
            "https://api.unisvg.com",
        ];
        $cdnList = implode(" ", $cdnHosts);

        // Allow unsafe inline and eval only on local/dev environment
        $unsafe = app()->environment('local') ? "'unsafe-inline' 'unsafe-eval'" : '';

        // Build the Content Security Policy string
        $policy = "default-src 'self'; "
            . "script-src 'self' $unsafe $cdnList; "
            . "script-src-elem 'self' $unsafe $cdnList; "
            . "worker-src 'self' blob:; "
            . "style-src 'self' $unsafe $cdnList https://api.mapbox.com; "
            . "style-src-elem 'self' $unsafe $cdnList https://api.mapbox.com; "
            . "font-src 'self' $cdnList data:; "
            . "img-src 'self' data: blob: https: http://127.0.0.1:5173; "
            . "media-src 'self' data: blob:; "
            . "connect-src 'self' "
            . "https://js.stripe.com "
            . "https://api.mapbox.com "
            . "https://events.mapbox.com "
            . "ws://localhost:9002 "
            . "ws://127.0.0.1:9002 "
            . "wss://localhost:9002 "
            . "wss://127.0.0.1:9002 "
            . "ws://127.0.0.1:5173 "
            . "http://127.0.0.1:5173 "
            . "https://api.iconify.design "
            . "https://api.simplesvg.com "
            . "https://api.unisvg.com "
            . "wss://tuskari.dedicateddevelopers.us:7009 "
            . "https://cdnjs.cloudflare.com "
            . "https://cdn.jsdelivr.net; "
            . "frame-src 'self' https://js.stripe.com; ";

        // Set headers including standard security headers
        $response->headers->set('Content-Security-Policy', $policy);
        $response->headers->set('Permissions-Policy', "geolocation=(self), microphone=(), camera=(), autoplay=(self), fullscreen=(self)");
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
