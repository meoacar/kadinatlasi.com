<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    /** @test */
    public function it_can_get_users_with_filters()
    {
        // Arrange
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'is_active' => true,
            'membership_type' => 'premium'
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'is_active' => false,
            'membership_type' => 'normal'
        ]);

        // Act
        $result = $this->userService->getUsersWithFilters([]);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(2, $result->total());
    }

    /** @test */
    public function it_can_filter_users_by_search()
    {
        // Arrange
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com'
        ]);

        // Act
        $result = $this->userService->getUsersWithFilters(['search' => 'John']);

        // Assert
        $this->assertEquals(1, $result->total());
        $this->assertEquals('John Doe', $result->first()->name);
    }

    /** @test */
    public function it_can_filter_users_by_status()
    {
        // Arrange
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        // Act
        $activeUsers = $this->userService->getUsersWithFilters(['status' => 'active']);
        $inactiveUsers = $this->userService->getUsersWithFilters(['status' => 'inactive']);

        // Assert
        $this->assertEquals(1, $activeUsers->total());
        $this->assertEquals(1, $inactiveUsers->total());
        $this->assertTrue($activeUsers->first()->is_active);
        $this->assertFalse($inactiveUsers->first()->is_active);
    }

    /** @test */
    public function it_can_filter_users_by_membership_type()
    {
        // Arrange
        User::factory()->create(['membership_type' => 'premium']);
        User::factory()->create(['membership_type' => 'normal']);
        User::factory()->create(['membership_type' => null]);

        // Act
        $premiumUsers = $this->userService->getUsersWithFilters(['membership' => 'premium']);
        $normalUsers = $this->userService->getUsersWithFilters(['membership' => 'normal']);

        // Assert
        $this->assertEquals(1, $premiumUsers->total());
        $this->assertEquals(2, $normalUsers->total()); // normal + null
        $this->assertEquals('premium', $premiumUsers->first()->membership_type);
    }

    /** @test */
    public function it_can_filter_users_by_date_range()
    {
        // Arrange
        User::factory()->create(['created_at' => now()->subDays(10)]);
        User::factory()->create(['created_at' => now()->subDays(5)]);
        User::factory()->create(['created_at' => now()->subDays(1)]);

        // Act
        $result = $this->userService->getUsersWithFilters([
            'date_from' => now()->subDays(6)->format('Y-m-d'),
            'date_to' => now()->format('Y-m-d')
        ]);

        // Assert
        $this->assertEquals(2, $result->total());
    }

    /** @test */
    public function it_can_update_user()
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com'
        ]);

        $updateData = [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'is_active' => false
        ];

        // Act
        $updatedUser = $this->userService->updateUser($user, $updateData);

        // Assert
        $this->assertEquals('New Name', $updatedUser->name);
        $this->assertEquals('new@example.com', $updatedUser->email);
        $this->assertFalse($updatedUser->is_active);
    }

    /** @test */
    public function it_can_toggle_user_status()
    {
        // Arrange
        $activeUser = User::factory()->create(['is_active' => true]);
        $inactiveUser = User::factory()->create(['is_active' => false]);

        // Act
        $toggledActive = $this->userService->toggleUserStatus($activeUser);
        $toggledInactive = $this->userService->toggleUserStatus($inactiveUser);

        // Assert
        $this->assertFalse($toggledActive->is_active);
        $this->assertTrue($toggledInactive->is_active);
    }

    /** @test */
    public function it_can_get_user_stats()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $stats = $this->userService->getUserStats($user);

        // Assert
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('posts_count', $stats);
        $this->assertArrayHasKey('products_count', $stats);
        $this->assertArrayHasKey('forum_topics_count', $stats);
        $this->assertArrayHasKey('total_views', $stats);
        $this->assertArrayHasKey('member_since', $stats);
    }

    /** @test */
    public function it_handles_empty_filters_gracefully()
    {
        // Arrange
        User::factory()->count(3)->create();

        // Act
        $result = $this->userService->getUsersWithFilters([]);

        // Assert
        $this->assertEquals(3, $result->total());
    }

    /** @test */
    public function it_handles_invalid_date_filters_gracefully()
    {
        // Arrange
        User::factory()->create();

        // Act
        $result = $this->userService->getUsersWithFilters([
            'date_from' => 'invalid-date',
            'date_to' => 'also-invalid'
        ]);

        // Assert
        $this->assertEquals(1, $result->total());
    }
}