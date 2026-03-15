<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CandidateCourseController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('candidate.courses.index', [
            'courses' => Course::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->paginate(12),
            'categoryLabels' => Course::categoryLabels(),
        ]);
    }

    public function show(Course $course): View|RedirectResponse
    {
        if (auth()->user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (! $course->is_active) {
            abort(404);
        }

        return view('candidate.courses.show', [
            'course' => $course,
            'categoryLabels' => Course::categoryLabels(),
        ]);
    }

    public function pdf(Course $course)
    {
        if (auth()->user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (! $course->is_active || ! $course->pdf_path) {
            abort(404);
        }

        if (! Storage::disk('public')->exists($course->pdf_path)) {
            abort(404);
        }

        $path = Storage::disk('public')->path($course->pdf_path);
        $filename = basename($course->pdf_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
