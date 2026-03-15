<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <p class="kicker">Paiements</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Votre fiche financière.</h2>
            <p class="max-w-2xl text-base leading-7 text-slate-600">
                Suivez les montants payés, en attente ou en retard.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @php
                $statusLabels = [
                    'pending' => 'En attente',
                    'paid' => 'Payé',
                    'overdue' => 'En retard',
                ];
            @endphp
            <section class="grid gap-4 md:grid-cols-3">
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Total</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ number_format((float) $payments->getCollection()->sum('amount'), 2) }} TND</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">En attente</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $payments->getCollection()->where('status', 'pending')->count() }}</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">En retard</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $payments->getCollection()->where('status', 'overdue')->count() }}</p>
                </div>
            </section>

            <section class="panel space-y-4">
                <p class="kicker">Historique</p>
                @forelse ($payments as $payment)
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ number_format((float) $payment->amount, 2) }} TND</p>
                            <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="status-pill status-pill-{{ $payment->status === 'paid' ? 'emerald' : ($payment->status === 'overdue' ? 'rose' : 'amber') }}">
                            {{ $statusLabels[$payment->status] ?? ucfirst($payment->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun paiement enregistré.</p>
                @endforelse

                <div>
                    {{ $payments->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
