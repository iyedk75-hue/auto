<div class="grid gap-6">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.full_name') }}</label>
        <input type="text" name="name" class="form-input-auth" value="{{ old('name', $candidate->name) }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.email') }}</label>
        <input type="email" name="email" class="form-input-auth" value="{{ old('email', $candidate->email) }}" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.phone') }}</label>
        <input type="text" name="phone" class="form-input-auth" value="{{ old('phone', $candidate->phone) }}" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.school') }}</label>
            @if (($canChooseSchool ?? true) === true)
                <select name="auto_school_id" class="form-input-auth">
                    <option value="">{{ __('ui.admin_candidates.no_school') }}</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('auto_school_id', $candidate->auto_school_id) == $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="auto_school_id" value="{{ old('auto_school_id', $candidate->auto_school_id ?? $schools->first()?->id) }}" />
                <div class="form-input-auth flex items-center bg-slate-50 text-slate-600">
                    {{ $schools->first()?->name ?? __('ui.admin_candidates.unassigned_school') }}
                </div>
            @endif
            <x-input-error :messages="$errors->get('auto_school_id')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.status') }}</label>
            <select name="status" class="form-input-auth">
                <option value="active" @selected(old('status', $candidate->status ?? 'active') === 'active')>{{ __('ui.admin_candidates.status_active') }}</option>
                <option value="inactive" @selected(old('status', $candidate->status ?? 'active') === 'inactive')>{{ __('ui.admin_candidates.status_inactive') }}</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.balance_due') }}</label>
        <input type="number" step="0.01" name="balance_due" class="form-input-auth" value="{{ old('balance_due', $candidate->balance_due ?? '0.00') }}" />
        <x-input-error :messages="$errors->get('balance_due')" class="mt-2" />
    </div>
</div>
