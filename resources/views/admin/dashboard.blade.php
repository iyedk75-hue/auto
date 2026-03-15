<x-app-layout>
    <x-slot name="header">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div class="space-y-4">
                <p class="kicker">Auto-école</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                    Tableau de bord Massar
                </h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Gardez une vue globale sur les candidats, les paiements et les examens.
                </p>
            </div>
            <div class="panel bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 text-white">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Aujourd'hui</p>
                <p class="mt-3 text-sm leading-6 text-white/90">Suivez les actions clés : paiements en attente, examens à venir et activité des candidats.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8 lg:flex-row">
                <div class="flex-1 space-y-8">
                    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Candidats</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $candidateCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">Comptes actifs dans votre auto-école.</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Questions actives</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $questionCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">Disponibles pour le quiz intelligent.</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Paiements en attente</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $pendingPaymentsCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">À traiter dans la fiche finance.</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Examens à venir</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $upcomingExamCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">Sessions planifiées.</p>
                        </div>
                    </section>

                    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                        <a href="{{ route('admin.candidates.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">Candidats</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Gestion visuelle</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">Accédez au trombinoscope et aux statuts de progression.</p>
                        </a>
                        <a href="{{ route('admin.questions.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">Questions</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Banque officielle</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">Ajoutez ou ajustez les questions du code de la route.</p>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">Finance</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Fiche candidat</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">Suivez les paiements, les retards et les soldes.</p>
                        </a>
                        <a href="{{ route('admin.exams.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">Examens</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Planning</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">Planifiez les passages au code et notifiez vos candidats.</p>
                        </a>
                    </section>
                </div>

                <aside class="w-full lg:w-64">
                    <div class="panel sticky top-24 space-y-6">
                        <div>
                            <p class="kicker">Sections</p>
                            <div class="mt-4 space-y-2">
                                <a href="{{ route('admin.candidates.index') }}" class="btn-neutral w-full justify-start">Candidates</a>
                                <a href="{{ route('admin.courses.index') }}" class="btn-neutral w-full justify-start">Courses</a>
                                <a href="{{ route('admin.payments.index') }}" class="btn-neutral w-full justify-start">Accounting</a>
                                <a href="{{ route('admin.questions.index') }}" class="btn-neutral w-full justify-start">Question Bank</a>
                                <a href="{{ route('admin.exams.index') }}" class="btn-neutral w-full justify-start">Exam Schedule</a>
                            </div>
                        </div>
                        <div>
                            <p class="kicker">Quick action</p>
                            <a href="{{ route('admin.candidates.index') }}" class="btn-admin-entry w-full justify-center">Add Candidate</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
