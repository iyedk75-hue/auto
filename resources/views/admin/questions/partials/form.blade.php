@php
    $optionMap = $question->options?->keyBy('option_id') ?? collect();
@endphp

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">الفئة</label>
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
            <label class="mb-2 block text-sm font-semibold text-slate-700">الصعوبة</label>
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
        <label class="mb-2 block text-sm font-semibold text-slate-700">السؤال</label>
        <textarea name="question_text" rows="3" class="form-input-auth">{{ old('question_text', $question->question_text) }}</textarea>
        <x-input-error :messages="$errors->get('question_text')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">صورة الاختبار</label>
        <input type="file" name="image" accept="image/*" class="form-input-auth" />
        <p class="mt-2 text-xs text-slate-400">الصيغ المقبولة: JPG و PNG و WEBP. الحد الأقصى: 10 MB.</p>
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
        @if (old('image_url', $question->image_url))
            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-3">
                <img src="{{ old('image_url', $question->image_url) }}" alt="معاينة السؤال" class="max-h-64 w-full rounded-xl object-contain bg-white" />
            </div>
        @endif
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">الصورة عبر رابط خارجي</label>
        <input type="url" name="image_url" class="form-input-auth" value="{{ old('image_url', $question->externalImageUrl()) }}" placeholder="https://" />
        <p class="mt-2 text-xs text-slate-400">اختياري إذا كنت تفضّل استعمال صورة مستضافة مسبقًا.</p>
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
            <label class="mb-2 block text-sm font-semibold text-slate-700">الإجابة الصحيحة</label>
            <select name="correct_answer" class="form-input-auth">
                @foreach (['أ', 'ب', 'ج'] as $answer)
                    <option value="{{ $answer }}" @selected(old('correct_answer', $question->correct_answer) === $answer)>{{ $answer }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('correct_answer')" class="mt-2" />
        </div>
        <div class="flex items-center gap-3 pt-7">
            <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-orange-600 shadow-sm" @checked(old('is_active', $question->is_active ?? true))>
            <label for="is_active" class="text-sm font-semibold text-slate-700">سؤال نشط</label>
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">شرح بيداغوجي</label>
        <textarea name="explanation" rows="3" class="form-input-auth">{{ old('explanation', $question->explanation) }}</textarea>
        <x-input-error :messages="$errors->get('explanation')" class="mt-2" />
    </div>
</div>
