<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.type') }}</label>
            <select name="resource_type" class="form-input-auth">
                @foreach ($resourceTypes as $type)
                    <option value="{{ $type }}" @selected(old('resource_type', $resource->resource_type) === $type)>
                        {{ __('ui.admin_course_resources.types.'.$type) }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('resource_type')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.sort_order') }}</label>
            <input type="number" min="0" name="sort_order" class="form-input-auth" value="{{ old('sort_order', $resource->sort_order ?? 0) }}" />
            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-6 rounded-[1.75rem] border border-slate-200 bg-slate-50/80 p-5">
            <div>
                <p class="kicker">{{ __('ui.admin_courses.french_section') }}</p>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.title_fr') }}</label>
                <input type="text" name="title" class="form-input-auth" value="{{ old('title', $resource->title) }}" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.note_body_fr') }}</label>
                <textarea name="note_body" rows="8" class="form-input-auth">{{ old('note_body', $resource->note_body) }}</textarea>
                <x-input-error :messages="$errors->get('note_body')" class="mt-2" />
            </div>
        </div>

        <div class="space-y-6 rounded-[1.75rem] border border-slate-200 bg-slate-50/80 p-5">
            <div>
                <p class="kicker">{{ __('ui.admin_courses.arabic_section') }}</p>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.title_ar') }}</label>
                <input type="text" name="title_ar" class="form-input-auth" dir="rtl" value="{{ old('title_ar', $resource->title_ar) }}" />
                <x-input-error :messages="$errors->get('title_ar')" class="mt-2" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.note_body_ar') }}</label>
                <textarea name="note_body_ar" rows="8" class="form-input-auth" dir="rtl">{{ old('note_body_ar', $resource->note_body_ar) }}</textarea>
                <x-input-error :messages="$errors->get('note_body_ar')" class="mt-2" />
            </div>
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.resource_file') }}</label>
        <input type="file" name="resource_file" class="form-input-auth" accept="audio/*" />
        <p class="mt-2 text-xs text-slate-400">{{ __('ui.admin_course_resources.file_hint_audio') }}</p>
        <x-input-error :messages="$errors->get('resource_file')" class="mt-2" />
        @if ($resource->file_path)
            <p class="mt-2 text-xs font-semibold text-sky-600">
                {{ __('ui.admin_course_resources.current_file') }}
            </p>
        @endif
    </div>

    <div class="flex items-center gap-3">
        <input id="resource_is_active" type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-orange-600 shadow-sm" @checked(old('is_active', $resource->is_active ?? true))>
        <label for="resource_is_active" class="text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.active') }}</label>
    </div>
</div>
