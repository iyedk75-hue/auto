<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAdminScope;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminQuestionController extends Controller
{
    use InteractsWithAdminScope;

    public function index(): View
    {
        $admin = request()->user();

        return view('admin.questions.index', [
            'questions' => Question::query()
                ->when($this->shouldScopeToSchool($admin), fn ($query) => $query->where('auto_school_id', $this->managedSchoolId($admin)))
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.questions.create', [
            'question' => new Question(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateQuestion($request);
        $admin = $request->user();
        $imageUrl = $this->storeQuestionImageIfPresent($request, $validated['image_url'] ?? null);

        $question = Question::create([
            'id' => (string) Str::uuid(),
            'auto_school_id' => $this->shouldScopeToSchool($admin) ? $this->managedSchoolId($admin) : null,
            'category' => $validated['category'],
            'image_url' => $imageUrl,
            'question_text' => $validated['question_text'],
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'] ?? null,
            'difficulty' => $validated['difficulty'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $question->options()->createMany($this->buildOptions($validated));

        return redirect()
            ->route('admin.questions.index')
            ->with('status', 'تمت إضافة السؤال بنجاح.');
    }

    public function edit(Question $question): View
    {
        $this->ensureManagedQuestion(request()->user(), $question);
        $question->load('options');

        return view('admin.questions.edit', [
            'question' => $question,
        ]);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $this->ensureManagedQuestion($request->user(), $question);
        $validated = $this->validateQuestion($request);
        $imageUrl = $this->storeQuestionImageIfPresent($request, $validated['image_url'] ?? $question->image_url, $question->image_url);

        $question->update([
            'category' => $validated['category'],
            'image_url' => $imageUrl,
            'question_text' => $validated['question_text'],
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'] ?? null,
            'difficulty' => $validated['difficulty'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $question->options()->delete();
        $question->options()->createMany($this->buildOptions($validated));

        return redirect()
            ->route('admin.questions.index')
            ->with('status', 'تم تحديث السؤال بنجاح.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $this->ensureManagedQuestion(request()->user(), $question);
        $question->delete();

        return redirect()
            ->route('admin.questions.index')
            ->with('status', 'تم حذف السؤال.');
    }

    private function validateQuestion(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(Question::CATEGORIES)],
            'image_url' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'question_text' => ['required', 'string'],
            'correct_answer' => ['required', Rule::in(['أ', 'ب', 'ج'])],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', Rule::in(Question::DIFFICULTIES)],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['correct_answer'] === 'ج' && empty($validated['option_c'])) {
            throw ValidationException::withMessages([
                'option_c' => 'الخيار ج مطلوب إذا كانت الإجابة الصحيحة هي ج.',
            ]);
        }

        return $validated;
    }

    private function storeQuestionImageIfPresent(Request $request, ?string $fallbackUrl = null, ?string $currentUrl = null): ?string
    {
        if ($request->hasFile('image')) {
            $this->deleteManagedQuestionImage($currentUrl);

            return $request->file('image')->store('questions/images', 'public');
        }

        $normalizedFallback = Question::managedImagePathFromValue($fallbackUrl) ?? $fallbackUrl;
        $normalizedCurrent = Question::managedImagePathFromValue($currentUrl) ?? $currentUrl;

        if ($normalizedCurrent && $normalizedFallback !== $normalizedCurrent && ! Question::managedImagePathFromValue($normalizedFallback)) {
            $this->deleteManagedQuestionImage($currentUrl);
        }

        return $normalizedFallback;
    }

    private function deleteManagedQuestionImage(?string $imageUrl): void
    {
        $path = Question::managedImagePathFromValue($imageUrl);

        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function buildOptions(array $validated): array
    {
        $options = [
            ['option_id' => 'أ', 'text' => $validated['option_a']],
            ['option_id' => 'ب', 'text' => $validated['option_b']],
        ];

        if (! empty($validated['option_c'])) {
            $options[] = ['option_id' => 'ج', 'text' => $validated['option_c']];
        }

        return $options;
    }

    private function ensureManagedQuestion($admin, Question $question): void
    {
        if ($this->shouldScopeToSchool($admin)) {
            abort_unless((int) $question->auto_school_id === $this->managedSchoolId($admin), 403);
        }
    }
}
