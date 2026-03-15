<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function show(): View
    {
        $question = Question::query()
            ->where('is_active', true)
            ->inRandomOrder()
            ->with('options')
            ->first();

        return view('candidate.quiz', [
            'question' => $question,
            'quizResult' => session('quiz_result'),
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question_id' => ['required', 'uuid'],
            'selected_option' => ['required', 'string', 'in:أ,ب,ج'],
        ]);

        $question = Question::query()
            ->where('is_active', true)
            ->with('options')
            ->findOrFail($validated['question_id']);

        $isCorrect = $validated['selected_option'] === $question->correct_answer;

        $session = QuizSession::create([
            'user_id' => $request->user()->id,
            'difficulty' => $question->difficulty,
            'score' => $isCorrect ? 1 : 0,
            'total_questions' => 1,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        QuizAnswer::create([
            'quiz_session_id' => $session->id,
            'question_id' => $question->id,
            'selected_option' => $validated['selected_option'],
            'is_correct' => $isCorrect,
        ]);

        return redirect()
            ->route('quiz.show')
            ->with('quiz_result', [
                'is_correct' => $isCorrect,
                'selected_option' => $validated['selected_option'],
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
            ]);
    }
}
