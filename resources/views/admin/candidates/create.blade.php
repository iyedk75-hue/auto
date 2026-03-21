<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">{{ __('ui.admin_candidates.kicker') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_candidates.create_title') }}</h2>
            </div>
            <a href="{{ route('admin.candidates.index') }}" class="btn-ghost">{{ __('ui.admin_candidates.back') }}</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.candidates.store') }}" class="space-y-6">
                    @csrf
                    @include('admin.candidates.partials.form', ['candidate' => $candidate, 'schools' => $schools, 'canChooseSchool' => $canChooseSchool ?? true])

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.password') }}</label>
                            <input type="password" name="password" class="form-input-auth" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.password_confirmation') }}</label>
                            <input type="password" name="password_confirmation" class="form-input-auth" required />
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">{{ __('ui.admin_candidates.create_submit') }}</button>
                        <a href="{{ route('admin.candidates.index') }}" class="btn-ghost">{{ __('ui.admin_candidates.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
