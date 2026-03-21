<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAdminScope;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCourseController extends Controller
{
    use InteractsWithAdminScope;

    public function index(): View
    {
        $admin = request()->user();

        return view('admin.courses.index', [
            'courses' => Course::query()
                ->when($this->shouldScopeToSchool($admin), fn ($query) => $query->where('auto_school_id', $this->managedSchoolId($admin)))
                ->withCount('resources')
                ->with('autoSchool')
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->paginate(12),
            'categoryLabels' => Course::categoryLabels(),
            'admin' => $admin,
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
        if ($response = $this->rejectOversizedAudioUpload($request)) {
            return $response;
        }

        $validated = $this->validateCourse($request);
        $admin = $request->user();

        $audioPath = null;
        $audioMime = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store(Course::PROTECTED_AUDIO_DIRECTORY, 'local');
            $audioMime = $request->file('audio')->getMimeType();
        }

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('courses/covers', 'public');
        }

        Course::create([
            'id' => (string) Str::uuid(),
            'auto_school_id' => $this->shouldScopeToSchool($admin) ? $this->managedSchoolId($admin) : null,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'title_ar' => $validated['title_ar'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'content' => $validated['content'] ?? null,
            'content_ar' => $validated['content_ar'] ?? null,
            'cover_path' => $coverPath,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'audio_path' => $audioPath,
            'audio_mime' => $audioMime,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('status', __('ui.admin_courses.created'));
    }

    public function edit(Course $course): View
    {
        $this->ensureManagedCourse(request()->user(), $course);

        return view('admin.courses.edit', [
            'course' => $course,
            'categories' => Course::categoryLabels(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->ensureManagedCourse($request->user(), $course);

        if ($response = $this->rejectOversizedAudioUpload($request)) {
            return $response;
        }

        $validated = $this->validateCourse($request);

        $audioPath = $course->audio_path;
        $audioMime = $course->audio_mime;
        if ($request->hasFile('audio')) {
            $course->deleteAudioAsset();
            $audioPath = $request->file('audio')->store(Course::PROTECTED_AUDIO_DIRECTORY, 'local');
            $audioMime = $request->file('audio')->getMimeType();
        }

        $coverPath = $course->cover_path;
        if ($request->hasFile('cover')) {
            if ($course->cover_path) {
                Storage::disk('public')->delete($course->cover_path);
            }
            $coverPath = $request->file('cover')->store('courses/covers', 'public');
        }

        $course->update([
            'category' => $validated['category'],
            'title' => $validated['title'],
            'title_ar' => $validated['title_ar'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'content' => $validated['content'] ?? null,
            'content_ar' => $validated['content_ar'] ?? null,
            'cover_path' => $coverPath,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'audio_path' => $audioPath,
            'audio_mime' => $audioMime,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('status', __('ui.admin_courses.updated'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->ensureManagedCourse(request()->user(), $course);

        $course->resources()->get()->each(function ($resource) {
            $resource->deleteFileAsset();
        });

        $course->deleteAudioAsset();

        if ($course->cover_path) {
            Storage::disk('public')->delete($course->cover_path);
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('status', __('ui.admin_courses.deleted'));
    }

    private function validateCourse(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(Course::CATEGORIES)],
            'description' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_ar' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'cover' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'audio' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:'.Course::AUDIO_MAX_KB],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function ensureManagedCourse($admin, Course $course): void
    {
        if ($this->shouldScopeToSchool($admin)) {
            abort_unless((int) $course->auto_school_id === $this->managedSchoolId($admin), 403);
        }
    }

    private function rejectOversizedAudioUpload(Request $request): ?RedirectResponse
    {
        if ($this->requestExceededPostMaxSize($request) || $this->audioUploadFailedDueToPhpLimit()) {
            return back()
                ->withInput()
                ->withErrors(new MessageBag([
                    'audio' => __('ui.admin_courses.audio_upload_too_large', [
                        'size' => ini_get('upload_max_filesize') ?: '2M',
                    ]),
                ]));
        }

        return null;
    }

    private function requestExceededPostMaxSize(Request $request): bool
    {
        $contentLength = (int) ($request->server('CONTENT_LENGTH') ?? 0);
        $postMaxSize = $this->normalizeIniSizeToBytes((string) ini_get('post_max_size'));

        return $contentLength > 0 && $postMaxSize > 0 && $contentLength > $postMaxSize;
    }

    private function audioUploadFailedDueToPhpLimit(): bool
    {
        $audioFile = $_FILES['audio'] ?? null;

        if (! is_array($audioFile)) {
            return false;
        }

        return in_array((int) ($audioFile['error'] ?? UPLOAD_ERR_OK), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true);
    }

    private function normalizeIniSizeToBytes(string $size): int
    {
        $size = trim($size);

        if ($size === '') {
            return 0;
        }

        $unit = strtolower(substr($size, -1));
        $value = (float) $size;

        return match ($unit) {
            'g' => (int) ($value * 1024 * 1024 * 1024),
            'm' => (int) ($value * 1024 * 1024),
            'k' => (int) ($value * 1024),
            default => (int) $value,
        };
    }
}
