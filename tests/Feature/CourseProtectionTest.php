<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseResource;
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
            'audio' => UploadedFile::fake()->create('lesson.mp3', 1200, 'audio/mpeg'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $course = Course::query()->where('title', 'Protected lesson')->firstOrFail();

        Storage::disk('local')->assertExists($course->audio_path);
        Storage::disk('public')->assertMissing($course->audio_path);
        $this->assertSame('local', $course->audioDisk());
    }

    public function test_updating_legacy_public_audio_moves_replacement_to_local_and_deletes_old_public_file(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        Storage::disk('public')->put('courses/audio/legacy.mp3', 'legacy-audio');

        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Legacy lesson',
            'description' => 'Legacy',
            'content' => 'Legacy',
            'audio_path' => 'courses/audio/legacy.mp3',
            'audio_mime' => 'audio/mpeg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.courses.update', $course), [
            'title' => 'Legacy lesson updated',
            'category' => Course::CATEGORIES[0],
            'description' => 'Updated',
            'content' => 'Updated',
            'audio' => UploadedFile::fake()->create('replacement.mp3', 800, 'audio/mpeg'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $course->refresh();

        Storage::disk('public')->assertMissing('courses/audio/legacy.mp3');
        Storage::disk('local')->assertExists($course->audio_path);
        $this->assertSame('local', $course->audioDisk());
    }

    public function test_legacy_public_audio_path_still_resolves_to_public_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('public')->put('courses/audio/legacy.mp3', 'legacy-audio');

        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Legacy lesson',
            'description' => 'Legacy',
            'content' => 'Legacy',
            'audio_path' => 'courses/audio/legacy.mp3',
            'audio_mime' => 'audio/mpeg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertSame('public', $course->audioDisk());
    }

    public function test_guest_cannot_access_protected_course_assets(): void
    {
        Storage::fake('local');
        $course = $this->makeProtectedCourse();

        $this->get(route('courses.audio', $course))
            ->assertRedirect(route('login'));
    }

    public function test_candidate_can_access_protected_local_assets_through_inline_routes(): void
    {
        Storage::fake('local');
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);
        $course = $this->makeProtectedCourse();

        $this->actingAs($candidate)
            ->get(route('courses.audio', $course))
            ->assertOk()
            ->assertHeader('content-type', 'audio/mpeg')
            ->assertHeader('content-disposition', 'inline; filename="lesson.mp3"');
    }

    public function test_admin_can_preview_protected_assets_from_course_management(): void
    {
        Storage::fake('local');
        $admin = User::factory()->admin()->create();
        $course = $this->makeProtectedCourse();

        $this->actingAs($admin)
            ->get(route('courses.audio', $course))
            ->assertOk();
    }

    public function test_guest_cannot_access_protected_child_resource_files(): void
    {
        Storage::fake('local');
        $course = $this->makeProtectedResourceCourse();
        $audio = $course->resources()->where('resource_type', CourseResource::TYPE_AUDIO)->firstOrFail();

        $this->get(route('courses.resources.file', [$course, $audio]))
            ->assertRedirect(route('login'));
    }

    public function test_candidate_can_access_protected_child_resource_files_through_inline_route(): void
    {
        Storage::fake('local');
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);
        $course = $this->makeProtectedResourceCourse();
        $audio = $course->resources()->where('resource_type', CourseResource::TYPE_AUDIO)->firstOrFail();

        $this->actingAs($candidate)
            ->get(route('courses.resources.file', [$course, $audio]))
            ->assertOk()
            ->assertHeader('content-type', 'audio/mpeg')
            ->assertHeader('content-disposition', 'inline; filename="chapter-audio.mp3"');
    }

    public function test_candidate_course_page_uses_protected_asset_routes_not_public_storage_urls(): void
    {
        Storage::fake('local');
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);
        $course = $this->makeProtectedCourse();

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee(route('courses.audio', $course, false), false)
            ->assertSee('Non demarre')
            ->assertSee('data-protected-course-viewer', false)
            ->assertSee('data-course-resource-viewer', false)
            ->assertSee('controlsList="nodownload noplaybackrate"', false)
            ->assertSee('Supports du cours')
                ->assertSee('Audio')
                ->assertDontSee('/storage/courses/audio', false);
    }

    public function test_candidate_course_page_uses_protected_child_resource_routes_not_public_storage_urls(): void
    {
        Storage::fake('local');
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);
        $course = $this->makeProtectedResourceCourse();
        $audio = $course->resources()->where('resource_type', CourseResource::TYPE_AUDIO)->firstOrFail();

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', ['course' => $course, 'resource' => $audio->id]))
            ->assertOk()
            ->assertSee('data-selected-resource-key="'.$audio->id.'"', false)
            ->assertSee(route('courses.resources.file', [$course, $audio], false), false)
            ->assertSee('Supports du cours')
            ->assertDontSee('/storage/courses/protected/resources', false);
    }

    private function makeProtectedCourse(): Course
    {
        Storage::disk('local')->put('courses/protected/audio/lesson.mp3', 'audio-data');

        return Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Protected lesson',
            'description' => 'Protected description',
            'content' => 'Protected content',
            'audio_path' => 'courses/protected/audio/lesson.mp3',
            'audio_mime' => 'audio/mpeg',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function makeProtectedResourceCourse(): Course
    {
        Storage::disk('local')->put('courses/protected/resources/audio/chapter-audio.mp3', 'audio-data');

        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Protected resource lesson',
            'description' => 'Protected description',
            'content' => 'Protected content',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $course->resources()->createMany([
            [
                'id' => (string) Str::uuid(),
                'resource_type' => CourseResource::TYPE_NOTE,
                'title' => 'Chapitre I',
                'note_body' => 'Texte de note',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => (string) Str::uuid(),
                'resource_type' => CourseResource::TYPE_AUDIO,
                'title' => 'Audio protégé',
                'file_path' => 'courses/protected/resources/audio/chapter-audio.mp3',
                'file_mime' => 'audio/mpeg',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ]);

        return $course->fresh('resources');
    }
}
