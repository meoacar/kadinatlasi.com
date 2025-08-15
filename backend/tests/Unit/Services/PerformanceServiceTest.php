<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PerformanceService;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ForumTopic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PerformanceService $performanceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->performanceService = new PerformanceService();
        Cache::flush();
    }

    /** @test */
    public function it_can_get_cached_dashboard_stats()
    {
        // Arrange
        User::factory()->count(5)->create(['is_active' => true]);
        User::factory()->count(2)->create(['is_active' => false]);
        BlogPost::factory()->count(3)->create(['status' => 'published']);
        BlogPost::factory()->count(1)->create(['status' => 'draft']);

        // Act
        $stats = $this->performanceService->getCachedDashboardStats();

        // Assert
        $this->assertIsArray($stats);
        $this->assertEquals(7, $stats['users_count']);
        $this->assertEquals(5, $stats['active_users_count']);
        $this->assertEquals(4, $stats['blog_posts_count']);
        $this->assertEquals(3, $stats['published_posts_count']);
    }

    /** @test */
    public function it_caches_dashboard_stats()
    {
        // Arrange
        User::factory()->create();

        // Act
        $stats1 = $this->performanceService->getCachedDashboardStats();
        
        // Create more users after first call
        User::factory()->count(5)->create();
        
        $stats2 = $this->performanceService->getCachedDashboardStats();

        // Assert - Should return cached result
        $this->assertEquals($stats1['users_count'], $stats2['users_count']);
        $this->assertEquals(1, $stats2['users_count']);
    }

    /** @test */
    public function it_can_optimize_user_query()
    {
        // Arrange
        $query = User::query();

        // Act
        $optimizedQuery = $this->performanceService->optimizeUserQuery($query);

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $optimizedQuery);
        
        // Check if eager loading is applied
        $eagerLoads = $optimizedQuery->getEagerLoads();
        $this->assertArrayHasKey('profile', $eagerLoads);
    }

    /** @test */
    public function it_can_optimize_blog_query()
    {
        // Arrange
        $query = BlogPost::query();

        // Act
        $optimizedQuery = $this->performanceService->optimizeBlogQuery($query);

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $optimizedQuery);
        
        $eagerLoads = $optimizedQuery->getEagerLoads();
        $this->assertArrayHasKey('author', $eagerLoads);
        $this->assertArrayHasKey('category', $eagerLoads);
    }

    /** @test */
    public function it_can_clear_cache()
    {
        // Arrange
        Cache::put('admin_dashboard_stats', ['test' => 'data'], 60);
        Cache::put('total_views', 100, 60);

        // Act
        $clearedKeys = $this->performanceService->clearCache([
            'admin_dashboard_stats',
            'total_views'
        ]);

        // Assert
        $this->assertContains('admin_dashboard_stats', $clearedKeys);
        $this->assertContains('total_views', $clearedKeys);
        $this->assertNull(Cache::get('admin_dashboard_stats'));
        $this->assertNull(Cache::get('total_views'));
    }

    /** @test */
    public function it_can_get_memory_usage()
    {
        // Act
        $memoryUsage = $this->performanceService->getMemoryUsage();

        // Assert
        $this->assertIsArray($memoryUsage);
        $this->assertArrayHasKey('current', $memoryUsage);
        $this->assertArrayHasKey('current_formatted', $memoryUsage);
        $this->assertArrayHasKey('peak', $memoryUsage);
        $this->assertArrayHasKey('peak_formatted', $memoryUsage);
        $this->assertArrayHasKey('limit', $memoryUsage);
        
        $this->assertIsInt($memoryUsage['current']);
        $this->assertIsInt($memoryUsage['peak']);
        $this->assertIsString($memoryUsage['current_formatted']);
    }

    /** @test */
    public function it_can_generate_performance_report()
    {
        // Act
        $report = $this->performanceService->generatePerformanceReport();

        // Assert
        $this->assertIsArray($report);
        $this->assertArrayHasKey('memory_usage', $report);
        $this->assertArrayHasKey('database_stats', $report);
        $this->assertArrayHasKey('cache_stats', $report);
        $this->assertArrayHasKey('index_recommendations', $report);
        $this->assertArrayHasKey('generated_at', $report);
    }

    /** @test */
    public function it_can_check_database_indexes()
    {
        // Act
        $recommendations = $this->performanceService->checkDatabaseIndexes();

        // Assert
        $this->assertIsArray($recommendations);
        // Recommendations might be empty if indexes already exist
        foreach ($recommendations as $recommendation) {
            $this->assertIsString($recommendation);
            $this->assertStringContains('CREATE INDEX', $recommendation);
        }
    }

    /** @test */
    public function it_handles_cache_clearing_gracefully_when_keys_dont_exist()
    {
        // Act
        $clearedKeys = $this->performanceService->clearCache([
            'non_existent_key',
            'another_missing_key'
        ]);

        // Assert
        $this->assertIsArray($clearedKeys);
        // Should not throw exception
    }

    /** @test */
    public function it_can_format_bytes_correctly()
    {
        // Use reflection to test private method
        $reflection = new \ReflectionClass($this->performanceService);
        $method = $reflection->getMethod('formatBytes');
        $method->setAccessible(true);

        // Test different byte sizes
        $this->assertEquals('1.00 KB', $method->invoke($this->performanceService, 1024));
        $this->assertEquals('1.00 MB', $method->invoke($this->performanceService, 1024 * 1024));
        $this->assertEquals('1.00 GB', $method->invoke($this->performanceService, 1024 * 1024 * 1024));
        $this->assertEquals('500 B', $method->invoke($this->performanceService, 500));
    }

    /** @test */
    public function it_handles_database_stats_gracefully_on_error()
    {
        // This test might not work in all environments, but it's good to have
        // Act
        $stats = $this->performanceService->getDatabaseStats();

        // Assert
        $this->assertIsArray($stats);
        // Should either have connection stats or error message
        $this->assertTrue(
            isset($stats['active_connections']) || isset($stats['error'])
        );
    }

    /** @test */
    public function it_caches_monthly_registrations()
    {
        // Arrange
        User::factory()->create(['created_at' => now()->subMonth()]);
        User::factory()->create(['created_at' => now()]);

        // Act
        $stats1 = $this->performanceService->getCachedDashboardStats();
        
        // Create more users
        User::factory()->create(['created_at' => now()]);
        
        $stats2 = $this->performanceService->getCachedDashboardStats();

        // Assert - Monthly registrations should be cached
        $this->assertEquals(
            $stats1['monthly_registrations'], 
            $stats2['monthly_registrations']
        );
    }
}