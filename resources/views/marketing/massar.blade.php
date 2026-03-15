<x-app-layout>
    <div class="massar-landing massar-body">
        <section class="relative overflow-hidden">
            <div class="absolute -top-32 left-0 h-80 w-80 rounded-full bg-orange-500/20 blur-3xl" aria-hidden="true"></div>
            <div class="absolute right-0 top-10 h-72 w-72 rounded-full bg-blue-700/20 blur-3xl" aria-hidden="true"></div>

            <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
                <div class="relative grid min-h-[70vh] gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    <div class="space-y-6">
                        <p class="massar-kicker">{{ __('ui.public.hero_kicker') }}</p>
                        <h1 class="massar-title text-5xl font-extrabold tracking-tight text-[#1e1b16] sm:text-6xl lg:text-7xl">
                            {{ __('ui.public.hero_title') }}
                        </h1>
                        <p class="max-w-2xl text-xl leading-8 text-slate-700">
                            {{ __('ui.public.hero_body') }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('admin.login') }}" class="massar-btn-primary px-8 py-4 text-lg">{{ __('ui.public.admin_cta') }}</a>
                            <a href="https://wa.me/21699000000" class="massar-btn-ghost px-8 py-4 text-lg" target="_blank" rel="noreferrer">{{ __('ui.public.whatsapp_demo') }}</a>
                        </div>
                    </div>

                    <div class="relative massar-hero-card">
                        <div class="massar-hero-badge">
                            {{ __('ui.public.hero_badge') }}
                        </div>
                        <div class="massar-card massar-card-ink p-8">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">{{ __('ui.public.hero_panel_kicker') }}</p>
                            <h2 class="mt-4 text-2xl font-extrabold text-white">{{ __('ui.public.hero_panel_title') }}</h2>
                            <div class="mt-6 space-y-4 text-sm leading-6 text-white/85">
                                <p>{{ __('ui.public.hero_panel_points.crm') }}</p>
                                <p>{{ __('ui.public.hero_panel_points.exams') }}</p>
                                <p>{{ __('ui.public.hero_panel_points.finance') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 grid gap-4 sm:grid-cols-3">
                    <div class="massar-card text-center">
                        <p class="text-sm font-semibold text-slate-600">{{ __('ui.public.stats.schools') }}</p>
                    </div>
                    <div class="massar-card text-center">
                        <p class="text-sm font-semibold text-slate-600">{{ __('ui.public.stats.success') }}</p>
                    </div>
                    <div class="massar-card text-center">
                        <p class="text-sm font-semibold text-slate-600">{{ __('ui.public.stats.candidates') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="massar-kicker">{{ __('ui.public.ops_kicker') }}</p>
                    <h2 class="massar-title mt-3 text-3xl font-extrabold text-slate-950">{{ __('ui.public.ops_title') }}</h2>
                </div>
                <p class="max-w-xl text-sm leading-7 text-slate-600">
                    {{ __('ui.public.ops_body') }}
                </p>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                <div class="massar-card">
                    <h3 class="text-lg font-bold text-slate-950">{{ __('ui.public.features.crm_title') }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('ui.public.features.crm_body') }}</p>
                </div>
                <div class="massar-card">
                    <h3 class="text-lg font-bold text-slate-950">{{ __('ui.public.features.finance_title') }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('ui.public.features.finance_body') }}</p>
                </div>
                <div class="massar-card">
                    <h3 class="text-lg font-bold text-slate-950">{{ __('ui.public.features.agenda_title') }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('ui.public.features.agenda_body') }}</p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <div class="massar-card massar-card-ink">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">{{ __('ui.public.security.kicker') }}</p>
                        <h2 class="massar-title mt-3 text-3xl font-extrabold text-white">{{ __('ui.public.security.title') }}</h2>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-white/70">{{ __('ui.public.security.badge') }}</span>
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">{{ __('ui.public.security.device_title') }}</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">{{ __('ui.public.security.device_body') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">{{ __('ui.public.security.capture_title') }}</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">{{ __('ui.public.security.capture_body') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">{{ __('ui.public.security.watermark_title') }}</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">{{ __('ui.public.security.watermark_body') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-semibold text-white">{{ __('ui.public.security.copy_title') }}</p>
                        <p class="mt-2 text-sm leading-6 text-white/75">{{ __('ui.public.security.copy_body') }}</p>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('admin.login') }}" class="massar-btn-primary">{{ __('ui.public.admin_cta') }}</a>
                    <a href="https://wa.me/21699000000" class="massar-btn-ghost" target="_blank" rel="noreferrer">{{ __('ui.public.security.demo_cta') }}</a>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
