<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCourseController extends Controller
{
    public function index(): View
    {
        return view('admin.courses.index', [
            'courses' => Course::query()
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->paginate(12),
            'categoryLabels' => Course::categoryLabels(),
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.create', [
            'course' => new Course(),
            'categories' => Course::categoryLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCourse($request);

        $mediaPath = null;
        $mediaMime = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('courses/media', 'public');
            $mediaMime = $request->file('media')->getMimeType();
        }

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('courses/covers', 'public');
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('courses/pdf', 'public');
        }

        Course::create([
            'id' => (string) Str::uuid(),
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'cover_path' => $coverPath,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'media_path' => $mediaPath,
            'media_mime' => $mediaMime,
            'pdf_path' => $pdfPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('status', 'Cours ajouté avec succès.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', [
            'course' => $course,
            'categories' => Course::categoryLabels(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $this->validateCourse($request);

        $mediaPath = $course->media_path;
        $mediaMime = $course->media_mime;
        if ($request->hasFile('media')) {
            if ($course->media_path) {
                Storage::disk('public')->delete($course->media_path);
            }
            $mediaPath = $request->file('media')->store('courses/media', 'public');
            $mediaMime = $request->file('media')->getMimeType();
        }

        $coverPath = $course->cover_path;
        if ($request->hasFile('cover')) {
            if ($course->cover_path) {
                Storage::disk('public')->delete($course->cover_path);
            }
            $coverPath = $request->file('cover')->store('courses/covers', 'public');
        }

        $pdfPath = $course->pdf_path;
        if ($request->hasFile('pdf')) {
            if ($course->pdf_path) {
                Storage::disk('public')->delete($course->pdf_path);
            }
            $pdfPath = $request->file('pdf')->store('courses/pdf', 'public');
        }

        $course->update([
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'cover_path' => $coverPath,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'media_path' => $mediaPath,
            'media_mime' => $mediaMime,
            'pdf_path' => $pdfPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('status', 'Cours mis à jour avec succès.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('status', 'Cours supprimé.');
    }

    private function validateCourse(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Course::CATEGORIES)],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'cover' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,webm', 'max:51200'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
