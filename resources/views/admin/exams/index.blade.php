<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-4">
                <p class="kicker">Examens</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Agenda des examens</h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Planifiez les dates de passage et gardez l'historique des résultats.
                </p>
            </div>
            <a href="{{ route('admin.exams.create') }}" class="btn-admin-entry">Planifier</a>
        </div>
    </x-slot>

    @php
        $statusLabels = [
            'planned' => 'Planifié',
            'passed' => 'Réussi',
            'failed' => 'Échoué',
            'postponed' => 'Reporté',
        ];

        $today = now();
        $monthStart = $today->copy()->startOfMonth();
        $daysInMonth = $monthStart->daysInMonth;
        $startOffset = max(0, $monthStart->dayOfWeekIso - 1);
        $calendarCells = [];
        for ($i = 0; $i < $startOffset; $i++) {
            $calendarCells[] = null;
        }
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $calendarCells[] = $monthStart->copy()->addDays($day - 1);
        }
        while (count($calendarCells) % 7 !== 0) {
            $calendarCells[] = null;
        }

        $examsCollection = $exams->getCollection();
        $examsByDate = $examsCollection->groupBy(fn ($exam) => $exam->exam_date->format('Y-m-d'));
        $selectedDate = $examsCollection->first()?->exam_date ?? $today;
        $selectedKey = $selectedDate->format('Y-m-d');
        $selectedExams = $examsByDate->get($selectedKey, collect());
        $upcomingExams = $examsCollection->sortBy('exam_date')->take(5);
        $calendarLabels = [];
        foreach ($calendarCells as $cell) {
            if ($cell) {
                $calendarLabels[$cell->format('Y-m-d')] = $cell->format('l, M d');
            }
        }
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="agenda-shell" x-data="{
                selectedDate: @js($selectedKey),
                dateLabels: @js($calendarLabels),
                examsByDate: @js($examsByDate->map->count()),
            }" x-init="
                if (!examsByDate[selectedDate]) {
                    const firstKey = Object.keys(examsByDate)[0];
                    if (firstKey) { selectedDate = firstKey; }
                }
            ">
                <aside class="agenda-panel">
                    <div class="agenda-panel-header">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Month</p>
                            <h3 class="text-2xl font-extrabold text-slate-900">{{ $monthStart->format('F Y') }}</h3>
                        </div>
                        <div class="agenda-panel-actions">
                            <button type="button" class="agenda-icon-btn agenda-icon-btn-sm" aria-label="Search">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="7" />
                                    <path d="m20 20-3.5-3.5" />
                                </svg>
                            </button>
                            <button type="button" class="agenda-icon-btn agenda-icon-btn-sm" aria-label="Notifications">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 1 1 12 0c0 7 3 7 3 7H3s3 0 3-7" />
                                    <path d="M10 19a2 2 0 0 0 4 0" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="calendar-card">
                        <div class="calendar-weekdays">
                            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $label)
                                <span>{{ $label }}</span>
                            @endforeach
                        </div>
                        <div class="calendar-grid">
                            @foreach ($calendarCells as $cell)
                                @php
                                    $cellKey = $cell?->format('Y-m-d');
                                    $hasExam = $cellKey && $examsByDate->has($cellKey);
                                @endphp
                                <button
                                    type="button"
                                    class="calendar-cell {{ $cellKey === $today->format('Y-m-d') ? 'calendar-cell-today' : '' }} {{ $cell ? '' : 'calendar-cell-empty' }}"
                                    @if ($cellKey)
                                        @click="selectedDate = '{{ $cellKey }}'"
                                        :class="{ 'calendar-cell-active': selectedDate === '{{ $cellKey }}' }"
                                    @endif
                                >
                                    @if ($cell)
                                        <span>{{ $cell->format('j') }}</span>
                                        @if ($hasExam)
                                            <span class="calendar-dot"></span>
                                        @endif
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="agenda-upcoming">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-900">Upcoming Exams</p>
                            <a href="{{ route('admin.exams.index') }}" class="text-xs font-semibold text-slate-400">See all</a>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse ($upcomingExams as $exam)
                                <div class="upcoming-card">
                                    <div class="upcoming-date">
                                        <span class="text-xs font-semibold uppercase text-slate-500">{{ $exam->exam_date->format('M') }}</span>
                                        <span class="text-lg font-extrabold text-slate-900">{{ $exam->exam_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $exam->user->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $exam->autoSchool?->name ?? 'Auto-école' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">No upcoming exams yet.</p>
                            @endforelse
                        </div>
                    </div>
                </aside>

                <section class="agenda-timeline">
                    <div class="agenda-timeline-header">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Schedule</p>
                            <h3 class="text-2xl font-extrabold text-slate-900" x-text="dateLabels[selectedDate] ?? 'Select a date'"></h3>
                        </div>
                        <div class="agenda-day-pill" x-text="selectedDate ? selectedDate.split('-')[2] : '--'"></div>
                    </div>

                    <div class="agenda-timeline-body">
                        @foreach ($examsCollection as $exam)
                            <div class="timeline-item" x-show="selectedDate === '{{ $exam->exam_date->format('Y-m-d') }}'" x-cloak>
                                <div class="timeline-time">Time TBD</div>
                                <div class="timeline-card">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $exam->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $exam->autoSchool?->name ?? 'Auto-école' }}</p>
                                            <p class="text-xs text-slate-400">{{ $exam->note ?? 'Session programmée' }}</p>
                                        </div>
                                        <span class="status-pill status-pill-{{ $exam->status === 'passed' ? 'emerald' : ($exam->status === 'failed' ? 'rose' : 'amber') }}">
                                            {{ $statusLabels[$exam->status] ?? ucfirst($exam->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('admin.exams.edit', $exam) }}" class="btn-ghost">Modifier</a>
                                        <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="timeline-empty space-y-3" x-show="!examsByDate[selectedDate]" x-cloak>
                            <p>Aucun examen pour cette date.</p>
                            <a href="#all-exams" class="btn-ghost w-full justify-center">Voir tous les examens</a>
                        </div>
                    </div>

                    <div class="mt-6">
                        {{ $exams->links() }}
                    </div>

                    <div id="all-exams" class="mt-10">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Tous les examens</p>
                                <h4 class="text-2xl font-extrabold text-slate-900">Liste complète</h4>
                            </div>
                            <a href="{{ route('admin.exams.index') }}" class="btn-ghost">Rafraîchir</a>
                        </div>

                        <div class="agenda-list mt-6">
                            @forelse ($exams as $exam)
                                <article class="agenda-item">
                                    <div class="agenda-date">
                                        <span class="agenda-day">{{ $exam->exam_date->format('d') }}</span>
                                        <span class="agenda-month">{{ $exam->exam_date->format('M') }}</span>
                                        <span class="agenda-year">{{ $exam->exam_date->format('Y') }}</span>
                                    </div>
                                    <div class="agenda-card">
                                        <div class="agenda-header">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $exam->user->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $exam->user->email }}</p>
                                            </div>
                                            <span class="status-pill status-pill-{{ $exam->status === 'passed' ? 'emerald' : ($exam->status === 'failed' ? 'rose' : 'amber') }}">
                                                {{ $statusLabels[$exam->status] ?? ucfirst($exam->status) }}
                                            </span>
                                        </div>
                                        <div class="agenda-meta">
                                            <p>{{ $exam->autoSchool?->name ?? 'Auto-école' }}</p>
                                            <p class="text-slate-400">{{ $exam->note ?? 'Session programmée' }}</p>
                                        </div>
                                        <div class="agenda-actions">
                                            <a href="{{ route('admin.exams.edit', $exam) }}" class="btn-ghost">Modifier</a>
                                            <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                                    Aucun examen planifié.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <aside class="agenda-panel agenda-panel-form">
                    <div class="agenda-panel-header">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Add New Event</p>
                            <h3 class="text-2xl font-extrabold text-slate-900">Planifier un examen</h3>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.exams.store') }}" class="agenda-form">
                        @csrf
                        @include('admin.exams.partials.form', ['exam' => new \App\Models\ExamSchedule(), 'candidates' => $candidates, 'schools' => $schools])
                        <button type="submit" class="btn-primary w-full justify-center">Save Event</button>
                    </form>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
