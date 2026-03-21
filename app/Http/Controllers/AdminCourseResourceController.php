<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAdminScope;
use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCourseResourceController extends Controller
{
    use InteractsWithAdminScope;

    public function index(Course $course): View
    {
        $this->ensureManagedCourse(request()->user(), $course);

        return view('admin.course-resources.index', [
            'course' => $course,
            'resources' => $course->resources()->get(),
        ]);
    }

    public function create(Course $course): View
    {
        $this->ensureManagedCourse(request()->user(), $course);

        return view('admin.course-resources.create', [
            'course' => $course,
            'resource' => new CourseResource(),
            'resourceTypes' => CourseResource::TYPES,
        ]);
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->ensureManagedCourse($request->user(), $course);
        $validated = $this->validateResource($request, true);

        $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => $validated['resource_type'],
            'title' => $validated['title'],
            'title_ar' => $validated['title_ar'] ?? null,
            'note_body' => $validated['resource_type'] === CourseResource::TYPE_NOTE ? ($validated['note_body'] ?? null) : null,
            'note_body_ar' => $validated['resource_type'] === CourseResource::TYPE_NOTE ? ($validated['note_body_ar'] ?? null) : null,
            'file_path' => $this->storeFileIfNeeded($request, $validated['resource_type']),
            'file_mime' => $this->determineMimeIfNeeded($request, $validated['resource_type']),
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.courses.resources.index', $course)
            ->with('status', 'تمت إضافة المورد بنجاح.');
    }

    public function edit(Course $course, CourseResource $resource): View
    {
        $this->ensureManagedCourse(request()->user(), $course);
        abort_unless($resource->course_id === $course->id, 404);

        return view('admin.course-resources.edit', [
            'course' => $course,
            'resource' => $resource,
            'resourceTypes' => CourseResource::TYPES,
        ]);
    }

    public function update(Request $request, Course $course, CourseResource $resource): RedirectResponse
    {
        $this->ensureManagedCourse($request->user(), $course);
        abort_unless($resource->course_id === $course->id, 404);

        $validated = $this->validateResource($request, false, $resource);
        $previousType = $resource->resource_type;
        $nextType = $validated['resource_type'];
        $filePath = $resource->file_path;
        $fileMime = $resource->file_mime;

        if ($nextType === CourseResource::TYPE_NOTE) {
            if ($resource->isFileResource()) {
                $resource->deleteFileAsset();
            }
            $filePath = null;
            $fileMime = null;
        } elseif ($request->hasFile('resource_file')) {
            if ($resource->isFileResource()) {
                $resource->deleteFileAsset();
            }
            $filePath = $this->storeFileIfNeeded($request, $nextType);
            $fileMime = $this->determineMimeIfNeeded($request, $nextType);
        } elseif ($previousType === CourseResource::TYPE_NOTE && $nextType !== CourseResource::TYPE_NOTE) {
            $filePath = null;
            $fileMime = null;
        }

        $resource->update([
            'resource_type' => $nextType,
            'title' => $validated['title'],
            'title_ar' => $validated['title_ar'] ?? null,
            'note_body' => $nextType === CourseResource::TYPE_NOTE ? ($validated['note_body'] ?? null) : null,
            'note_body_ar' => $nextType === CourseResource::TYPE_NOTE ? ($validated['note_body_ar'] ?? null) : null,
            'file_path' => $filePath,
            'file_mime' => $fileMime,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.courses.resources.index', $course)
            ->with('status', 'تم تحديث المورد بنجاح.');
    }

    public function destroy(Course $course, CourseResource $resource): RedirectResponse
    {
        $this->ensureManagedCourse(request()->user(), $course);
        abort_unless($resource->course_id === $course->id, 404);

        $resource->deleteFileAsset();
        $resource->delete();

        return redirect()
            ->route('admin.courses.resources.index', $course)
            ->with('status', 'تم حذف المورد.');
    }

    private function validateResource(Request $request, bool $isCreate, ?CourseResource $resource = null): array
    {
        $baseRules = [
            'resource_type' => ['required', Rule::in(CourseResource::TYPES)],
            'title' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'note_body' => ['nullable', 'string'],
            'note_body_ar' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];

        $type = $request->input('resource_type');

        if ($type === CourseResource::TYPE_NOTE) {
            $baseRules['note_body'][] = 'required';
        }

        if ($type === CourseResource::TYPE_AUDIO) {
            $requiresFile = $isCreate;

            if (! $isCreate && $resource) {
                $sameType = $resource->resource_type === $type;
                $hasExistingFile = filled($resource->file_path);
                $requiresFile = ! $sameType || ! $hasExistingFile;
            }

            $baseRules['resource_file'] = array_filter([
                $requiresFile ? 'required' : 'nullable',
                'file',
                'mimes:mp3,wav,ogg,m4a,aac',
                'max:51200',
            ]);
        }

        return $request->validate($baseRules);
    }

    private function storeFileIfNeeded(Request $request, string $type): ?string
    {
        if (! $request->hasFile('resource_file')) {
            return null;
        }

        return match ($type) {
            CourseResource::TYPE_AUDIO => $request->file('resource_file')->store(CourseResource::PROTECTED_AUDIO_DIRECTORY, 'local'),
            default => null,
        };
    }

    private function determineMimeIfNeeded(Request $request, string $type): ?string
    {
        if (! $request->hasFile('resource_file')) {
            return null;
        }

        return match ($type) {
            CourseResource::TYPE_AUDIO => $request->file('resource_file')->getMimeType(),
            default => null,
        };
    }

    private function ensureManagedCourse($admin, Course $course): void
    {
        if ($this->shouldScopeToSchool($admin)) {
            abort_unless((int) $course->auto_school_id === $this->managedSchoolId($admin), 403);
        }
    }
}
