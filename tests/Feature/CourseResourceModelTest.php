<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourseResourceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_can_own_many_ordered_resources(): void
    {
        $course = $this->makeCourse();

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
            [
                'id' => (string) Str::uuid(),
                'resource_type' => CourseResource::TYPE_PDF,
                'title' => 'Fiche PDF',
                'file_path' => 'courses/protected/pdf/chapter-1.pdf',
                'file_mime' => 'application/pdf',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ]);

        $resources = $course->resources()->get();

        $this->assertCount(3, $resources);
        $this->assertSame([
            CourseResource::TYPE_VIDEO,
            CourseResource::TYPE_NOTE,
            CourseResource::TYPE_PDF,
        ], $resources->pluck('resource_type')->all());
    }

    public function test_resource_model_supports_video_pdf_and_note_types(): void
    {
        $this->assertSame([
            'video',
            'pdf',
            'note',
        ], CourseResource::TYPES);
    }

    public function test_resource_type_helpers_match_the_stored_type(): void
    {
        $course = $this->makeCourse();

        $video = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_VIDEO,
            'title' => 'Vidéo 1',
            'file_path' => 'courses/protected/media/video-1.mp4',
            'file_mime' => 'video/mp4',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $note = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Note 1',
            'note_body' => 'Texte',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $pdf = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_PDF,
            'title' => 'PDF 1',
            'file_path' => 'courses/protected/pdf/pdf-1.pdf',
            'file_mime' => 'application/pdf',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $this->assertTrue($video->isVideo());
        $this->assertTrue($note->isNote());
        $this->assertTrue($pdf->isPdf());
    }

    private function makeCourse(): Course
    {
        return Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours multi-supports',
            'description' => 'Description',
            'content' => 'Contenu',
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }
}
