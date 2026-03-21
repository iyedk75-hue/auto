<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-4">
                <p class="kicker">{{ __('ui.nav.school_admins') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ $school->name }}</h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Gérez les comptes admin rattachés à cette auto-école.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.auto-schools.index') }}" class="btn-ghost">Retour aux auto-écoles</a>
                <a href="{{ route('admin.auto-schools.admins.create', $school) }}" class="btn-admin-entry">Ajouter un admin</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Admins</p>
                    <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $school->admins_count }}</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Candidats</p>
                    <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $school->candidates_count }}</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Ville</p>
                    <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $school->city ?: '—' }}</p>
                </div>
            </section>

            <section class="candidate-list">
                @forelse ($admins as $admin)
                    <article class="candidate-row">
                        <div class="candidate-row-main">
                            <div class="candidate-avatar">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div class="candidate-identity">
                                <div class="candidate-name">
                                    <h3>{{ $admin->name }}</h3>
                                    <span class="status-pill status-pill-{{ ($admin->status ?? 'active') === 'active' ? 'emerald' : 'slate' }}">
                                        {{ ($admin->status ?? 'active') === 'active' ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                                <p class="candidate-meta">
                                    <span>{{ $admin->email }}</span>
                                    @if ($admin->phone)
                                        <span>• {{ $admin->phone }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="candidate-row-actions">
                            <a href="{{ route('admin.auto-schools.admins.edit', [$school, $admin]) }}" class="btn-neutral">Modifier</a>
                            <form method="POST" action="{{ route('admin.auto-schools.admins.destroy', [$school, $admin]) }}" onsubmit="return confirm('Supprimer ce compte admin ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                        <p class="text-sm font-semibold text-slate-700">Aucun admin rattaché à cette auto-école.</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</x-app-layout>