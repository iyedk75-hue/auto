<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <p class="kicker">اختبار التذكير الذكي</p>
            <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">اختبر معلوماتك.</h2>
            <p class="max-w-2xl text-base leading-7 text-slate-600">
                أجب عن 10 أسئلة لترسيخ ردود فعلك قبل الامتحان.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($completedSession ?? false)
                <div class="panel">
                    <p class="kicker">انتهى الاختبار</p>
                    @if (! empty($completedSession['chapter']))
                        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.18em] text-orange-600">{{ $completedSession['chapter'] }}</p>
                    @endif
                    <h3 class="mt-3 text-2xl font-extrabold text-slate-950">
                        النتيجة: {{ $completedSession['score'] }} / {{ $completedSession['total'] }}
                    </h3>
                    <p class="mt-3 text-sm text-slate-600">
                        @if ($completedSession['score'] >= $completedSession['total'] * 0.7)
                            أحسنت، مستواك جيد.
                        @elseif ($completedSession['score'] >= $completedSession['total'] * 0.5)
                            النتيجة جيدة، ويمكنك التقدم أكثر.
                        @else
                            واصل المراجعة، يمكنك تحقيق نتيجة أفضل.
                        @endif
                    </p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        @if ($selectedChapter)
                            <form method="POST" action="{{ route('quiz.start') }}">
                                @csrf
                                <input type="hidden" name="chapter" value="{{ $selectedChapter }}" />
                                <button type="submit" class="btn-primary">أعد هذا الفصل</button>
                            </form>
                        @endif
                        <a href="{{ route('quiz.history') }}" class="btn-ghost">السجل</a>
                    </div>
                </div>
            @endif

            @if ($quizResult ?? false)
                <div class="panel">
                    <p class="kicker">النتيجة</p>
                    <h3 class="mt-3 text-2xl font-extrabold text-slate-950">
                        {{ $quizResult['is_correct'] ? 'إجابة صحيحة' : 'إجابة غير صحيحة' }}
                    </h3>
                    <p class="mt-3 text-sm text-slate-600">
                        إجابتك: {{ $quizResult['selected_option'] }} | الإجابة الصحيحة: {{ $quizResult['correct_answer'] }}
                    </p>
                    @if (! empty($quizResult['explanation']))
                        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            {{ $quizResult['explanation'] }}
                        </div>
                    @endif
                </div>
            @endif

            @if ($session && $progress && $question)
                <div class="panel-muted">
                    <div class="flex items-center justify-between">
                        <div>
                            @if ($session->question_category)
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-600">{{ $session->chapterLabel() }}</p>
                            @endif
                            <p class="text-sm font-semibold text-slate-700">السؤال {{ $progress['answered'] + 1 }} / {{ $progress['total'] }}</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-500">النتيجة: {{ $progress['score'] }}</p>
                    </div>
                    <div class="mt-2 h-2 w-full rounded-full bg-slate-200">
                        <div class="h-2 rounded-full bg-orange-500 transition-all" style="width: {{ ($progress['answered'] / $progress['total']) * 100 }}%"></div>
                    </div>
                </div>

                <form method="POST" action="{{ route('quiz.submit') }}" class="panel space-y-5">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}" />

                    <div>
                        <p class="kicker">السؤال</p>
                        <h3 class="mt-3 text-2xl font-extrabold text-slate-950">{{ $question->question_text }}</h3>
                        @if ($question->image_url)
                            <img src="{{ $question->image_url }}" alt="توضيح للحالة" class="mt-4 rounded-2xl border border-slate-200" />
                        @endif
                    </div>

                    <div class="space-y-3">
                        @foreach ($question->options->sortBy('option_id') as $option)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
                                <input type="radio" name="selected_option" value="{{ $option->option_id }}" class="h-4 w-4 text-orange-600" required>
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-50 text-xs font-bold text-orange-600">
                                    {{ $option->option_id }}
                                </span>
                                <span>{{ $option->text }}</span>
                            </label>
                        @endforeach
                        <x-input-error :messages="$errors->get('selected_option')" class="mt-2" />
                    </div>

                    <button type="submit" class="btn-primary">تأكيد الإجابة</button>
                </form>
            @elseif (! ($completedSession ?? false))
                @if (! empty($availableChapters))
                    <div class="panel space-y-5">
                        <div>
                            <p class="kicker">الفصول</p>
                            <h3 class="mt-3 text-2xl font-extrabold text-slate-950">اختر فصلًا قبل بدء الاختبار.</h3>
                            <p class="mt-2 text-sm text-slate-600">كل اختبار يخص الفصل الذي يختاره المترشح فقط.</p>
                        </div>

                        <x-input-error :messages="$errors->get('chapter')" class="mt-2" />

                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($availableChapters as $chapterKey => $chapter)
                                <div class="overflow-hidden rounded-[1.75rem] border {{ $selectedChapter === $chapterKey ? 'border-orange-300 shadow-[0_20px_60px_-35px_rgba(234,88,12,0.45)]' : 'border-slate-200' }} bg-white shadow-sm">
                                    <div class="min-h-36 px-5 py-5 {{ $selectedChapter === $chapterKey ? 'bg-gradient-to-br '.$chapter['visual']['gradient'].' text-white' : 'bg-gradient-to-br '.$chapter['visual']['dark_gradient'].' text-white' }}" @if (! empty($chapter['cover_image_url'])) style="background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.5), rgba(234, 88, 12, 0.45)), url('{{ $chapter['cover_image_url'] }}'); background-size: cover; background-position: center;" @endif>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] {{ $selectedChapter === $chapterKey ? 'text-white/75' : 'text-white/70' }}">فصل</p>
                                        <h4 class="mt-3 text-xl font-extrabold">{{ $chapter['label'] }}</h4>
                                        <p class="mt-3 max-w-xs text-sm leading-6 {{ $selectedChapter === $chapterKey ? 'text-white/90' : 'text-white/80' }}">
                                            {{ $chapter['sample_question'] ?: 'أسئلة مخصصة لهذا الفصل.' }}
                                        </p>
                                        @if (empty($chapter['cover_image_url']))
                                            <div class="mt-4 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white/85">
                                                {{ $chapter['visual']['icon'] }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                            <span>{{ $chapter['count'] }} {{ $chapter['count'] > 1 ? 'أسئلة' : 'سؤال' }}</span>
                                            @if ($selectedChapter === $chapterKey)
                                                <span class="rounded-full border border-orange-200 bg-orange-50 px-3 py-1 text-orange-700">محدد</span>
                                            @endif
                                        </div>
                                        <div class="mt-4 flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
                                            <div>
                                                <p class="font-semibold text-slate-900">
                                                    {{ $chapter['average_percentage'] !== null ? $chapter['average_percentage'].'%' : 'لا توجد محاولات' }}
                                                </p>
                                                <p class="text-xs text-slate-500">{{ $chapter['attempts'] > 0 ? 'متوسط هذا الفصل' : 'ابدأ هذا الفصل لرؤية متوسطك' }}</p>
                                            </div>
                                            @if (! empty($chapter['related_course_url']))
                                                <a href="{{ $chapter['related_course_url'] }}" class="btn-neutral">عرض الدرس</a>
                                            @endif
                                        </div>
                                        <div class="mt-5 flex flex-wrap gap-3">
                                            <a href="{{ route('quiz.show', ['chapter' => $chapterKey]) }}" class="btn-ghost">عرض</a>
                                            <form method="POST" action="{{ route('quiz.start') }}">
                                                @csrf
                                                <input type="hidden" name="chapter" value="{{ $chapterKey }}" />
                                                <button type="submit" class="btn-primary">ابدأ</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="panel">
                        <p class="text-sm text-slate-600">لا توجد أسئلة نشطة حاليًا.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
