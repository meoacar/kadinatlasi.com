<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ForumTopic;
use App\Models\AdminActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_blog_posts_relationship()
    {
        // Arrange
        $user = User::factory()->create();
        $post = BlogPost::factory()->create(['user_id' => $user->id]);

        // Act & Assert
        $this->assertTrue($user->blogPosts()->exists());
        $this->assertEquals($post->id, $user->blogPosts->first()->id);
    }

    /** @test */
    public function it_has_products_relationship()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        // Act & Assert
        $this->assertTrue($user->products()->exists());
        $this->assertEquals($product->id, $user->products->first()->id);
    }

    /** @test */
    public function it_has_forum_topics_relationship()
    {
        // Arrange
        $user = User::factory()->create();
        $topic = ForumTopic::factory()->create(['user_id' => $user->id]);

        // Act & Assert
        $this->assertTrue($user->forumTopics()->exists());
        $this->assertEquals($topic->id, $user->forumTopics->first()->id);
    }

    /** @test */
    public function it_has_admin_activities_relationship()
    {
        // Arrange
        $user = User::factory()->create();
        $activity = AdminActivity::factory()->create(['admin_id' => $user->id]);

        // Act & Assert
        $this->assertTrue($user->adminActivities()->exists());
        $this->assertEquals($activity->id, $user->adminActivities->first()->id);
    }

    /** @test */
    public function it_can_check_if_user_is_admin()
    {
        // Arrange
        $adminUser = User::factory()->create(['email' => 'admin@kadinatlasi.com']);
        $regularUser = User::factory()->create(['email' => 'user@example.com']);

        // Act & Assert
        $this->assertTrue($adminUser->hasRole('admin'));
        $this->assertFalse($regularUser->hasRole('admin'));
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        // Arrange
        $fillable = [
            'name', 'email', 'email_verified_at', 'password', 'is_active',
            'membership_type', 'birth_date', 'zodiac_sign', 'points'
        ];

        // Act
        $user = new User();

        // Assert
        $this->assertEquals($fillable, $user->getFillable());
    }

    /** @test */
    public function it_hides_sensitive_attributes()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => 'secret123',
            'remember_token' => 'token123'
        ]);

        // Act
        $array = $user->toArray();

        // Assert
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        // Arrange
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'birth_date' => '1990-01-01',
            'is_active' => true,
            'points' => 100
        ]);

        // Act & Assert
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->birth_date);
        $this->assertIsBool($user->is_active);
        $this->assertIsInt($user->points);
    }

    /** @test */
    public function it_can_scope_active_users()
    {
        // Arrange
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        // Act
        $activeUsers = User::active()->get();

        // Assert
        $this->assertEquals(1, $activeUsers->count());
        $this->assertTrue($activeUsers->first()->is_active);
    }

    /** @test */
    public function it_can_scope_by_membership_type()
    {
        // Arrange
        User::factory()->create(['membership_type' => 'premium']);
        User::factory()->create(['membership_type' => 'normal']);

        // Act
        $premiumUsers = User::membershipType('premium')->get();

        // Assert
        $this->assertEquals(1, $premiumUsers->count());
        $this->assertEquals('premium', $premiumUsers->first()->membership_type);
    }

    /** @test */
    public function it_can_search_users()
    {
        // Arrange
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

        // Act
        $searchResults = User::search('John')->get();

        // Assert
        $this->assertEquals(1, $searchResults->count());
        $this->assertEquals('John Doe', $searchResults->first()->name);
    }

    /** @test */
    public function it_can_apply_quick_date_filter()
    {
        // Arrange
        User::factory()->create(['created_at' => now()->subDays(1)]);
        User::factory()->create(['created_at' => now()->subDays(8)]);
        User::factory()->create(['created_at' => now()->subDays(32)]);

        // Act
        $thisWeek = User::quickDateFilter('this_week')->get();
        $thisMonth = User::quickDateFilter('this_month')->get();

        // Assert
        $this->assertEquals(1, $thisWeek->count());
        $this->assertEquals(2, $thisMonth->count());
    }

    /** @test */
    public function it_can_apply_advanced_filters()
    {
        // Arrange
        User::factory()->create([
            'is_active' => true,
            'membership_type' => 'premium',
            'created_at' => now()->subDays(5)
        ]);

        User::factory()->create([
            'is_active' => false,
            'membership_type' => 'normal',
            'created_at' => now()->subDays(10)
        ]);

        // Act
        $filtered = User::advancedFilter([
            'status' => 'active',
            'membership' => 'premium',
            'date_from' => now()->subDays(7)->format('Y-m-d')
        ])->get();

        // Assert
        $this->assertEquals(1, $filtered->count());
        $this->assertTrue($filtered->first()->is_active);
        $this->assertEquals('premium', $filtered->first()->membership_type);
    }

    /** @test */
    public function it_generates_avatar_url()
    {
        // Arrange
        $user = User::factory()->create(['name' => 'John Doe']);

        // Act
        $avatarUrl = $user->getAvatarUrl();

        // Assert
        $this->assertIsString($avatarUrl);
        $this->assertStringContains('gravatar.com', $avatarUrl);
    }

    /** @test */
    public function it_calculates_membership_duration()
    {
        // Arrange
        $user = User::factory()->create(['created_at' => now()->subMonths(6)]);

        // Act
        $duration = $user->getMembershipDuration();

        // Assert
        $this->assertIsString($duration);
        $this->assertStringContains('6', $duration);
    }
}