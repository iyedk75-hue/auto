<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        $user = $request->user();

        if ($user && in_array($user->role, [User::ROLE_CANDIDATE, User::ROLE_ADMIN], true)) {
            $locale = 'ar';
            $request->session()->put('locale', $locale);
        } else {
            $locale = $request->session()->get('locale')
                ?? $request->cookie('massar_locale')
                ?? $defaultLocale;

            if (! in_array($locale, $supportedLocales, true)) {
                $locale = $defaultLocale;
            }
        }

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
