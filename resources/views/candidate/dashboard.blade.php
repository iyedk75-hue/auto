<x-app-layout>
    <x-slot name="header">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div class="space-y-4">
                <p class="kicker">Espace candidat</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                    Bienvenue {{ $user->name }}, on avance ensemble.
                </h2>
                @if ($user->autoSchool)
                    <p class="text-sm font-semibold text-slate-500">Auto-école : {{ $user->autoSchool->name }}</p>
                @endif
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Suivez votre progression, répondez au quiz intelligent et gardez un œil sur vos paiements et dates d'examen.
                </p>
            </div>
            <div class="panel bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-400 text-white">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Smart Reminder Quiz</p>
                <p class="mt-4 text-sm leading-6 text-white/90">
                    Un mini-quiz à chaque connexion pour valider vos acquis de la session précédente.
                </p>
                <div class="mt-4">
                    @if ($hasQuestions)
                        <a href="{{ route('quiz.show') }}" class="btn-primary">Commencer le quiz</a>
                    @else
                        <span class="btn-ghost">Aucune question active</span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Statut</p>
                    @php
                        $statusLabel = ($user->status ?? 'active') === 'active' ? 'Actif' : ucfirst($user->status ?? 'actif');
                    @endphp
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $statusLabel }}</p>
                    <p class="mt-2 text-sm text-slate-500">Profil candidat Massar.</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Reste à payer</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ number_format((float) $user->balance_due, 2) }} TND</p>
                    <p class="mt-2 text-sm text-slate-500">Solde actuel chez l'auto-école.</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Dernier quiz</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">
                        {{ $lastQuiz ? $lastQuiz->score.'/'.$lastQuiz->total_questions : 'Aucun' }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">Score de votre dernière session.</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Prochain examen</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">
                        {{ $nextExam ? $nextExam->exam_date->format('d M Y') : 'À planifier' }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">Date proposée par votre auto-école.</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="panel">
                    <p class="kicker">Paiements récents</p>
                    <div class="mt-5 space-y-4">
                        @forelse ($latestPayments as $payment)
                            <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ number_format((float) $payment->amount, 2) }} TND</p>
                                    <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="status-pill status-pill-{{ $payment->status === 'paid' ? 'emerald' : ($payment->status === 'overdue' ? 'rose' : 'amber') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Aucun paiement enregistré pour le moment.</p>
                        @endforelse
                    </div>
                </div>

                <div class="panel">
                    <p class="kicker">Prochaines étapes</p>
                    <div class="mt-5 space-y-4 text-sm leading-6 text-slate-600">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            Répondez au quiz intelligent pour consolider vos acquis.
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            Vérifiez vos paiements et contactez l'auto-école si besoin.
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            Suivez vos notifications pour la date d'examen.
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
