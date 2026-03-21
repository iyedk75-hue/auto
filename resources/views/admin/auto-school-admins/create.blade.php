<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">{{ __('ui.nav.school_admins') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Ajouter un admin</h2>
            </div>
            <a href="{{ route('admin.auto-schools.admins.index', $school) }}" class="btn-ghost">Retour</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.auto-schools.admins.store', $school) }}" class="space-y-6">
                    @csrf
                    @include('admin.auto-school-admins.partials.form', ['admin' => $admin, 'school' => $school, 'requirePassword' => true])

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">Créer le compte</button>
                        <a href="{{ route('admin.auto-schools.admins.index', $school) }}" class="btn-ghost">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>