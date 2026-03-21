<x-app-layout>
    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="classroom-shell">
                <header class="classroom-header">
                    <p class="kicker">{{ __('ui.classroom.kicker') }}</p>
                    <h2 class="classroom-title">{{ __('ui.classroom.title') }}</h2>
                    <p class="classroom-subtitle">
                        {{ __('ui.classroom.subtitle') }}
                    </p>
                </header>

                <section class="classroom-grid">
                    @forelse ($courses as $course)
                        @php
                            $categoryLabel = $categoryLabels[$course->category] ?? ucfirst(str_replace('_', ' ', $course->category));
                            $bannerClass = $course->category === 'traffic_signs' ? 'classroom-card-banner-alt' : '';
                            $localizedTitle = $locale === 'ar' ? ($course->titleForLocale('ar') ?: $course->title) : $course->titleForLocale('fr');
                            $localizedDescription = $locale === 'ar'
                                ? ($course->descriptionForLocale('ar') ?: $course->description)
                                : $course->descriptionForLocale('fr');
                        @endphp
                        <article class="classroom-card">
                            <div class="classroom-card-banner {{ $bannerClass }}" @if ($course->cover_path) style="background-image: linear-gradient(135deg, rgba(30, 58, 138, 0.4), rgba(15, 23, 42, 0.5)), url('{{ Storage::url($course->cover_path) }}'); background-size: cover; background-position: center;" @endif>
                                <p class="classroom-card-meta">{{ $categoryLabel }}</p>
                                <h3 class="classroom-card-title">{{ $localizedTitle }}</h3>
                            </div>
                            <div class="classroom-card-body">
                                @if ($localizedDescription)
                                    <p>{{ $localizedDescription }}</p>
                                @else
                                    <p>{{ __('ui.classroom.default_description') }}</p>
                                @endif
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                                    <span>{{ $course->duration_minutes ? $course->duration_minutes.' min' : __('ui.classroom.free_duration') }}</span>
                                    @if ($course->hasAudioMedia())
                                        <span>{{ __('ui.classroom.audio_included') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="classroom-card-footer">
                                <span class="text-xs font-semibold text-slate-400">{{ __('ui.classroom.school') }}</span>
                                <a href="{{ route('courses.show', $course) }}" class="btn-primary">{{ __('ui.classroom.open') }}</a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                            {{ __('ui.classroom.empty') }}
                        </div>
                    @endforelse
                </section>
            </div>

            <div>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
