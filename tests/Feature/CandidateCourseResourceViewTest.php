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
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);
        $course = $this->makeCourse();

        $note = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'note_body' => 'Texte de note',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $audio = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Audio priorité',
            'file_path' => 'courses/protected/resources/audio/priorite.mp3',
            'file_mime' => 'audio/mpeg',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('data-course-resource-viewer', false)
            ->assertSee('data-selected-resource-key="'.$note->id.'"', false)
                ->assertSeeInOrder(['Chapitre I', 'Audio priorité'], false)
            ->assertSee('Supports du cours')
            ->assertSee('Note')
                ->assertSee('Audio')
            ->assertSee('Texte de note')
                ->assertSee('?resource='.$audio->id.'#course-resource-viewer', false);
    }

    public function test_candidate_can_select_a_specific_resource_from_query_string(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);
        $course = $this->makeCourse();

        $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_NOTE,
            'title' => 'Chapitre I',
            'note_body' => 'Texte de note',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $audio = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Audio priorité',
            'file_path' => 'courses/protected/resources/audio/priorite.mp3',
            'file_mime' => 'audio/mpeg',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
                ->get(route('courses.show', ['course' => $course, 'resource' => $audio->id]))
            ->assertOk()
                ->assertSee('data-selected-resource-key="'.$audio->id.'"', false)
                ->assertSee('Audio priorité')
                ->assertSee('Non demarre')
                ->assertSee(route('courses.resources.file', [$course, $audio], false), false);
    }

    public function test_invalid_selection_falls_back_to_first_legacy_audio_resource(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);
        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Cours legacy',
            'description' => 'Description',
            'content' => 'Contenu',
            'audio_path' => 'courses/protected/audio/legacy-audio.mp3',
            'audio_mime' => 'audio/mpeg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', ['course' => $course, 'resource' => 'missing-key']))
            ->assertOk()
                ->assertSee('data-selected-resource-key="legacy-audio"', false)
                ->assertSee(route('courses.audio', $course, false), false);
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
