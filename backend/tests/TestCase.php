<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test environment
        $this->withoutVite();
        
        // Disable middleware for some tests
        if (property_exists($this, 'disableMiddleware') && $this->disableMiddleware) {
            $this->withoutMiddleware();
        }
    }

    /**
     * Create an admin user for testing
     */
    protected function createAdmin(array $attributes = []): \App\Models\User
    {
        return \App\Models\User::factory()->create(array_merge([
            'email' => 'admin@kadinatlasi.com',
            'is_active' => true
        ], $attributes));
    }

    /**
     * Create a regular user for testing
     */
    protected function createUser(array $attributes = []): \App\Models\User
    {
        return \App\Models\User::factory()->create(array_merge([
            'email' => 'user@example.com',
            'is_active' => true
        ], $attributes));
    }

    /**
     * Assert that a model was logged in admin activities
     */
    protected function assertModelWasLogged(string $action, $model): void
    {
        $this->assertDatabaseHas('admin_activities', [
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id
        ]);
    }

    /**
     * Assert that cache key exists
     */
    protected function assertCacheHas(string $key): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Cache::has($key),
            "Cache key '{$key}' does not exist"
        );
    }

    /**
     * Assert that cache key does not exist
     */
    protected function assertCacheMissing(string $key): void
    {
        $this->assertFalse(
            \Illuminate\Support\Facades\Cache::has($key),
            "Cache key '{$key}' should not exist"
        );
    }

    /**
     * Mock file upload for testing
     */
    protected function mockFileUpload(string $filename = 'test.jpg', int $width = 800, int $height = 600): \Illuminate\Http\UploadedFile
    {
        return \Illuminate\Http\UploadedFile::fake()->image($filename, $width, $height);
    }

    /**
     * Assert that a file was uploaded to storage
     */
    protected function assertFileUploaded(string $path, string $disk = 'public'): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Storage::disk($disk)->exists($path),
            "File '{$path}' was not uploaded to '{$disk}' disk"
        );
    }

    /**
     * Assert that a file was deleted from storage
     */
    protected function assertFileDeleted(string $path, string $disk = 'public'): void
    {
        $this->assertFalse(
            \Illuminate\Support\Facades\Storage::disk($disk)->exists($path),
            "File '{$path}' should have been deleted from '{$disk}' disk"
        );
    }

    /**
     * Create test data for dashboard
     */
    protected function createDashboardTestData(): array
    {
        $users = \App\Models\User::factory()->count(10)->create();
        $posts = \App\Models\BlogPost::factory()->count(5)->create();
        $products = \App\Models\Product::factory()->count(8)->create();
        
        return [
            'users' => $users,
            'posts' => $posts,
            'products' => $products
        ];
    }

    /**
     * Assert JSON response structure for API tests
     */
    protected function assertJsonResponseStructure(array $structure, $response): void
    {
        $response->assertJsonStructure($structure);
    }

    /**
     * Assert that response has success message
     */
    protected function assertHasSuccessMessage($response, ?string $message = null): void
    {
        if ($message) {
            $response->assertSessionHas('success', $message);
        } else {
            $response->assertSessionHas('success');
        }
    }

    /**
     * Assert that response has error message
     */
    protected function assertHasErrorMessage($response, ?string $message = null): void
    {
        if ($message) {
            $response->assertSessionHas('error', $message);
        } else {
            $response->assertSessionHas('error');
        }
    }

    /**
     * Create test categories
     */
    protected function createTestCategories(int $count = 3): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Category::factory()->count($count)->create();
    }

    /**
     * Create test forum data
     */
    protected function createForumTestData(): array
    {
        $groups = \App\Models\ForumGroup::factory()->count(3)->create();
        $topics = \App\Models\ForumTopic::factory()->count(10)->create();
        $posts = \App\Models\ForumPost::factory()->count(25)->create();
        
        return [
            'groups' => $groups,
            'topics' => $topics,
            'posts' => $posts
        ];
    }

    /**
     * Assert that validation errors exist for specific fields
     */
    protected function assertValidationErrors($response, array $fields): void
    {
        $response->assertSessionHasErrors($fields);
        
        foreach ($fields as $field) {
            $this->assertTrue(
                session('errors')->has($field),
                "Validation error for field '{$field}' not found"
            );
        }
    }

    /**
     * Assert that no validation errors exist
     */
    protected function assertNoValidationErrors($response): void
    {
        $response->assertSessionHasNoErrors();
    }

    /**
     * Create admin activity for testing
     */
    protected function createAdminActivity(array $attributes = []): \App\Models\AdminActivity
    {
        return \App\Models\AdminActivity::factory()->create($attributes);
    }

    /**
     * Assert that performance metrics are within acceptable range
     */
    protected function assertPerformanceMetrics(array $metrics): void
    {
        if (isset($metrics['memory_usage'])) {
            $this->assertLessThan(
                128 * 1024 * 1024, // 128MB
                $metrics['memory_usage']['current'],
                'Memory usage is too high'
            );
        }
        
        if (isset($metrics['query_count'])) {
            $this->assertLessThan(
                50,
                $metrics['query_count'],
                'Too many database queries executed'
            );
        }
    }

    /**
     * Simulate slow network for testing
     */
    protected function simulateSlowNetwork(): void
    {
        if (app()->environment('testing')) {
            // Add artificial delay for testing timeout scenarios
            usleep(100000); // 100ms delay
        }
    }

    /**
     * Clean up test files after test
     */
    protected function tearDown(): void
    {
        // Clean up any test files
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('test-uploads')) {
            \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('test-uploads');
        }
        
        parent::tearDown();
    }
}