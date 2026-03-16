<div class="grid gap-6">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Candidat</label>
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
        <label class="mb-2 block text-sm font-semibold text-slate-700">Auto-école</label>
        <select name="auto_school_id" class="form-input-auth">
            <option value="">Aucune</option>
            @foreach ($schools as $school)
                <option value="{{ $school->id }}" @selected(old('auto_school_id', $exam->auto_school_id) == $school->id)>
                    {{ $school->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('auto_school_id')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Date d'examen</label>
            <input type="date" name="exam_date" class="form-input-auth" value="{{ old('exam_date', optional($exam->exam_date)->format('Y-m-d')) }}" required />
            <x-input-error :messages="$errors->get('exam_date')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Statut</label>
            <select name="status" class="form-input-auth">
                @foreach (\App\Models\ExamSchedule::statuses() as $status)
                    <option value="{{ $status }}" @selected(old('status', $exam->status ?? 'planned') === $status)>
                        Planifié
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Note</label>
        <input type="text" name="note" class="form-input-auth" value="{{ old('note', $exam->note) }}" />
        <x-input-error :messages="$errors->get('note')" class="mt-2" />
    </div>
</div>
