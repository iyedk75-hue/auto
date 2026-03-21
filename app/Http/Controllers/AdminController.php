<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAdminScope;
use App\Models\Course;
use App\Models\ExamSchedule;
use App\Models\PaymentRecord;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    use InteractsWithAdminScope;

    public function dashboard(Request $request): View
    {
        $admin = $this->adminUser($request)->load('autoSchool');

        $candidateCount = $this->candidateQueryForAdmin($admin)->count();
        $questionCount = Question::query()
            ->where('is_active', true)
            ->when($this->shouldScopeToSchool($admin), fn ($query) => $query->where('auto_school_id', $this->managedSchoolId($admin)))
            ->count();
        $courseCount = Course::query()
            ->where('is_active', true)
            ->when($this->shouldScopeToSchool($admin), fn ($query) => $query->where('auto_school_id', $this->managedSchoolId($admin)))
            ->count();
        $quizSessionsCount = $admin->isSuperAdmin()
            ? 0
            : \App\Models\QuizSession::query()
                ->when($this->shouldScopeToSchool($admin), fn ($query) => $query->whereHas('user', fn ($inner) => $inner->where('auto_school_id', $this->managedSchoolId($admin))))
                ->whereNotNull('completed_at')
                ->count();
        $pendingPaymentsCount = $this->paymentQueryForAdmin($admin)
            ->where('status', PaymentRecord::STATUS_PENDING)
            ->count();
        $pendingProofReviewCount = $this->paymentQueryForAdmin($admin)
            ->where('payment_method', PaymentRecord::METHOD_BANK_TRANSFER)
            ->where('status', PaymentRecord::STATUS_PENDING)
            ->whereNotNull('proof_path')
            ->count();
        $upcomingExamCount = $this->examQueryForAdmin($admin)
            ->where('status', ExamSchedule::STATUS_PLANNED)
            ->whereDate('exam_date', '>=', now()->toDateString())
            ->count();

        return view('admin.dashboard', [
            'admin' => $admin,
            'isSuperAdmin' => $admin->isSuperAdmin(),
            'candidateCount' => $candidateCount,
            'questionCount' => $questionCount,
            'courseCount' => $courseCount,
            'quizSessionsCount' => $quizSessionsCount,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'pendingProofReviewCount' => $pendingProofReviewCount,
            'upcomingExamCount' => $upcomingExamCount,
        ]);
    }

    //
}
