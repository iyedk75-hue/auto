<x-app-layout>
    <x-slot name="header">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div class="space-y-4">
                <p class="kicker">{{ __('ui.admin_dashboard.kicker') }}</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                    {{ __('ui.admin_dashboard.title') }}
                </h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    {{ __('ui.admin_dashboard.intro') }}
                </p>
            </div>
            <div class="panel bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 text-white">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">{{ __('ui.admin_dashboard.today') }}</p>
                <p class="mt-3 text-sm leading-6 text-white/90">{{ __('ui.admin_dashboard.today_body') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8 lg:flex-row">
                <div class="flex-1 space-y-8">
                    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.admin_dashboard.candidate_count') }}</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $candidateCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ __('ui.admin_dashboard.candidate_count_note') }}</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.admin_dashboard.question_count') }}</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $questionCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ __('ui.admin_dashboard.question_count_note') }}</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.admin_dashboard.pending_payments') }}</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $pendingPaymentsCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ __('ui.admin_dashboard.pending_payments_note') }}</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('ui.admin_dashboard.upcoming_exams') }}</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $upcomingExamCount }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ __('ui.admin_dashboard.upcoming_exams_note') }}</p>
                        </div>
                    </section>

                    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                        <a href="{{ route('admin.candidates.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">{{ __('ui.nav.candidates') }}</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_dashboard.candidate_panel_title') }}</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ __('ui.admin_dashboard.candidate_panel_body') }}</p>
                        </a>
                        <a href="{{ route('admin.questions.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">{{ __('ui.nav.questions') }}</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_dashboard.question_panel_title') }}</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ __('ui.admin_dashboard.question_panel_body') }}</p>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">{{ __('ui.nav.payments') }}</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_dashboard.finance_panel_title') }}</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ __('ui.admin_dashboard.finance_panel_body') }}</p>
                        </a>
                        <a href="{{ route('admin.exams.index') }}" class="panel transition hover:-translate-y-1">
                            <p class="kicker">{{ __('ui.nav.exams') }}</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_dashboard.exam_panel_title') }}</h3>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ __('ui.admin_dashboard.exam_panel_body') }}</p>
                        </a>
                    </section>
                </div>

                <aside class="w-full lg:w-64">
                    <div class="panel sticky top-24 space-y-6">
                        <div>
                            <p class="kicker">{{ __('ui.admin_dashboard.sections') }}</p>
                            <div class="mt-4 space-y-2">
                                <a href="{{ route('admin.candidates.index') }}" class="btn-neutral w-full justify-start">{{ __('ui.nav.candidates') }}</a>
                                <a href="{{ route('admin.courses.index') }}" class="btn-neutral w-full justify-start">{{ __('ui.nav.courses') }}</a>
                                <a href="{{ route('admin.payments.index') }}" class="btn-neutral w-full justify-start">{{ __('ui.nav.payments') }}</a>
                                <a href="{{ route('admin.questions.index') }}" class="btn-neutral w-full justify-start">{{ __('ui.nav.questions') }}</a>
                                <a href="{{ route('admin.exams.index') }}" class="btn-neutral w-full justify-start">{{ __('ui.nav.exams') }}</a>
                            </div>
                        </div>
                        <div>
                            <p class="kicker">{{ __('ui.admin_dashboard.quick_action') }}</p>
                            <a href="{{ route('admin.candidates.index') }}" class="btn-admin-entry w-full justify-center">{{ __('ui.admin_dashboard.add_candidate') }}</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
