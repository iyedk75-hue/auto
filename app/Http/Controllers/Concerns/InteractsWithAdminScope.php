<?php

namespace App\Http\Controllers\Concerns;

use App\Models\AutoSchool;
use App\Models\ExamSchedule;
use App\Models\PaymentRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait InteractsWithAdminScope
{
    protected function shouldScopeToSchool(User $admin): bool
    {
        return $admin->isSchoolAdmin() && $admin->auto_school_id !== null;
    }

    protected function adminUser(Request $request): User
    {
        return $request->user();
    }

    protected function managedSchoolId(User $admin): int
    {
        abort_unless($admin->isAdmin(), 403);
        abort_unless($admin->isSuperAdmin() || $admin->auto_school_id !== null, 403);

        return (int) $admin->auto_school_id;
    }

    protected function schoolQueryForAdmin(User $admin): Builder
    {
        return AutoSchool::query()
            ->when($this->shouldScopeToSchool($admin), fn (Builder $query) => $query->whereKey($this->managedSchoolId($admin)));
    }

    protected function availableSchoolsForAdmin(User $admin): Collection
    {
        return $this->schoolQueryForAdmin($admin)
            ->orderBy('name')
            ->get();
    }

    protected function candidateQueryForAdmin(User $admin): Builder
    {
        return User::query()
            ->where('role', User::ROLE_CANDIDATE)
            ->when($this->shouldScopeToSchool($admin), fn (Builder $query) => $query->where('auto_school_id', $this->managedSchoolId($admin)));
    }

    protected function paymentQueryForAdmin(User $admin): Builder
    {
        return PaymentRecord::query()
            ->whereHas('user', function (Builder $query) use ($admin): void {
                $query->where('role', User::ROLE_CANDIDATE)
                    ->when($this->shouldScopeToSchool($admin), fn (Builder $inner) => $inner->where('auto_school_id', $this->managedSchoolId($admin)));
            });
    }

    protected function examQueryForAdmin(User $admin): Builder
    {
        return ExamSchedule::query()
            ->when($this->shouldScopeToSchool($admin), fn (Builder $query) => $query->where('auto_school_id', $this->managedSchoolId($admin)));
    }

    protected function ensureManagedCandidate(User $admin, User $candidate): void
    {
        abort_unless($candidate->role === User::ROLE_CANDIDATE, 404);

        if ($this->shouldScopeToSchool($admin)) {
            abort_unless((int) $candidate->auto_school_id === $this->managedSchoolId($admin), 403);
        }
    }

    protected function ensureManagedPayment(User $admin, PaymentRecord $payment): void
    {
        $payment->loadMissing('user');
        abort_unless($payment->user?->role === User::ROLE_CANDIDATE, 404);

        if ($this->shouldScopeToSchool($admin)) {
            abort_unless((int) $payment->user?->auto_school_id === $this->managedSchoolId($admin), 403);
        }
    }

    protected function ensureManagedExam(User $admin, ExamSchedule $exam): void
    {
        $exam->loadMissing('user');
        abort_unless($exam->user?->role === User::ROLE_CANDIDATE, 404);

        if ($this->shouldScopeToSchool($admin)) {
            $managedSchoolId = $this->managedSchoolId($admin);
            abort_unless((int) ($exam->auto_school_id ?? $exam->user?->auto_school_id) === $managedSchoolId, 403);
        }
    }
}