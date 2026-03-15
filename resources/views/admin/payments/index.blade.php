<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-2">
                <p class="kicker">Finance</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Paiements candidats</h2>
                <p class="text-sm text-slate-600">Suivez les montants payés, en attente ou en retard.</p>
            </div>
            <a href="{{ route('admin.payments.create') }}" class="btn-admin-entry">Ajouter un paiement</a>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.payments.index') }}" class="{{ $statusFilter === null ? 'btn-admin-entry' : 'btn-ghost' }}">Tous</a>
                <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="{{ $statusFilter === 'pending' ? 'btn-admin-entry' : 'btn-ghost' }}">En attente</a>
                <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}" class="{{ $statusFilter === 'paid' ? 'btn-admin-entry' : 'btn-ghost' }}">Payés</a>
                <a href="{{ route('admin.payments.index', ['status' => 'overdue']) }}" class="{{ $statusFilter === 'overdue' ? 'btn-admin-entry' : 'btn-ghost' }}">En retard</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @php
                $statusLabels = [
                    'pending' => 'En attente',
                    'paid' => 'Payé',
                    'overdue' => 'En retard',
                ];
            @endphp
            <div class="grid gap-4">
                @forelse ($payments as $payment)
                    <div class="panel flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $payment->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $payment->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ number_format((float) $payment->amount, 2) }} TND</p>
                            <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="status-pill status-pill-{{ $payment->status === 'paid' ? 'emerald' : ($payment->status === 'overdue' ? 'rose' : 'amber') }}">
                            {{ $statusLabels[$payment->status] ?? ucfirst($payment->status) }}
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.payments.edit', $payment) }}" class="btn-ghost">Modifier</a>
                            <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                        Aucun paiement trouvé.
                    </div>
                @endforelse
            </div>

            <div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
