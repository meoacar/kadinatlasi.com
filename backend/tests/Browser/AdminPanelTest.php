<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminPanelTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'email' => 'admin@kadinatlasi.com',
            'password' => bcrypt('password123'),
            'is_active' => true
        ]);
    }

    /** @test */
    public function admin_can_login_and_access_dashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->assertSee('Admin Panel')
                    ->type('email', 'admin@kadinatlasi.com')
                    ->type('password', 'password123')
                    ->press('Giriş Yap')
                    ->assertPathIs('/admin')
                    ->assertSee('Dashboard')
                    ->assertSee('Kullanıcılar')
                    ->assertSee('Blog Yazıları');
        });
    }

    /** @test */
    public function admin_login_shows_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->press('Giriş Yap')
                    ->assertSee('E-posta adresi gereklidir')
                    ->assertSee('Şifre gereklidir');
        });
    }

    /** @test */
    public function admin_can_navigate_through_menu()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin')
                    ->assertSee('Dashboard')
                    
                    // Navigate to Users
                    ->clickLink('Kullanıcılar')
                    ->assertPathIs('/admin/users')
                    ->assertSee('Kullanıcı Yönetimi')
                    
                    // Navigate to Blog
                    ->clickLink('Blog Yazıları')
                    ->assertPathIs('/admin/blog')
                    ->assertSee('Blog Yönetimi')
                    
                    // Navigate to Products
                    ->clickLink('Ürünler')
                    ->assertPathIs('/admin/products')
                    ->assertSee('Ürün Yönetimi');
        });
    }

    /** @test */
    public function admin_can_create_blog_post()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/blog')
                    ->clickLink('Yeni Yazı')
                    ->assertPathIs('/admin/blog/create')
                    ->type('title', 'Test Blog Post')
                    ->type('content', 'This is a test blog post content.')
                    ->select('status', 'published')
                    ->press('Kaydet')
                    ->assertPathIs('/admin/blog')
                    ->assertSee('Blog yazısı başarıyla oluşturuldu')
                    ->assertSee('Test Blog Post');
        });
    }

    /** @test */
    public function admin_can_edit_blog_post()
    {
        $post = BlogPost::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original content',
            'user_id' => $this->admin->id
        ]);

        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/blog')
                    ->click("@edit-post-{$post->id}")
                    ->assertPathIs("/admin/blog/{$post->id}/edit")
                    ->clear('title')
                    ->type('title', 'Updated Title')
                    ->clear('content')
                    ->type('content', 'Updated content')
                    ->press('Güncelle')
                    ->assertPathIs('/admin/blog')
                    ->assertSee('Blog yazısı başarıyla güncellendi')
                    ->assertSee('Updated Title');
        });
    }

    /** @test */
    public function admin_can_search_and_filter_users()
    {
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/users')
                    ->type('search', 'John')
                    ->press('Ara')
                    ->assertSee('John Doe')
                    ->assertDontSee('Jane Smith')
                    
                    // Clear search
                    ->clear('search')
                    ->press('Ara')
                    ->assertSee('John Doe')
                    ->assertSee('Jane Smith');
        });
    }

    /** @test */
    public function admin_can_toggle_user_status()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/users')
                    ->assertSee('Test User')
                    ->click("@toggle-status-{$user->id}")
                    ->waitForText('Kullanıcı durumu güncellendi')
                    ->assertSee('Pasif'); // Should show inactive status
        });
    }

    /** @test */
    public function admin_can_export_data()
    {
        User::factory()->count(5)->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/exports')
                    ->assertSee('Veri Dışa Aktarma')
                    ->select('format', 'csv')
                    ->press('Kullanıcıları Dışa Aktar')
                    ->waitFor('.alert-success', 5)
                    ->assertSee('Export başarıyla tamamlandı');
        });
    }

    /** @test */
    public function admin_can_manage_cache()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/cache')
                    ->assertSee('Cache ve Performans Yönetimi')
                    ->press('Dashboard Cache Temizle')
                    ->waitFor('.alert-success', 5)
                    ->assertSee('Cache başarıyla temizlendi');
        });
    }

    /** @test */
    public function admin_can_view_activities()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/activities')
                    ->assertSee('Admin Aktiviteleri')
                    ->assertSee('Aktivite Logları');
        });
    }

    /** @test */
    public function admin_can_logout()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin')
                    ->assertSee('Dashboard')
                    ->click('@user-menu')
                    ->clickLink('Çıkış Yap')
                    ->assertPathIs('/admin/login')
                    ->assertSee('Admin Panel');
        });
    }

    /** @test */
    public function responsive_design_works_on_mobile()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone size
                    ->loginAs($this->admin)
                    ->visit('/admin')
                    ->assertSee('Dashboard')
                    
                    // Mobile menu should be visible
                    ->click('@mobile-menu-button')
                    ->assertVisible('@mobile-menu')
                    ->clickLink('Kullanıcılar')
                    ->assertPathIs('/admin/users')
                    ->assertSee('Kullanıcı Yönetimi');
        });
    }

    /** @test */
    public function form_validation_works_in_browser()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/blog/create')
                    ->press('Kaydet') // Submit without filling required fields
                    ->assertSee('Başlık gereklidir')
                    ->assertSee('İçerik gereklidir')
                    
                    // Fill form correctly
                    ->type('title', 'Valid Title')
                    ->type('content', 'Valid content')
                    ->select('status', 'published')
                    ->press('Kaydet')
                    ->assertPathIs('/admin/blog')
                    ->assertSee('Blog yazısı başarıyla oluşturuldu');
        });
    }

    /** @test */
    public function admin_can_upload_files()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/blog/create')
                    ->type('title', 'Post with Image')
                    ->type('content', 'Content here')
                    ->attach('featured_image', __DIR__.'/fixtures/test-image.jpg')
                    ->select('status', 'published')
                    ->press('Kaydet')
                    ->assertPathIs('/admin/blog')
                    ->assertSee('Blog yazısı başarıyla oluşturuldu')
                    ->assertSee('Post with Image');
        });
    }

    /** @test */
    public function admin_can_use_bulk_actions()
    {
        $users = User::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($users) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/users')
                    ->check("@select-user-{$users[0]->id}")
                    ->check("@select-user-{$users[1]->id}")
                    ->select('bulk_action', 'deactivate')
                    ->press('Uygula')
                    ->waitFor('.alert-success', 5)
                    ->assertSee('Toplu işlem başarıyla uygulandı');
        });
    }

    /** @test */
    public function admin_can_use_quick_filters()
    {
        User::factory()->create(['created_at' => now()->subDays(1)]);
        User::factory()->create(['created_at' => now()->subDays(8)]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/users')
                    ->click('@quick-filter-this-week')
                    ->waitForReload()
                    ->assertQueryStringHas('quick_date', 'this_week');
        });
    }

    /** @test */
    public function admin_panel_handles_errors_gracefully()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/users/999999') // Non-existent user
                    ->assertSee('404')
                    ->orAssertSee('Kullanıcı bulunamadı');
        });
    }

    /** @test */
    public function admin_can_use_advanced_search()
    {
        User::factory()->create([
            'name' => 'Premium User',
            'membership_type' => 'premium',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                    ->visit('/admin/users')
                    ->click('@advanced-search-toggle')
                    ->select('membership', 'premium')
                    ->select('status', 'active')
                    ->press('Filtrele')
                    ->assertSee('Premium User');
        });
    }
}