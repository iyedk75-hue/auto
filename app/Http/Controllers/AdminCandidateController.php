<?php

namespace App\Http\Controllers;

use App\Models\AutoSchool;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCandidateController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', $request->query('search', '')));

        return view('admin.candidates.index', [
            'candidates' => User::query()
                ->where('role', User::ROLE_CANDIDATE)
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
        ]);
    }

    public function show(User $candidate): View
    {
        $this->ensureCandidate($candidate);

        $candidate->load('autoSchool')->loadCount(['payments', 'quizSessions', 'exams']);

        return view('admin.candidates.show', [
            'candidate' => $candidate,
        ]);
    }

    public function edit(User $candidate): View
    {
        $this->ensureCandidate($candidate);

        return view('admin.candidates.edit', [
            'candidate' => $candidate->load('autoSchool'),
            'schools' => AutoSchool::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $candidate): RedirectResponse
    {
        $this->ensureCandidate($candidate);

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
        ]);

        $candidate->update($validated);

        return redirect()
            ->route('admin.candidates.show', $candidate)
            ->with('status', 'Candidat mis à jour.');
    }

    public function destroy(User $candidate): RedirectResponse
    {
        $this->ensureCandidate($candidate);

        $candidate->delete();

        return redirect()
            ->route('admin.candidates.index')
            ->with('status', 'Candidat supprimé.');
    }

    private function ensureCandidate(User $candidate): void
    {
        abort_unless($candidate->role === User::ROLE_CANDIDATE, 404);
    }
}
