<!DOCTYPE html>
@php
    $isRtl = app()->getLocale() === 'ar';
    $themeClass = auth()->check()
        ? (auth()->user()->isAdmin() ? 'theme-admin' : 'theme-candidate')
        : 'theme-guest';
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800|noto-kufi-arabic:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-body {{ $themeClass }} {{ $isRtl ? 'locale-rtl' : 'locale-ltr' }}">
        <div class="page-shell {{ auth()->check() ? 'page-shell-sidebar' : '' }}">
            @include('layouts.navigation')

            <div class="page-content">
                @isset($header)
                    <header class="page-header page-header-compact">
                        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                @if (session('status') || session('error'))
                    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                        @if (session('status'))
                            <div class="flash-banner flash-banner-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="flash-banner flash-banner-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
