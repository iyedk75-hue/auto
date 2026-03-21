<?php

namespace Tests\Feature;

use App\Models\PaymentRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_admin_dashboard_renders_arabic_copy_when_locale_is_arabic(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('لوحة تحكم مسار')
            ->assertSee('الأقسام');
    }

    public function test_super_admin_can_view_admin_dashboard(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_super_admin_dashboard_exposes_pending_proof_review_shortcut(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $candidate = User::factory()->create();

        PaymentRecord::create([
            'user_id' => $candidate->id,
            'amount' => 200,
            'payment_method' => PaymentRecord::METHOD_BANK_TRANSFER,
            'proof_path' => 'payments/proofs/proof-shortcut.png',
            'proof_mime' => 'image/png',
            'proof_uploaded_at' => now(),
            'status' => PaymentRecord::STATUS_PENDING,
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(__('ui.admin_dashboard.review_proofs_cta'))
            ->assertSee(route('admin.payments.index', ['review' => 'proof-pending']));
    }

    public function test_candidate_cannot_view_admin_dashboard(): void
    {
        $candidate = User::factory()->create();

        $this->actingAs($candidate)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_is_redirected_from_main_dashboard_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
