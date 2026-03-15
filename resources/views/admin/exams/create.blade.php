<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="kicker">Examens</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Planifier un examen</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.exams.store') }}" class="space-y-6">
                    @csrf
                    @include('admin.exams.partials.form', ['exam' => $exam, 'candidates' => $candidates, 'schools' => $schools])

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">Enregistrer</button>
                        <a href="{{ route('admin.exams.index') }}" class="btn-ghost">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
