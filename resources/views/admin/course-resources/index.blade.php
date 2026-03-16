<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">{{ __('ui.admin_course_resources.kicker') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_course_resources.index_title', ['course' => $course->title]) }}</h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">{{ __('ui.admin_course_resources.index_intro') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn-ghost">{{ __('ui.admin_course_resources.back_to_course') }}</a>
                <a href="{{ route('admin.courses.resources.create', $course) }}" class="btn-admin-entry">{{ __('ui.admin_course_resources.add_resource') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @forelse ($resources as $resource)
                <article class="panel">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="space-y-2">
                            <p class="kicker">{{ __('ui.admin_course_resources.types.'.$resource->resource_type) }}</p>
                            <h3 class="text-2xl font-extrabold tracking-tight text-slate-950">{{ $resource->title }}</h3>
                            @if ($resource->title_ar)
                                <p class="text-sm font-semibold text-slate-500">{{ $resource->title_ar }}</p>
                            @endif
                            @if ($resource->isNote() && $resource->note_body)
                                <p class="max-w-3xl text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit($resource->note_body, 180) }}</p>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.courses.resources.edit', [$course, $resource]) }}" class="btn-neutral">{{ __('ui.admin_course_resources.edit') }}</a>
                            <form method="POST" action="{{ route('admin.courses.resources.destroy', [$course, $resource]) }}" onsubmit="return confirm('{{ __('ui.admin_course_resources.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">{{ __('ui.admin_course_resources.delete') }}</button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-3 text-sm text-slate-600 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_course_resources.order_value') }}</span>
                            <span class="mt-2 block font-semibold text-slate-900">{{ $resource->sort_order }}</span>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_course_resources.type_value') }}</span>
                            <span class="mt-2 block font-semibold text-slate-900">{{ __('ui.admin_course_resources.types.'.$resource->resource_type) }}</span>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_course_resources.created_at') }}</span>
                            <span class="mt-2 block font-semibold text-slate-900">{{ $resource->created_at?->format('d M Y') }}</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                    <p class="text-sm font-semibold text-slate-700">{{ __('ui.admin_course_resources.empty') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
