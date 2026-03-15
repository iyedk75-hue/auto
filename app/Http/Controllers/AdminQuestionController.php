<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminQuestionController extends Controller
{
    public function index(): View
    {
        return view('admin.questions.index', [
            'questions' => Question::query()->latest()->paginate(12),
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

        $question = Question::create([
            'id' => (string) Str::uuid(),
            'category' => $validated['category'],
            'image_url' => $validated['image_url'] ?? null,
            'question_text' => $validated['question_text'],
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'] ?? null,
            'difficulty' => $validated['difficulty'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $question->options()->createMany($this->buildOptions($validated));

        return redirect()
            ->route('admin.questions.index')
            ->with('status', 'Question ajoutée avec succès.');
    }

    public function edit(Question $question): View
    {
        $question->load('options');

        return view('admin.questions.edit', [
            'question' => $question,
        ]);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $this->validateQuestion($request);

        $question->update([
            'category' => $validated['category'],
            'image_url' => $validated['image_url'] ?? null,
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
            ->with('status', 'Question mise à jour avec succès.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()
            ->route('admin.questions.index')
            ->with('status', 'Question supprimée.');
    }

    private function validateQuestion(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(Question::CATEGORIES)],
            'image_url' => ['nullable', 'url'],
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
                'option_c' => 'Le choix ج est requis si la bonne réponse est ج.',
            ]);
        }

        return $validated;
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
}
