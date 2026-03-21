<?php

namespace Tests\Feature\Auth;

use App\Models\AutoSchool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertSame(User::ROLE_CANDIDATE, User::firstWhere('email', 'test@example.com')?->role);
        $this->assertSame('inactive', User::firstWhere('email', 'test@example.com')?->status);
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'test-confirm@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_registration_sets_device_and_registered_fields(): void
    {
        $this->post('/register', [
            'name' => 'Device User',
            'email' => 'device@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::firstWhere('email', 'device@example.com');

        $this->assertNotNull($user);
        $this->assertNotNull($user?->registered_at);
        $this->assertNotNull($user?->device_uuid);
        $this->assertNotNull($user?->device_bound_at);
        $this->assertNotNull($user?->last_login_at);
        $this->assertNotNull($user?->last_login_ip);
        $this->assertNotNull($user?->last_user_agent);
    }

    public function test_registration_sets_device_cookie(): void
    {
        $response = $this->post('/register', [
            'name' => 'Cookie User',
            'email' => 'cookie@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::firstWhere('email', 'cookie@example.com');

        $this->assertNotNull($user);
        $response->assertCookie('massar_device', $user?->device_uuid);
        $response->assertCookieNotExpired('massar_device');
    }

    public function test_registration_assigns_auto_school_when_available(): void
    {
        $autoSchool = AutoSchool::create(['name' => 'Test Auto School']);

        $this->post('/register', [
            'name' => 'School User',
            'email' => 'school@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::firstWhere('email', 'school@example.com');

        $this->assertNotNull($user);
        $this->assertSame($autoSchool->id, $user?->auto_school_id);
    }

    public function test_registration_leaves_auto_school_null_when_none_exist(): void
    {
        $this->post('/register', [
            'name' => 'No School User',
            'email' => 'noschool@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::firstWhere('email', 'noschool@example.com');

        $this->assertNotNull($user);
        $this->assertNull($user?->auto_school_id);
    }

    public function test_authenticated_users_are_redirected_from_registration(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect(route('dashboard'));
    }
}
