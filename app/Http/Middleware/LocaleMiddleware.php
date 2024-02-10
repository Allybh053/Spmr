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
        $locale = $request->server('HTTP_ACCEPT_LANGUAGE');
        $locale = substr($locale, 0, 2); // Extract the first two characters (language code)
        
        // Check if the locale is supported, otherwise fallback to the default locale
        $supportedLocales = ['en', 'bm']; // Add more locales as needed
        $locale = in_array($locale, $supportedLocales) ? $locale : config('app.fallback_locale');

        app()->setLocale($locale);

        return $next($request);
    }
}
