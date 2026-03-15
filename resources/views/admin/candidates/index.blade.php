<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <p class="kicker">Candidats</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Trombinoscope candidats</h2>
            <p class="max-w-2xl text-base leading-7 text-slate-600">
                Suivez les profils, leur progression et leur situation financière.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($candidates as $candidate)
                    @php
                        $statusLabel = ($candidate->status ?? 'active') === 'active' ? 'Actif' : ucfirst($candidate->status ?? 'actif');
                        $balanceDue = (float) $candidate->balance_due;
                        if ($balanceDue <= 0) {
                            $financeLabel = 'Fully Paid';
                            $financeClass = 'emerald';
                        } elseif ($candidate->payments_count > 0) {
                            $financeLabel = 'Partially Paid';
                            $financeClass = 'amber';
                        } else {
                            $financeLabel = 'Payment Pending';
                            $financeClass = 'rose';
                        }
                        $registeredAt = $candidate->registered_at ?? $candidate->created_at;
                        $registeredLabel = $registeredAt
                            ? \Illuminate\Support\Carbon::parse($registeredAt)->format('d M Y')
                            : '—';
                    @endphp
                    <article class="candidate-card">
                        <div class="candidate-card-media">
                            <div class="candidate-card-photo">
                                {{ strtoupper(substr($candidate->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="space-y-4 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="kicker">Candidate</p>
                                    <h3 class="mt-2 text-xl font-extrabold tracking-tight text-slate-950">{{ $candidate->name }}</h3>
                                    @if ($candidate->phone)
                                        <p class="mt-1 text-sm text-slate-500">{{ $candidate->phone }}</p>
                                    @endif
                                    <p class="mt-1 text-xs text-slate-400">{{ $candidate->email }}</p>
                                </div>
                                <span class="status-pill status-pill-{{ ($candidate->status ?? 'active') === 'active' ? 'emerald' : 'slate' }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="grid gap-2 text-sm text-slate-600">
                                <div class="flex items-center justify-between">
                                    <span>Registration date</span>
                                    <span class="font-semibold text-slate-900">{{ $registeredLabel }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Learning progress</span>
                                    <span class="font-semibold text-slate-900">{{ $candidate->quiz_sessions_count }} quizzes</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Financial status</span>
                                    <span class="status-pill status-pill-{{ $financeClass }}">{{ $financeLabel }}</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 pt-2">
                                <button type="button" class="btn-neutral">Edit</button>
                                <button type="button" class="btn-ghost">View results</button>
                                <button type="button" class="btn-danger">Send reminder</button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
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
