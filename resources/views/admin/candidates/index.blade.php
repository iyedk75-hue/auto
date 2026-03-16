<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="kicker">Candidats</p>
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-950">Trombinoscope candidats</h2>
            <p class="max-w-xl text-sm leading-6 text-slate-600">
                Suivez les profils, leur progression et leur situation financière.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="candidate-toolbar">
                <form method="GET" action="{{ route('admin.candidates.index') }}" class="candidate-search">
                    <div class="candidate-search-input">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search ?? '' }}"
                            placeholder="Rechercher un client (nom, email, ID)..."
                            autocomplete="off"
                        />
                        <button type="submit" class="candidate-search-button">Search</button>
                    </div>
                    @if (!empty($search))
                        <a href="{{ route('admin.candidates.index') }}" class="btn-ghost">Réinitialiser</a>
                    @endif
                </form>
                <div class="candidate-count">
                    {{ $candidates->total() }} candidat{{ $candidates->total() > 1 ? 's' : '' }}
                </div>
            </div>

            <section class="candidate-list">
                @forelse ($candidates as $candidate)
                    @php
                        $statusLabel = ($candidate->status ?? 'active') === 'active' ? 'Actif' : ucfirst($candidate->status ?? 'actif');
                        $registeredAt = $candidate->registered_at ?? $candidate->created_at;
                        $registeredLabel = $registeredAt
                            ? \Illuminate\Support\Carbon::parse($registeredAt)->format('d M Y')
                            : '—';
                    @endphp
                    <article class="candidate-row">
                        <div class="candidate-row-main">
                            <div class="candidate-avatar">
                                {{ strtoupper(substr($candidate->name, 0, 1)) }}
                            </div>
                            <div class="candidate-identity">
                                <div class="candidate-name">
                                    <h3>{{ $candidate->name }}</h3>
                                    <span class="status-pill status-pill-{{ ($candidate->status ?? 'active') === 'active' ? 'emerald' : 'slate' }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <p class="candidate-meta">
                                    <span>{{ $candidate->email }}</span>
                                    @if ($candidate->phone)
                                        <span>• {{ $candidate->phone }}</span>
                                    @endif
                                </p>
                            <p class="candidate-sub">
                                    <span>{{ $candidate->autoSchool?->name ?? 'Auto-école' }}</span>
                                    <span>• Inscrit le {{ $registeredLabel }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="candidate-row-actions">
                        <a href="{{ route('admin.candidates.show', $candidate) }}" class="btn-ghost">Voir</a>
                        <a href="{{ route('admin.candidates.edit', $candidate) }}" class="btn-neutral">Modifier</a>
                            <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" onsubmit="return confirm('Supprimer ce candidat ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                        <p class="text-sm font-semibold text-slate-700">Aucun candidat enregistré pour le moment.</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $candidates->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
