<div class="grid gap-6">
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Nom</label>
        <input type="text" name="name" class="form-input-auth" value="{{ old('name', $school->name) }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Ville</label>
            <input type="text" name="city" class="form-input-auth" value="{{ old('city', $school->city) }}" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Téléphone WhatsApp</label>
            <input type="text" name="whatsapp_phone" class="form-input-auth" value="{{ old('whatsapp_phone', $school->whatsapp_phone) }}" />
            <x-input-error :messages="$errors->get('whatsapp_phone')" class="mt-2" />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">Adresse</label>
        <input type="text" name="address" class="form-input-auth" value="{{ old('address', $school->address) }}" />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <label class="flex items-center gap-3">
            <input type="hidden" name="is_active" value="0" />
            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-orange-600" @checked(old('is_active', $school->is_active ?? true)) />
            <span class="text-sm font-semibold text-slate-700">Active</span>
        </label>
    </div>
</div>
