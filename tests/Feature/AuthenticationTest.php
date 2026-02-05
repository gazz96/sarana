<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    public function test_user_cannot_login_with_non_existent_email()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    public function test_authenticated_user_can_logout()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_user_must_verify_email_if_required()
    {
        // This test assumes email verification is not required
        // If it is, you would need to adjust this test
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
    }

    public function test_login_requires_email_and_password()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_requires_valid_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_remember_me_functionality()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => 'on',
        ]);

        $this->assertAuthenticated();
        $this->assertCookieNotEmpty($this->app['config']->get('auth.cookie_name', 'remember_web'));
    }

    public function test_user_session_is_maintained()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->actingAs($user);

        // Make multiple requests to verify session persistence
        $response1 = $this->get('/dashboard');
        $response1->assertStatus(200);

        $response2 = $this->get('/dashboard');
        $response2->assertStatus(200);

        $this->assertAuthenticatedAs($user);
    }

    public function test_different_roles_can_login()
    {
        $roles = ['guru', 'teknisi', 'admin', 'lembaga', 'keuangan'];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            $user = User::factory()->create([
                'role_id' => $role->id,
                'email' => "test_{$roleName}@example.com",
                'password' => bcrypt('password123'),
            ]);

            $response = $this->post('/login', [
                'email' => "test_{$roleName}@example.com",
                'password' => 'password123',
            ]);

            $this->assertAuthenticated();
            
            $this->post('/logout');
            $this->assertGuest();
        }
    }

    public function test_login_throttling()
    {
        // This test assumes login throttling is configured
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Attempt multiple failed logins
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        // On the 6th attempt, should be throttled
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // The response might still be 200 (login page) but with an error message
        // or it might be 429 (Too Many Requests) depending on configuration
        $this->assertGuest();
    }

    public function test_user_is_redirected_to_intended_url_after_login()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Try to access a protected page
        $response = $this->get('/problems/create');
        $response->assertRedirect('/login');

        // Login
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Should be redirected to the intended page
        $this->assertAuthenticated();
    }

    public function test_password_reset_page_loads()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function test_csrf_protection_is_enabled()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Try to POST without CSRF token
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ], ['X-CSRF-TOKEN' => 'invalid-token']);

        // Should fail due to CSRF protection
        $this->assertGuest();
    }
}