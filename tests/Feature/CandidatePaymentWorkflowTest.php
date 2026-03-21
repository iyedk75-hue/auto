<?php

namespace Tests\Feature;

use App\Models\PaymentRecord;
use App\Models\Question;
use App\Models\User;
use App\Notifications\PaymentValidatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidatePaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_submit_bank_transfer_payment_with_proof(): void
    {
        Storage::fake('local');

        $candidate = User::factory()->create([
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($candidate)->post(route('payments.store'), [
            'amount' => '180.00',
            'transfer_reference' => 'RIB-2026-001',
            'note' => 'Virement envoye ce matin',
            'proof_file' => UploadedFile::fake()->image('proof.png'),
        ]);

        $response->assertRedirect(route('payments.index'));
        $response->assertSessionHas('status', __('ui.payments.bank_transfer_submitted'));

        $payment = PaymentRecord::query()->firstOrFail();

        $this->assertSame(PaymentRecord::METHOD_BANK_TRANSFER, $payment->payment_method);
        $this->assertSame(PaymentRecord::STATUS_PENDING, $payment->status);
        $this->assertSame('RIB-2026-001', $payment->transfer_reference);
        $this->assertNotNull($payment->proof_path);
        Storage::disk('local')->assertExists($payment->proof_path);
    }

    public function test_newly_registered_candidate_cannot_access_courses_but_can_open_payments(): void
    {
        $candidate = User::factory()->create([
            'status' => 'inactive',
        ]);

        $this->actingAs($candidate)
            ->get(route('courses.index'))
            ->assertRedirect(route('dashboard'));

        $this->actingAs($candidate)
            ->get(route('payments.index'))
            ->assertOk();
    }

    public function test_inactive_candidate_dashboard_hides_learning_navigation_links(): void
    {
        $candidate = User::factory()->create([
            'status' => 'inactive',
        ]);

        $this->actingAs($candidate)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(__('ui.candidate_access.pending_title'))
            ->assertSee(route('payments.index'))
            ->assertDontSee(route('courses.index'))
            ->assertDontSee(route('quiz.show'));
    }

    public function test_inactive_candidate_is_redirected_from_quiz_until_payment_is_validated(): void
    {
        $candidate = User::factory()->create([
            'status' => 'inactive',
        ]);

        $this->actingAs($candidate)
            ->get(route('quiz.show'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_super_admin_marking_payment_as_paid_activates_candidate_access(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $candidate = User::factory()->create([
            'status' => 'inactive',
        ]);
        $payment = PaymentRecord::create([
            'user_id' => $candidate->id,
            'amount' => 220,
            'payment_method' => PaymentRecord::METHOD_BANK_TRANSFER,
            'transfer_reference' => 'RIB-2026-VALID',
            'status' => PaymentRecord::STATUS_PENDING,
        ]);

        $response = $this->actingAs($superAdmin)->patch(route('admin.payments.update', $payment), [
            'user_id' => $candidate->id,
            'amount' => '220.00',
            'payment_method' => PaymentRecord::METHOD_BANK_TRANSFER,
            'transfer_reference' => 'RIB-2026-VALID',
            'status' => PaymentRecord::STATUS_PAID,
            'note' => 'Virement confirme',
        ]);

        $response->assertRedirect(route('admin.payments.index'));

        $candidate->refresh();
        $payment->refresh();

        $this->assertSame('active', $candidate->status);
        $this->assertNotNull($payment->reviewed_by_user_id);
        $this->assertNotNull($payment->reviewed_at);
        $this->assertSame(1, $candidate->notifications()->count());
        $this->assertSame('payment_validated', $candidate->notifications()->first()->data['kind']);
    }

    public function test_super_admin_dashboard_shows_pending_bank_proof_counter(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $candidate = User::factory()->create();

        PaymentRecord::create([
            'user_id' => $candidate->id,
            'amount' => 180,
            'payment_method' => PaymentRecord::METHOD_BANK_TRANSFER,
            'transfer_reference' => 'RIB-2026-PROOF-1',
            'proof_path' => 'payments/proofs/proof-1.png',
            'proof_mime' => 'image/png',
            'proof_uploaded_at' => now(),
            'status' => PaymentRecord::STATUS_PENDING,
        ]);

        PaymentRecord::create([
            'user_id' => $candidate->id,
            'amount' => 120,
            'payment_method' => PaymentRecord::METHOD_MANUAL,
            'status' => PaymentRecord::STATUS_PENDING,
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(__('ui.admin_dashboard.pending_proof_reviews'))
            ->assertSee('1')
            ->assertSee(route('admin.payments.index', ['review' => 'proof-pending']));
    }
}