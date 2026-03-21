<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-2">
                <p class="kicker">Finance</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Paiements candidats</h2>
                <p class="text-sm text-slate-600">Suivez les montants payés, en attente ou en retard.</p>
            </div>
            <a href="{{ route('admin.payments.create') }}" class="btn-admin-entry">Ajouter un paiement</a>
            <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap items-center gap-3">
                <label for="payment_auto_school_id" class="text-sm font-semibold text-slate-600">Auto-école</label>
                <select id="payment_auto_school_id" name="auto_school_id" class="form-input-auth max-w-xs" onchange="this.form.submit()">
                    <option value="">Toutes</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected((string) $selectedSchoolId === (string) $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @if ($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}" />
                @endif
                @if ($pendingProofReview)
                    <input type="hidden" name="review" value="proof-pending" />
                @endif
                @if ($selectedSchoolId || $statusFilter)
                    <a href="{{ route('admin.payments.index') }}" class="btn-ghost">Réinitialiser</a>
                @endif
            </form>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.payments.index', array_filter(['auto_school_id' => $selectedSchoolId, 'review' => $pendingProofReview ? 'proof-pending' : null])) }}" class="{{ $statusFilter === null ? 'btn-admin-entry' : 'btn-ghost' }}">Tous</a>
                <a href="{{ route('admin.payments.index', array_filter(['status' => 'pending', 'auto_school_id' => $selectedSchoolId, 'review' => $pendingProofReview ? 'proof-pending' : null])) }}" class="{{ $statusFilter === 'pending' ? 'btn-admin-entry' : 'btn-ghost' }}">En attente</a>
                <a href="{{ route('admin.payments.index', array_filter(['status' => 'paid', 'auto_school_id' => $selectedSchoolId, 'review' => $pendingProofReview ? 'proof-pending' : null])) }}" class="{{ $statusFilter === 'paid' ? 'btn-admin-entry' : 'btn-ghost' }}">Payés</a>
                <a href="{{ route('admin.payments.index', array_filter(['status' => 'overdue', 'auto_school_id' => $selectedSchoolId, 'review' => $pendingProofReview ? 'proof-pending' : null])) }}" class="{{ $statusFilter === 'overdue' ? 'btn-admin-entry' : 'btn-ghost' }}">En retard</a>
                <a href="{{ route('admin.payments.index', array_filter(['auto_school_id' => $selectedSchoolId, 'status' => $statusFilter, 'review' => 'proof-pending'])) }}" class="{{ $pendingProofReview ? 'btn-admin-entry' : 'btn-ghost' }}">Justificatifs à valider</a>
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
                $methodLabels = [
                    'manual' => 'Saisie admin',
                    'bank_transfer' => 'Virement bancaire',
                ];
            @endphp
            <div class="grid gap-4">
                @forelse ($payments as $payment)
                    <div class="panel flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $payment->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $payment->user->email }}</p>
                            <p class="text-xs text-slate-500">
                                @if ($payment->user->autoSchool)
                                    <a href="{{ route('admin.payments.index', array_filter(['auto_school_id' => $payment->user->autoSchool->id, 'status' => $statusFilter])) }}" class="font-semibold text-orange-600 hover:text-orange-700 hover:underline">
                                        {{ $payment->user->autoSchool->name }}
                                    </a>
                                @else
                                    Auto-école
                                @endif
                            </p>
                                <p class="text-xs text-slate-500">{{ $methodLabels[$payment->payment_method] ?? ucfirst($payment->payment_method) }}</p>
                                @if ($payment->transfer_reference)
                                    <p class="text-xs text-slate-500">Réf. {{ $payment->transfer_reference }}</p>
                                @endif
                                @if ($payment->payment_method === \App\Models\PaymentRecord::METHOD_BANK_TRANSFER && $payment->status === \App\Models\PaymentRecord::STATUS_PENDING && $payment->proof_path)
                                    <p class="text-xs font-semibold text-amber-700">Justificatif bancaire en attente de validation</p>
                                @endif
                                @if ($payment->reviewedBy)
                                    <p class="text-xs text-slate-500">Validé par {{ $payment->reviewedBy->name }}</p>
                                @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ number_format((float) $payment->amount, 2) }} TND</p>
                            <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="status-pill status-pill-{{ $payment->status === 'paid' ? 'emerald' : ($payment->status === 'overdue' ? 'rose' : 'amber') }}">
                            {{ $statusLabels[$payment->status] ?? ucfirst($payment->status) }}
                        </span>
                        <div class="flex items-center gap-2">
                            @if ($payment->proof_path)
                                <a href="{{ route('admin.payments.proof', $payment) }}" class="btn-neutral" target="_blank" rel="noreferrer">Justificatif</a>
                            @endif
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
