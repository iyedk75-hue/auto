<x-app-layout>
    <div class="massar-landing massar-body">
        <section class="relative overflow-hidden">
            <div class="absolute -top-32 left-0 h-80 w-80 rounded-full bg-orange-500/20 blur-3xl" aria-hidden="true"></div>
            <div class="absolute right-0 top-10 h-72 w-72 rounded-full bg-blue-700/20 blur-3xl" aria-hidden="true"></div>

            <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
                <div class="relative grid min-h-[70vh] gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    <div class="space-y-6">
                        <p class="massar-kicker">Tunisie Auto-École (Masar)</p>
                        <h1 class="massar-title text-5xl font-extrabold tracking-tight text-[#1e1b16] sm:text-6xl lg:text-7xl">
                            Le cockpit digital des auto-écoles tunisiennes.
                        </h1>
                        <p class="max-w-2xl text-xl leading-8 text-slate-700">
                            Centralisez candidats, paiements, examens et cours dans une seule plateforme, tout en sécurisant le contenu pédagogique du code de la route.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('admin.login') }}" class="massar-btn-primary px-8 py-4 text-lg">Accès auto-école</a>
                            <a href="https://wa.me/21699000000" class="massar-btn-ghost px-8 py-4 text-lg" target="_blank" rel="noreferrer">Demander une démo WhatsApp</a>
                        </div>
                    </div>

                    <div class="relative massar-hero-card">
                        <div class="massar-hero-badge">
                            98% Success Rate
                        </div>
                        <div class="massar-card massar-card-ink p-8">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Masar Platform</p>
                            <h2 class="mt-4 text-2xl font-extrabold text-white">Tout le pilotage auto-école.</h2>
                            <div class="mt-6 space-y-4 text-sm leading-6 text-white/85">
                                <p>CRM candidats avec fiches, statuts et suivi visuel.</p>
                                <p>Planning d’examens et rappels automatisés.</p>
                                <p>Finance, cours et anti-piratage intégrés.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 grid gap-4 sm:grid-cols-3">
                    <div class="massar-card text-center">
                        <p class="text-sm font-semibold text-slate-600">50+ Partner Driving Schools</p>
                    </div>
                    <div class="massar-card text-center">
                        <p class="text-sm font-semibold text-slate-600">98% Success Rate</p>
                    </div>
                    <div class="massar-card text-center">
                        <p class="text-sm font-semibold text-slate-600">5000+ Candidates</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="massar-kicker">Auto-école OS</p>
                    <h2 class="massar-title mt-3 text-3xl font-extrabold text-slate-950">Une seule plateforme pour tout gérer.</h2>
                </div>
                <p class="max-w-xl text-sm leading-7 text-slate-600">
                    Masar centralise le CRM, la finance et l’agenda d’examens pour réduire la charge opérationnelle.
                </p>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                <div class="massar-card">
                    <h3 class="text-lg font-bold text-slate-950">CRM Candidats</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Trombinoscope, statuts pédagogiques et fiches détaillées.</p>
                </div>
                <div class="massar-card">
                    <h3 class="text-lg font-bold text-slate-950">Finance & Paiements</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Suivi des soldes, alertes de reste à payer et historique complet.</p>
                </div>
                <div class="massar-card">
                    <h3 class="text-lg font-bold text-slate-950">Agenda & Examens</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Planification des sessions, notifications et suivi des résultats.</p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <div class="massar-card massar-card-ink">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Sécurité & anti-piratage</p>
                    <h2 class="massar-title mt-3 text-3xl font-extrabold text-white">Protégez votre contenu pédagogique.</h2>
                </div>
                <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Core priority</span>
            </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">Appareil unique</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">Connexion liée à un seul device avec déconnexion automatique.</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">DRM anti-capture</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">Blocage des enregistrements d’écran et écran noir.</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">Filigrane dynamique</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">Nom et téléphone visibles en filigrane léger.</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">Anti-copier-coller</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">Sélection, clic droit et copie désactivés.</p>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('admin.login') }}" class="massar-btn-primary">Accès auto-école</a>
                    <a href="https://wa.me/21699000000" class="massar-btn-ghost" target="_blank" rel="noreferrer">Demander une démo</a>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
