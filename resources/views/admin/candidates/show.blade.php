<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">Candidats</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Profil candidat</h2>
            </div>
            <a href="{{ route('admin.candidates.index') }}" class="btn-ghost">Retour</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="candidate-avatar">
                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-2xl font-extrabold text-slate-950">{{ $candidate->name }}</h3>
                                <span class="status-pill status-pill-{{ ($candidate->status ?? 'active') === 'active' ? 'emerald' : 'slate' }}">
                                    {{ ($candidate->status ?? 'active') === 'active' ? 'Actif' : ucfirst($candidate->status ?? 'actif') }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-500">{{ $candidate->email }}</p>
                            @if ($candidate->phone)
                                <p class="text-sm text-slate-500">{{ $candidate->phone }}</p>
                            @endif
                            <p class="text-sm text-slate-500">{{ $candidate->autoSchool?->name ?? 'Auto-école' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.candidates.edit', $candidate) }}" class="btn-neutral">Modifier</a>
                        <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" onsubmit="return confirm('Supprimer ce candidat ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Inscription</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">
                            {{ optional($candidate->registered_at ?? $candidate->created_at)->format('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Solde dû</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ number_format((float) $candidate->balance_due, 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Quiz passés</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $candidate->quiz_sessions_count }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Examens</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $candidate->exams_count }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
