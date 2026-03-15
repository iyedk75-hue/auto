<x-guest-layout>
    @php
        $isAdminLogin = ($loginMode ?? 'candidate') === 'admin';
    @endphp

    <div class="space-y-8">
        <div class="space-y-4">
            <x-auth-session-status class="text-sm text-emerald-600" :status="session('status')" />

            <div class="flex items-center justify-between rounded-full bg-slate-100 p-1 text-sm font-semibold">
                <a href="{{ route('login') }}" class="{{ $isAdminLogin ? 'text-slate-500' : 'bg-white text-slate-900 shadow-sm' }} rounded-full px-4 py-2">
                    {{ __('ui.auth.candidate_tab') }}
                </a>
                <a href="{{ route('admin.login') }}" class="{{ $isAdminLogin ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' }} rounded-full px-4 py-2">
                    {{ __('ui.auth.admin_tab') }}
                </a>
            </div>

            <div class="space-y-3">
                <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">
                    {{ $isAdminLogin ? __('ui.auth.admin_badge') : __('ui.auth.candidate_badge') }}
                </span>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-950">
                        {{ $isAdminLogin ? __('ui.auth.admin_heading') : __('ui.auth.candidate_heading') }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ $isAdminLogin ? __('ui.auth.admin_intro') : __('ui.auth.candidate_intro') }}
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ $isAdminLogin ? route('admin.login.store') : route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.auth.phone_number') }}</label>
                <input id="email" class="form-input-auth" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">{{ __('Password') }}</label>
                <input id="password" class="form-input-auth" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                <span>{{ __('ui.auth.remember_me') }}</span>
            </label>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-slate-500 hover:text-slate-900" href="{{ route('password.request') }}">
                        {{ __('ui.auth.forgot_password') }}
                    </a>
                @endif

                <button type="submit" class="{{ $isAdminLogin ? 'btn-admin-entry' : 'btn-primary' }}">
                    {{ $isAdminLogin ? __('ui.auth.enter_dashboard') : __('ui.auth.sign_in') }}
                </button>
            </div>
        </form>

        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-600">
            @if ($isAdminLogin)
                {{ __('ui.auth.admin_activation_note') }}
            @else
                {{ __('ui.auth.candidate_activation_note') }}
                <div class="mt-4">
                    <a href="{{ route('register') }}" class="btn-primary w-full justify-center">{{ __('ui.auth.whatsapp_activation') }}</a>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
