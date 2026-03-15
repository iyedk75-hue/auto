<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <p class="kicker">Smart Reminder Quiz</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Répondez à une question rapide.</h2>
            <p class="max-w-2xl text-base leading-7 text-slate-600">
                Une question à chaque connexion pour consolider vos réflexes avant l'examen.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($quizResult)
                <div class="panel">
                    <p class="kicker">Résultat</p>
                    <h3 class="mt-3 text-2xl font-extrabold text-slate-950">
                        {{ $quizResult['is_correct'] ? 'Bonne réponse !' : 'Réponse incorrecte.' }}
                    </h3>
                    <p class="mt-3 text-sm text-slate-600">
                        Votre réponse : {{ $quizResult['selected_option'] }} | Réponse correcte : {{ $quizResult['correct_answer'] }}
                    </p>
                    @if (! empty($quizResult['explanation']))
                        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            {{ $quizResult['explanation'] }}
                        </div>
                    @endif
                </div>
            @endif

            @if ($question)
                <form method="POST" action="{{ route('quiz.submit') }}" class="panel space-y-5">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}" />

                    <div>
                        <p class="kicker">Question</p>
                        <h3 class="mt-3 text-2xl font-extrabold text-slate-950">{{ $question->question_text }}</h3>
                        @if ($question->image_url)
                            <img src="{{ $question->image_url }}" alt="Illustration de la situation" class="mt-4 rounded-2xl border border-slate-200" />
                        @endif
                    </div>

                    <div class="space-y-3">
                        @foreach ($question->options->sortBy('option_id') as $option)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
                                <input type="radio" name="selected_option" value="{{ $option->option_id }}" class="h-4 w-4 text-orange-600" required>
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-50 text-xs font-bold text-orange-600">
                                    {{ $option->option_id }}
                                </span>
                                <span>{{ $option->text }}</span>
                            </label>
                        @endforeach
                        <x-input-error :messages="$errors->get('selected_option')" class="mt-2" />
                    </div>

                    <button type="submit" class="btn-primary">Valider ma réponse</button>
                </form>
            @else
                <div class="panel">
                    <p class="text-sm text-slate-600">Aucune question active pour le moment.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
