<x-app-layout>
    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-8 px-4 sm:px-6 lg:px-8" data-protected-course-viewer data-course-resource-viewer data-selected-resource-key="{{ $selectedResourceKey ?? '' }}" data-protection-message="{{ __('ui.classroom.protection_feedback') }}">
            <div class="classroom-hero">
                <div class="classroom-hero-banner" @if ($course->cover_path) style="background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.5), rgba(30, 58, 138, 0.4)), url('{{ Storage::url($course->cover_path) }}'); background-size: cover; background-position: center;" @endif></div>
                <div class="classroom-hero-content">
                    <span class="classroom-pill">{{ $categoryLabels[$course->category] ?? ucfirst(str_replace('_', ' ', $course->category)) }}</span>
                    <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ $localizedTitle }}</h2>
                    @if ($course->duration_minutes)
                        <p class="text-sm font-semibold text-slate-500">{{ __('ui.classroom.estimated_duration', ['minutes' => $course->duration_minutes]) }}</p>
                    @endif
                </div>
            </div>

            <div class="classroom-section border border-slate-900/10 bg-slate-50">
                <p class="classroom-section-title">{{ __('ui.classroom.protection_notice_title') }}</p>
                <p class="classroom-section-text">{{ __('ui.classroom.protection_notice_body') }}</p>
            </div>

            @if ($showArabicUnavailable)
                <div class="classroom-section border border-amber-200 bg-amber-50">
                    <p class="classroom-section-title text-amber-900">{{ __('ui.classroom.arabic_unavailable_title') }}</p>
                    <p class="classroom-section-text text-amber-800">{{ __('ui.classroom.arabic_unavailable_body') }}</p>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                <div class="space-y-6">
                    <section class="classroom-section classroom-resource-stream">
                        <div class="classroom-resource-header">
                            <div class="space-y-2">
                                <p class="classroom-section-title">{{ __('ui.classroom.course_supports') }}</p>
                                <p class="classroom-section-text mt-0">{{ __('ui.classroom.supports_intro') }}</p>
                            </div>
                            <span class="classroom-pill">{{ trans_choice('ui.classroom.support_count', $resourceItems->count(), ['count' => $resourceItems->count()]) }}</span>
                        </div>

                        @if ($resourceItems->isNotEmpty())
                            <div class="classroom-resource-list" data-resource-list>
                                @foreach ($resourceItems as $resource)
                                    @php
                                        $isSelected = $selectedResourceKey === $resource['key'];
                                    @endphp
                                    <a href="{{ $resource['select_url'] }}" class="classroom-resource-item {{ $isSelected ? 'classroom-resource-item-active' : '' }}" aria-current="{{ $isSelected ? 'true' : 'false' }}">
                                        <span class="classroom-resource-icon {{ $isSelected ? 'classroom-resource-icon-active' : '' }}">
                                            @if ($resource['viewer_kind'] === 'video')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h6A2.5 2.5 0 0 1 15 7.5v9A2.5 2.5 0 0 1 12.5 19h-6A2.5 2.5 0 0 1 4 16.5z" />
                                                    <path d="m15 10 5-3v10l-5-3z" />
                                                </svg>
                                            @elseif ($resource['viewer_kind'] === 'pdf')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                                                    <path d="M14 3v5h5" />
                                                    <path d="M8 13h8" />
                                                    <path d="M8 17h5" />
                                                </svg>
                                            @elseif ($resource['viewer_kind'] === 'image')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                                    <circle cx="8.5" cy="10.5" r="1.5" />
                                                    <path d="m21 15-5-5L5 21" />
                                                </svg>
                                            @else
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M6 4.5h9A2.5 2.5 0 0 1 17.5 7v12a.5.5 0 0 1-.8.4L12 16l-4.7 3.4a.5.5 0 0 1-.8-.4V7A2.5 2.5 0 0 1 6 4.5Z" />
                                                </svg>
                                            @endif
                                        </span>
                                        <span class="classroom-resource-body">
                                            <span class="classroom-resource-title">{{ $resource['display_title'] }}</span>
                                            @if ($resource['meta_label'])
                                                <span class="classroom-resource-meta">{{ $resource['meta_label'] }}</span>
                                            @endif
                                        </span>
                                        <span class="classroom-resource-action" aria-hidden="true">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.3 4.3a1 1 0 0 1 1.4 0l5 5a1 1 0 0 1 0 1.4l-5 5a1 1 0 1 1-1.4-1.4L11.59 10 7.3 5.7a1 1 0 0 1 0-1.4Z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="classroom-resource-empty">
                                <p class="text-base font-semibold text-slate-800">{{ __('ui.classroom.resource_list_empty_title') }}</p>
                                <p class="mt-2 text-sm leading-7 text-slate-500">{{ __('ui.classroom.resource_list_empty_body') }}</p>
                            </div>
                        @endif
                    </section>

                    @if ($selectedResource)
                        <section id="course-resource-viewer" class="classroom-section classroom-resource-viewer" data-resource-viewer-panel data-selected-resource-key="{{ $selectedResourceKey }}">
                            <div class="classroom-resource-viewer-header">
                                <div>
                                    <p class="kicker">{{ __('ui.classroom.selected_support') }}</p>
                                    <h3 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950">{{ $selectedResource['display_title'] }}</h3>
                                    @if ($selectedResource['meta_label'])
                                        <p class="classroom-section-text mt-2">{{ $selectedResource['meta_label'] }}</p>
                                    @endif
                                </div>
                                <span class="status-pill status-pill-blue">{{ $selectedResource['type_label'] }}</span>
                            </div>

                            @switch($selectedResource['viewer_kind'])
                                @case('video')
                                    <video class="mt-6 w-full rounded-[1.75rem] bg-slate-950" controls controlsList="nodownload noplaybackrate" disablePictureInPicture>
                                        <source src="{{ $selectedResource['viewer_url'] }}" type="{{ $selectedResource['file_mime'] ?? 'video/mp4' }}" />
                                    </video>
                                @break

                                @case('pdf')
                                    <p class="classroom-section-text">{{ __('ui.classroom.online_only') }}</p>
                                    <iframe class="mt-4 h-[38rem] w-full rounded-[1.75rem] border border-slate-200 bg-white" src="{{ $selectedResource['viewer_url'] }}"></iframe>
                                @break

                                @case('image')
                                    <img src="{{ $selectedResource['viewer_url'] }}" alt="{{ $selectedResource['display_title'] }}" class="mt-6 w-full rounded-[1.75rem] object-cover" draggable="false" />
                                @break

                                @default
                                    <article class="classroom-note-surface">
                                        @if (filled($selectedResource['display_note_body']))
                                            <div class="classroom-note-body">{!! nl2br(e($selectedResource['display_note_body'])) !!}</div>
                                        @else
                                            <p class="classroom-section-text mt-0">{{ __('ui.classroom.resource_empty') }}</p>
                                        @endif
                                    </article>
                            @endswitch
                        </section>
                    @endif

                    <section class="classroom-section">
                        <p class="classroom-section-title">{{ __('ui.classroom.about_course') }}</p>
                        @if ($localizedDescription)
                            <p class="classroom-section-text">{{ $localizedDescription }}</p>
                        @else
                            <p class="classroom-section-text">{{ __('ui.classroom.about_default') }}</p>
                        @endif
                    </section>

                    @if ($localizedContent)
                        <section class="classroom-section">
                            <p class="classroom-section-title">{{ __('ui.classroom.content') }}</p>
                            <div class="classroom-section-text">
                                {!! nl2br(e($localizedContent)) !!}
                            </div>
                        </section>
                    @endif
                </div>

                <aside class="space-y-6">
                    <div class="classroom-section">
                        <p class="classroom-section-title">{{ __('ui.classroom.quick_info') }}</p>
                        <div class="mt-4 space-y-3 text-sm text-slate-600">
                            <div class="flex items-center justify-between gap-4">
                                <span>{{ __('ui.classroom.status') }}</span>
                                <span class="font-semibold text-slate-900">{{ __('ui.classroom.active') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span>{{ __('ui.classroom.duration') }}</span>
                                <span class="font-semibold text-slate-900">{{ $course->duration_minutes ? $course->duration_minutes.' min' : __('ui.classroom.free') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span>{{ __('ui.classroom.resources') }}</span>
                                <span class="font-semibold text-slate-900">{{ trans_choice('ui.classroom.support_count', $resourceItems->count(), ['count' => $resourceItems->count()]) }}</span>
                            </div>
                            @if ($selectedResource)
                                <div class="flex items-center justify-between gap-4">
                                    <span>{{ __('ui.classroom.selected_support') }}</span>
                                    <span class="text-right font-semibold text-slate-900">{{ $selectedResource['type_label'] }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('courses.index') }}" class="btn-ghost w-full justify-center">{{ __('ui.classroom.back_to_courses') }}</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <div class="protection-feedback hidden" data-protection-feedback></div>
    </div>
</x-app-layout>
