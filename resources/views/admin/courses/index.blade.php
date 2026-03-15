<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">{{ __('ui.admin_courses.kicker') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_courses.index_title') }}</h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    {{ __('ui.admin_courses.index_intro') }}
                </p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn-admin-entry">{{ __('ui.admin_courses.add_course') }}</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($courses as $course)
                    <article class="panel">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="kicker">{{ $categoryLabels[$course->category] ?? $course->category }}</p>
                                <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950">{{ $course->title }}</h3>
                                @if ($course->title_ar)
                                    <p class="mt-2 text-sm font-semibold text-slate-500">{{ $course->title_ar }}</p>
                                @endif
                                @if ($course->description)
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $course->description }}</p>
                                @endif
                            </div>
                            <span class="status-pill status-pill-{{ $course->is_active ? 'emerald' : 'slate' }}">
                                {{ $course->is_active ? __('ui.admin_courses.status_active') : __('ui.admin_courses.status_inactive') }}
                            </span>
                        </div>

                        <div class="mt-6 grid gap-3 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>{{ __('ui.admin_courses.duration_value') }}</span>
                                <span class="font-semibold text-slate-900">
                                    {{ $course->duration_minutes ? $course->duration_minutes.' min' : '—' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ __('ui.admin_courses.order_value') }}</span>
                                <span class="font-semibold text-slate-900">{{ $course->sort_order }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ __('ui.admin_courses.assets') }}</span>
                                <span class="font-semibold text-slate-900">
                                    @if ($course->media_path)
                                        {{ \Illuminate\Support\Str::startsWith($course->media_mime ?? '', 'image/') ? __('ui.admin_courses.image') : __('ui.admin_courses.video') }}
                                    @else
                                        {{ __('ui.admin_courses.no_media') }}
                                    @endif
                                    @if ($course->pdf_path)
                                        · PDF
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ __('ui.admin_courses.resource_count') }}</span>
                                <span class="font-semibold text-slate-900">{{ trans_choice('ui.admin_courses.resource_items', $course->resources_count) }}</span>
                            </div>
                        </div>

                        @php
                            $hasLegacySupports = ! $course->resources_count && ($course->media_path || $course->pdf_path);
                            $resourceStateLabel = $course->resources_count
                                ? __('ui.admin_courses.resource_state_child')
                                : ($hasLegacySupports ? __('ui.admin_courses.resource_state_legacy') : __('ui.admin_courses.resource_state_empty'));
                        @endphp

                        <div class="mt-4 inline-flex rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                            {{ $resourceStateLabel }}
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn-neutral">{{ __('ui.admin_courses.edit') }}</a>
                            <a href="{{ route('admin.courses.resources.index', $course) }}" class="btn-primary">{{ __('ui.admin_courses.manage_resources') }}</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('ui.admin_courses.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">{{ __('ui.admin_courses.delete') }}</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                        <p class="text-sm font-semibold text-slate-700">{{ __('ui.admin_courses.empty') }}</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
