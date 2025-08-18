# KadınAtlası Özel Admin Paneli - Tasarım Dokümanı

## Genel Bakış

KadınAtlası için Laravel tabanlı, modern ve responsive bir admin panel sistemi. Mevcut Eloquent modellerini kullanarak CRUD işlemleri, dashboard analytics ve sistem yönetimi sağlayan bir yönetim arayüzü.

## Mimari

### Üst Seviye Mimari
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Admin Routes  │────│  Controllers    │────│   Services      │
│   (/admin/*)    │    │                 │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                │                        │
                       ┌─────────────────┐    ┌─────────────────┐
                       │   Middleware    │    │   Repositories  │
                       │   (Auth, CSRF)  │    │                 │
                       └─────────────────┘    └─────────────────┘
                                                        │
                                              ┌─────────────────┐
                                              │ Eloquent Models │
                                              │ (User, Post...) │
                                              └─────────────────┘
```

### Teknoloji Yığını
- **Backend:** Laravel 11 (mevcut)
- **Frontend:** Blade Şablonları + Alpine.js + Tailwind CSS
- **Kimlik Doğrulama:** Laravel'in yerleşik Auth sistemi
- **Veritabanı:** MySQL (mevcut)
- **Dosya Depolama:** Laravel Storage (mevcut)

## Bileşenler ve Arayüzler

### 1. Kimlik Doğrulama Sistemi

#### AdminAuthController
```php
class AdminAuthController extends Controller
{
    public function showLogin()           // GET /admin/login
    public function login(Request $req)   // POST /admin/login  
    public function logout()              // POST /admin/logout
}
```

#### AdminAuthMiddleware
```php
class AdminAuthMiddleware
{
    // Checks if user is authenticated and has admin privileges
    // Redirects to login if not authenticated
    // Returns 403 if authenticated but not admin
}
```

### 2. Dashboard System

#### AdminDashboardController
```php
class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => $this->getDashboardStats(),
            'recentUsers' => $this->getRecentUsers(),
            'recentPosts' => $this->getRecentPosts(),
            'recentTopics' => $this->getRecentTopics()
        ]);
    }
    
    private function getDashboardStats(): array
    private function getRecentUsers(): Collection
    private function getRecentPosts(): Collection
    private function getRecentTopics(): Collection
}
```

### 3. User Management System

#### AdminUserController
```php
class AdminUserController extends Controller
{
    public function index(Request $request)     // List users with search/filter
    public function show(User $user)           // Show user details
    public function edit(User $user)           // Show edit form
    public function update(User $user, Request $request) // Update user
    public function toggleStatus(User $user)   // Activate/deactivate user
    public function destroy(User $user)        // Soft delete user
}
```

#### UserService
```php
class UserService
{
    public function getUsersWithFilters(array $filters): LengthAwarePaginator
    public function updateUser(User $user, array $data): User
    public function toggleUserStatus(User $user): User
    public function getUserStats(User $user): array
}
```

### 4. Content Management System

#### AdminBlogController
```php
class AdminBlogController extends Controller
{
    public function index(Request $request)    // List posts with filters
    public function create()                   // Show create form
    public function store(Request $request)    // Create new post
    public function show(BlogPost $post)       // Show post details
    public function edit(BlogPost $post)       // Show edit form
    public function update(BlogPost $post, Request $request) // Update post
    public function destroy(BlogPost $post)    // Delete post
    public function toggleStatus(BlogPost $post) // Publish/unpublish
}
```

#### BlogService
```php
class BlogService
{
    public function createPost(array $data): BlogPost
    public function updatePost(BlogPost $post, array $data): BlogPost
    public function handleImageUpload(UploadedFile $file): string
    public function getPostsWithFilters(array $filters): LengthAwarePaginator
}
```

### 5. Product Management System

#### AdminProductController
```php
class AdminProductController extends Controller
{
    public function index(Request $request)    // List products
    public function create()                   // Show create form
    public function store(Request $request)    // Create product
    public function show(Product $product)     // Show product details
    public function edit(Product $product)     // Show edit form
    public function update(Product $product, Request $request) // Update
    public function destroy(Product $product)  // Delete product
    public function manageImages(Product $product) // Manage product images
}
```

#### AdminCategoryController
```php
class AdminCategoryController extends Controller
{
    public function index()                    // List categories
    public function store(Request $request)    // Create category
    public function update(Category $category, Request $request) // Update
    public function destroy(Category $category) // Delete category
}
```

### 6. Forum Management System

#### AdminForumController
```php
class AdminForumController extends Controller
{
    public function topics(Request $request)   // List forum topics
    public function showTopic(ForumTopic $topic) // Show topic with posts
    public function moderatePost(ForumPost $post, Request $request) // Moderate
    public function groups()                   // Manage forum groups
    public function createGroup(Request $request) // Create forum group
    public function updateGroup(ForumGroup $group, Request $request) // Update
}
```

### 7. Settings Management System

#### AdminSettingsController
```php
class AdminSettingsController extends Controller
{
    public function siteSettings()            // Show site settings form
    public function updateSiteSettings(Request $request) // Update settings
    public function paymentSettings()         // Show payment settings
    public function updatePaymentSettings(Request $request) // Update payments
    public function footerLinks()             // Manage footer links
    public function updateFooterLinks(Request $request) // Update links
}
```

## Data Models

### Existing Models (No Changes Required)
- `User` - User management
- `BlogPost` - Blog content management
- `Product` - Product catalog
- `ProductCategory` - Product categorization
- `ForumTopic` - Forum discussions
- `ForumPost` - Forum replies
- `ForumGroup` - Forum groups
- `SiteSetting` - System configuration
- `FooterLink` - Footer navigation

### New Models (If Needed)

#### AdminActivity (Optional - for audit logging)
```php
class AdminActivity extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];
}
```

## Error Handling

### Exception Handling Strategy
```php
// In AdminController base class
class AdminController extends Controller
{
    protected function handleException(\Exception $e, string $action = 'perform this action')
    {
        Log::error('Admin panel error: ' . $e->getMessage(), [
            'user_id' => auth()->id(),
            'url' => request()->url(),
            'action' => $action
        ]);
        
        return back()->with('error', "Failed to {$action}. Please try again.");
    }
}
```

### Validation Rules
```php
// User validation
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email,' . $user->id,
'password' => 'nullable|min:8|confirmed',

// Blog post validation
'title' => 'required|string|max:255',
'content' => 'required|string',
'featured_image' => 'nullable|image|max:2048',
'status' => 'required|in:draft,published',

// Product validation
'name' => 'required|string|max:255',
'price' => 'required|numeric|min:0',
'category_id' => 'required|exists:product_categories,id',
'images.*' => 'image|max:2048'
```

## Testing Strategy

### Unit Tests
- Service classes (UserService, BlogService, etc.)
- Model relationships and methods
- Validation rules

### Feature Tests
- Authentication flow
- CRUD operations for each resource
- File upload functionality
- Permission checks

### Browser Tests (Laravel Dusk)
- Complete admin workflows
- Form submissions
- File uploads
- Responsive design

### Test Structure
```
tests/
├── Feature/
│   ├── Admin/
│   │   ├── AuthTest.php
│   │   ├── DashboardTest.php
│   │   ├── UserManagementTest.php
│   │   ├── BlogManagementTest.php
│   │   └── ProductManagementTest.php
├── Unit/
│   ├── Services/
│   │   ├── UserServiceTest.php
│   │   └── BlogServiceTest.php
└── Browser/
    └── AdminPanelTest.php
```

## Security Considerations

### Authentication & Authorization
- Laravel's built-in authentication
- Admin role checking middleware
- Session management with proper timeouts
- CSRF protection on all forms

### Input Validation & Sanitization
- Form Request classes for complex validation
- File upload validation (type, size, malware scanning)
- HTML purification for rich text content
- SQL injection prevention (Eloquent ORM)

### File Security
- Secure file upload handling
- Image optimization and resizing
- File type validation
- Storage outside web root

### Audit Logging
- Admin action logging
- Failed login attempt tracking
- Sensitive data change logging
- IP address and user agent tracking

## Performance Optimization

### Database Optimization
- Eager loading for relationships
- Database indexing on frequently queried columns
- Query optimization for large datasets
- Pagination for all list views

### Caching Strategy
- Settings caching (Redis/File)
- Query result caching for dashboard stats
- View caching for static content
- Cache invalidation on data updates

### Frontend Optimization
- Asset minification and compression
- Image optimization and lazy loading
- Alpine.js for lightweight interactivity
- Tailwind CSS purging for smaller bundle size

## Deployment Considerations

### Environment Configuration
- Separate admin panel configuration
- File upload limits configuration
- Session and cache configuration
- Error reporting and logging levels

### Security Headers
- Content Security Policy (CSP)
- X-Frame-Options
- X-Content-Type-Options
- Strict-Transport-Security

### Monitoring & Logging
- Application performance monitoring
- Error tracking and alerting
- Admin activity monitoring
- Resource usage tracking