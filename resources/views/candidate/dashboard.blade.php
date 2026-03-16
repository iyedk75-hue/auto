<x-app-layout>
    <x-slot name="header">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div class="space-y-4">
                <p class="kicker">{{ __('ui.candidate_dashboard.kicker') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                    {{ __('ui.candidate_dashboard.welcome', ['name' => $user->name]) }}
                </h2>
                @if ($user->autoSchool)
                    <p class="text-sm font-semibold text-slate-500">{{ __('ui.candidate_dashboard.auto_school', ['name' => $user->autoSchool->name]) }}</p>
                @endif
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    {{ __('ui.candidate_dashboard.intro') }}
                </p>
            </div>
            <div class="panel bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-400 text-white">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">{{ __('ui.candidate_dashboard.quiz_kicker') }}</p>
                <p class="mt-4 text-sm leading-6 text-white/90">
                    {{ __('ui.candidate_dashboard.quiz_body') }}
                </p>
                <div class="mt-4">
                    @if ($hasQuestions)
                        <a href="{{ route('quiz.show') }}" class="btn-primary">{{ __('ui.candidate_dashboard.start_quiz') }}</a>
                    @else
                        <span class="btn-ghost">{{ __('ui.candidate_dashboard.no_questions') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.candidate_dashboard.status') }}</p>
                    @php
                        $statusLabel = ($user->status ?? 'active') === 'active'
                            ? __('ui.common.status_values.active')
                            : __('ui.common.status_values.inactive');
                    @endphp
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $statusLabel }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ __('ui.candidate_dashboard.status_note') }}</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.candidate_dashboard.balance_due') }}</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ number_format((float) $user->balance_due, 2) }} TND</p>
                    <p class="mt-2 text-sm text-slate-500">{{ __('ui.candidate_dashboard.balance_note') }}</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.candidate_dashboard.last_quiz') }}</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">
                        {{ $lastQuiz ? $lastQuiz->score.'/'.$lastQuiz->total_questions : __('ui.common.no_quiz') }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">{{ __('ui.candidate_dashboard.last_quiz_note') }}</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.candidate_dashboard.next_exam') }}</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">
                        {{ $nextExam ? $nextExam->exam_date->format('d M Y') : __('ui.common.exam_unplanned') }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">{{ __('ui.candidate_dashboard.next_exam_note') }}</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="panel">
                    <p class="kicker">{{ __('ui.candidate_dashboard.recent_payments') }}</p>
                    <div class="mt-5 space-y-4">
                        @forelse ($latestPayments as $payment)
                            @php
                                $paymentStatusLabel = match ($payment->status) {
                                    'paid' => __('ui.common.payment_status.paid'),
                                    'overdue' => __('ui.common.payment_status.overdue'),
                                    default => __('ui.common.payment_status.pending'),
                                };
                            @endphp
                            <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ number_format((float) $payment->amount, 2) }} TND</p>
                                    <p class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="status-pill status-pill-{{ $payment->status === 'paid' ? 'emerald' : ($payment->status === 'overdue' ? 'rose' : 'amber') }}">
                                    {{ $paymentStatusLabel }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">{{ __('ui.candidate_dashboard.no_payments') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="panel">
                    <p class="kicker">{{ __('ui.candidate_dashboard.next_steps') }}</p>
                    <div class="mt-5 space-y-4 text-sm leading-6 text-slate-600">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            {{ __('ui.candidate_dashboard.steps.quiz') }}
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            {{ __('ui.candidate_dashboard.steps.payments') }}
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            {{ __('ui.candidate_dashboard.steps.exam') }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
