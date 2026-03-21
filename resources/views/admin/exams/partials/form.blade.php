<div class="grid gap-6">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">المترشح</label>
        <select name="user_id" class="form-input-auth">
            @foreach ($candidates as $candidate)
                <option value="{{ $candidate->id }}" @selected(old('user_id', $exam->user_id) == $candidate->id)>
                    {{ $candidate->name }} ({{ $candidate->email }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">مدرسة السياقة</label>
        @if (($canChooseSchool ?? true) === true)
            <select name="auto_school_id" class="form-input-auth">
                <option value="">لا شيء</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" @selected(old('auto_school_id', $exam->auto_school_id) == $school->id)>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        @else
            <input type="hidden" name="auto_school_id" value="{{ old('auto_school_id', $exam->auto_school_id ?? $schools->first()?->id) }}" />
            <div class="form-input-auth flex items-center bg-slate-50 text-slate-600">
                {{ $schools->first()?->name ?? 'مدرسة سياقة غير معيّنة' }}
            </div>
        @endif
        <x-input-error :messages="$errors->get('auto_school_id')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">تاريخ الامتحان</label>
            <input type="date" name="exam_date" class="form-input-auth" value="{{ old('exam_date', optional($exam->exam_date)->format('Y-m-d')) }}" required />
            <x-input-error :messages="$errors->get('exam_date')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">الحالة</label>
            <select name="status" class="form-input-auth">
                @foreach (\App\Models\ExamSchedule::statusLabels() as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $exam->status ?? 'planned') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">ملاحظة</label>
        <input type="text" name="note" class="form-input-auth" value="{{ old('note', $exam->note) }}" />
        <x-input-error :messages="$errors->get('note')" class="mt-2" />
    </div>
</div>
