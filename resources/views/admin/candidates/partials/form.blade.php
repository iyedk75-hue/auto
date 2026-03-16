<div class="grid gap-6">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Nom complet</label>
        <input type="text" name="name" class="form-input-auth" value="{{ old('name', $candidate->name) }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
        <input type="email" name="email" class="form-input-auth" value="{{ old('email', $candidate->email) }}" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Téléphone</label>
        <input type="text" name="phone" class="form-input-auth" value="{{ old('phone', $candidate->phone) }}" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Auto-école</label>
            <select name="auto_school_id" class="form-input-auth">
                <option value="">Aucune</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" @selected(old('auto_school_id', $candidate->auto_school_id) == $school->id)>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('auto_school_id')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Statut</label>
            <select name="status" class="form-input-auth">
                <option value="active" @selected(old('status', $candidate->status ?? 'active') === 'active')>Actif</option>
                <option value="inactive" @selected(old('status', $candidate->status ?? 'active') === 'inactive')>Inactif</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>
</div>
