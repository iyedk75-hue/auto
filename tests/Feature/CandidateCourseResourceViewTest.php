<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CandidateCourseResourceViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_course_page_uses_ordered_resource_stream_and_defaults_to_first_resource(): void
    {
        $candidate = User::factory()->create();
        $course = $this->makeCourse();

        $note = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'note_body' => 'Texte de note',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $video = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_VIDEO,
            'title' => 'Vidéo priorité',
            'file_path' => 'courses/protected/resources/video/priorite.mp4',
            'file_mime' => 'video/mp4',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $pdf = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_PDF,
            'title' => 'Fiche PDF',
            'file_path' => 'courses/protected/resources/pdf/priorite.pdf',
            'file_mime' => 'application/pdf',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('data-course-resource-viewer', false)
            ->assertSee('data-selected-resource-key="'.$note->id.'"', false)
            ->assertSeeInOrder(['Chapitre I', 'Vidéo priorité', 'Fiche PDF'], false)
            ->assertSee('Supports du cours')
            ->assertSee('Note')
            ->assertSee('Vidéo')
            ->assertSee('PDF')
            ->assertSee('Texte de note')
            ->assertSee('?resource='.$video->id.'#course-resource-viewer', false)
            ->assertSee('?resource='.$pdf->id.'#course-resource-viewer', false);
    }

    public function test_candidate_can_select_a_specific_resource_from_query_string(): void
    {
        $candidate = User::factory()->create();
        $course = $this->makeCourse();

        $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'note_body' => 'Texte de note',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $pdf = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_PDF,
            'title' => 'Fiche PDF',
            'file_path' => 'courses/protected/resources/pdf/priorite.pdf',
            'file_mime' => 'application/pdf',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', ['course' => $course, 'resource' => $pdf->id]))
            ->assertOk()
            ->assertSee('data-selected-resource-key="'.$pdf->id.'"', false)
            ->assertSee('Fiche PDF')
            ->assertSee(route('courses.resources.file', [$course, $pdf]), false);
    }

    public function test_invalid_selection_falls_back_to_first_legacy_resource(): void
    {
        $candidate = User::factory()->create();
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours legacy',
            'description' => 'Description',
            'content' => 'Contenu',
            'media_path' => 'courses/protected/media/legacy-video.mp4',
            'media_mime' => 'video/mp4',
            'pdf_path' => 'courses/protected/pdf/legacy-guide.pdf',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', ['course' => $course, 'resource' => 'missing-key']))
            ->assertOk()
            ->assertSee('data-selected-resource-key="legacy-media"', false)
            ->assertSee(route('courses.media', $course), false)
            ->assertSee('?resource=legacy-pdf#course-resource-viewer', false);
    }

    private function makeCourse(): Course
    {
        return Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours ressources',
            'description' => 'Description',
            'content' => 'Contenu',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
