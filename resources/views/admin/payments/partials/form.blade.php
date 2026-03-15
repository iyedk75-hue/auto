<div class="grid gap-6">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Candidat</label>
        <select name="user_id" class="form-input-auth">
            @foreach ($candidates as $candidate)
                <option value="{{ $candidate->id }}" @selected(old('user_id', $payment->user_id) == $candidate->id)>
                    {{ $candidate->name }} ({{ $candidate->email }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Montant (TND)</label>
            <input type="number" step="0.01" name="amount" class="form-input-auth" value="{{ old('amount', $payment->amount) }}" required />
            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Statut</label>
            <select name="status" class="form-input-auth">
                @foreach (\App\Models\PaymentRecord::statuses() as $status)
                    <option value="{{ $status }}" @selected(old('status', $payment->status ?? 'pending') === $status)>
                        {{ $status === 'paid' ? 'Payé' : ($status === 'overdue' ? 'En retard' : 'En attente') }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Date de paiement</label>
        <input type="datetime-local" name="paid_at" class="form-input-auth" value="{{ old('paid_at', optional($payment->paid_at)->format('Y-m-d\TH:i')) }}" />
        <x-input-error :messages="$errors->get('paid_at')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Note</label>
        <input type="text" name="note" class="form-input-auth" value="{{ old('note', $payment->note) }}" />
        <x-input-error :messages="$errors->get('note')" class="mt-2" />
    </div>
</div>
