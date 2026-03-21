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
            ->where('status', ExamSchedule::STATUS_PLANNED)
            ->whereDate('exam_date', '>=', now()->toDateString())
            ->orderBy('exam_date')
            ->first();

        $latestPayments = $user->payments()->latest()->take(5)->get();
        $lastQuiz = $user->quizSessions()->latest()->first();
        $hasQuestions = Question::query()
            ->where('is_active', true)
            ->when($user->auto_school_id, fn ($query) => $query->where('auto_school_id', $user->auto_school_id))
            ->exists();
        $notifications = $user->notifications()->latest()->take(5)->get();

        return view('candidate.dashboard', [
            'user' => $user,
            'nextExam' => $nextExam,
            'latestPayments' => $latestPayments,
            'lastQuiz' => $lastQuiz,
            'hasQuestions' => $hasQuestions,
            'canAccessLearning' => $user->hasLearningAccess(),
            'notifications' => $notifications,
        ]);
    }
}
