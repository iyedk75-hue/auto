<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $isAdminLogin = request()->routeIs('admin.login*');
    @endphp
    <body class="app-body theme-guest {{ $isAdminLogin ? 'theme-admin-auth' : 'theme-candidate-auth' }}">
        <div class="guest-shell">
            <div class="guest-brand-panel">
                <a href="{{ route('home') }}" class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-white/90 text-sky-700 shadow-lg shadow-sky-950/10">
                    <x-application-logo class="h-10 w-10 fill-current" />
                </a>

                <div class="space-y-5">
                    <span class="inline-flex rounded-full border border-white/40 bg-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-white">
                        {{ $isAdminLogin ? 'Espace Auto-école' : 'Espace Candidat' }}
                    </span>
                    <div class="space-y-3">
                        <h1 class="text-4xl font-extrabold leading-tight text-white sm:text-5xl">
                            {{ $isAdminLogin ? 'Pilotez votre auto-école en temps réel.' : 'Réussissez le code plus vite avec Massar.' }}
                        </h1>
                        <p class="max-w-xl text-base leading-7 text-white/78">
                            {{ $isAdminLogin
                                ? 'Suivez les candidats, planifiez les examens, gérez les paiements et gardez le contrôle sur tout le flux.'
                                : 'Accédez aux cours, passez des quiz intelligents et préparez-vous efficacement à l’examen.' }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="guest-stat">
                        <span class="guest-stat-label">Flow</span>
                        <strong class="guest-stat-value">{{ $isAdminLogin ? 'CRM + finance + agenda' : 'Quiz -> progression -> examen' }}</strong>
                    </div>
                    <div class="guest-stat">
                        <span class="guest-stat-label">Security</span>
                        <strong class="guest-stat-value">DRM, filigrane dynamique, appareil unique</strong>
                    </div>
                </div>
            </div>

            <div class="guest-form-panel">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
