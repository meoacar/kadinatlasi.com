<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'is_active' => true
        ]);
    }

    /** @test */
    public function admin_can_view_users_index()
    {
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }

    /** @test */
    public function admin_can_search_users()
    {
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    /** @test */
    public function admin_can_filter_users_by_status()
    {
        User::factory()->create(['is_active' => true, 'name' => 'Active User']);
        User::factory()->create(['is_active' => false, 'name' => 'Inactive User']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['status' => 'active']));

        $response->assertStatus(200);
        $response->assertSee('Active User');
        $response->assertDontSee('Inactive User');
    }

    /** @test */
    public function admin_can_view_user_details()
    {
        $user = User::factory()->create(['name' => 'Test User']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
        $response->assertSee('Test User');
    }

    /** @test */
    public function admin_can_view_user_edit_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas('user');
    }

    /** @test */
    public function admin_can_update_user()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com'
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.users.update', $user), [
                'name' => 'New Name',
                'email' => 'new@example.com',
                'is_active' => true,
                'membership_type' => 'premium'
            ]);

        $response->assertRedirect(route('admin.users.show', $user));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('premium', $user->membership_type);
    }

    /** @test */
    public function admin_cannot_update_user_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.users.update', $user), [
                'name' => '', // required
                'email' => 'invalid-email', // invalid format
                'membership_type' => 'invalid_type' // invalid option
            ]);

        $response->assertSessionHasErrors(['name', 'email', 'membership_type']);
    }

    /** @test */
    public function admin_can_toggle_user_status()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.toggle-status', $user));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertFalse($user->is_active);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($user);
    }

    /** @test */
    public function admin_cannot_delete_themselves()
    {
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /** @test */
    public function user_index_is_paginated()
    {
        User::factory()->count(25)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        
        $users = $response->viewData('users');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $users);
    }

    /** @test */
    public function admin_can_filter_users_by_membership_type()
    {
        User::factory()->create(['membership_type' => 'premium']);
        User::factory()->create(['membership_type' => 'normal']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['membership' => 'premium']));

        $response->assertStatus(200);
        
        $users = $response->viewData('users');
        $this->assertEquals(1, $users->total());
        $this->assertEquals('premium', $users->first()->membership_type);
    }

    /** @test */
    public function admin_can_filter_users_by_date_range()
    {
        User::factory()->create(['created_at' => now()->subDays(10)]);
        User::factory()->create(['created_at' => now()->subDays(5)]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', [
                'date_from' => now()->subDays(6)->format('Y-m-d'),
                'date_to' => now()->format('Y-m-d')
            ]));

        $response->assertStatus(200);
        
        $users = $response->viewData('users');
        $this->assertEquals(1, $users->total());
    }

    /** @test */
    public function admin_can_export_users()
    {
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.exports.users'), [
                'format' => 'csv'
            ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /** @test */
    public function guest_cannot_access_user_management()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function non_admin_cannot_access_user_management()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->actingAs($user)
            ->get(route('admin.users.index'));

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    /** @test */
    public function admin_can_bulk_update_users()
    {
        $users = User::factory()->count(3)->create(['is_active' => true]);
        $userIds = $users->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.bulk-update'), [
                'user_ids' => $userIds,
                'action' => 'deactivate'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        foreach ($users as $user) {
            $this->assertFalse($user->fresh()->is_active);
        }
    }

    /** @test */
    public function admin_activity_is_logged_for_user_operations()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->put(route('admin.users.update', $user), [
                'name' => 'Updated Name',
                'email' => $user->email,
                'is_active' => true
            ]);

        $this->assertDatabaseHas('admin_activities', [
            'admin_id' => $this->admin->id,
            'action' => 'update',
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
    }
}