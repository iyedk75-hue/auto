<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourseLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_model_returns_french_content_by_default(): void
    {
        $course = $this->makeCourse([
            'title' => 'Priorité',
            'description' => 'Description française',
            'content' => 'Contenu français',
            'title_ar' => 'الأولوية',
            'description_ar' => 'وصف عربي',
            'content_ar' => 'محتوى عربي',
        ]);

        $this->assertSame('Priorité', $course->titleForLocale('fr'));
        $this->assertSame('Description française', $course->descriptionForLocale('fr'));
        $this->assertSame('Contenu français', $course->contentForLocale('fr'));
    }

    public function test_course_model_returns_arabic_content_when_available(): void
    {
        $course = $this->makeCourse([
            'title' => 'Priorité',
            'description' => 'Description française',
            'content' => 'Contenu français',
            'title_ar' => 'الأولوية',
            'description_ar' => 'وصف عربي',
            'content_ar' => 'محتوى عربي',
        ]);

        $this->assertSame('الأولوية', $course->titleForLocale('ar'));
        $this->assertSame('وصف عربي', $course->descriptionForLocale('ar'));
        $this->assertSame('محتوى عربي', $course->contentForLocale('ar'));
        $this->assertTrue($course->hasArabicTranslation());
    }

    public function test_course_model_reports_missing_arabic_translation_when_no_arabic_fields_exist(): void
    {
        $course = $this->makeCourse([
            'title' => 'Priorité',
            'description' => 'Description française',
            'content' => 'Contenu français',
            'title_ar' => null,
            'description_ar' => null,
            'content_ar' => null,
        ]);

        $this->assertFalse($course->hasArabicTranslation());
        $this->assertNull($course->titleForLocale('ar'));
        $this->assertNull($course->descriptionForLocale('ar'));
        $this->assertNull($course->contentForLocale('ar'));
    }

    public function test_candidate_course_page_renders_arabic_content_when_available(): void
    {
        $candidate = User::factory()->create();
        $course = $this->makeCourse([
            'title' => 'Priorité',
            'description' => 'Description française',
            'content' => 'Contenu français',
            'title_ar' => 'الأولوية',
            'description_ar' => 'وصف عربي',
            'content_ar' => 'محتوى عربي',
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('الأولوية')
            ->assertSee('وصف عربي')
            ->assertSee('محتوى عربي')
            ->assertDontSee('العربية غير متاحة بعد');
    }

    public function test_candidate_course_page_shows_unavailable_state_when_arabic_content_is_missing(): void
    {
        $candidate = User::factory()->create();
        $course = $this->makeCourse([
            'title' => 'Priorité',
            'description' => 'Description française',
            'content' => 'Contenu français',
            'title_ar' => null,
            'description_ar' => null,
            'content_ar' => null,
        ]);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('Priorité')
            ->assertSee('العربية غير متاحة بعد')
            ->assertDontSee('Description française')
            ->assertDontSee('Contenu français');
    }

    private function makeCourse(array $overrides = []): Course
    {
        return Course::create(array_merge([
            'id' => (string) Str::uuid(),
            'category' => Course::CATEGORIES[0],
            'title' => 'Titre',
            'description' => 'Description',
            'content' => 'Contenu',
            'title_ar' => null,
            'description_ar' => null,
            'content_ar' => null,
            'duration_minutes' => 30,
            'sort_order' => 0,
            'is_active' => true,
        ], $overrides));
    }
}
