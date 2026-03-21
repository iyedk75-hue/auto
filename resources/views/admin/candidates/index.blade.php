<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-2">
                <p class="kicker">{{ __('ui.admin_candidates.kicker') }}</p>
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-950">{{ __('ui.admin_candidates.index_title') }}</h2>
                <p class="max-w-xl text-sm leading-6 text-slate-600">
                    {{ __('ui.admin_candidates.index_intro') }}
                </p>
            </div>
            @if ($canManageCandidates)
                <a href="{{ route('admin.candidates.create') }}" class="btn-admin-entry">{{ __('ui.admin_candidates.add') }}</a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="candidate-toolbar">
                <form method="GET" action="{{ route('admin.candidates.index') }}" class="candidate-search">
                    @if ($canFilterBySchool)
                        <div class="flex flex-wrap items-center gap-3">
                            <label for="auto_school_id" class="text-sm font-semibold text-slate-600">{{ __('ui.admin_candidates.school') }}</label>
                            <select id="auto_school_id" name="auto_school_id" class="form-input-auth max-w-xs" onchange="this.form.submit()">
                                <option value="">{{ __('ui.admin_candidates.all_schools') }}</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected((string) $selectedSchoolId === (string) $school->id)>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="candidate-search-input">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search ?? '' }}"
                            placeholder="{{ __('ui.admin_candidates.search_placeholder') }}"
                            autocomplete="off"
                        />
                        <button type="submit" class="candidate-search-button">{{ __('ui.admin_candidates.search') }}</button>
                    </div>
                    @if (!empty($search) || !empty($selectedSchoolId))
                        <a href="{{ route('admin.candidates.index') }}" class="btn-ghost">{{ __('ui.admin_candidates.reset') }}</a>
                    @endif
                </form>
                <div class="candidate-count">
                    {{ trans_choice('ui.admin_candidates.count', $candidates->total(), ['count' => $candidates->total()]) }}
                </div>
            </div>

            <section class="candidate-list">
                @forelse ($candidates as $candidate)
                    @php
                        $statusLabel = ($candidate->status ?? 'active') === 'active' ? __('ui.admin_candidates.status_active') : __('ui.admin_candidates.status_inactive');
                        $registeredAt = $candidate->registered_at ?? $candidate->created_at;
                        $registeredLabel = $registeredAt
                            ? \Illuminate\Support\Carbon::parse($registeredAt)->format('d M Y')
                            : '—';
                    @endphp
                    <article class="candidate-row">
                        <div class="candidate-row-main">
                            <div class="candidate-avatar">
                                {{ strtoupper(substr($candidate->name, 0, 1)) }}
                            </div>
                            <div class="candidate-identity">
                                <div class="candidate-name">
                                    <h3>{{ $candidate->name }}</h3>
                                    <span class="status-pill status-pill-{{ ($candidate->status ?? 'active') === 'active' ? 'emerald' : 'slate' }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <p class="candidate-meta">
                                    <span>{{ $candidate->email }}</span>
                                    @if ($candidate->phone)
                                        <span>• {{ $candidate->phone }}</span>
                                    @endif
                                </p>
                            <p class="candidate-sub">
                                    @if ($candidate->autoSchool)
                                        <a href="{{ route('admin.candidates.index', array_filter(['auto_school_id' => $candidate->autoSchool->id, 'q' => $search ?: null])) }}" class="font-semibold text-orange-600 hover:text-orange-700 hover:underline">
                                            {{ $candidate->autoSchool->name }}
                                        </a>
                                    @else
                                        <span>{{ __('ui.admin_candidates.school') }}</span>
                                    @endif
                                    <span>• {{ __('ui.admin_candidates.registered_on', ['date' => $registeredLabel]) }}</span>
                                    <span>• {{ __('ui.admin_candidates.quiz_count', ['count' => $candidate->quiz_sessions_count]) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="candidate-row-actions">
                        <a href="{{ route('admin.candidates.show', $candidate) }}" class="btn-ghost">{{ __('ui.admin_candidates.view') }}</a>
                        @if ($canManageCandidates)
                            <a href="{{ route('admin.candidates.edit', $candidate) }}" class="btn-neutral">{{ __('ui.admin_candidates.edit') }}</a>
                            <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" onsubmit="return confirm('{{ __('ui.admin_candidates.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">{{ __('ui.admin_candidates.delete') }}</button>
                            </form>
                        @endif
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                        <p class="text-sm font-semibold text-slate-700">{{ __('ui.admin_candidates.empty') }}</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $candidates->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
