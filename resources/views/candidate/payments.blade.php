<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <p class="kicker">الدفعات</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">ملفك المالي.</h2>
            <p class="max-w-2xl text-base leading-7 text-slate-600">
                تابع المبالغ المدفوعة أو المعلقة أو المتأخرة، ثم أرسل إثبات التحويل البنكي إلى المشرف العام.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @php
                $statusLabels = [
                    'pending' => 'قيد الانتظار',
                    'paid' => 'مدفوع',
                    'overdue' => 'متأخر',
                ];
                $methodLabels = [
                    'manual' => 'إدخال إداري',
                    'bank_transfer' => 'تحويل بنكي',
                ];
            @endphp
            <section class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="panel space-y-4">
                    <div>
                        <p class="kicker">البيانات البنكية</p>
                        <h3 class="mt-3 text-2xl font-extrabold text-slate-950">الدفع عبر المعرف البنكي</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">أكمل التحويل إلى الحساب أدناه ثم أرسل إثباتك. سيقوم المشرف العام بتفعيل وصولك بعد التحقق.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                        <p><span class="font-semibold text-slate-950">صاحب الحساب:</span> {{ $bankDetails['account_holder'] }}</p>
                        <p class="mt-2"><span class="font-semibold text-slate-950">البنك:</span> {{ $bankDetails['bank_name'] }}</p>
                        <p class="mt-2"><span class="font-semibold text-slate-950">RIB / IBAN:</span> {{ $bankDetails['iban'] }}</p>
                    </div>
                    <p class="text-xs text-slate-500">الحد الأقصى للإثبات: {{ number_format($proofMaxKb / 1024, 0) }} MB.</p>
                </div>

                <div class="panel">
                    <p class="kicker">إرسال التحويل</p>
                    <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="mt-5 space-y-5">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">المبلغ (دينار)</label>
                                <input type="number" step="0.01" min="0.01" name="amount" class="form-input-auth" value="{{ old('amount') }}" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">مرجع التحويل</label>
                                <input type="text" name="transfer_reference" class="form-input-auth" value="{{ old('transfer_reference') }}" required />
                                <x-input-error :messages="$errors->get('transfer_reference')" class="mt-2" />
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">الإثبات البنكي</label>
                            <input type="file" name="proof_file" class="form-input-auth" accept=".pdf,image/*" required />
                            <x-input-error :messages="$errors->get('proof_file')" class="mt-2" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">ملاحظة</label>
                            <textarea name="note" rows="3" class="form-input-auth">{{ old('note') }}</textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>
                        <button type="submit" class="btn-admin-entry">{{ __('ui.payments.submit_bank_transfer') }}</button>
                    </form>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-3">
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">الإجمالي</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ number_format((float) $payments->getCollection()->sum('amount'), 2) }} TND</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">قيد الانتظار</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $payments->getCollection()->where('status', 'pending')->count() }}</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">متأخر</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $payments->getCollection()->where('status', 'overdue')->count() }}</p>
                </div>
            </section>

            <section class="panel space-y-4">
                <p class="kicker">السجل</p>
                @forelse ($payments as $payment)
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ number_format((float) $payment->amount, 2) }} TND</p>
                            <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $methodLabels[$payment->payment_method] ?? ucfirst($payment->payment_method) }}</p>
                            @if ($payment->transfer_reference)
                                <p class="text-xs text-slate-500">Réf. {{ $payment->transfer_reference }}</p>
                            @endif
                        </div>
                        <span class="status-pill status-pill-{{ $payment->status === 'paid' ? 'emerald' : ($payment->status === 'overdue' ? 'rose' : 'amber') }}">
                            {{ $statusLabels[$payment->status] ?? ucfirst($payment->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">لا توجد أي دفعات مسجلة.</p>
                @endforelse

                <div>
                    {{ $payments->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
