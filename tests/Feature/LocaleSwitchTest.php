<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_switch_to_arabic_and_persist_the_choice(): void
    {
        $response = $this->from(route('home'))
            ->get(route('locale.switch', ['locale' => 'ar']));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('locale', 'ar');
        $response->assertCookie('massar_locale', 'ar');

        $this->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('home'))
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('المقصورة الرقمية لمدارس السياقة التونسية.')
            ->assertDontSee('احمِ محتواك التعليمي.');
    }

    public function test_guest_can_switch_back_to_french(): void
    {
        $response = $this->from(route('home'))
            ->get(route('locale.switch', ['locale' => 'fr']));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('locale', 'fr');
        $response->assertCookie('massar_locale', 'fr');

        $this->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('home'))
            ->assertOk()
            ->assertSee('lang="fr"', false)
            ->assertSee('dir="ltr"', false)
            ->assertSee('Le cockpit digital des auto-écoles tunisiennes.')
            ->assertDontSee('Protégez votre contenu pédagogique.');
    }

    public function test_candidate_dashboard_uses_the_selected_locale(): void
    {
        $candidate = User::factory()->create();

        $this->actingAs($candidate)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('مرحبًا')
            ->assertSee('اختبار تذكيري ذكي')
            ->assertDontSee('Fr');
    }

    public function test_admin_dashboard_forces_arabic_locale(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('لوحة تحكم مسار')
            ->assertSee('إجراء سريع')
            ->assertDontSee('Fr');
    }

    public function test_candidate_locale_switch_route_keeps_arabic_only(): void
    {
        $candidate = User::factory()->create();

        $response = $this->actingAs($candidate)
            ->from(route('dashboard'))
            ->get(route('locale.switch', ['locale' => 'fr']));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('locale', 'ar');
        $response->assertCookie('massar_locale', 'ar');
    }

    public function test_super_admin_can_still_use_french_locale(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)
            ->from(route('admin.dashboard'))
            ->get(route('locale.switch', ['locale' => 'fr']));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('locale', 'fr');
        $response->assertCookie('massar_locale', 'fr');

        $this->actingAs($superAdmin)
            ->withSession(['locale' => 'fr'])
            ->withCookie('massar_locale', 'fr')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('lang="fr"', false)
            ->assertSee('dir="ltr"', false);
    }

    public function test_unsupported_locale_returns_not_found(): void
    {
        $this->get(route('locale.switch', ['locale' => 'en']))
            ->assertNotFound();
    }
}
