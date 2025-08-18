<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_login_page_can_be_rendered()
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
        $response->assertSee('Admin Panel');
    }

    /** @test */
    public function admin_can_login_with_valid_credentials()
    {
        $admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'password' => Hash::make('password123'),
            'is_active' => true
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@kadinatlasi.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function admin_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@kadinatlasi.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function non_admin_user_cannot_login_to_admin_panel()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'user@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function inactive_admin_cannot_login()
    {
        User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'password' => Hash::make('password123'),
            'is_active' => false
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@kadinatlasi.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function admin_can_logout()
    {
        $admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'is_active' => true
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_admin_is_redirected_from_login_page()
    {
        $admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'is_active' => true
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function login_requires_email_and_password()
    {
        $response = $this->post(route('admin.login.post'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function login_requires_valid_email_format()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function login_throttling_works()
    {
        // Make multiple failed login attempts
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('admin.login.post'), [
                'email' => 'admin@kadinatlasi.com',
                'password' => 'wrongpassword'
            ]);
        }

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@kadinatlasi.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    /** @test */
    public function admin_middleware_protects_admin_routes()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function admin_middleware_allows_authenticated_admin()
    {
        $admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'is_active' => true
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_middleware_blocks_inactive_admin()
    {
        $admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'is_active' => false
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    /** @test */
    public function admin_middleware_blocks_non_admin_user()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'is_active' => true
        ]);

        $this->actingAs($user);

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    /** @test */
    public function remember_me_functionality_works()
    {
        $admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'password' => Hash::make('password123'),
            'is_active' => true
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@kadinatlasi.com',
            'password' => 'password123',
            'remember' => true
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        
        // Check if remember token is set
        $this->assertNotNull($admin->fresh()->remember_token);
    }
}