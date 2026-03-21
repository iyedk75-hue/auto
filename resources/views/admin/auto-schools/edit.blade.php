<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">Auto-écoles</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Modifier une auto-école</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.auto-schools.admins.index', $school) }}" class="btn-neutral">Admins</a>
                <a href="{{ route('admin.auto-schools.index') }}" class="btn-ghost">Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.auto-schools.update', $school) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    @include('admin.auto-schools.partials.form', ['school' => $school])

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">Sauvegarder</button>
                        <a href="{{ route('admin.auto-schools.index') }}" class="btn-ghost">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
