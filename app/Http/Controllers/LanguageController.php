<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class LanguageController extends Controller
{
    public function switchLang($locale)
{
    // Check if the locale is supported
    $supportedLocales = ['en', 'ms']; // Add more locales as needed

    if (in_array($locale, $supportedLocales)) {
        // Set the locale in the session
        Session::put('locale', $locale);
        // Set the application locale
        App::setLocale($locale);
    }
    $this->clearCacheAndAutoload();
    // Redirect back with locale as query parameter
    return redirect()->back();
}


    private function clearCacheAndAutoload()
    {
        // Clear the application cache
        Artisan::call('cache:clear');

        // Clear and optimize the configuration cache
        Artisan::call('config:cache');
    }
}
