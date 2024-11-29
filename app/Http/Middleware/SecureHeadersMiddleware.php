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

        // Additional Headers
        $referrerPolicy = env('SECURE_REFERRER_POLICY', 'no-referrer-when-downgrade');
        $response->headers->set('Referrer-Policy', $referrerPolicy);

        $xContentTypeOptions = env('SECURE_X_CONTENT_TYPE_OPTIONS', 'nosniff');
        $response->headers->set('X-Content-Type-Options', $xContentTypeOptions);

        $coep = env('SECURE_COEP', 'require-corp');
        $response->headers->set('Cross-Origin-Embedder-Policy', $coep);

        $corsOrigin = env('SECURE_CORS_ORIGIN', 'https://spmr.mygovuc.gov.my');
        $response->headers->set('Access-Control-Allow-Origin', $corsOrigin);

        $coop = env('SECURE_COOP', 'same-origin');
        $response->headers->set('Cross-Origin-Opener-Policy', $coop);


        $hsts = env('SECURE_HSTS', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('Strict-Transport-Security', $hsts);

        return $response;
    }
}
