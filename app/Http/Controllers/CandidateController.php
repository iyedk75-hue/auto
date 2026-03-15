<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function dashboard(Request $request): View|RedirectResponse
    {
        $user = $request->user()->load('autoSchool');
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $nextExam = ExamSchedule::query()
            ->where('user_id', $user->id)
            ->orderBy('exam_date')
            ->first();

        $latestPayments = $user->payments()->latest()->take(5)->get();
        $lastQuiz = $user->quizSessions()->latest()->first();
        $hasQuestions = Question::query()->where('is_active', true)->exists();

        return view('candidate.dashboard', [
            'user' => $user,
            'nextExam' => $nextExam,
            'latestPayments' => $latestPayments,
            'lastQuiz' => $lastQuiz,
            'hasQuestions' => $hasQuestions,
        ]);
    }
}
