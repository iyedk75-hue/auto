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
        if (auth()->user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $locale = app()->getLocale();

        return view('candidate.courses.index', [
            'courses' => Course::query()
                ->where('is_active', true)
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

    public function media(Course $course, Request $request): BinaryFileResponse|RedirectResponse
    {
        [$disk, $path] = $this->resolveProtectedAsset($course, $course->media_path, $course->mediaDisk(), $request);

        return response()->file(Storage::disk($disk)->path($path), [
            'Content-Type' => $course->media_mime ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

    public function pdf(Course $course, Request $request): BinaryFileResponse|RedirectResponse
    {
        [$disk, $path] = $this->resolveProtectedAsset($course, $course->pdf_path, $course->pdfDisk(), $request);

        return response()->file(Storage::disk($disk)->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

    public function resourceFile(Course $course, CourseResource $resource, Request $request): BinaryFileResponse|RedirectResponse
    {
        abort_unless($resource->course_id === $course->id, 404);
        abort_unless($resource->isFileResource(), 404);

        [$disk, $path, $mime] = $this->resolveProtectedResourceAsset($course, $resource, $request);

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

        return array_merge($resource, [
            'viewer_kind' => $viewerKind,
            'viewer_url' => $this->viewerUrlForResource($course, $resource, $viewerKind),
            'type_label' => $typeLabel,
            'date_label' => $dateLabel,
            'meta_label' => collect([$typeLabel, $dateLabel])->filter()->implode(' · '),
            'select_url' => route('courses.show', [
                'course' => $course,
                'resource' => $resource['key'],
            ]).'#course-resource-viewer',
        ]);
    }

    /**
     * @param  array<string, mixed>  $resource
     */
    private function viewerKindForResource(array $resource): string
    {
        $fileMime = (string) ($resource['file_mime'] ?? '');

        if ($fileMime !== '' && str_starts_with($fileMime, 'image/')) {
            return 'image';
        }

        return match ($resource['type'] ?? null) {
            CourseResource::TYPE_VIDEO => 'video',
            CourseResource::TYPE_PDF => 'pdf',
            default => 'note',
        };
    }

    /**
     * @param  array<string, mixed>  $resource
     */
    private function typeLabelForResource(array $resource, string $viewerKind): string
    {
        if ($viewerKind === 'image') {
            return __('ui.classroom.resource_types.media');
        }

        return match ($resource['type'] ?? null) {
            CourseResource::TYPE_VIDEO => __('ui.classroom.resource_types.video'),
            CourseResource::TYPE_PDF => __('ui.classroom.resource_types.pdf'),
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
            return $viewerKind === 'pdf'
                ? route('courses.pdf', $course)
                : route('courses.media', $course);
        }

        return route('courses.resources.file', [
            'course' => $course,
            'resource' => $resource['key'],
        ]);
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

        abort_unless(Storage::disk($disk)->exists($path), 404);

        return [$disk, $path, $mime];
    }
}
