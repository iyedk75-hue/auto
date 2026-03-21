<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-3">
                <p class="kicker">الأسئلة</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950">تعديل سؤال</h2>
            </div>
            <a href="{{ route('admin.questions.index') }}" class="btn-ghost">العودة إلى القائمة</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="panel">
                <form method="POST" action="{{ route('admin.questions.update', $question) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    @include('admin.questions.partials.form', ['question' => $question])

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-admin-entry">حفظ</button>
                        <a href="{{ route('admin.questions.index') }}" class="btn-ghost">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
