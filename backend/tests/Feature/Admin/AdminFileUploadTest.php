<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminFileUploadTest extends TestCase
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

        Storage::fake('public');
    }

    /** @test */
    public function admin_can_upload_blog_featured_image()
    {
        $file = UploadedFile::fake()->image('featured.jpg', 800, 600);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $post = BlogPost::latest()->first();
        $this->assertNotNull($post->featured_image);
        
        Storage::disk('public')->assertExists($post->featured_image);
    }

    /** @test */
    public function admin_can_upload_product_images()
    {
        $files = [
            UploadedFile::fake()->image('product1.jpg', 800, 600),
            UploadedFile::fake()->image('product2.jpg', 800, 600)
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Product',
                'description' => 'Test description',
                'price' => 99.99,
                'category_id' => 1,
                'images' => $files
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $product = Product::latest()->first();
        $this->assertNotNull($product->images);
        
        $images = json_decode($product->images, true);
        $this->assertCount(2, $images);
        
        foreach ($images as $image) {
            Storage::disk('public')->assertExists($image);
        }
    }

    /** @test */
    public function admin_can_upload_user_avatar()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.users.update', $user), [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $file
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        
        Storage::disk('public')->assertExists($user->avatar);
    }

    /** @test */
    public function file_upload_validates_file_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file
            ]);

        $response->assertSessionHasErrors(['featured_image']);
    }

    /** @test */
    public function file_upload_validates_file_size()
    {
        // Create a file larger than allowed (assuming 2MB limit)
        $file = UploadedFile::fake()->image('large.jpg')->size(3000); // 3MB

        $response = $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file
            ]);

        $response->assertSessionHasErrors(['featured_image']);
    }

    /** @test */
    public function admin_can_delete_uploaded_files()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        
        // First upload a file
        $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file
            ]);

        $post = BlogPost::latest()->first();
        $imagePath = $post->featured_image;
        
        Storage::disk('public')->assertExists($imagePath);

        // Delete the post (should also delete the file)
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.blog.destroy', $post));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Storage::disk('public')->assertMissing($imagePath);
    }

    /** @test */
    public function admin_can_replace_uploaded_files()
    {
        $originalFile = UploadedFile::fake()->image('original.jpg');
        
        // Upload original file
        $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $originalFile
            ]);

        $post = BlogPost::latest()->first();
        $originalPath = $post->featured_image;
        
        Storage::disk('public')->assertExists($originalPath);

        // Replace with new file
        $newFile = UploadedFile::fake()->image('new.jpg');
        
        $response = $this->actingAs($this->admin)
            ->put(route('admin.blog.update', $post), [
                'title' => $post->title,
                'content' => $post->content,
                'status' => $post->status,
                'featured_image' => $newFile
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $post->refresh();
        $newPath = $post->featured_image;
        
        $this->assertNotEquals($originalPath, $newPath);
        Storage::disk('public')->assertExists($newPath);
        Storage::disk('public')->assertMissing($originalPath);
    }

    /** @test */
    public function admin_can_upload_multiple_files()
    {
        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
            UploadedFile::fake()->image('image3.jpg')
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.media.upload'), [
                'files' => $files,
                'directory' => 'gallery'
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'uploaded',
            'results' => [
                '*' => ['success', 'path', 'url']
            ]
        ]);

        foreach ($files as $file) {
            // Check that files were uploaded (exact path may vary)
            $this->assertTrue(
                count(Storage::disk('public')->files('gallery')) >= count($files)
            );
        }
    }

    /** @test */
    public function file_upload_creates_thumbnails_for_images()
    {
        $file = UploadedFile::fake()->image('test.jpg', 1200, 800);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $post = BlogPost::latest()->first();
        $imagePath = $post->featured_image;
        
        // Check main image exists
        Storage::disk('public')->assertExists($imagePath);
        
        // Check thumbnail exists
        $thumbnailPath = str_replace('blog/', 'blog/thumbnails/', $imagePath);
        Storage::disk('public')->assertExists($thumbnailPath);
    }

    /** @test */
    public function file_upload_handles_duplicate_names()
    {
        $file1 = UploadedFile::fake()->image('test.jpg');
        $file2 = UploadedFile::fake()->image('test.jpg');

        // Upload first file
        $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post 1',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file1
            ]);

        // Upload second file with same name
        $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post 2',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file2
            ]);

        $posts = BlogPost::latest()->take(2)->get();
        
        // Both files should exist with different paths
        $this->assertNotEquals($posts[0]->featured_image, $posts[1]->featured_image);
        Storage::disk('public')->assertExists($posts[0]->featured_image);
        Storage::disk('public')->assertExists($posts[1]->featured_image);
    }

    /** @test */
    public function file_upload_sanitizes_file_names()
    {
        $file = UploadedFile::fake()->image('test file with spaces & special chars!.jpg');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Test Blog Post',
                'content' => 'Test content',
                'status' => 'published',
                'featured_image' => $file
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $post = BlogPost::latest()->first();
        $imagePath = $post->featured_image;
        
        // File name should be sanitized
        $this->assertStringNotContainsString(' ', $imagePath);
        $this->assertStringNotContainsString('&', $imagePath);
        $this->assertStringNotContainsString('!', $imagePath);
        
        Storage::disk('public')->assertExists($imagePath);
    }

    /** @test */
    public function admin_can_view_media_library()
    {
        // Upload some files first
        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg')
        ];

        foreach ($files as $file) {
            Storage::disk('public')->putFile('uploads', $file);
        }

        $response = $this->actingAs($this->admin)
            ->get(route('admin.media.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.media.index');
        $response->assertViewHas('files');
    }
}