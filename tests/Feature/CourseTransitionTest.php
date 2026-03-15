<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourseTransitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_course_resolves_media_and_pdf_into_a_usable_resource_list(): void
    {
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Priority rules',
            'title_ar' => 'قواعد الأولوية',
            'description' => 'Description',
            'content' => 'Contenu',
            'media_path' => 'courses/protected/media/legacy-video.mp4',
            'media_mime' => 'video/mp4',
            'pdf_path' => 'courses/protected/pdf/legacy-guide.pdf',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $resolved = $course->resolvedResources();

        $this->assertCount(2, $resolved);
        $this->assertSame(['legacy-media', 'legacy-pdf'], array_column($resolved->all(), 'key'));
        $this->assertSame(['legacy', 'legacy'], array_column($resolved->all(), 'origin'));
        $this->assertSame([CourseResource::TYPE_VIDEO, CourseResource::TYPE_PDF], array_column($resolved->all(), 'type'));
    }

    public function test_persisted_child_resources_take_precedence_over_legacy_fields(): void
    {
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Priority rules',
            'description' => 'Description',
            'content' => 'Contenu',
            'media_path' => 'courses/protected/media/legacy-video.mp4',
            'media_mime' => 'video/mp4',
            'pdf_path' => 'courses/protected/pdf/legacy-guide.pdf',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $course->resources()->createMany([
            [
                'id' => (string) Str::uuid(),
                'resource_type' => CourseResource::TYPE_NOTE,
                'title' => 'Chapitre I',
                'note_body' => 'Introduction',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'id' => (string) Str::uuid(),
                'resource_type' => CourseResource::TYPE_VIDEO,
                'title' => 'Vidéo 1',
                'file_path' => 'courses/protected/media/video-1.mp4',
                'file_mime' => 'video/mp4',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ]);

        $resolved = $course->resolvedResources();

        $this->assertCount(2, $resolved);
        $this->assertSame(['resource', 'resource'], array_column($resolved->all(), 'origin'));
        $this->assertSame(['Vidéo 1', 'Chapitre I'], array_column($resolved->all(), 'title'));
        $this->assertSame([1, 2], array_column($resolved->all(), 'sort_order'));
    }

    public function test_resolved_resource_shape_exposes_note_and_file_metadata_for_downstream_slices(): void
    {
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Priority rules',
            'title_ar' => 'قواعد الأولوية',
            'description' => 'Description',
            'content' => 'Contenu',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $course->resources()->createMany([
            [
                'id' => (string) Str::uuid(),
                'resource_type' => CourseResource::TYPE_NOTE,
                'title' => 'Résumé',
                'title_ar' => 'ملخص',
                'note_body' => 'Texte de note',
                'note_body_ar' => 'نص الملاحظة',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => (string) Str::uuid(),
                'resource_type' => CourseResource::TYPE_PDF,
                'title' => 'Fiche PDF',
                'title_ar' => 'ملف PDF',
                'file_path' => 'courses/protected/pdf/guide.pdf',
                'file_mime' => 'application/pdf',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ]);

        $resolved = $course->resolvedResources()->all();

        $this->assertTrue($resolved[0]['is_note']);
        $this->assertFalse($resolved[0]['is_file']);
        $this->assertSame('Résumé', $resolved[0]['title']);
        $this->assertSame('ملخص', $resolved[0]['title_ar']);
        $this->assertSame('Résumé', $resolved[0]['display_title']);
        $this->assertSame('Texte de note', $resolved[0]['note_body']);
        $this->assertSame('نص الملاحظة', $resolved[0]['note_body_ar']);
        $this->assertSame('Texte de note', $resolved[0]['display_note_body']);

        $arabicResolved = $course->resolvedResources('ar')->all();
        $this->assertSame('ملخص', $arabicResolved[0]['display_title']);
        $this->assertSame('نص الملاحظة', $arabicResolved[0]['display_note_body']);
        $this->assertTrue($arabicResolved[0]['has_arabic_translation']);

        $this->assertTrue($resolved[1]['is_file']);
        $this->assertFalse($resolved[1]['is_note']);
        $this->assertSame('application/pdf', $resolved[1]['file_mime']);
        $this->assertSame(CourseResource::TYPE_PDF, $resolved[1]['type']);
    }
}
