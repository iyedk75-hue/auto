<x-app-layout>
    <div class="py-10">
        <div class="mx-auto max-w-5xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="classroom-hero">
                <div class="classroom-hero-banner" @if ($course->cover_path) style="background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.5), rgba(30, 58, 138, 0.4)), url('{{ Storage::url($course->cover_path) }}'); background-size: cover; background-position: center;" @endif></div>
                <div class="classroom-hero-content">
                    <span class="classroom-pill">{{ $categoryLabels[$course->category] ?? ucfirst(str_replace('_', ' ', $course->category)) }}</span>
                    <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ $course->title }}</h2>
                    @if ($course->duration_minutes)
                        <p class="text-sm font-semibold text-slate-500">{{ __('ui.classroom.estimated_duration', ['minutes' => $course->duration_minutes]) }}</p>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-6">
                    @if ($course->media_path)
                        @php
                            $mediaUrl = Storage::url($course->media_path);
                        @endphp
                        <div class="classroom-section">
                            <p class="classroom-section-title">{{ __('ui.classroom.primary_support') }}</p>
                            <div class="mt-4">
                                @if ($course->media_mime &&
                                    (Str::startsWith($course->media_mime, 'video/') || Str::endsWith($course->media_mime, ['mp4', 'webm'])))
                                    <video class="w-full rounded-3xl" controls>
                                        <source src="{{ $mediaUrl }}" type="{{ $course->media_mime ?? 'video/mp4' }}" />
                                    </video>
                                @else
                                    <img src="{{ $mediaUrl }}" alt="{{ $course->title }}" class="w-full rounded-3xl object-cover" />
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="classroom-section">
                        <p class="classroom-section-title">{{ __('ui.classroom.about_course') }}</p>
                        @if ($course->description)
                            <p class="classroom-section-text">{{ $course->description }}</p>
                        @else
                            <p class="classroom-section-text">{{ __('ui.classroom.about_default') }}</p>
                        @endif
                    </div>

                    @if ($course->content)
                        <div class="classroom-section">
                            <p class="classroom-section-title">{{ __('ui.classroom.content') }}</p>
                            <div class="classroom-section-text">
                                {!! nl2br(e($course->content)) !!}
                            </div>
                        </div>
                    @endif

                    @if ($course->pdf_path)
                        <div class="classroom-section">
                            <p class="classroom-section-title">{{ __('ui.classroom.pdf_resource') }}</p>
                            <p class="classroom-section-text">{{ __('ui.classroom.online_only') }}</p>
                            <iframe class="mt-4 h-96 w-full rounded-2xl border border-slate-200" src="{{ route('courses.pdf', $course) }}"></iframe>
                        </div>
                    @endif
                </div>

                <aside class="space-y-6">
                    <div class="classroom-section">
                        <p class="classroom-section-title">{{ __('ui.classroom.quick_info') }}</p>
                        <div class="mt-4 space-y-3 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>{{ __('ui.classroom.status') }}</span>
                                <span class="font-semibold text-slate-900">{{ __('ui.classroom.active') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ __('ui.classroom.duration') }}</span>
                                <span class="font-semibold text-slate-900">{{ $course->duration_minutes ? $course->duration_minutes.' min' : __('ui.classroom.free') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ __('ui.classroom.resources') }}</span>
                                <span class="font-semibold text-slate-900">
                                    @if ($course->media_path)
                                        {{ __('ui.classroom.media') }}
                                    @else
                                        {{ __('ui.classroom.none') }}
                                    @endif
                                    @if ($course->pdf_path)
                                        · PDF
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('courses.index') }}" class="btn-ghost w-full justify-center">{{ __('ui.classroom.back_to_courses') }}</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
