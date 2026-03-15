<!DOCTYPE html>
@php
    $isRtl = app()->getLocale() === 'ar';
    $isAdminLogin = request()->routeIs('admin.login*');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|noto-kufi-arabic:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-body theme-guest {{ $isAdminLogin ? 'theme-admin-auth' : 'theme-candidate-auth' }} {{ $isRtl ? 'locale-rtl' : 'locale-ltr' }}">
        <div class="guest-shell">
            <div class="guest-brand-panel">
                <div class="flex items-start justify-between gap-4">
                    <a href="{{ route('home') }}" class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-white/90 text-sky-700 shadow-lg shadow-sky-950/10">
                        <x-application-logo class="h-10 w-10 fill-current" />
                    </a>

                    <x-locale-switcher />
                </div>

                <div class="space-y-5">
                    <span class="inline-flex rounded-full border border-white/40 bg-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-white">
                        {{ $isAdminLogin ? __('ui.guest.admin_badge') : __('ui.guest.candidate_badge') }}
                    </span>
                    <div class="space-y-3">
                        <h1 class="text-4xl font-extrabold leading-tight text-white sm:text-5xl">
                            {{ $isAdminLogin ? __('ui.guest.admin_heading') : __('ui.guest.candidate_heading') }}
                        </h1>
                        <p class="max-w-xl text-base leading-7 text-white/78">
                            {{ $isAdminLogin ? __('ui.guest.admin_body') : __('ui.guest.candidate_body') }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="guest-stat">
                        <span class="guest-stat-label">{{ __('ui.guest.flow') }}</span>
                        <strong class="guest-stat-value">{{ $isAdminLogin ? __('ui.guest.admin_flow_value') : __('ui.guest.candidate_flow_value') }}</strong>
                    </div>
                    <div class="guest-stat">
                        <span class="guest-stat-label">{{ __('ui.guest.security') }}</span>
                        <strong class="guest-stat-value">{{ __('ui.guest.security_value') }}</strong>
                    </div>
                </div>
            </div>

            <div class="guest-form-panel">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
