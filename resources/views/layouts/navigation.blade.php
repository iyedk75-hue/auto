@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
    $brandTarget = auth()->check()
        ? ($isAdmin ? route('admin.dashboard') : route('dashboard'))
        : route('home');
@endphp

@auth
    <aside class="sidebar-shell">
        <a href="{{ $brandTarget }}" class="sidebar-brand" title="Masar">
            <x-application-logo class="sidebar-logo" />
        </a>

        <div class="sidebar-links">
            @if ($isAdmin)
                <a href="{{ route('admin.dashboard') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.dashboard')]) title="Dashboard">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10.5L12 3l9 7.5v9a1.5 1.5 0 0 1-1.5 1.5H16.5A1.5 1.5 0 0 1 15 19.5V15a1.5 1.5 0 0 0-1.5-1.5h-3A1.5 1.5 0 0 0 9 15v4.5A1.5 1.5 0 0 1 7.5 21H4.5A1.5 1.5 0 0 1 3 19.5z" />
                    </svg>
                    <span class="sr-only">Dashboard</span>
                </a>
                <a href="{{ route('admin.candidates.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.candidates.*')]) title="Candidats">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 11a4 4 0 1 0-8 0" />
                        <path d="M3 20a7 7 0 0 1 18 0" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="sr-only">Candidats</span>
                </a>
                <a href="{{ route('admin.courses.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.courses.*')]) title="Cours">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 5h11a3 3 0 0 1 3 3v11" />
                        <path d="M4 5v11a3 3 0 0 0 3 3h11" />
                        <path d="M8 9h6" />
                        <path d="M8 13h6" />
                    </svg>
                    <span class="sr-only">Cours</span>
                </a>
                <a href="{{ route('admin.questions.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.questions.*')]) title="Questions">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 18h.01" />
                        <path d="M9.09 9a3 3 0 0 1 5.82 1c0 2-3 2-3 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                    <span class="sr-only">Questions</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.payments.*')]) title="Paiements">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M3 10h18" />
                        <path d="M7 15h4" />
                    </svg>
                    <span class="sr-only">Paiements</span>
                </a>
                <a href="{{ route('admin.exams.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.exams.*')]) title="Examens">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4" />
                        <path d="M8 2v4" />
                        <path d="M3 10h18" />
                    </svg>
                    <span class="sr-only">Examens</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('dashboard')]) title="Dashboard">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10.5L12 3l9 7.5v9a1.5 1.5 0 0 1-1.5 1.5H16.5A1.5 1.5 0 0 1 15 19.5V15a1.5 1.5 0 0 0-1.5-1.5h-3A1.5 1.5 0 0 0 9 15v4.5A1.5 1.5 0 0 1 7.5 21H4.5A1.5 1.5 0 0 1 3 19.5z" />
                    </svg>
                    <span class="sr-only">Dashboard</span>
                </a>
                <a href="{{ route('quiz.show') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('quiz.*')]) title="Quiz">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 7h10" />
                        <path d="M7 11h10" />
                        <path d="M7 15h6" />
                        <rect x="4" y="3" width="16" height="18" rx="2" />
                    </svg>
                    <span class="sr-only">Quiz</span>
                </a>
                <a href="{{ route('courses.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('courses.*')]) title="Cours">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 5h11a3 3 0 0 1 3 3v11" />
                        <path d="M4 5v11a3 3 0 0 0 3 3h11" />
                        <path d="M8 9h6" />
                        <path d="M8 13h6" />
                    </svg>
                    <span class="sr-only">Cours</span>
                </a>
                <a href="{{ route('payments.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('payments.*')]) title="Paiements">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M3 10h18" />
                        <path d="M7 15h4" />
                    </svg>
                    <span class="sr-only">Paiements</span>
                </a>
            @endif
        </div>

        <div class="sidebar-footer">
            <span class="sidebar-avatar" title="{{ Auth::user()->name }}">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
            <a href="{{ route('profile.edit') }}" class="sidebar-link" title="Profile">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="7" r="4" />
                    <path d="M4 21a8 8 0 0 1 16 0" />
                </svg>
                <span class="sr-only">Profile</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link" title="Log Out">
                    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12h12" />
                        <path d="M16 8l4 4-4 4" />
                        <path d="M5 21a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2" />
                    </svg>
                    <span class="sr-only">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <nav x-data="{ open: false }" class="nav-shell md:hidden">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
            <a href="{{ $brandTarget }}" class="nav-brand">
                <span class="nav-brand-mark">
                    <x-application-logo class="h-8 w-8 fill-current" />
                </span>
                <span class="hidden sm:flex sm:flex-col">
                    <span class="text-sm font-extrabold tracking-tight text-slate-950">
                        {{ $isAdmin ? 'Massar Control' : 'Massar' }}
                    </span>
                    <span class="text-xs font-medium text-slate-500">
                        {{ $isAdmin ? 'Auto-école workspace' : 'Plateforme candidats' }}
                    </span>
                </span>
            </a>

            <button @click="open = !open" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-cloak x-show="open" class="border-t border-slate-200/80 bg-white/95 px-4 py-4">
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="mobile-nav-link">Massar</a>

                @if ($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link">Dashboard</a>
                    <a href="{{ route('admin.candidates.index') }}" class="mobile-nav-link">Candidats</a>
                    <a href="{{ route('admin.courses.index') }}" class="mobile-nav-link">Cours</a>
                    <a href="{{ route('admin.questions.index') }}" class="mobile-nav-link">Questions</a>
                    <a href="{{ route('admin.payments.index') }}" class="mobile-nav-link">Paiements</a>
                    <a href="{{ route('admin.exams.index') }}" class="mobile-nav-link">Examens</a>
                @else
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link">Dashboard</a>
                    <a href="{{ route('quiz.show') }}" class="mobile-nav-link">Quiz</a>
                    <a href="{{ route('courses.index') }}" class="mobile-nav-link">Cours</a>
                    <a href="{{ route('payments.index') }}" class="mobile-nav-link">Paiements</a>
                @endif

                <a href="{{ route('profile.edit') }}" class="mobile-nav-link">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link w-full text-left">Log Out</button>
                </form>
            </div>
        </div>
    </nav>
@else
    <nav x-data="{ open: false }" class="nav-shell">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 lg:gap-10">
                <a href="{{ $brandTarget }}" class="nav-brand">
                    <span class="nav-brand-mark">
                        <x-application-logo class="h-8 w-8 fill-current" />
                    </span>
                    <span class="hidden sm:flex sm:flex-col">
                        <span class="text-sm font-extrabold tracking-tight text-slate-950">Massar</span>
                        <span class="text-xs font-medium text-slate-500">Plateforme auto-école</span>
                    </span>
                </a>

                <div class="hidden items-center gap-2 md:flex">
                    <a href="{{ route('home') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('home') || request()->routeIs('marketing.massar')])>
                        Massar
                    </a>
                </div>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <a href="{{ route('login') }}" class="btn-ghost">Connexion</a>
                <a href="{{ route('admin.login') }}" class="btn-admin-entry">Auto-école</a>
                <a href="{{ route('register') }}" class="btn-primary">Créer un compte</a>
            </div>

            <button @click="open = !open" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 md:hidden">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-cloak x-show="open" class="border-t border-slate-200/80 bg-white/95 px-4 py-4 md:hidden">
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="mobile-nav-link">Massar</a>
                <a href="{{ route('login') }}" class="mobile-nav-link">Connexion</a>
                <a href="{{ route('admin.login') }}" class="mobile-nav-link">Auto-école</a>
                <a href="{{ route('register') }}" class="mobile-nav-link">Créer un compte</a>
            </div>
        </div>
    </nav>
@endauth
