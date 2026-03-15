<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AutoSchool;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $autoSchool = AutoSchool::query()->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => User::ROLE_CANDIDATE,
            'auto_school_id' => $autoSchool?->id,
            'status' => 'active',
            'registered_at' => now(),
            'password' => Hash::make($request->password),
        ]);

        $deviceId = (string) Str::uuid();

        $user->forceFill([
            'device_uuid' => $deviceId,
            'device_bound_at' => now(),
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'last_user_agent' => (string) $request->userAgent(),
        ])->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->withCookie(Cookie::forever('massar_device', $deviceId));
    }
}
