<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-4">
                <p class="kicker">Auto-écoles</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Gestion des auto-écoles</h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Gérez les auto-écoles partenaires de la plateforme.
                </p>
            </div>
            <a href="{{ route('admin.auto-schools.create') }}" class="btn-admin-entry">Ajouter</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="candidate-list">
                @forelse ($schools as $school)
                    <article class="candidate-row">
                        <div class="candidate-row-main">
                            <div class="candidate-avatar">
                                {{ strtoupper(substr($school->name, 0, 1)) }}
                            </div>
                            <div class="candidate-identity">
                                <div class="candidate-name">
                                    <h3>{{ $school->name }}</h3>
                                    <span class="status-pill status-pill-{{ $school->is_active ? 'emerald' : 'slate' }}">
                                        {{ $school->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <p class="candidate-meta">
                                    @if ($school->city)
                                        <span>{{ $school->city }}</span>
                                    @endif
                                    @if ($school->whatsapp_phone)
                                        <span>• {{ $school->whatsapp_phone }}</span>
                                    @endif
                                </p>
                                <p class="candidate-sub">
                                    <span>{{ $school->candidates_count }} candidat{{ $school->candidates_count > 1 ? 's' : '' }}</span>
                                    <span>• {{ $school->admins_count }} admin{{ $school->admins_count > 1 ? 's' : '' }}</span>
                                    @if ($school->address)
                                        <span>• {{ $school->address }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="candidate-row-actions">
                            <a href="{{ route('admin.auto-schools.admins.index', $school) }}" class="btn-ghost">Admins</a>
                            <a href="{{ route('admin.auto-schools.edit', $school) }}" class="btn-neutral">Modifier</a>
                            <form method="POST" action="{{ route('admin.auto-schools.destroy', $school) }}" onsubmit="return confirm('Supprimer cette auto-école ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                        <p class="text-sm font-semibold text-slate-700">Aucune auto-école enregistrée.</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $schools->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
