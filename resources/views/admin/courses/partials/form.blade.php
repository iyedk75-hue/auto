<div class="grid gap-6">
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-6 rounded-[1.75rem] border border-slate-200 bg-slate-50/80 p-5">
            <div>
                <p class="kicker">{{ __('ui.admin_courses.french_section') }}</p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.title_fr') }}</label>
                <input type="text" name="title" class="form-input-auth" value="{{ old('title', $course->title) }}" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.description_fr') }}</label>
                <textarea name="description" rows="4" class="form-input-auth">{{ old('description', $course->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.content_fr') }}</label>
                <textarea name="content" rows="8" class="form-input-auth">{{ old('content', $course->content) }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />
            </div>
        </div>

        <div class="space-y-6 rounded-[1.75rem] border border-slate-200 bg-slate-50/80 p-5">
            <div>
                <p class="kicker">{{ __('ui.admin_courses.arabic_section') }}</p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.title_ar') }}</label>
                <input type="text" name="title_ar" class="form-input-auth" dir="rtl" value="{{ old('title_ar', $course->title_ar) }}" />
                <x-input-error :messages="$errors->get('title_ar')" class="mt-2" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.description_ar') }}</label>
                <textarea name="description_ar" rows="4" class="form-input-auth" dir="rtl">{{ old('description_ar', $course->description_ar) }}</textarea>
                <x-input-error :messages="$errors->get('description_ar')" class="mt-2" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.content_ar') }}</label>
                <textarea name="content_ar" rows="8" class="form-input-auth" dir="rtl">{{ old('content_ar', $course->content_ar) }}</textarea>
                <x-input-error :messages="$errors->get('content_ar')" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.category') }}</label>
            <select name="category" class="form-input-auth">
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $course->category) === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('category')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.cover') }}</label>
            <input type="file" name="cover" accept="image/*" class="form-input-auth" />
            <p class="mt-2 text-xs text-slate-400">{{ __('ui.admin_courses.cover_hint') }}</p>
            <x-input-error :messages="$errors->get('cover')" class="mt-2" />
            @if ($course->cover_path)
                <img src="{{ Storage::url($course->cover_path) }}" alt="Cover" class="mt-3 w-full rounded-2xl border border-slate-200 object-cover" />
            @endif
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.duration') }}</label>
            <input type="number" min="1" name="duration_minutes" class="form-input-auth" value="{{ old('duration_minutes', $course->duration_minutes) }}" />
            <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.sort_order') }}</label>
            <input type="number" min="0" name="sort_order" class="form-input-auth" value="{{ old('sort_order', $course->sort_order ?? 0) }}" />
            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
        </div>
        <div class="flex items-center gap-3 pt-7">
            <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-orange-600 shadow-sm" @checked(old('is_active', $course->is_active ?? true))>
            <label for="is_active" class="text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.active') }}</label>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.media') }}</label>
            <input type="file" name="media" accept="image/*,video/*" class="form-input-auth" />
            <p class="mt-2 text-xs text-slate-400">{{ __('ui.admin_courses.media_hint') }}</p>
            <x-input-error :messages="$errors->get('media')" class="mt-2" />
            @if ($course->media_path)
                <a href="{{ Storage::url($course->media_path) }}" class="mt-2 inline-flex text-xs font-semibold text-sky-600 underline" target="_blank" rel="noreferrer">
                    {{ __('ui.admin_courses.media_current') }}
                </a>
            @endif
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.pdf') }}</label>
            <input type="file" name="pdf" accept="application/pdf" class="form-input-auth" />
            <p class="mt-2 text-xs text-slate-400">{{ __('ui.admin_courses.pdf_hint') }}</p>
            <x-input-error :messages="$errors->get('pdf')" class="mt-2" />
            @if ($course->pdf_path)
                <a href="{{ Storage::url($course->pdf_path) }}" class="mt-2 inline-flex text-xs font-semibold text-sky-600 underline" target="_blank" rel="noreferrer">
                    {{ __('ui.admin_courses.pdf_current') }}
                </a>
            @endif
        </div>
    </div>
</div>
