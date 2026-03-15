<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['fr', 'ar']);
        $defaultLocale = config('app.locale', 'fr');

        $locale = $request->session()->get('locale')
            ?? $request->cookie('massar_locale')
            ?? $defaultLocale;

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = $defaultLocale;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
