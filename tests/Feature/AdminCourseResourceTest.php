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

class AdminCourseResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_resource_management_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $course = $this->makeCourse();
        $resource = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'note_body' => 'Texte de note',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.courses.resources.index', $course))
            ->assertOk()
            ->assertSee('Chapitre I')
            ->assertSee(__('ui.admin_course_resources.add_resource'));

        $this->actingAs($admin)
            ->get(route('admin.courses.resources.create', $course))
            ->assertOk()
            ->assertSee(__('ui.admin_course_resources.create_title'))
            ->assertSee(__('ui.admin_course_resources.type'));

        $this->actingAs($admin)
            ->get(route('admin.courses.resources.edit', [$course, $resource]))
            ->assertOk()
            ->assertSee(__('ui.admin_course_resources.edit_title'))
            ->assertSee('Chapitre I');
    }

    public function test_admin_course_index_surfaces_resource_counts_and_legacy_state(): void
    {
        $admin = User::factory()->admin()->create();
        $legacyCourse = $this->makeCourse(title: 'Cours legacy');
        $legacyCourse->update([
            'audio_path' => 'courses/protected/audio/legacy-audio.mp3',
            'audio_mime' => 'audio/mpeg',
        ]);

        $resourceCourse = $this->makeCourse(title: 'Cours ressources');
        $resourceCourse->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'note_body' => 'Texte',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('admin.courses.index'))
            ->assertOk()
            ->assertSee('Cours legacy')
            ->assertSee(__('ui.admin_courses.resource_state_legacy'))
            ->assertSee(trans_choice('ui.admin_courses.resource_items', 0, ['count' => 0]))
            ->assertSee('Cours ressources')
            ->assertSee(__('ui.admin_courses.resource_state_child'))
            ->assertSee(trans_choice('ui.admin_courses.resource_items', 1, ['count' => 1]))
            ->assertSee(__('ui.admin_courses.manage_resources'));
    }

    public function test_admin_can_create_note_resource_for_a_course(): void
    {
        $admin = User::factory()->admin()->create();
        $course = $this->makeCourse();

        $response = $this->actingAs($admin)->post(route('admin.courses.resources.store', $course), [
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'title_ar' => 'الفصل الأول',
            'note_body' => 'Texte de note',
            'note_body_ar' => 'نص الملاحظة',
            'sort_order' => 2,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.resources.index', $course));
        $response->assertSessionHas('status', 'Resource added.');

        $this->assertDatabaseHas('course_resources', [
            'course_id' => $course->id,
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'title_ar' => 'الفصل الأول',
            'note_body' => 'Texte de note',
            'note_body_ar' => 'نص الملاحظة',
            'sort_order' => 2,
        ]);
    }

    public function test_admin_can_create_audio_resource_with_protected_file_storage(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $course = $this->makeCourse();

        $response = $this->actingAs($admin)->post(route('admin.courses.resources.store', $course), [
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Support audio',
            'sort_order' => 1,
            'resource_file' => UploadedFile::fake()->create('support.mp3', 200, 'audio/mpeg'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.resources.index', $course));

        $resource = CourseResource::query()->where('course_id', $course->id)->firstOrFail();

        Storage::disk('local')->assertExists($resource->file_path);
        $this->assertSame(CourseResource::TYPE_AUDIO, $resource->resource_type);
        $this->assertSame('audio/mpeg', $resource->file_mime);
    }

    public function test_admin_can_update_resource_order_and_replace_file_when_type_stays_file(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $course = $this->makeCourse();

        Storage::disk('local')->put('courses/protected/resources/audio/legacy-audio.mp3', 'legacy-audio');

        $resource = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Audio 1',
            'file_path' => 'courses/protected/resources/audio/legacy-audio.mp3',
            'file_mime' => 'audio/mpeg',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.courses.resources.update', [$course, $resource]), [
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Audio 1 modifié',
            'sort_order' => 1,
            'resource_file' => UploadedFile::fake()->create('new-audio.mp3', 1000, 'audio/mpeg'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.resources.index', $course));
        Storage::disk('local')->assertMissing('courses/protected/resources/audio/legacy-audio.mp3');

        $resource->refresh();

        $this->assertSame('Audio 1 modifié', $resource->title);
        $this->assertSame(1, $resource->sort_order);
        Storage::disk('local')->assertExists($resource->file_path);
    }

    public function test_admin_can_delete_file_resource_and_its_protected_asset(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $course = $this->makeCourse();

        Storage::disk('local')->put('courses/protected/resources/audio/to-delete.mp3', 'audio');

        $resource = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Audio 1',
            'file_path' => 'courses/protected/resources/audio/to-delete.mp3',
            'file_mime' => 'audio/mpeg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.courses.resources.destroy', [$course, $resource]));

        $response->assertRedirect(route('admin.courses.resources.index', $course));
        $this->assertDatabaseMissing('course_resources', ['id' => $resource->id]);
        Storage::disk('local')->assertMissing('courses/protected/resources/audio/to-delete.mp3');
    }

    private function makeCourse(string $title = 'Cours ressources'): Course
    {
        return Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => $title,
            'description' => 'Description',
            'content' => 'Contenu',
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }
}
