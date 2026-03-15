<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourseProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_course_assets_are_stored_on_local_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Protected lesson',
            'category' => Course::CATEGORIES[0],
            'media' => UploadedFile::fake()->create('lesson.mp4', 1200, 'video/mp4'),
            'pdf' => UploadedFile::fake()->create('lesson.pdf', 400, 'application/pdf'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $course = Course::query()->where('title', 'Protected lesson')->firstOrFail();

        Storage::disk('local')->assertExists($course->media_path);
        Storage::disk('local')->assertExists($course->pdf_path);
        Storage::disk('public')->assertMissing($course->media_path);
        Storage::disk('public')->assertMissing($course->pdf_path);
        $this->assertSame('local', $course->mediaDisk());
        $this->assertSame('local', $course->pdfDisk());
    }

    public function test_updating_legacy_public_assets_moves_replacements_to_local_and_deletes_old_public_files(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        Storage::disk('public')->put('courses/media/legacy.mp4', 'legacy-video');
        Storage::disk('public')->put('courses/pdf/legacy.pdf', 'legacy-pdf');

        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Legacy lesson',
            'description' => 'Legacy',
            'content' => 'Legacy',
            'media_path' => 'courses/media/legacy.mp4',
            'media_mime' => 'video/mp4',
            'pdf_path' => 'courses/pdf/legacy.pdf',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.courses.update', $course), [
            'title' => 'Legacy lesson updated',
            'category' => Course::CATEGORIES[0],
            'description' => 'Updated',
            'content' => 'Updated',
            'media' => UploadedFile::fake()->create('replacement.mp4', 800, 'video/mp4'),
            'pdf' => UploadedFile::fake()->create('replacement.pdf', 300, 'application/pdf'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $course->refresh();

        Storage::disk('public')->assertMissing('courses/media/legacy.mp4');
        Storage::disk('public')->assertMissing('courses/pdf/legacy.pdf');
        Storage::disk('local')->assertExists($course->media_path);
        Storage::disk('local')->assertExists($course->pdf_path);
        $this->assertSame('local', $course->mediaDisk());
        $this->assertSame('local', $course->pdfDisk());
    }

    public function test_legacy_public_asset_paths_still_resolve_to_public_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('public')->put('courses/media/legacy.mp4', 'legacy-video');
        Storage::disk('public')->put('courses/pdf/legacy.pdf', 'legacy-pdf');

        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Legacy lesson',
            'description' => 'Legacy',
            'content' => 'Legacy',
            'media_path' => 'courses/media/legacy.mp4',
            'media_mime' => 'video/mp4',
            'pdf_path' => 'courses/pdf/legacy.pdf',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertSame('public', $course->mediaDisk());
        $this->assertSame('public', $course->pdfDisk());
    }

    public function test_guest_cannot_access_protected_course_assets(): void
    {
        Storage::fake('local');
        $course = $this->makeProtectedCourse();

        $this->get(route('courses.media', $course))
            ->assertRedirect(route('login'));

        $this->get(route('courses.pdf', $course))
            ->assertRedirect(route('login'));
    }

    public function test_candidate_can_access_protected_local_assets_through_inline_routes(): void
    {
        Storage::fake('local');
        $candidate = User::factory()->create();
        $course = $this->makeProtectedCourse();

        $this->actingAs($candidate)
            ->get(route('courses.media', $course))
            ->assertOk()
            ->assertHeader('content-type', 'video/mp4')
            ->assertHeader('content-disposition', 'inline; filename="lesson.mp4"');

        $this->actingAs($candidate)
            ->get(route('courses.pdf', $course))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'inline; filename="lesson.pdf"');
    }

    public function test_admin_can_preview_protected_assets_from_course_management(): void
    {
        Storage::fake('local');
        $admin = User::factory()->admin()->create();
        $course = $this->makeProtectedCourse();

        $this->actingAs($admin)
            ->get(route('courses.media', $course))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('courses.pdf', $course))
            ->assertOk();
    }

    public function test_candidate_course_page_uses_protected_asset_routes_not_public_storage_urls(): void
    {
        Storage::fake('local');
        $candidate = User::factory()->create();
        $course = $this->makeProtectedCourse();

        $this->actingAs($candidate)
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee(route('courses.media', $course), false)
            ->assertSee(route('courses.pdf', $course), false)
            ->assertSee('data-protected-course-viewer', false)
            ->assertSee('controlsList="nodownload noplaybackrate"', false)
            ->assertSee(__('ui.classroom.protection_notice_title'))
            ->assertDontSee('/storage/courses/media', false)
            ->assertDontSee('/storage/courses/pdf', false);
    }

    private function makeProtectedCourse(): Course
    {
        Storage::disk('local')->put('courses/protected/media/lesson.mp4', 'video-data');
        Storage::disk('local')->put('courses/protected/pdf/lesson.pdf', 'pdf-data');

        return Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Protected lesson',
            'description' => 'Protected description',
            'content' => 'Protected content',
            'media_path' => 'courses/protected/media/lesson.mp4',
            'media_mime' => 'video/mp4',
            'pdf_path' => 'courses/protected/pdf/lesson.pdf',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
