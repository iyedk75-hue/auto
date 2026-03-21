<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CandidateCourseController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $locale = app()->getLocale();

        return view('candidate.courses.index', [
            'courses' => Course::query()
                ->where('is_active', true)
                ->when($user?->auto_school_id, fn ($query) => $query->where('auto_school_id', $user->auto_school_id))
                ->orderBy('sort_order')
                ->paginate(12),
            'categoryLabels' => Course::categoryLabels(),
            'locale' => $locale,
        ]);
    }

    public function show(Request $request, Course $course): View|RedirectResponse
    {
        if (auth()->user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (! $course->is_active) {
            abort(404);
        }

        if ($request->user()?->auto_school_id && (int) $course->auto_school_id !== (int) $request->user()->auto_school_id) {
            abort(404);
        }

        $locale = app()->getLocale();
        $isArabicView = $locale === 'ar';
        $showArabicUnavailable = $isArabicView && ! $course->hasArabicTranslation();

        $localizedTitle = $isArabicView
            ? ($course->titleForLocale('ar') ?: $course->title)
            : $course->titleForLocale('fr');

        $localizedDescription = $showArabicUnavailable
            ? null
            : ($isArabicView ? ($course->descriptionForLocale('ar') ?: $course->description) : $course->descriptionForLocale('fr'));

        $localizedContent = $showArabicUnavailable
            ? null
            : ($isArabicView ? ($course->contentForLocale('ar') ?: $course->content) : $course->contentForLocale('fr'));

        $resourceItems = $course->resolvedResources($locale)
            ->map(fn (array $resource) => $this->presentResolvedResource($course, $resource, $locale))
            ->values();

        $selectedResource = $this->resolveSelectedResource($resourceItems, (string) $request->query('resource', ''));
        $selectedResourceKey = $selectedResource['key'] ?? null;

        return view('candidate.courses.show', [
            'course' => $course,
            'categoryLabels' => Course::categoryLabels(),
            'localizedTitle' => $localizedTitle,
            'localizedDescription' => $localizedDescription,
            'localizedContent' => $localizedContent,
            'showArabicUnavailable' => $showArabicUnavailable,
            'resourceItems' => $resourceItems,
            'selectedResource' => $selectedResource,
            'selectedResourceKey' => $selectedResourceKey,
        ]);
    }

    public function audio(Course $course, Request $request): BinaryFileResponse|RedirectResponse
    {
        [$disk, $path] = $this->resolveProtectedAsset($course, $course->audio_path, $course->audioDisk(), $request);
        abort_unless($course->hasAudioMedia(), 404);

        $mime = $course->audio_mime
            ?: Storage::disk($disk)->mimeType($path)
            ?: 'audio/mpeg';

        return response()->file(Storage::disk($disk)->path($path), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

    public function resourceFile(Course $course, CourseResource $resource, Request $request): BinaryFileResponse|RedirectResponse
    {
        abort_unless($resource->course_id === $course->id, 404);
        abort_unless($resource->isAudio(), 404);

        [$disk, $path, $mime] = $this->resolveProtectedResourceAsset($course, $resource, $request);
        if ($mime === 'application/octet-stream') {
            $mime = Storage::disk($disk)->mimeType($path)
                ?: 'audio/mpeg';
        }

        return response()->file(Storage::disk($disk)->path($path), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function resolveSelectedResource(Collection $resourceItems, string $selectedKey): ?array
    {
        if ($selectedKey !== '') {
            $selected = $resourceItems->firstWhere('key', $selectedKey);

            if ($selected) {
                return $selected;
            }
        }

        return $resourceItems->first();
    }

    /**
     * @param  array<string, mixed>  $resource
     * @return array<string, mixed>
     */
    private function presentResolvedResource(Course $course, array $resource, string $locale): array
    {
        $viewerKind = $this->viewerKindForResource($resource);
        $typeLabel = $this->typeLabelForResource($resource, $viewerKind);
        $dateLabel = $resource['created_at']?->locale($locale)->translatedFormat('d M Y');
        $resourceMime = $resource['file_mime'] ?? null;
        if (! $resourceMime) {
            $resourceMime = match ($resource['type'] ?? null) {
                CourseResource::TYPE_AUDIO => 'audio/mpeg',
                default => null,
            };
        }

        return array_merge($resource, [
            'viewer_kind' => $viewerKind,
            'viewer_url' => $this->viewerUrlForResource($course, $resource, $viewerKind),
            'viewer_storage_key' => $this->viewerStorageKeyForResource($course, $resource, $viewerKind),
            'type_label' => $typeLabel,
            'date_label' => $dateLabel,
            'file_mime' => $resourceMime,
            'meta_label' => collect([$typeLabel, $dateLabel])->filter()->implode(' · '),
            'select_url' => route('courses.show', [
                'course' => $course,
                'resource' => $resource['key'],
            ], absolute: false).'#course-resource-viewer',
        ]);
    }

    /**
     * @param  array<string, mixed>  $resource
     */
    private function viewerKindForResource(array $resource): string
    {
        $fileMime = (string) ($resource['file_mime'] ?? '');

        if ($fileMime !== '' && str_starts_with($fileMime, 'audio/')) {
            return 'audio';
        }

        return match ($resource['type'] ?? null) {
            CourseResource::TYPE_AUDIO => 'audio',
            default => 'note',
        };
    }

    /**
     * @param  array<string, mixed>  $resource
     */
    private function typeLabelForResource(array $resource, string $viewerKind): string
    {
        return match ($resource['type'] ?? null) {
            CourseResource::TYPE_AUDIO => __('ui.classroom.resource_types.audio'),
            default => __('ui.classroom.resource_types.note'),
        };
    }

    /**
     * @param  array<string, mixed>  $resource
     */
    private function viewerUrlForResource(Course $course, array $resource, string $viewerKind): ?string
    {
        if ($viewerKind === 'note') {
            return null;
        }

        if (($resource['origin'] ?? null) === 'legacy') {
            return route('courses.audio', $course, absolute: false);
        }

        return route('courses.resources.file', [
            'course' => $course,
            'resource' => $resource['key'],
        ], absolute: false);
    }

    /**
     * @param  array<string, mixed>  $resource
     */
    private function viewerStorageKeyForResource(Course $course, array $resource, string $viewerKind): ?string
    {
        if ($viewerKind !== 'audio') {
            return null;
        }

        return (string) ($resource['origin'] ?? 'resource').':'.$course->getKey().':'.$resource['key'];
    }

    /**
     * @return array{0:string,1:string}
     */
    private function resolveProtectedAsset(Course $course, ?string $path, ?string $disk, Request $request): array
    {
        $user = $request->user();

        abort_unless($user, 403);
        abort_if(blank($path) || blank($disk), 404);

        if (! $user->isAdmin() && ! $course->is_active) {
            abort(404);
        }

        if (! $user->isAdmin() && $user->auto_school_id && (int) $course->auto_school_id !== (int) $user->auto_school_id) {
            abort(404);
        }

        abort_unless(Storage::disk($disk)->exists($path), 404);

        return [$disk, $path];
    }

    /**
     * @return array{0:string,1:string,2:string}
     */
    private function resolveProtectedResourceAsset(Course $course, CourseResource $resource, Request $request): array
    {
        $user = $request->user();
        $disk = $resource->assetDisk();
        $path = $resource->file_path;
        $mime = $resource->file_mime ?? 'application/octet-stream';

        abort_unless($user, 403);
        abort_if(blank($path) || blank($disk), 404);

        if (! $user->isAdmin() && (! $course->is_active || ! $resource->is_active)) {
            abort(404);
        }

        if (! $user->isAdmin() && $user->auto_school_id && (int) $course->auto_school_id !== (int) $user->auto_school_id) {
            abort(404);
        }

        abort_unless(Storage::disk($disk)->exists($path), 404);

        return [$disk, $path, $mime];
    }
}
