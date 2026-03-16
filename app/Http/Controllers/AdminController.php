<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\PaymentRecord;
use App\Models\Question;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $candidateCount = User::query()->where('role', User::ROLE_CANDIDATE)->count();
        $questionCount = Question::query()->where('is_active', true)->count();
        $pendingPaymentsCount = PaymentRecord::query()
            ->where('status', PaymentRecord::STATUS_PENDING)
            ->count();
        $upcomingExamCount = ExamSchedule::query()
            ->where('status', ExamSchedule::STATUS_PLANNED)
            ->whereDate('exam_date', '>=', now()->toDateString())
            ->count();

        return view('admin.dashboard', [
            'candidateCount' => $candidateCount,
            'questionCount' => $questionCount,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'upcomingExamCount' => $upcomingExamCount,
        ]);
    }

    //
}
