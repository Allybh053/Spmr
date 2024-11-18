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

        // Get header values from .env configuration
        $xFrameOptions = env('SECURE_X_FRAME_OPTIONS', 'SAMEORIGIN');
        $contentSecurityPolicy = env('SECURE_CONTENT_SECURITY_POLICY', "frame-ancestors 'self'");
        $permissionsPolicy = env('SECURE_PERMISSIONS_POLICY', 'geolocation=(self), camera=(), microphone=()');
        $hsts = env('SECURE_HSTS', 'max-age=31536000; includeSubDomains; preload');
        $referrerPolicy = env('SECURE_REFERRER_POLICY', 'no-referrer-when-downgrade');
        $xContentTypeOptions = env('SECURE_X_CONTENT_TYPE_OPTIONS', 'nosniff');
        $coep = env('SECURE_COEP', 'require-corp');
        $corsOrigin = env('SECURE_CORS_ORIGIN', 'https://spmr.mygovuc.gov.my');
        $coop = env('SECURE_COOP', 'same-origin');


        // Set headers using values from .env
        $response->headers->set('X-Frame-Options', $xFrameOptions);
        $response->headers->set('Content-Security-Policy', $contentSecurityPolicy);
        $response->headers->set('Permissions-Policy', $permissionsPolicy);
        $response->headers->set('Strict-Transport-Security', $hsts);
        $response->headers->set('Referrer-Policy', $referrerPolicy);
        $response->headers->set('X-Content-Type-Options', $xContentTypeOptions);
        $response->headers->set('Cross-Origin-Embedder-Policy', $coep);
        $response->headers->set('Access-Control-Allow-Origin', $corsOrigin);
        $response->headers->set('Cross-Origin-Opener-Policy', $coop);

        return $response;
    }
}
