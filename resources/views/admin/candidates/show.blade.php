<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">{{ __('ui.admin_candidates.kicker') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_candidates.show_title') }}</h2>
            </div>
            <a href="{{ route('admin.candidates.index') }}" class="btn-ghost">{{ __('ui.admin_candidates.back') }}</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="candidate-avatar">
                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-2xl font-extrabold text-slate-950">{{ $candidate->name }}</h3>
                                <span class="status-pill status-pill-{{ ($candidate->status ?? 'active') === 'active' ? 'emerald' : 'slate' }}">
                                    {{ ($candidate->status ?? 'active') === 'active' ? __('ui.admin_candidates.status_active') : __('ui.admin_candidates.status_inactive') }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-500">{{ $candidate->email }}</p>
                            @if ($candidate->phone)
                                <p class="text-sm text-slate-500">{{ $candidate->phone }}</p>
                            @endif
                            <p class="text-sm text-slate-500">{{ $candidate->autoSchool?->name ?? __('ui.admin_candidates.school') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if ($canManageCandidate)
                            <a href="{{ route('admin.candidates.edit', $candidate) }}" class="btn-neutral">{{ __('ui.admin_candidates.edit') }}</a>
                            <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" onsubmit="return confirm('{{ __('ui.admin_candidates.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">{{ __('ui.admin_candidates.delete') }}</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_candidates.registration') }}</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">
                            {{ optional($candidate->registered_at ?? $candidate->created_at)->format('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_candidates.balance_due') }}</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ number_format((float) $candidate->balance_due, 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_candidates.quizzes_taken') }}</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $candidate->quiz_sessions_count }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_candidates.exams') }}</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $candidate->exams_count }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_candidates.average_quiz_score') }}</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ number_format($averageQuizScore, 1) }}%</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_candidates.recent_quizzes') }}</p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">{{ $quizSessions->count() }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('ui.admin_candidates.quiz_history') }}</p>
                    @forelse ($quizSessions as $session)
                        @php
                            $scorePercent = $session->total_questions > 0 ? round(($session->score / $session->total_questions) * 100) : 0;
                        @endphp
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $session->score }} / {{ $session->total_questions }}</p>
                                    <p class="text-xs text-slate-500">{{ optional($session->completed_at)->format('d M Y H:i') }}</p>
                                </div>
                                <span class="status-pill status-pill-{{ $scorePercent >= 70 ? 'emerald' : ($scorePercent >= 50 ? 'amber' : 'rose') }}">{{ $scorePercent }}%</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">{{ __('ui.admin_candidates.empty_quiz_history') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
