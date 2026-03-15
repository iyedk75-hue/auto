<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-2">
                <p class="kicker">Questions</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Banque de questions</h2>
                <p class="text-sm text-slate-600">Gérez les questions officielles du code de la route.</p>
            </div>
            <a href="{{ route('admin.questions.create') }}" class="btn-admin-entry">Ajouter une question</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6">
                @forelse ($questions as $question)
                    <article class="panel">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="kicker">{{ ucfirst(str_replace('_', ' ', $question->category)) }}</p>
                                <h3 class="mt-2 text-2xl font-extrabold text-slate-950">{{ $question->question_text }}</h3>
                                <p class="mt-2 text-sm text-slate-500">Difficulté : {{ ucfirst($question->difficulty) }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="status-pill status-pill-{{ $question->is_active ? 'emerald' : 'slate' }}">
                                    {{ $question->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <a href="{{ route('admin.questions.edit', $question) }}" class="btn-ghost">Modifier</a>
                                <form method="POST" action="{{ route('admin.questions.destroy', $question) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                        Aucune question disponible.
                    </div>
                @endforelse
            </div>

            <div>
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
