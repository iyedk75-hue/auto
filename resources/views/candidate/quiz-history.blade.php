<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <p class="kicker">الاختبار</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">سجل الاختبارات</h2>
            <p class="max-w-2xl text-base leading-7 text-slate-600">
                راجع جميع نتائج اختباراتك السابقة.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($bestChapter || $weakestChapter)
                <section class="grid gap-4 sm:grid-cols-2">
                    @if ($bestChapter)
                        <div class="panel bg-gradient-to-br {{ $bestChapter['visual']['gradient'] }} text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70">أفضل فصل</p>
                            <h3 class="mt-3 text-2xl font-extrabold">{{ $bestChapter['label'] }}</h3>
                            <p class="mt-3 text-sm text-white/90">{{ $bestChapter['percentage'] }}% عبر {{ $bestChapter['attempts'] }} {{ $bestChapter['attempts'] > 1 ? 'محاولات' : 'محاولة' }}.</p>
                        </div>
                    @endif
                    @if ($weakestChapter)
                        <div class="panel bg-white border border-amber-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">فصل يحتاج إلى تقوية</p>
                            <h3 class="mt-3 text-2xl font-extrabold text-slate-950">{{ $weakestChapter['label'] }}</h3>
                            <p class="mt-3 text-sm text-slate-600">{{ $weakestChapter['percentage'] }}% عبر {{ $weakestChapter['attempts'] }} {{ $weakestChapter['attempts'] > 1 ? 'محاولات' : 'محاولة' }}.</p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <form method="POST" action="{{ route('quiz.start') }}">
                                    @csrf
                                    <input type="hidden" name="chapter" value="{{ $weakestChapter['key'] }}" />
                                    <button type="submit" class="btn-primary">أعد هذا الفصل</button>
                                </form>
                                @if (! empty($weakestChapter['related_course_url']))
                                    <a href="{{ $weakestChapter['related_course_url'] }}" class="btn-neutral">عرض درس هذا الفصل</a>
                                @endif
                            </div>
                        </div>
                    @endif
                </section>
            @endif

            @if (($chapterStats ?? collect())->isNotEmpty())
                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($chapterStats as $chapterStat)
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-600">{{ $chapterStat['label'] }}</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $chapterStat['percentage'] }}%</p>
                            <p class="mt-2 text-sm text-slate-500">المجموع التراكمي: {{ $chapterStat['score'] }} / {{ $chapterStat['total_questions'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $chapterStat['attempts'] }} {{ $chapterStat['attempts'] > 1 ? 'محاولات' : 'محاولة' }}</p>
                        </div>
                    @endforeach
                </section>
            @endif

            @forelse ($sessions as $session)
                @php
                    $pct = $session->total_questions > 0 ? round($session->score / $session->total_questions * 100) : 0;
                @endphp
                <div class="panel">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            @if ($session->question_category)
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-600">{{ $session->chapterLabel() }}</p>
                            @endif
                            <p class="text-sm font-semibold text-slate-900">
                                النتيجة: {{ $session->score }} / {{ $session->total_questions }}
                                <span class="ml-2 text-xs font-medium text-slate-500">({{ $pct }}%)</span>
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $session->completed_at->format('d M Y à H:i') }}
                            </p>
                        </div>
                        <span class="status-pill status-pill-{{ $pct >= 70 ? 'emerald' : ($pct >= 50 ? 'amber' : 'rose') }}">
                            {{ $pct >= 70 ? 'جيد' : ($pct >= 50 ? 'متوسط' : 'يحتاج مراجعة') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="panel">
                    <p class="text-sm text-slate-600">لا يوجد أي اختبار مكتمل حاليًا.</p>
                    <a href="{{ route('quiz.show') }}" class="btn-primary mt-4">ابدأ اختبارًا</a>
                </div>
            @endforelse

            <div>
                {{ $sessions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
