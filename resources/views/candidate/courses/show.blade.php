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
                                            @if ($resource['viewer_kind'] === 'audio')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M11 5 6 9H3v6h3l5 4z" />
                                                    <path d="M15.5 8.5a5 5 0 0 1 0 7" />
                                                    <path d="M18.5 6a8.5 8.5 0 0 1 0 12" />
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
                                            @if ($resource['viewer_kind'] === 'audio')
                                                <span class="classroom-resource-meta" data-audio-status-label data-audio-storage-key="{{ $resource['viewer_storage_key'] }}">{{ __('ui.classroom.audio_status_idle') }}</span>
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
                                @case('audio')
                                    <div class="mt-6 space-y-4 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5" data-audio-player data-audio-key="{{ $selectedResource['viewer_storage_key'] ?? $selectedResource['viewer_url'] }}">
                                        <audio class="w-full" controls controlsList="nodownload noplaybackrate" preload="metadata" data-audio-element>
                                            <source src="{{ $selectedResource['viewer_url'] }}" type="{{ $selectedResource['file_mime'] ?? 'audio/mpeg' }}" />
                                        </audio>
                                        <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600">
                                            <div class="flex items-center gap-3">
                                                <button type="button" class="btn-ghost px-4 py-2" data-audio-skip="-10">{{ __('ui.classroom.audio_back') }}</button>
                                                <button type="button" class="btn-ghost px-4 py-2" data-audio-skip="10">{{ __('ui.classroom.audio_forward') }}</button>
                                            </div>
                                            <label class="flex items-center gap-2">
                                                <span>{{ __('ui.classroom.audio_speed') }}</span>
                                                <select class="form-input-auth min-w-[110px] py-2" data-audio-rate>
                                                    <option value="1">1x</option>
                                                    <option value="1.25">1.25x</option>
                                                    <option value="1.5">1.5x</option>
                                                    <option value="1.75">1.75x</option>
                                                </select>
                                            </label>
                                        </div>
                                        <div class="space-y-2">
                                            <input type="range" min="0" max="100" value="0" class="w-full accent-orange-500" data-audio-progress />
                                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                <span data-audio-current>0:00</span>
                                                <span data-audio-status-current>{{ __('ui.classroom.audio_status_idle') }}</span>
                                                <span data-audio-duration>0:00</span>
                                            </div>
                                        </div>
                                    </div>
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
