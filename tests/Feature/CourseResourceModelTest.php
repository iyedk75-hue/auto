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
                'resource_type' => CourseResource::TYPE_AUDIO,
                'title' => 'Audio 1',
                'file_path' => 'courses/protected/resources/audio/audio-1.mp3',
                'file_mime' => 'audio/mpeg',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ]);

        $resources = $course->resources()->get();

        $this->assertCount(2, $resources);
        $this->assertSame([
            CourseResource::TYPE_AUDIO,
            CourseResource::TYPE_NOTE,
        ], $resources->pluck('resource_type')->all());
    }

    public function test_resource_model_supports_audio_and_note_types(): void
    {
        $this->assertSame([
            'audio',
            'note',
        ], CourseResource::TYPES);
    }

    public function test_resource_type_helpers_match_the_stored_type(): void
    {
        $course = $this->makeCourse();

        $audio = $course->resources()->create([
            'id' => (string) Str::uuid(),
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Audio 1',
            'file_path' => 'courses/protected/resources/audio/audio-1.mp3',
            'file_mime' => 'audio/mpeg',
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

        $this->assertTrue($audio->isAudio());
        $this->assertTrue($note->isNote());
        $this->assertTrue($audio->isFileResource());
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
