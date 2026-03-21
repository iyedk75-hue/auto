<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_bilingual_course_create_form(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->withSession(['locale' => 'ar'])
            ->get(route('admin.courses.create'))
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('العنوان (فرنسي)')
            ->assertSee('العنوان (عربي)')
            ->assertSee('محتوى الدرس (عربي)');
    }

    public function test_admin_can_create_course_with_bilingual_text_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Priorité',
            'title_ar' => 'الأولوية',
            'category' => Course::CATEGORIES[0],
            'description' => 'Description française',
            'description_ar' => 'وصف عربي',
            'content' => 'Contenu français',
            'content_ar' => 'محتوى عربي',
            'duration_minutes' => 40,
            'sort_order' => 2,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('status', __('ui.admin_courses.created'));

        $this->assertDatabaseHas('courses', [
            'title' => 'Priorité',
            'title_ar' => 'الأولوية',
            'description' => 'Description française',
            'description_ar' => 'وصف عربي',
            'content' => 'Contenu français',
            'content_ar' => 'محتوى عربي',
        ]);
    }

    public function test_admin_can_create_course_with_audio_upload(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Cours avec audio',
            'category' => Course::CATEGORIES[0],
            'audio' => UploadedFile::fake()->create('lesson.mp3', 1500, 'audio/mpeg'),
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $course = Course::query()->where('title', 'Cours avec audio')->firstOrFail();

        $this->assertNotNull($course->audio_path);
        $this->assertSame('audio/mpeg', $course->audio_mime);
        Storage::disk('local')->assertExists($course->audio_path);
    }

    public function test_admin_can_update_bilingual_course_text_fields(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Avant',
            'title_ar' => 'قبل',
            'description' => 'Ancienne description',
            'description_ar' => 'وصف قديم',
            'content' => 'Ancien contenu',
            'content_ar' => 'محتوى قديم',
            'duration_minutes' => 30,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.courses.update', $course), [
            'title' => 'Après',
            'title_ar' => 'بعد',
            'category' => Course::CATEGORIES[1],
            'description' => 'Nouvelle description',
            'description_ar' => 'وصف جديد',
            'content' => 'Nouveau contenu',
            'content_ar' => 'محتوى جديد',
            'duration_minutes' => 55,
            'sort_order' => 4,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('status', __('ui.admin_courses.updated'));

        $course->refresh();

        $this->assertSame('Après', $course->title);
        $this->assertSame('بعد', $course->title_ar);
        $this->assertSame('Nouvelle description', $course->description);
        $this->assertSame('وصف جديد', $course->description_ar);
        $this->assertSame('Nouveau contenu', $course->content);
        $this->assertSame('محتوى جديد', $course->content_ar);
        $this->assertSame(Course::CATEGORIES[1], $course->category);
    }

    public function test_edit_form_displays_current_audio_player_when_course_has_audio(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $audioPath = Course::PROTECTED_AUDIO_DIRECTORY.'/sample-audio.mp3';
        Storage::disk('local')->put($audioPath, 'fake-audio-content');

        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours audio',
            'audio_path' => $audioPath,
            'audio_mime' => 'audio/mpeg',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.courses.edit', $course))
            ->assertOk()
            ->assertSee(__('ui.admin_courses.audio_preview'))
            ->assertSee('audio', false)
            ->assertSee(route('courses.audio', $course));
    }

    public function test_admin_course_base_url_redirects_to_edit_page(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours redirection',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/courses/'.$course->id)
            ->assertRedirect(route('admin.courses.edit', $course));
    }

    public function test_edit_form_displays_missing_audio_notice_when_course_has_no_audio(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours sans audio',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.courses.edit', $course))
            ->assertOk()
            ->assertSee(__('ui.admin_courses.audio_missing_title'))
            ->assertSee(__('ui.admin_courses.audio_max_allowed', ['size' => Course::audioMaxSizeLabel()]))
            ->assertSee(__('ui.admin_courses.audio_server_limit', ['size' => ini_get('upload_max_filesize') ?: '2M']));
    }

    public function test_store_returns_audio_error_when_request_exceeds_php_post_limit(): void
    {
        $admin = User::factory()->admin()->create();
        $postMaxBytes = $this->normalizeIniSizeToBytes((string) ini_get('post_max_size')) + 1;

        $response = $this->actingAs($admin)->from(route('admin.courses.create'))->call(
            'POST',
            route('admin.courses.store'),
            [],
            [],
            [],
            [
                'CONTENT_LENGTH' => (string) $postMaxBytes,
            ],
        );

        $response
            ->assertRedirect(route('admin.courses.create'))
            ->assertSessionHasErrors([
                'audio' => __('ui.admin_courses.audio_upload_too_large', ['size' => ini_get('upload_max_filesize') ?: '2M']),
            ]);
    }

    public function test_update_returns_audio_error_when_php_rejects_uploaded_audio(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours audio limite',
            'is_active' => true,
        ]);

        $_FILES['audio'] = [
            'name' => 'too-large.mp3',
            'type' => 'audio/mpeg',
            'tmp_name' => '',
            'error' => UPLOAD_ERR_INI_SIZE,
            'size' => 0,
        ];

        try {
            $response = $this->actingAs($admin)->from(route('admin.courses.edit', $course))->put(route('admin.courses.update', $course), [
                'title' => $course->title,
                'category' => $course->category,
            ]);
        } finally {
            unset($_FILES['audio']);
        }

        $response
            ->assertRedirect(route('admin.courses.edit', $course))
            ->assertSessionHasErrors([
                'audio' => __('ui.admin_courses.audio_upload_too_large', ['size' => ini_get('upload_max_filesize') ?: '2M']),
            ]);
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
