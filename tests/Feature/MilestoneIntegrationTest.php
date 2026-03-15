<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MilestoneIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_bilingual_protected_course_flow_works_end_to_end(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $candidate = User::factory()->create();

        $this->from(route('home'))
            ->get(route('locale.switch', ['locale' => 'ar']))
            ->assertRedirect(route('home'));

        $this->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('home'))
            ->assertOk()
            ->assertSee('المقصورة الرقمية لمدارس السياقة التونسية.')
            ->assertDontSee('احمِ محتواك التعليمي.');

        $createResponse = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Priorité',
            'title_ar' => 'الأولوية',
            'category' => Course::CATEGORIES[0],
            'description' => 'Description française',
            'description_ar' => 'وصف عربي',
            'content' => 'Contenu français',
            'content_ar' => 'محتوى عربي',
            'media' => UploadedFile::fake()->create('lesson.mp4', 1200, 'video/mp4'),
            'pdf' => UploadedFile::fake()->create('lesson.pdf', 400, 'application/pdf'),
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect(route('admin.courses.index'));

        $course = Course::query()->where('title', 'Priorité')->firstOrFail();

        Storage::disk('local')->assertExists($course->media_path);
        Storage::disk('local')->assertExists($course->pdf_path);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('الأولوية')
            ->assertSee('وصف عربي')
            ->assertSee('محتوى عربي')
            ->assertSee('دعامات الدرس')
            ->assertSee(route('courses.media', $course), false)
            ->assertSee('?resource=legacy-pdf#course-resource-viewer', false)
            ->assertSee('data-protected-course-viewer', false)
            ->assertSee('data-course-resource-viewer', false)
            ->assertSee('data-selected-resource-key="legacy-media"', false)
            ->assertDontSee('/storage/courses/media', false);

        $this->actingAs($candidate)
            ->get(route('courses.media', $course))
            ->assertOk()
            ->assertHeader('content-type', 'video/mp4');

        $this->actingAs($candidate)
            ->get(route('courses.pdf', $course))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($candidate)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->get(route('courses.media', $course))
            ->assertRedirect(route('login'));

        $this->get(route('courses.pdf', $course))
            ->assertRedirect(route('login'));
    }

    public function test_admin_authored_multi_resource_course_flow_works_end_to_end(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $candidate = User::factory()->create();

        $createCourseResponse = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Cours multi-supports',
            'title_ar' => 'درس متعدد الدعامات',
            'category' => Course::CATEGORIES[0],
            'description' => 'Description française',
            'description_ar' => 'وصف عربي',
            'content' => 'Contenu français',
            'content_ar' => 'محتوى عربي',
            'is_active' => '1',
        ]);

        $createCourseResponse->assertRedirect(route('admin.courses.index'));

        $course = Course::query()->where('title', 'Cours multi-supports')->firstOrFail();

        $this->actingAs($admin)->post(route('admin.courses.resources.store', $course), [
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'title_ar' => 'الفصل الأول',
            'note_body' => 'Texte de note',
            'note_body_ar' => 'نص الملاحظة',
            'sort_order' => 1,
            'is_active' => '1',
        ])->assertRedirect(route('admin.courses.resources.index', $course));

        $this->actingAs($admin)->post(route('admin.courses.resources.store', $course), [
            'resource_type' => CourseResource::TYPE_VIDEO,
            'title' => 'Vidéo priorité',
            'title_ar' => 'فيديو الأولوية',
            'resource_file' => UploadedFile::fake()->create('chapter-video.mp4', 1200, 'video/mp4'),
            'sort_order' => 2,
            'is_active' => '1',
        ])->assertRedirect(route('admin.courses.resources.index', $course));

        $this->actingAs($admin)->post(route('admin.courses.resources.store', $course), [
            'resource_type' => CourseResource::TYPE_PDF,
            'title' => 'Fiche PDF',
            'title_ar' => 'ملف PDF',
            'resource_file' => UploadedFile::fake()->create('chapter-guide.pdf', 300, 'application/pdf'),
            'sort_order' => 3,
            'is_active' => '1',
        ])->assertRedirect(route('admin.courses.resources.index', $course));

        $course->refresh();
        $note = $course->resources()->where('resource_type', CourseResource::TYPE_NOTE)->firstOrFail();
        $video = $course->resources()->where('resource_type', CourseResource::TYPE_VIDEO)->firstOrFail();
        $pdf = $course->resources()->where('resource_type', CourseResource::TYPE_PDF)->firstOrFail();

        Storage::disk('local')->assertExists($video->file_path);
        Storage::disk('local')->assertExists($pdf->file_path);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('Supports du cours')
            ->assertSee('data-selected-resource-key="'.$note->id.'"', false)
            ->assertSeeInOrder(['Chapitre I', 'Vidéo priorité', 'Fiche PDF'], false)
            ->assertSee('Texte de note')
            ->assertSee('?resource='.$video->id.'#course-resource-viewer', false)
            ->assertSee('?resource='.$pdf->id.'#course-resource-viewer', false);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', ['course' => $course, 'resource' => $pdf->id]))
            ->assertOk()
            ->assertSee('data-selected-resource-key="'.$pdf->id.'"', false)
            ->assertSee(route('courses.resources.file', [$course, $pdf]), false)
            ->assertDontSee('/storage/courses/protected/resources', false);

        $this->actingAs($candidate)
            ->get(route('courses.resources.file', [$course, $video]))
            ->assertOk()
            ->assertHeader('content-type', 'video/mp4');

        $this->actingAs($candidate)
            ->get(route('courses.resources.file', [$course, $pdf]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($candidate)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->get(route('courses.resources.file', [$course, $video]))
            ->assertRedirect(route('login'));
    }

    public function test_missing_arabic_course_state_is_visible_end_to_end(): void
    {
        $candidate = User::factory()->create();

        $course = Course::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Priorité sans arabe',
            'description' => 'Description française',
            'content' => 'Contenu français',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('Priorité sans arabe')
            ->assertSee('العربية غير متاحة بعد')
            ->assertDontSee('Description française')
            ->assertDontSee('Contenu français');
    }
}
