<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="kicker">{{ __('ui.admin_course_resources.kicker') }}</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_course_resources.create_title') }}</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.courses.resources.store', $course) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @include('admin.course-resources.partials.form', ['course' => $course, 'resource' => $resource, 'resourceTypes' => $resourceTypes])

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">{{ __('ui.admin_course_resources.save') }}</button>
                        <a href="{{ route('admin.courses.resources.index', $course) }}" class="btn-ghost">{{ __('ui.admin_course_resources.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
