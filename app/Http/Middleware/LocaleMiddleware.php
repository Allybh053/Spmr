<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request object is null
        if (!$request) {
            // Handle the null request case, such as returning an error response
            return response()->json(['error' => 'Request object is null'], 500);
        }

        // Check if the headers are available in the request
        if (!$request->headers) {
            // Handle the case where headers are not present, such as returning an error response
            return response()->json(['error' => 'Headers are not available in the request'], 400);
        }

        $locale = $request->server('HTTP_ACCEPT_LANGUAGE');

        // Check if the locale is not null before further processing
        if ($locale) {
            $locale = substr($locale, 0, 2); // Extract the first two characters (language code)

            // Check if the locale is supported, otherwise fallback to the default locale
            $supportedLocales = ['en', 'bm']; // Add more locales as needed
            $locale = in_array($locale, $supportedLocales) ? $locale : config('app.fallback_locale');

            app()->setLocale($locale);
        } else {
            // Handle the case where 'HTTP_ACCEPT_LANGUAGE' header is not present
            // Fallback to default locale or handle as per your application's requirements
            $locale = config('app.fallback_locale');
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
