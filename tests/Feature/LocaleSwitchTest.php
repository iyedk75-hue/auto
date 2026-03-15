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
            ->assertSee('dir="rtl"', false);
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
            ->assertSee('dir="ltr"', false);
    }

    public function test_candidate_dashboard_uses_the_selected_locale(): void
    {
        $candidate = User::factory()->create();

        $this->actingAs($candidate)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false);
    }

    public function test_admin_dashboard_uses_the_selected_locale(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->withSession(['locale' => 'ar'])
            ->withCookie('massar_locale', 'ar')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false);
    }

    public function test_unsupported_locale_returns_not_found(): void
    {
        $this->get(route('locale.switch', ['locale' => 'en']))
            ->assertNotFound();
    }
}
