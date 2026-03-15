<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function show(Course $course): View|RedirectResponse
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

        return view('candidate.courses.show', [
            'course' => $course,
            'categoryLabels' => Course::categoryLabels(),
            'localizedTitle' => $localizedTitle,
            'localizedDescription' => $localizedDescription,
            'localizedContent' => $localizedContent,
            'showArabicUnavailable' => $showArabicUnavailable,
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
}
