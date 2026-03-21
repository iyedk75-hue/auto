<?php

namespace Tests\Feature;

use App\Models\AutoSchool;
use App\Models\Course;
use App\Models\CourseResource;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class MilestoneIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_bilingual_protected_course_flow_works_end_to_end(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

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
            'audio' => UploadedFile::fake()->create('lesson.mp3', 1200, 'audio/mpeg'),
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect(route('admin.courses.index'));

        $course = Course::query()->where('title', 'Priorité')->firstOrFail();

        Storage::disk('local')->assertExists($course->audio_path);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('الأولوية')
            ->assertSee('وصف عربي')
            ->assertSee('محتوى عربي')
            ->assertSee('دعامات الدرس')
            ->assertSee(route('courses.audio', $course, false), false)
            ->assertSee('غير مبدوء')
            ->assertSee('data-protected-course-viewer', false)
            ->assertSee('data-course-resource-viewer', false)
            ->assertSee('data-selected-resource-key="legacy-audio"', false)
            ->assertDontSee('/storage/courses/audio', false);

        $this->actingAs($candidate)
            ->get(route('courses.audio', $course))
            ->assertOk()
            ->assertHeader('content-type', 'audio/mpeg');

        $this->actingAs($candidate)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->get(route('courses.audio', $course))
            ->assertRedirect(route('login'));
    }

    public function test_admin_authored_multi_resource_course_flow_works_end_to_end(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

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
            'resource_type' => CourseResource::TYPE_AUDIO,
            'title' => 'Audio priorité',
            'title_ar' => 'صوت الأولوية',
            'resource_file' => UploadedFile::fake()->create('chapter-audio.mp3', 1200, 'audio/mpeg'),
            'sort_order' => 2,
            'is_active' => '1',
        ])->assertRedirect(route('admin.courses.resources.index', $course));

        $course->refresh();
        $note = $course->resources()->where('resource_type', CourseResource::TYPE_NOTE)->firstOrFail();
        $audio = $course->resources()->where('resource_type', CourseResource::TYPE_AUDIO)->firstOrFail();

        Storage::disk('local')->assertExists($audio->file_path);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('دعامات الدرس')
            ->assertSee('data-selected-resource-key="'.$note->id.'"', false)
            ->assertSeeInOrder(['الفصل الأول', 'صوت الأولوية'], false)
            ->assertSee('نص الملاحظة')
            ->assertSee('?resource='.$audio->id.'#course-resource-viewer', false);

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('courses.show', ['course' => $course, 'resource' => $audio->id]))
            ->assertOk()
            ->assertSee('data-selected-resource-key="'.$audio->id.'"', false)
            ->assertSee(route('courses.resources.file', [$course, $audio], false), false)
            ->assertDontSee('/storage/courses/protected/resources', false);

        $this->actingAs($candidate)
            ->get(route('courses.resources.file', [$course, $audio]))
            ->assertOk()
            ->assertHeader('content-type', 'audio/mpeg');

        $this->actingAs($candidate)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->get(route('courses.resources.file', [$course, $audio]))
            ->assertRedirect(route('login'));
    }

    public function test_candidate_quiz_displays_question_image_when_present(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

        $question = Question::create([
            'id' => (string) Str::uuid(),
            'category' => Question::CATEGORIES[0],
            'image_url' => 'https://example.test/quiz-image.png',
            'question_text' => 'Quelle voiture passe en premier ?',
            'correct_answer' => 'أ',
            'difficulty' => Question::DIFFICULTIES[0],
            'is_active' => true,
        ]);

        $question->options()->createMany([
            ['option_id' => 'أ', 'text' => 'Voiture A'],
            ['option_id' => 'ب', 'text' => 'Voiture B'],
        ]);

        QuizSession::create([
            'user_id' => $candidate->id,
            'difficulty' => 'mixed',
            'question_category' => Question::CATEGORIES[0],
            'score' => 0,
            'total_questions' => 1,
            'started_at' => now(),
        ]);

        $this->actingAs($candidate)
            ->get(route('quiz.show'))
            ->assertOk()
            ->assertSee('السؤال 1 / 1')
            ->assertSee('https://example.test/quiz-image.png', false)
            ->assertSee('توضيح للحالة');
    }

    public function test_candidate_quiz_displays_uploaded_question_image_from_local_storage(): void
    {
        Storage::fake('public');

        $school = AutoSchool::create([
            'name' => 'Massar Test School',
            'city' => 'Tunis',
            'address' => 'Centre ville',
            'whatsapp_phone' => '+21611111111',
            'is_active' => true,
        ]);

        $schoolAdmin = User::factory()->admin()->create([
            'auto_school_id' => $school->id,
        ]);
        $candidate = User::factory()->create([
            'auto_school_id' => $school->id,
            'status' => 'active',
        ]);

        $this->actingAs($schoolAdmin)
            ->post(route('admin.questions.store'), [
                'category' => Question::CATEGORIES[0],
                'question_text' => 'Question avec image locale',
                'correct_answer' => 'أ',
                'difficulty' => Question::DIFFICULTIES[0],
                'image' => UploadedFile::fake()->image('quiz-local.png'),
                'option_a' => 'Voiture A',
                'option_b' => 'Voiture B',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.questions.index'));

        $question = Question::query()->where('question_text', 'Question avec image locale')->firstOrFail();

        Storage::disk('public')->assertExists($question->getRawOriginal('image_url'));

        QuizSession::create([
            'user_id' => $candidate->id,
            'difficulty' => 'mixed',
            'question_category' => Question::CATEGORIES[0],
            'score' => 0,
            'total_questions' => 1,
            'started_at' => now(),
        ]);

        $this->actingAs($candidate)
            ->get(route('quiz.show'))
            ->assertOk()
            ->assertSee('/storage/questions/images/', false)
            ->assertDontSee('http://localhost/storage/questions/images/', false)
            ->assertSee('توضيح للحالة');
    }

    public function test_quiz_home_lists_available_chapters_for_candidate_selection(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

        $course = Course::create([
            'id' => (string) Str::uuid(),
            'category' => 'priority_rules',
            'title' => 'Cours priorite',
            'description' => 'Description',
            'content' => 'Contenu',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        QuizSession::create([
            'user_id' => $candidate->id,
            'difficulty' => 'mixed',
            'question_category' => 'priorite',
            'score' => 7,
            'total_questions' => 10,
            'started_at' => now()->subDay(),
            'completed_at' => now()->subDay(),
        ]);

        $question = Question::create([
            'id' => (string) Str::uuid(),
            'category' => 'priorite',
            'image_url' => 'https://example.test/chapter-priorite.png',
            'question_text' => 'Question priorite',
            'correct_answer' => 'أ',
            'difficulty' => Question::DIFFICULTIES[0],
            'is_active' => true,
        ]);

        $question->options()->createMany([
            ['option_id' => 'أ', 'text' => 'Rep A'],
            ['option_id' => 'ب', 'text' => 'Rep B'],
        ]);

        $this->actingAs($candidate)
            ->get(route('quiz.show'))
            ->assertOk()
            ->assertSee('اختر فصلًا قبل بدء الاختبار.')
            ->assertSee('Priorite')
            ->assertSee('https://example.test/chapter-priorite.png', false)
            ->assertSee('Question priorite')
            ->assertSee('70%')
            ->assertSee(route('courses.show', $course, false), false)
            ->assertSee('عرض الدرس')
            ->assertSee('ابدأ');
    }

    public function test_quiz_home_uses_visual_fallback_when_chapter_has_no_image(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

        $question = Question::create([
            'id' => (string) Str::uuid(),
            'category' => 'stationnement',
            'question_text' => 'Question stationnement',
            'correct_answer' => 'أ',
            'difficulty' => Question::DIFFICULTIES[0],
            'is_active' => true,
        ]);

        $question->options()->createMany([
            ['option_id' => 'أ', 'text' => 'Rep A'],
            ['option_id' => 'ب', 'text' => 'Rep B'],
        ]);

        $this->actingAs($candidate)
            ->get(route('quiz.show'))
            ->assertOk()
            ->assertSee('Stationnement')
            ->assertSee('Parking')
            ->assertDontSee('background-image:', false);
    }

    public function test_candidate_can_start_quiz_for_selected_chapter_only(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

        $prioriteQuestion = Question::create([
            'id' => (string) Str::uuid(),
            'category' => 'priorite',
            'question_text' => 'Question priorite',
            'correct_answer' => 'أ',
            'difficulty' => Question::DIFFICULTIES[0],
            'is_active' => true,
        ]);
        $prioriteQuestion->options()->createMany([
            ['option_id' => 'أ', 'text' => 'Rep A'],
            ['option_id' => 'ب', 'text' => 'Rep B'],
        ]);

        $signalisationQuestion = Question::create([
            'id' => (string) Str::uuid(),
            'category' => 'signalisation',
            'question_text' => 'Question signalisation',
            'correct_answer' => 'أ',
            'difficulty' => Question::DIFFICULTIES[0],
            'is_active' => true,
        ]);
        $signalisationQuestion->options()->createMany([
            ['option_id' => 'أ', 'text' => 'Rep A'],
            ['option_id' => 'ب', 'text' => 'Rep B'],
        ]);

        $this->actingAs($candidate)
            ->post(route('quiz.start'), [
                'chapter' => 'signalisation',
            ])
            ->assertRedirect(route('quiz.show'));

        $session = QuizSession::query()->where('user_id', $candidate->id)->firstOrFail();

        $this->assertSame('signalisation', $session->question_category);

        $this->actingAs($candidate)
            ->get(route('quiz.show'))
            ->assertOk()
            ->assertSee('Signalisation')
            ->assertSee('Question signalisation')
            ->assertDontSee('Question priorite');
    }

    public function test_quiz_history_displays_aggregated_score_by_chapter(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

        QuizSession::create([
            'user_id' => $candidate->id,
            'difficulty' => 'mixed',
            'question_category' => 'priorite',
            'score' => 6,
            'total_questions' => 10,
            'started_at' => now()->subDays(2),
            'completed_at' => now()->subDays(2),
        ]);

        QuizSession::create([
            'user_id' => $candidate->id,
            'difficulty' => 'mixed',
            'question_category' => 'priorite',
            'score' => 8,
            'total_questions' => 10,
            'started_at' => now()->subDay(),
            'completed_at' => now()->subDay(),
        ]);

        QuizSession::create([
            'user_id' => $candidate->id,
            'difficulty' => 'mixed',
            'question_category' => 'signalisation',
            'score' => 4,
            'total_questions' => 5,
            'started_at' => now()->subHours(12),
            'completed_at' => now()->subHours(12),
        ]);

        $this->actingAs($candidate)
            ->get(route('quiz.history'))
            ->assertOk()
            ->assertSee('أفضل فصل')
            ->assertSee('Signalisation')
            ->assertSee('فصل يحتاج إلى تقوية')
            ->assertSee('Priorite')
            ->assertSee('70%')
            ->assertSee('المجموع التراكمي: 14 / 20')
            ->assertSee('2 محاولات')
            ->assertSee('80%')
            ->assertSee('المجموع التراكمي: 4 / 5')
            ->assertSee('أعد هذا الفصل');
    }

    public function test_missing_arabic_course_state_is_visible_end_to_end(): void
    {
        $candidate = User::factory()->create([
            'status' => 'active',
        ]);

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
