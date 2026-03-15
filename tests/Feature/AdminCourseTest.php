<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
