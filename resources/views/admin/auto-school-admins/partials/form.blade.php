<div class="grid gap-6">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Auto-école</label>
        <div class="form-input-auth flex items-center bg-slate-50 text-slate-600">{{ $school->name }}</div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Nom complet</label>
        <input type="text" name="name" class="form-input-auth" value="{{ old('name', $admin->name) }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
        <input type="email" name="email" class="form-input-auth" value="{{ old('email', $admin->email) }}" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Téléphone</label>
        <input type="text" name="phone" class="form-input-auth" value="{{ old('phone', $admin->phone) }}" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Statut</label>
            <select name="status" class="form-input-auth">
                <option value="active" @selected(old('status', $admin->status ?? 'active') === 'active')>Actif</option>
                <option value="inactive" @selected(old('status', $admin->status ?? 'active') === 'inactive')>Inactif</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ $requirePassword ? 'Mot de passe' : 'Nouveau mot de passe' }}</label>
            <input type="password" name="password" class="form-input-auth" @required($requirePassword) />
            @unless ($requirePassword)
                <p class="mt-2 text-xs text-slate-500">Laissez vide pour conserver le mot de passe actuel.</p>
            @endunless
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-input-auth" @required($requirePassword) />
        </div>
    </div>
</div>