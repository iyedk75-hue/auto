<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="kicker">{{ __('ui.admin_courses.kicker') }}</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_courses.edit_title') }}</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.courses.partials.form', ['course' => $course, 'categories' => $categories])

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">{{ __('ui.admin_courses.update') }}</button>
                        <a href="{{ route('admin.courses.index') }}" class="btn-ghost">{{ __('ui.admin_courses.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
