<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">Cours</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">Cours et modules</h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Gérez les modules de cours pour structurer l'apprentissage.
                </p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn-admin-entry">Ajouter un cours</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($courses as $course)
                    <article class="panel">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="kicker">{{ $categoryLabels[$course->category] ?? $course->category }}</p>
                                <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950">{{ $course->title }}</h3>
                                @if ($course->description)
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $course->description }}</p>
                                @endif
                            </div>
                            <span class="status-pill status-pill-{{ $course->is_active ? 'emerald' : 'slate' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="mt-6 grid gap-3 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Duration</span>
                                <span class="font-semibold text-slate-900">
                                    {{ $course->duration_minutes ? $course->duration_minutes.' min' : '—' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Order</span>
                                <span class="font-semibold text-slate-900">{{ $course->sort_order }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Assets</span>
                                <span class="font-semibold text-slate-900">
                                    @if ($course->media_path)
                                        {{ \Illuminate\Support\Str::startsWith($course->media_mime ?? '', 'image/') ? 'Image' : 'Video' }}
                                    @else
                                        No media
                                    @endif
                                    @if ($course->pdf_path)
                                        · PDF
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn-neutral">Edit</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Supprimer ce cours ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                        <p class="text-sm font-semibold text-slate-700">Aucun cours enregistré pour le moment.</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
