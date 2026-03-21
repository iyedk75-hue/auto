<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAdminScope;
use App\Models\AutoSchool;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminCandidateController extends Controller
{
    use InteractsWithAdminScope;

    private function ensureSuperAdmin(Request $request): void
    {
        abort_unless($this->adminUser($request)->isSuperAdmin(), 403);
    }

    public function index(Request $request): View
    {
        $admin = $this->adminUser($request);
        $search = trim((string) $request->query('q', $request->query('search', '')));
        $selectedSchoolInput = $request->query('auto_school_id');
        $selectedSchoolId = is_numeric($selectedSchoolInput) ? (int) $selectedSchoolInput : null;
        $availableSchools = $this->availableSchoolsForAdmin($admin);

        if ($selectedSchoolId && ! $availableSchools->contains('id', $selectedSchoolId)) {
            $selectedSchoolId = null;
        }

        return view('admin.candidates.index', [
            'candidates' => $this->candidateQueryForAdmin($admin)
                ->withCount(['quizSessions', 'payments'])
                ->when($selectedSchoolId !== null, fn ($query) => $query->where('auto_school_id', $selectedSchoolId))
                ->when($search !== '', function ($query) use ($search) {
                    $like = '%'.$search.'%';
                    $query->where(function ($inner) use ($like, $search) {
                        $inner
                            ->where('name', 'like', $like)
                            ->orWhere('email', 'like', $like)
                            ->orWhere('phone', 'like', $like);
                        $inner->orWhereHas('autoSchool', function ($schoolQuery) use ($like) {
                            $schoolQuery->where('name', 'like', $like);
                        });
                        if (is_numeric($search)) {
                            $inner->orWhere('id', (int) $search);
                        }
                    });
                })
                ->with('autoSchool')
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'search' => $search,
            'schools' => $availableSchools,
            'selectedSchoolId' => $selectedSchoolId,
            'canFilterBySchool' => $availableSchools->count() > 1 || $admin->isSuperAdmin(),
            'canManageCandidates' => $admin->isSuperAdmin(),
        ]);
    }

    public function show(Request $request, User $candidate): View
    {
        $this->ensureManagedCandidate($this->adminUser($request), $candidate);

        $candidate->load('autoSchool')
            ->loadCount(['payments', 'quizSessions', 'exams']);

        $quizSessions = $candidate->quizSessions()
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(5)
            ->get();

        $averageQuizScore = (float) $candidate->quizSessions()
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(CASE WHEN total_questions > 0 THEN (score * 100.0) / total_questions ELSE 0 END) as average_score')
            ->value('average_score');

        return view('admin.candidates.show', [
            'candidate' => $candidate,
            'quizSessions' => $quizSessions,
            'averageQuizScore' => $averageQuizScore,
            'canManageCandidate' => $this->adminUser($request)->isSuperAdmin(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->ensureSuperAdmin($request);
        $admin = $this->adminUser($request);

        return view('admin.candidates.create', [
            'candidate' => new User(),
            'schools' => $this->availableSchoolsForAdmin($admin),
            'canChooseSchool' => ! $this->shouldScopeToSchool($admin),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin($request);
        $admin = $this->adminUser($request);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'auto_school_id' => ['nullable', 'exists:auto_schools,id'],
            'balance_due' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['role'] = User::ROLE_CANDIDATE;
        $validated['registered_at'] = now();
        $validated['auto_school_id'] = ! $this->shouldScopeToSchool($admin)
            ? ($validated['auto_school_id'] ?? null)
            : $this->managedSchoolId($admin);

        User::create($validated);

        return redirect()
            ->route('admin.candidates.index')
            ->with('status', __('ui.admin_candidates.created'));
    }

    public function edit(Request $request, User $candidate): View
    {
        $this->ensureSuperAdmin($request);
        $admin = $this->adminUser($request);
        $this->ensureManagedCandidate($admin, $candidate);

        return view('admin.candidates.edit', [
            'candidate' => $candidate->load('autoSchool'),
            'schools' => $this->availableSchoolsForAdmin($admin),
            'canChooseSchool' => ! $this->shouldScopeToSchool($admin),
        ]);
    }

    public function update(Request $request, User $candidate): RedirectResponse
    {
        $this->ensureSuperAdmin($request);
        $admin = $this->adminUser($request);
        $this->ensureManagedCandidate($admin, $candidate);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($candidate->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'auto_school_id' => ['nullable', 'exists:auto_schools,id'],
            'balance_due' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['auto_school_id'] = ! $this->shouldScopeToSchool($admin)
            ? ($validated['auto_school_id'] ?? null)
            : $this->managedSchoolId($admin);

        $candidate->update($validated);

        return redirect()
            ->route('admin.candidates.show', $candidate)
            ->with('status', __('ui.admin_candidates.updated'));
    }

    public function destroy(Request $request, User $candidate): RedirectResponse
    {
        $this->ensureSuperAdmin($request);
        $this->ensureManagedCandidate($this->adminUser($request), $candidate);

        $candidate->delete();

        return redirect()
            ->route('admin.candidates.index')
            ->with('status', __('ui.admin_candidates.deleted'));
    }
}
