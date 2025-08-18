<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_validation_rules_work_correctly()
    {
        // Valid data
        $validData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => true,
            'membership_type' => 'premium'
        ];

        $validator = Validator::make($validData, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'is_active' => 'boolean',
            'membership_type' => 'nullable|in:normal,premium,vip'
        ]);

        $this->assertFalse($validator->fails());

        // Invalid data - missing required fields
        $invalidData = [
            'email' => 'invalid-email',
            'password' => '123', // too short
        ];

        $validator = Validator::make($invalidData, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function blog_post_validation_rules_work_correctly()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Valid data
        $validData = [
            'title' => 'Test Blog Post',
            'content' => 'This is the content of the blog post.',
            'status' => 'published',
            'user_id' => $user->id,
            'category_id' => $category->id
        ];

        $validator = Validator::make($validData, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $this->assertFalse($validator->fails());

        // Invalid data
        $invalidData = [
            'title' => '', // empty
            'content' => '', // empty
            'status' => 'invalid_status',
            'user_id' => 999999, // non-existent
            'category_id' => 999999 // non-existent
        ];

        $validator = Validator::make($invalidData, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $this->assertTrue($validator->fails());
        $errors = $validator->errors()->toArray();
        $this->assertArrayHasKey('title', $errors);
        $this->assertArrayHasKey('content', $errors);
        $this->assertArrayHasKey('status', $errors);
        $this->assertArrayHasKey('user_id', $errors);
        $this->assertArrayHasKey('category_id', $errors);
    }

    /** @test */
    public function product_validation_rules_work_correctly()
    {
        $category = Category::factory()->create();

        // Valid data
        $validData = [
            'name' => 'Test Product',
            'description' => 'Product description',
            'price' => 99.99,
            'sale_price' => 79.99,
            'sku' => 'TEST-001',
            'category_id' => $category->id,
            'is_active' => true,
            'in_stock' => true
        ];

        $validator = Validator::make($validData, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'nullable|string|unique:products,sku',
            'category_id' => 'required|exists:product_categories,id',
            'is_active' => 'boolean',
            'in_stock' => 'boolean'
        ]);

        $this->assertFalse($validator->fails());

        // Invalid data
        $invalidData = [
            'name' => '', // empty
            'price' => -10, // negative
            'sale_price' => 150, // higher than price
            'category_id' => 999999 // non-existent
        ];

        $validator = Validator::make($invalidData, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:product_categories,id'
        ]);

        $this->assertTrue($validator->fails());
        $errors = $validator->errors()->toArray();
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('price', $errors);
        $this->assertArrayHasKey('sale_price', $errors);
        $this->assertArrayHasKey('category_id', $errors);
    }

    /** @test */
    public function export_validation_rules_work_correctly()
    {
        // Valid export data
        $validData = [
            'format' => 'csv',
            'date_from' => '2024-01-01',
            'date_to' => '2024-12-31',
            'status' => 'active',
            'export_types' => ['users', 'blog', 'products']
        ];

        $validator = Validator::make($validData, [
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:active,inactive,published,draft',
            'export_types' => 'nullable|array',
            'export_types.*' => 'in:users,blog,products,forum,activities'
        ]);

        $this->assertFalse($validator->fails());

        // Invalid export data
        $invalidData = [
            'format' => 'invalid_format',
            'date_from' => '2024-12-31',
            'date_to' => '2024-01-01', // before date_from
            'export_types' => ['invalid_type']
        ];

        $validator = Validator::make($invalidData, [
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'export_types' => 'nullable|array',
            'export_types.*' => 'in:users,blog,products,forum,activities'
        ]);

        $this->assertTrue($validator->fails());
        $errors = $validator->errors()->toArray();
        $this->assertArrayHasKey('format', $errors);
        $this->assertArrayHasKey('date_to', $errors);
        $this->assertArrayHasKey('export_types.0', $errors);
    }

    /** @test */
    public function file_upload_validation_rules_work_correctly()
    {
        // Test image upload validation
        $imageData = [
            'image' => 'fake_image.jpg'
        ];

        // Since we can't easily test actual file uploads in unit tests,
        // we'll test the validation rules structure
        $rules = [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        $validator = Validator::make($imageData, $rules);
        // This will fail because it's not a real file, but we can check the rules
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('image', $validator->errors()->toArray());
    }

    /** @test */
    public function settings_validation_rules_work_correctly()
    {
        // Valid settings data
        $validData = [
            'site_name' => 'KadınAtlası',
            'site_description' => 'Kadınlar için platform',
            'contact_email' => 'contact@kadinatlasi.com',
            'social_facebook' => 'https://facebook.com/kadinatlasi',
            'social_instagram' => 'https://instagram.com/kadinatlasi',
            'maintenance_mode' => false
        ];

        $validator = Validator::make($validData, [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'maintenance_mode' => 'boolean'
        ]);

        $this->assertFalse($validator->fails());

        // Invalid settings data
        $invalidData = [
            'site_name' => '', // empty
            'contact_email' => 'invalid-email',
            'social_facebook' => 'not-a-url',
            'maintenance_mode' => 'not-boolean'
        ];

        $validator = Validator::make($invalidData, [
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'social_facebook' => 'nullable|url',
            'maintenance_mode' => 'boolean'
        ]);

        $this->assertTrue($validator->fails());
        $errors = $validator->errors()->toArray();
        $this->assertArrayHasKey('site_name', $errors);
        $this->assertArrayHasKey('contact_email', $errors);
        $this->assertArrayHasKey('social_facebook', $errors);
        $this->assertArrayHasKey('maintenance_mode', $errors);
    }

    /** @test */
    public function unique_validation_works_with_updates()
    {
        // Create a user
        $user = User::factory()->create(['email' => 'existing@example.com']);

        // Test updating same user (should pass)
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'existing@example.com' // same email
        ];

        $validator = Validator::make($updateData, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $this->assertFalse($validator->fails());

        // Test updating with another user's email (should fail)
        $anotherUser = User::factory()->create(['email' => 'another@example.com']);

        $invalidUpdateData = [
            'name' => 'Updated Name',
            'email' => 'another@example.com' // another user's email
        ];

        $validator = Validator::make($invalidUpdateData, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }
}