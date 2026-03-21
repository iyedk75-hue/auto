<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    private const QUIZ_SIZE = 10;

    public function show(Request $request): View
    {
        $session = $this->activeSession($request);
        $user = $request->user();
        $chapterStats = $this->chapterStatsForUser($user);
        $availableChapters = $this->availableChapters($request, $chapterStats);

        $question = null;
        $progress = null;
        $selectedChapter = $session?->question_category;

        if ($session) {
            $answeredIds = $session->answers()->pluck('question_id');
            $question = $this->questionQueryForUser($user, $session->question_category)
                ->whereNotIn('id', $answeredIds)
                ->inRandomOrder()
                ->with('options')
                ->first();

            $progress = [
                'answered' => $answeredIds->count(),
                'total' => $session->total_questions,
                'score' => $session->score,
            ];

            if (! $question) {
                $session->update(['completed_at' => now()]);
                $session = null;
            }
        }

        if (! $session) {
            $selectedChapterInput = $request->string('chapter')->toString();
            $selectedChapter = array_key_exists($selectedChapterInput, $availableChapters)
                ? $selectedChapterInput
                : array_key_first($availableChapters);
        }

        return view('candidate.quiz', [
            'question' => $question,
            'session' => $session,
            'progress' => $progress,
            'availableChapters' => $availableChapters,
            'selectedChapter' => $selectedChapter,
            'quizResult' => session('quiz_result'),
            'completedSession' => session('completed_session'),
        ]);
    }

    public function start(Request $request): RedirectResponse
    {
        $existing = $this->activeSession($request);
        if ($existing) {
            return redirect()->route('quiz.show');
        }

        $availableChapters = $this->availableChapters($request, $this->chapterStatsForUser($request->user()));
        $selectedChapter = $request->string('chapter')->toString();

        if (! array_key_exists($selectedChapter, $availableChapters)) {
            return redirect()
                ->route('quiz.show')
                ->withErrors([
                    'chapter' => 'يرجى اختيار فصل صالح.',
                ]);
        }

        $questionCount = $this->questionQueryForUser($request->user(), $selectedChapter)
            ->count();
        $total = min(self::QUIZ_SIZE, $questionCount);

        if ($total === 0) {
            return redirect()->route('quiz.show');
        }

        QuizSession::create([
            'user_id' => $request->user()->id,
            'difficulty' => 'mixed',
            'question_category' => $selectedChapter,
            'score' => 0,
            'total_questions' => $total,
            'started_at' => now(),
        ]);

        return redirect()->route('quiz.show');
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question_id' => ['required', 'uuid'],
            'selected_option' => ['required', 'string', 'in:أ,ب,ج'],
        ]);

        $session = $this->activeSession($request);
        if (! $session) {
            return redirect()->route('quiz.show');
        }

        $question = $this->questionQueryForUser($request->user(), $session->question_category)
            ->with('options')
            ->findOrFail($validated['question_id']);

        if ($session->answers()->where('question_id', $question->id)->exists()) {
            return redirect()->route('quiz.show');
        }

        $isCorrect = $validated['selected_option'] === $question->correct_answer;

        QuizAnswer::create([
            'quiz_session_id' => $session->id,
            'question_id' => $question->id,
            'selected_option' => $validated['selected_option'],
            'is_correct' => $isCorrect,
        ]);

        if ($isCorrect) {
            $session->increment('score');
        }

        $answeredCount = $session->answers()->count();
        $isComplete = $answeredCount >= $session->total_questions;

        if ($isComplete) {
            $session->update(['completed_at' => now()]);

            return redirect()
                ->route('quiz.show')
                ->with('completed_session', [
                    'chapter' => $session->fresh()->chapterLabel(),
                    'score' => $session->fresh()->score,
                    'total' => $session->total_questions,
                ]);
        }

        return redirect()
            ->route('quiz.show')
            ->with('quiz_result', [
                'is_correct' => $isCorrect,
                'selected_option' => $validated['selected_option'],
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
            ]);
    }

    public function history(Request $request): View
    {
        $sessionQuery = $request->user()
            ->quizSessions()
            ->whereNotNull('completed_at')
            ->latest('completed_at');

        $sessions = (clone $sessionQuery)->paginate(12);
        $chapterStats = $this->chapterStatsForUser($request->user());

        $bestChapter = $chapterStats
            ->sortByDesc('percentage')
            ->sortByDesc('attempts')
            ->first();
        $weakestChapter = $chapterStats
            ->sortBy('percentage')
            ->sortByDesc('attempts')
            ->first();

        return view('candidate.quiz-history', [
            'sessions' => $sessions,
            'chapterStats' => $chapterStats,
            'bestChapter' => $bestChapter,
            'weakestChapter' => $weakestChapter,
        ]);
    }

    private function activeSession(Request $request): ?QuizSession
    {
        return QuizSession::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('completed_at')
            ->latest()
            ->first();
    }

    private function questionQueryForUser($user, ?string $chapter = null)
    {
        return Question::query()
            ->where('is_active', true)
            ->when($user?->auto_school_id, fn ($query) => $query->where('auto_school_id', $user->auto_school_id))
            ->when($chapter, fn ($query) => $query->where('category', $chapter));
    }

    private function availableChapters(Request $request, $chapterStats): array
    {
        return $this->questionQueryForUser($request->user())
            ->get(['category', 'image_url', 'question_text'])
            ->groupBy('category')
            ->mapWithKeys(function ($questions, $category) use ($request, $chapterStats) {
                $chapterQuestions = $questions->values();
                $coverImage = $chapterQuestions->first(fn ($question) => filled($question->image_url))?->image_url;
                $sampleQuestion = $chapterQuestions->first()?->question_text;
                $relatedCourse = $this->relatedCourseForChapter($request->user(), $category);
                $stats = $chapterStats->firstWhere('key', $category);

                return [
                    $category => [
                        'label' => Question::categoryLabels()[$category] ?? ucfirst(str_replace('_', ' ', (string) $category)),
                        'count' => $chapterQuestions->count(),
                        'cover_image_url' => $coverImage,
                        'sample_question' => $sampleQuestion,
                        'visual' => $this->chapterVisual($category),
                        'average_percentage' => $stats['percentage'] ?? null,
                        'attempts' => $stats['attempts'] ?? 0,
                        'related_course_url' => $relatedCourse ? route('courses.show', $relatedCourse) : null,
                    ],
                ];
            })
            ->all();
    }

    private function chapterStatsForUser($user)
    {
        return $user->quizSessions()
            ->whereNotNull('completed_at')
            ->get()
            ->groupBy(fn (QuizSession $session) => $session->question_category ?: 'general')
            ->map(function ($chapterSessions, $chapterKey) use ($user) {
                $score = $chapterSessions->sum('score');
                $totalQuestions = $chapterSessions->sum('total_questions');
                $attempts = $chapterSessions->count();
                $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100) : 0;
                $relatedCourse = $this->relatedCourseForChapter($user, $chapterKey);

                return [
                    'key' => $chapterKey,
                    'label' => Question::categoryLabels()[$chapterKey] ?? 'Quiz general',
                    'attempts' => $attempts,
                    'score' => $score,
                    'total_questions' => $totalQuestions,
                    'percentage' => $percentage,
                    'latest_completed_at' => $chapterSessions->max('completed_at'),
                    'visual' => $this->chapterVisual($chapterKey),
                    'related_course_url' => $relatedCourse ? route('courses.show', $relatedCourse) : null,
                ];
            })
            ->sortByDesc('latest_completed_at')
            ->values();
    }

    private function relatedCourseForChapter($user, ?string $chapter): ?Course
    {
        $courseCategory = $this->courseCategoryForChapter($chapter);

        if (! $courseCategory) {
            return null;
        }

        return Course::query()
            ->where('is_active', true)
            ->where('category', $courseCategory)
            ->when($user?->auto_school_id, fn ($query) => $query->where('auto_school_id', $user->auto_school_id))
            ->orderBy('sort_order')
            ->first();
    }

    private function courseCategoryForChapter(?string $chapter): ?string
    {
        return match ($chapter) {
            'priorite' => 'priority_rules',
            'signalisation' => 'traffic_signs',
            'vitesse', 'stationnement', 'croisement_depassement' => 'driving_safety',
            'conducteur_et_vehicule' => 'vehicle_basics',
            default => null,
        };
    }

    private function chapterVisual(?string $chapter): array
    {
        return match ($chapter) {
            'priorite' => [
                'icon' => 'Intersection',
                'gradient' => 'from-orange-500 via-amber-500 to-yellow-400',
                'dark_gradient' => 'from-slate-900 via-orange-900 to-amber-700',
            ],
            'signalisation' => [
                'icon' => 'Panneaux',
                'gradient' => 'from-sky-500 via-cyan-500 to-emerald-400',
                'dark_gradient' => 'from-slate-900 via-sky-900 to-cyan-700',
            ],
            'vitesse' => [
                'icon' => 'Vitesse',
                'gradient' => 'from-rose-500 via-pink-500 to-orange-400',
                'dark_gradient' => 'from-slate-900 via-rose-900 to-orange-800',
            ],
            'stationnement' => [
                'icon' => 'Parking',
                'gradient' => 'from-violet-500 via-fuchsia-500 to-pink-400',
                'dark_gradient' => 'from-slate-900 via-violet-900 to-fuchsia-700',
            ],
            'conducteur_et_vehicule' => [
                'icon' => 'Vehicule',
                'gradient' => 'from-emerald-500 via-teal-500 to-cyan-400',
                'dark_gradient' => 'from-slate-900 via-emerald-900 to-teal-700',
            ],
            'croisement_depassement' => [
                'icon' => 'Depassement',
                'gradient' => 'from-indigo-500 via-blue-500 to-sky-400',
                'dark_gradient' => 'from-slate-900 via-indigo-900 to-blue-700',
            ],
            default => [
                'icon' => 'Quiz',
                'gradient' => 'from-slate-700 via-slate-600 to-slate-500',
                'dark_gradient' => 'from-slate-900 via-slate-800 to-slate-600',
            ],
        };
    }
}
