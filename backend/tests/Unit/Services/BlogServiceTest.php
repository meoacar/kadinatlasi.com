<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\BlogService;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BlogServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BlogService $blogService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blogService = new BlogService();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_create_blog_post()
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $data = [
            'title' => 'Test Blog Post',
            'content' => 'This is test content',
            'status' => 'published',
            'category_id' => $category->id,
            'user_id' => $user->id
        ];

        // Act
        $post = $this->blogService->createPost($data);

        // Assert
        $this->assertInstanceOf(BlogPost::class, $post);
        $this->assertEquals('Test Blog Post', $post->title);
        $this->assertEquals('published', $post->status);
        $this->assertEquals($category->id, $post->category_id);
        $this->assertEquals($user->id, $post->user_id);
    }

    /** @test */
    public function it_can_update_blog_post()
    {
        // Arrange
        $post = BlogPost::factory()->create([
            'title' => 'Old Title',
            'content' => 'Old content'
        ]);

        $updateData = [
            'title' => 'New Title',
            'content' => 'New content',
            'status' => 'draft'
        ];

        // Act
        $updatedPost = $this->blogService->updatePost($post, $updateData);

        // Assert
        $this->assertEquals('New Title', $updatedPost->title);
        $this->assertEquals('New content', $updatedPost->content);
        $this->assertEquals('draft', $updatedPost->status);
    }

    /** @test */
    public function it_can_handle_image_upload()
    {
        // Arrange
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        // Act
        $imagePath = $this->blogService->handleImageUpload($file);

        // Assert
        $this->assertNotNull($imagePath);
        $this->assertStringContains('blog/', $imagePath);
        Storage::disk('public')->assertExists($imagePath);
    }

    /** @test */
    public function it_can_get_posts_with_filters()
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        BlogPost::factory()->create([
            'title' => 'Published Post',
            'status' => 'published',
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        BlogPost::factory()->create([
            'title' => 'Draft Post',
            'status' => 'draft',
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        // Act
        $publishedPosts = $this->blogService->getPostsWithFilters(['status' => 'published']);
        $draftPosts = $this->blogService->getPostsWithFilters(['status' => 'draft']);
        $userPosts = $this->blogService->getPostsWithFilters(['author_id' => $user->id]);

        // Assert
        $this->assertEquals(1, $publishedPosts->total());
        $this->assertEquals(1, $draftPosts->total());
        $this->assertEquals(2, $userPosts->total());
    }

    /** @test */
    public function it_can_search_posts_by_title()
    {
        // Arrange
        BlogPost::factory()->create(['title' => 'Laravel Tutorial']);
        BlogPost::factory()->create(['title' => 'Vue.js Guide']);
        BlogPost::factory()->create(['title' => 'PHP Best Practices']);

        // Act
        $result = $this->blogService->getPostsWithFilters(['search' => 'Laravel']);

        // Assert
        $this->assertEquals(1, $result->total());
        $this->assertEquals('Laravel Tutorial', $result->first()->title);
    }

    /** @test */
    public function it_can_filter_posts_by_category()
    {
        // Arrange
        $category1 = Category::factory()->create(['name' => 'Technology']);
        $category2 = Category::factory()->create(['name' => 'Lifestyle']);

        BlogPost::factory()->create(['category_id' => $category1->id]);
        BlogPost::factory()->create(['category_id' => $category2->id]);

        // Act
        $techPosts = $this->blogService->getPostsWithFilters(['category_id' => $category1->id]);

        // Assert
        $this->assertEquals(1, $techPosts->total());
        $this->assertEquals($category1->id, $techPosts->first()->category_id);
    }

    /** @test */
    public function it_can_filter_posts_by_date_range()
    {
        // Arrange
        BlogPost::factory()->create(['created_at' => now()->subDays(10)]);
        BlogPost::factory()->create(['created_at' => now()->subDays(5)]);
        BlogPost::factory()->create(['created_at' => now()->subDays(1)]);

        // Act
        $result = $this->blogService->getPostsWithFilters([
            'date_from' => now()->subDays(6)->format('Y-m-d'),
            'date_to' => now()->format('Y-m-d')
        ]);

        // Assert
        $this->assertEquals(2, $result->total());
    }

    /** @test */
    public function it_generates_slug_from_title()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'title' => 'This is a Test Blog Post',
            'content' => 'Content here',
            'user_id' => $user->id
        ];

        // Act
        $post = $this->blogService->createPost($data);

        // Assert
        $this->assertEquals('this-is-a-test-blog-post', $post->slug);
    }

    /** @test */
    public function it_handles_duplicate_slugs()
    {
        // Arrange
        $user = User::factory()->create();
        BlogPost::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post'
        ]);

        $data = [
            'title' => 'Test Post',
            'content' => 'Content here',
            'user_id' => $user->id
        ];

        // Act
        $post = $this->blogService->createPost($data);

        // Assert
        $this->assertNotEquals('test-post', $post->slug);
        $this->assertStringContains('test-post', $post->slug);
    }

    /** @test */
    public function it_can_toggle_post_status()
    {
        // Arrange
        $publishedPost = BlogPost::factory()->create(['status' => 'published']);
        $draftPost = BlogPost::factory()->create(['status' => 'draft']);

        // Act
        $toggledPublished = $this->blogService->toggleStatus($publishedPost);
        $toggledDraft = $this->blogService->toggleStatus($draftPost);

        // Assert
        $this->assertEquals('draft', $toggledPublished->status);
        $this->assertEquals('published', $toggledDraft->status);
    }

    /** @test */
    public function it_increments_view_count()
    {
        // Arrange
        $post = BlogPost::factory()->create(['views_count' => 5]);

        // Act
        $this->blogService->incrementViews($post);

        // Assert
        $this->assertEquals(6, $post->fresh()->views_count);
    }

    /** @test */
    public function it_handles_missing_featured_image_gracefully()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'title' => 'Post without image',
            'content' => 'Content here',
            'user_id' => $user->id
        ];

        // Act
        $post = $this->blogService->createPost($data);

        // Assert
        $this->assertNull($post->featured_image);
    }
}