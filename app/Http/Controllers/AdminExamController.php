<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAdminScope;
use App\Models\AutoSchool;
use App\Models\ExamSchedule;
use App\Models\User;
use App\Notifications\ExamScheduleUpdatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminExamController extends Controller
{
    use InteractsWithAdminScope;

    public function index(Request $request): View
    {
        $admin = $this->adminUser($request);

        return view('admin.exams.index', [
            'exams' => $this->examQueryForAdmin($admin)
                ->with(['user', 'autoSchool'])
                ->orderBy('exam_date')
                ->paginate(12),
            'candidates' => $this->candidateQueryForAdmin($admin)
                ->orderBy('name')
                ->get(),
            'schools' => $this->availableSchoolsForAdmin($admin),
            'canChooseSchool' => ! $this->shouldScopeToSchool($admin),
        ]);
    }

    public function create(Request $request): View
    {
        $admin = $this->adminUser($request);

        return view('admin.exams.create', [
            'exam' => new ExamSchedule(),
            'candidates' => $this->candidateQueryForAdmin($admin)
                ->orderBy('name')
                ->get(),
            'schools' => $this->availableSchoolsForAdmin($admin),
            'canChooseSchool' => ! $this->shouldScopeToSchool($admin),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateExam($request, $this->adminUser($request));

        $exam = ExamSchedule::create($validated);
        $exam->load(['user', 'autoSchool']);
        $exam->user?->notify(new ExamScheduleUpdatedNotification($exam, 'created'));

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'تمت برمجة الامتحان بنجاح.');
    }

    public function edit(Request $request, ExamSchedule $exam): View
    {
        $admin = $this->adminUser($request);
        $this->ensureManagedExam($admin, $exam);

        return view('admin.exams.edit', [
            'exam' => $exam,
            'candidates' => $this->candidateQueryForAdmin($admin)
                ->orderBy('name')
                ->get(),
            'schools' => $this->availableSchoolsForAdmin($admin),
            'canChooseSchool' => ! $this->shouldScopeToSchool($admin),
        ]);
    }

    public function update(Request $request, ExamSchedule $exam): RedirectResponse
    {
        $admin = $this->adminUser($request);
        $this->ensureManagedExam($admin, $exam);
        $validated = $this->validateExam($request, $admin, $exam);

        $exam->update($validated);
        $exam->load(['user', 'autoSchool']);
        $exam->user?->notify(new ExamScheduleUpdatedNotification($exam, 'updated'));

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'تم تحديث الامتحان.');
    }

    public function destroy(Request $request, ExamSchedule $exam): RedirectResponse
    {
        $this->ensureManagedExam($this->adminUser($request), $exam);

        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'تم حذف الامتحان.');
    }

    private function validateExam(Request $request, User $admin, ?ExamSchedule $exam = null): array
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_CANDIDATE),
            ],
            'auto_school_id' => ['nullable', 'exists:auto_schools,id'],
            'exam_date' => ['required', 'date'],
            'status' => ['required', Rule::in(ExamSchedule::statuses())],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $candidate = $this->candidateQueryForAdmin($admin)
            ->whereKey($validated['user_id'])
            ->first();

        if (! $candidate) {
            throw ValidationException::withMessages([
                'user_id' => 'هذا المترشح غير متاح من داخل مساحة الإدارة الخاصة بك.',
            ]);
        }

        $activeStatuses = [ExamSchedule::STATUS_PLANNED];
        $isActiveStatus = in_array($validated['status'], $activeStatuses, true);
        if ($isActiveStatus) {
            $hasActiveExam = ExamSchedule::query()
                ->where('user_id', $validated['user_id'])
                ->whereIn('status', $activeStatuses)
                ->when($exam, fn ($query) => $query->whereKeyNot($exam->id))
                ->exists();

            if ($hasActiveExam) {
                throw ValidationException::withMessages([
                    'user_id' => 'لدى هذا المترشح امتحان مبرمج بالفعل.',
                ]);
            }
        }

        if ($this->shouldScopeToSchool($admin)) {
            $validated['auto_school_id'] = $this->managedSchoolId($admin);
        } elseif (! empty($candidate->auto_school_id)) {
            if (! empty($validated['auto_school_id']) && (int) $validated['auto_school_id'] !== (int) $candidate->auto_school_id) {
                throw ValidationException::withMessages([
                    'auto_school_id' => 'يجب أن تتطابق مدرسة السياقة المحددة مع مدرسة المترشح.',
                ]);
            }

            $validated['auto_school_id'] = $candidate->auto_school_id;
        }

        return $validated;
    }
}
