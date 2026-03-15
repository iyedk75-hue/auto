<?php

namespace App\Http\Controllers;

use App\Models\AutoSchool;
use App\Models\ExamSchedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminExamController extends Controller
{
    public function index(): View
    {
        return view('admin.exams.index', [
            'exams' => ExamSchedule::query()
                ->with(['user', 'autoSchool'])
                ->orderBy('exam_date')
                ->paginate(12),
            'candidates' => User::query()
                ->where('role', User::ROLE_CANDIDATE)
                ->orderBy('name')
                ->get(),
            'schools' => AutoSchool::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.exams.create', [
            'exam' => new ExamSchedule(),
            'candidates' => User::query()
                ->where('role', User::ROLE_CANDIDATE)
                ->orderBy('name')
                ->get(),
            'schools' => AutoSchool::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateExam($request);

        ExamSchedule::create($validated);

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'Examen planifié avec succès.');
    }

    public function edit(ExamSchedule $exam): View
    {
        return view('admin.exams.edit', [
            'exam' => $exam,
            'candidates' => User::query()
                ->where('role', User::ROLE_CANDIDATE)
                ->orderBy('name')
                ->get(),
            'schools' => AutoSchool::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, ExamSchedule $exam): RedirectResponse
    {
        $validated = $this->validateExam($request);

        $exam->update($validated);

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'Examen mis à jour.');
    }

    public function destroy(ExamSchedule $exam): RedirectResponse
    {
        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'Examen supprimé.');
    }

    private function validateExam(Request $request): array
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

        if (empty($validated['auto_school_id'])) {
            $validated['auto_school_id'] = User::query()
                ->where('id', $validated['user_id'])
                ->value('auto_school_id');
        }

        return $validated;
    }
}
