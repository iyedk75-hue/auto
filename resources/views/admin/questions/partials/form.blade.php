@php
    $optionMap = $question->options?->keyBy('option_id') ?? collect();
@endphp

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Catégorie</label>
            <select name="category" class="form-input-auth">
                @foreach (\App\Models\Question::CATEGORIES as $category)
                    <option value="{{ $category }}" @selected(old('category', $question->category) === $category)>
                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('category')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Difficulté</label>
            <select name="difficulty" class="form-input-auth">
                @foreach (\App\Models\Question::DIFFICULTIES as $difficulty)
                    <option value="{{ $difficulty }}" @selected(old('difficulty', $question->difficulty ?? 'easy') === $difficulty)>
                        {{ ucfirst($difficulty) }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('difficulty')" class="mt-2" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Question</label>
        <textarea name="question_text" rows="3" class="form-input-auth">{{ old('question_text', $question->question_text) }}</textarea>
        <x-input-error :messages="$errors->get('question_text')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Image (URL)</label>
        <input type="url" name="image_url" class="form-input-auth" value="{{ old('image_url', $question->image_url) }}" placeholder="https://" />
        <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Choix أ</label>
            <input type="text" name="option_a" class="form-input-auth" value="{{ old('option_a', $optionMap->get('أ')?->text) }}" required />
            <x-input-error :messages="$errors->get('option_a')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Choix ب</label>
            <input type="text" name="option_b" class="form-input-auth" value="{{ old('option_b', $optionMap->get('ب')?->text) }}" required />
            <x-input-error :messages="$errors->get('option_b')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Choix ج</label>
            <input type="text" name="option_c" class="form-input-auth" value="{{ old('option_c', $optionMap->get('ج')?->text) }}" />
            <x-input-error :messages="$errors->get('option_c')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Bonne réponse</label>
            <select name="correct_answer" class="form-input-auth">
                @foreach (['أ', 'ب', 'ج'] as $answer)
                    <option value="{{ $answer }}" @selected(old('correct_answer', $question->correct_answer) === $answer)>{{ $answer }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('correct_answer')" class="mt-2" />
        </div>
        <div class="flex items-center gap-3 pt-7">
            <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-orange-600 shadow-sm" @checked(old('is_active', $question->is_active ?? true))>
            <label for="is_active" class="text-sm font-semibold text-slate-700">Question active</label>
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Explication pédagogique</label>
        <textarea name="explanation" rows="3" class="form-input-auth">{{ old('explanation', $question->explanation) }}</textarea>
        <x-input-error :messages="$errors->get('explanation')" class="mt-2" />
    </div>
</div>
