<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login', [
            'loginMode' => 'candidate',
        ]);
    }

    public function createAdmin(): View
    {
        return view('auth.login', [
            'loginMode' => 'admin',
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if ($request->user()?->isAdmin()) {
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'email' => 'Utilisez la page auto-école pour les comptes administrateurs.',
            ]);
        }

        $request->session()->regenerate();

        $cookie = $this->bindDeviceIfNeeded($request);

        $response = redirect()->intended(route('dashboard', absolute: false));

        return $cookie ? $response->withCookie($cookie) : $response;
    }

    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if (! $request->user()?->isAdmin()) {
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'email' => 'Cette connexion est réservée aux comptes auto-école.',
            ]);
        }

        $request->session()->regenerate();

        $this->touchLoginMeta($request);

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function bindDeviceIfNeeded(Request $request): ?\Symfony\Component\HttpFoundation\Cookie
    {
        $user = $request->user();

        if (! $user || $user->isAdmin()) {
            return null;
        }

        $cookieName = 'massar_device';
        $deviceId = $request->cookie($cookieName) ?? (string) Str::uuid();

        if ($user->device_uuid && $user->device_uuid !== $deviceId) {
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'email' => 'Ce compte est déjà lié à un autre appareil.',
            ]);
        }

        if (! $user->device_uuid) {
            $user->device_uuid = $deviceId;
            $user->device_bound_at = now();
        }

        $this->touchLoginMeta($request);

        return Cookie::forever($cookieName, $deviceId);
    }

    private function touchLoginMeta(Request $request): void
    {
        $user = $request->user();

        if (! $user) {
            return;
        }

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'last_user_agent' => (string) $request->userAgent(),
        ])->save();
    }
}
