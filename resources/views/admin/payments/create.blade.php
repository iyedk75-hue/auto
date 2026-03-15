<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="kicker">Finance</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Ajouter un paiement</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-6">
                    @csrf
                    @include('admin.payments.partials.form', ['payment' => $payment, 'candidates' => $candidates])

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">Enregistrer</button>
                        <a href="{{ route('admin.payments.index') }}" class="btn-ghost">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
