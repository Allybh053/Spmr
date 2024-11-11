<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set X-Frame-Options header to prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Set Content-Security-Policy header to control resources loading
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'self'");
        $response->headers->set('Permissions-Policy', 'geolocation=(self), microphone=()');

        return $response;
    }
}

