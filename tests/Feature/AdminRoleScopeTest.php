<?php

namespace Tests\Feature;

use App\Models\AutoSchool;
use App\Models\ExamSchedule;
use App\Models\PaymentRecord;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminRoleScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_auto_school_management(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get(route('admin.auto-schools.index'))
            ->assertOk();
    }

    public function test_school_admin_cannot_access_auto_school_management(): void
    {
        $school = $this->createSchool('Massar Lac');
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $school->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.auto-schools.index'))
            ->assertForbidden();
    }

    public function test_school_admin_candidate_index_is_scoped_to_own_auto_school(): void
    {
        [$schoolA, $schoolB] = $this->createSchools();
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $schoolA->id,
        ]);

        User::factory()->create([
            'name' => 'Candidate Alpha',
            'email' => 'alpha@example.test',
            'auto_school_id' => $schoolA->id,
        ]);

        User::factory()->create([
            'name' => 'Candidate Beta',
            'email' => 'beta@example.test',
            'auto_school_id' => $schoolB->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.candidates.index'))
            ->assertOk()
            ->assertSee('Candidate Alpha')
            ->assertDontSee('Candidate Beta');
    }

    public function test_super_admin_can_filter_candidates_by_auto_school(): void
    {
        [$schoolA, $schoolB] = $this->createSchools();
        $superAdmin = User::factory()->superAdmin()->create();

        User::factory()->create([
            'name' => 'Candidate School A',
            'email' => 'candidate-school-a@example.test',
            'auto_school_id' => $schoolA->id,
        ]);

        User::factory()->create([
            'name' => 'Candidate School B',
            'email' => 'candidate-school-b@example.test',
            'auto_school_id' => $schoolB->id,
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.candidates.index', ['auto_school_id' => $schoolA->id]))
            ->assertOk()
            ->assertSee('Candidate School A')
            ->assertDontSee('Candidate School B');
    }

    public function test_school_admin_cannot_edit_candidates_even_from_own_auto_school(): void
    {
        $schoolA = $this->createSchool('Massar A');
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $schoolA->id,
        ]);
        $candidate = User::factory()->create([
            'auto_school_id' => $schoolA->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.candidates.edit', $candidate))
            ->assertForbidden();
    }

    public function test_super_admin_can_create_auto_school_admin(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $school = $this->createSchool('Massar Centre');

        $response = $this->actingAs($superAdmin)
            ->post(route('admin.auto-schools.admins.store', $school), [
                'name' => 'Admin Centre',
                'email' => 'admin-centre@example.test',
                'phone' => '+21699000001',
                'status' => 'active',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertRedirect(route('admin.auto-schools.admins.index', $school));

        $this->assertDatabaseHas('users', [
            'email' => 'admin-centre@example.test',
            'role' => User::ROLE_ADMIN,
            'auto_school_id' => $school->id,
        ]);
    }

    public function test_school_admin_cannot_access_payments_management(): void
    {
        $schoolA = $this->createSchool('Massar A');
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $schoolA->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.payments.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_filter_payments_by_auto_school(): void
    {
        [$schoolA, $schoolB] = $this->createSchools();
        $superAdmin = User::factory()->superAdmin()->create();
        $candidateA = User::factory()->create([
            'name' => 'Candidate Payment A',
            'email' => 'candidate-payment-a@example.test',
            'auto_school_id' => $schoolA->id,
        ]);
        $candidateB = User::factory()->create([
            'name' => 'Candidate Payment B',
            'email' => 'candidate-payment-b@example.test',
            'auto_school_id' => $schoolB->id,
        ]);

        PaymentRecord::create([
            'user_id' => $candidateA->id,
            'amount' => 100,
            'status' => PaymentRecord::STATUS_PENDING,
        ]);

        PaymentRecord::create([
            'user_id' => $candidateB->id,
            'amount' => 200,
            'status' => PaymentRecord::STATUS_PAID,
            'paid_at' => now(),
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.payments.index', ['auto_school_id' => $schoolA->id]))
            ->assertOk()
            ->assertSee('Candidate Payment A')
            ->assertDontSee('Candidate Payment B');
    }

    public function test_super_admin_can_filter_pending_bank_transfer_proofs(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $candidatePending = User::factory()->create([
            'name' => 'Candidate Proof Pending',
            'email' => 'candidate-proof-pending@example.test',
        ]);
        $candidatePaid = User::factory()->create([
            'name' => 'Candidate Proof Paid',
            'email' => 'candidate-proof-paid@example.test',
        ]);

        PaymentRecord::create([
            'user_id' => $candidatePending->id,
            'amount' => 150,
            'payment_method' => PaymentRecord::METHOD_BANK_TRANSFER,
            'transfer_reference' => 'RIB-PENDING-01',
            'proof_path' => 'payments/proofs/pending-proof.pdf',
            'proof_mime' => 'application/pdf',
            'proof_uploaded_at' => now(),
            'status' => PaymentRecord::STATUS_PENDING,
        ]);

        PaymentRecord::create([
            'user_id' => $candidatePaid->id,
            'amount' => 190,
            'payment_method' => PaymentRecord::METHOD_BANK_TRANSFER,
            'transfer_reference' => 'RIB-PAID-01',
            'proof_path' => 'payments/proofs/paid-proof.pdf',
            'proof_mime' => 'application/pdf',
            'proof_uploaded_at' => now(),
            'status' => PaymentRecord::STATUS_PAID,
            'paid_at' => now(),
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.payments.index', ['review' => 'proof-pending']))
            ->assertOk()
            ->assertSee('Candidate Proof Pending')
            ->assertDontSee('Candidate Proof Paid');
    }

    public function test_school_admin_exam_index_only_shows_own_auto_school_exams(): void
    {
        [$schoolA, $schoolB] = $this->createSchools();
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $schoolA->id,
        ]);
        $candidateA = User::factory()->create([
            'name' => 'Candidate Exam A',
            'email' => 'exam-a@example.test',
            'auto_school_id' => $schoolA->id,
        ]);
        $candidateB = User::factory()->create([
            'name' => 'Candidate Exam B',
            'email' => 'exam-b@example.test',
            'auto_school_id' => $schoolB->id,
        ]);

        ExamSchedule::create([
            'user_id' => $candidateA->id,
            'auto_school_id' => $schoolA->id,
            'exam_date' => now()->addDay(),
            'status' => ExamSchedule::STATUS_PLANNED,
            'note' => 'School A exam',
        ]);

        ExamSchedule::create([
            'user_id' => $candidateB->id,
            'auto_school_id' => $schoolB->id,
            'exam_date' => now()->addDays(2),
            'status' => ExamSchedule::STATUS_PLANNED,
            'note' => 'School B exam',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.exams.index'))
            ->assertOk()
            ->assertSee('Candidate Exam A')
            ->assertDontSee('Candidate Exam B');
    }

    public function test_super_admin_cannot_access_course_management(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get(route('admin.courses.index'))
            ->assertForbidden();
    }

    public function test_school_admin_created_course_is_scoped_to_its_auto_school(): void
    {
        $school = $this->createSchool('Massar Centre');
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $school->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Cours local',
            'category' => 'priority_rules',
            'description' => 'Description',
            'content' => 'Contenu',
            'duration_minutes' => 30,
            'sort_order' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $this->assertDatabaseHas('courses', [
            'title' => 'Cours local',
            'auto_school_id' => $school->id,
        ]);
    }

    public function test_school_admin_created_question_is_scoped_to_its_auto_school(): void
    {
        Storage::fake('public');

        $school = $this->createSchool('Massar Centre');
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $school->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.questions.store'), [
            'category' => Question::CATEGORIES[0],
            'question_text' => 'Qui passe en premier ?',
            'correct_answer' => 'أ',
            'difficulty' => Question::DIFFICULTIES[0],
            'image' => UploadedFile::fake()->image('question.png'),
            'option_a' => 'Voiture A',
            'option_b' => 'Voiture B',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.questions.index'));

        $question = Question::query()->where('question_text', 'Qui passe en premier ?')->firstOrFail();

        $this->assertDatabaseHas('questions', [
            'question_text' => 'Qui passe en premier ?',
            'auto_school_id' => $school->id,
        ]);
        $this->assertNotNull($question->image_url);
        Storage::disk('public')->assertExists(Question::managedImagePathFromValue($question->getRawOriginal('image_url')));
    }

    public function test_exam_creation_notifies_the_candidate(): void
    {
        $school = $this->createSchool('Massar Centre');
        $admin = User::factory()->admin()->create([
            'auto_school_id' => $school->id,
        ]);
        $candidate = User::factory()->create([
            'auto_school_id' => $school->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.exams.store'), [
            'user_id' => $candidate->id,
            'exam_date' => now()->addWeek()->toDateString(),
            'status' => ExamSchedule::STATUS_PLANNED,
            'note' => 'Session principale',
        ]);

        $response->assertRedirect(route('admin.exams.index'));
        $candidate->refresh();

        $this->assertSame(1, $candidate->notifications()->count());
        $this->assertSame('exam_schedule', $candidate->notifications()->first()->data['kind']);
    }

    private function createSchools(): array
    {
        return [
            $this->createSchool('Massar A'),
            $this->createSchool('Massar B'),
        ];
    }

    private function createSchool(string $name): AutoSchool
    {
        return AutoSchool::create([
            'name' => $name,
            'city' => 'Tunis',
            'address' => 'Centre-ville',
            'whatsapp_phone' => '+21699000000',
            'is_active' => true,
        ]);
    }
}