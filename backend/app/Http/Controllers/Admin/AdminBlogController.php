<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use App\Models\Category;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminBlogController extends AdminController
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        parent::__construct();
        $this->blogService = $blogService;
    }

    /**
     * Blog yazıları listesi
     */
    public function index(Request $request)
    {
        try {
            $filters = $this->getPaginationData($request);
            $filters['status'] = $request->get('status');
            $filters['category_id'] = $request->get('category_id');
            $filters['author_id'] = $request->get('author_id');
            $filters['date_from'] = $request->get('date_from');
            $filters['date_to'] = $request->get('date_to');

            $posts = $this->blogService->getPostsWithFilters($filters);
            $categories = Category::orderBy('name')->get();
            
            $stats = [
                'total' => BlogPost::count(),
                'published' => BlogPost::where('status', 'published')->count(),
                'draft' => BlogPost::where('status', 'draft')->count(),
                'today' => BlogPost::whereDate('created_at', today())->count(),
            ];

            return view('admin.blog.index', compact('posts', 'categories', 'stats', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load blog posts');
        }
    }

    /**
     * Blog yazısı oluşturma formu
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.blog.create', compact('categories'));
    }

    /**
     * Yeni blog yazısı oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Başlık gereklidir.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
            'content.required' => 'İçerik gereklidir.',
            'excerpt.max' => 'Özet en fazla 500 karakter olabilir.',
            'category_id.exists' => 'Seçilen kategori geçersiz.',
            'featured_image.image' => 'Öne çıkan görsel bir resim dosyası olmalıdır.',
            'featured_image.max' => 'Görsel boyutu en fazla 2MB olabilir.',
            'status.required' => 'Durum seçimi gereklidir.',
            'status.in' => 'Geçersiz durum seçimi.',
            'meta_title.max' => 'Meta başlık en fazla 255 karakter olabilir.',
            'meta_description.max' => 'Meta açıklama en fazla 500 karakter olabilir.',
        ]);

        try {
            $postData = $request->only([
                'title', 'slug', 'content', 'excerpt', 'category_id', 
                'status', 'meta_title', 'meta_description', 'is_featured'
            ]);

            // Slug oluştur
            if (empty($postData['slug'])) {
                $postData['slug'] = Str::slug($postData['title']);
            }

            // Yazar bilgisi
            $postData['user_id'] = auth()->id();

            // Yayın tarihi
            if ($request->filled('published_at')) {
                $postData['published_at'] = $request->published_at;
            } elseif ($postData['status'] === 'published') {
                $postData['published_at'] = now();
            }

            // Tags işleme
            if ($request->filled('tags')) {
                $postData['tags'] = array_map('trim', explode(',', $request->tags));
            }

            $post = $this->blogService->createPost($postData);

            // Öne çıkan görsel yükle
            if ($request->hasFile('featured_image')) {
                $imagePath = $this->blogService->handleImageUpload($request->file('featured_image'));
                $post->update(['featured_image' => $imagePath]);
            }

            return $this->successResponse('Blog yazısı başarıyla oluşturuldu.')
                ->with('redirect', route('admin.blog.show', $post));
        } catch (\Exception $e) {
            return $this->handleException($e, 'create blog post');
        }
    }

    /**
     * Blog yazısı detayları
     */
    public function show(BlogPost $post)
    {
        try {
            $post->load(['user', 'category', 'comments']);
            
            $stats = $this->blogService->getPostStats($post);
            
            return view('admin.blog.show', compact('post', 'stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load blog post details');
        }
    }

    /**
     * Blog yazısı düzenleme formu
     */
    public function edit(BlogPost $post)
    {
        $categories = Category::orderBy('name')->get();
        $post->load('category');
        
        return view('admin.blog.edit', compact('post', 'categories'));
    }

    /**
     * Blog yazısı güncelle
     */
    public function update(Request $request, BlogPost $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_posts')->ignore($post->id)],
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Başlık gereklidir.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
            'content.required' => 'İçerik gereklidir.',
            'featured_image.image' => 'Öne çıkan görsel bir resim dosyası olmalıdır.',
            'featured_image.max' => 'Görsel boyutu en fazla 2MB olabilir.',
            'status.required' => 'Durum seçimi gereklidir.',
        ]);

        try {
            $postData = $request->only([
                'title', 'slug', 'content', 'excerpt', 'category_id', 
                'status', 'meta_title', 'meta_description', 'is_featured'
            ]);

            // Slug güncelle
            if (empty($postData['slug'])) {
                $postData['slug'] = Str::slug($postData['title']);
            }

            // Yayın tarihi güncelle
            if ($request->filled('published_at')) {
                $postData['published_at'] = $request->published_at;
            } elseif ($postData['status'] === 'published' && !$post->published_at) {
                $postData['published_at'] = now();
            }

            // Tags işleme
            if ($request->filled('tags')) {
                $postData['tags'] = array_map('trim', explode(',', $request->tags));
            }

            $post = $this->blogService->updatePost($post, $postData);

            // Öne çıkan görsel güncelle
            if ($request->hasFile('featured_image')) {
                $imagePath = $this->blogService->handleImageUpload($request->file('featured_image'));
                $post->update(['featured_image' => $imagePath]);
            }

            return $this->successResponse('Blog yazısı başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update blog post');
        }
    }

    /**
     * Blog yazısı sil
     */
    public function destroy(BlogPost $post)
    {
        try {
            // Öne çıkan görseli sil
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }

            $post->delete();

            return $this->successResponse('Blog yazısı başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete blog post');
        }
    }

    /**
     * Blog yazısı durumunu değiştir (yayınla/taslak)
     */
    public function toggleStatus(BlogPost $post)
    {
        try {
            $newStatus = $post->status === 'published' ? 'draft' : 'published';
            
            $updateData = ['status' => $newStatus];
            
            // Yayınlanıyorsa tarih ekle
            if ($newStatus === 'published' && !$post->published_at) {
                $updateData['published_at'] = now();
            }
            
            $post->update($updateData);
            
            $statusText = $newStatus === 'published' ? 'yayınlandı' : 'taslağa alındı';
            return $this->successResponse("Blog yazısı başarıyla {$statusText}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle post status');
        }
    }

    /**
     * Blog yazılarını toplu işlem
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,draft,delete,feature,unfeature',
            'posts' => 'required|array|min:1',
            'posts.*' => 'exists:blog_posts,id',
        ]);

        try {
            $postIds = $request->posts;
            $action = $request->action;
            
            $posts = BlogPost::whereIn('id', $postIds)->get();
            
            if ($posts->isEmpty()) {
                return $this->errorResponse('İşlem yapılacak blog yazısı bulunamadı.');
            }

            $count = 0;
            switch ($action) {
                case 'publish':
                    $count = $posts->where('status', 'draft')->count();
                    BlogPost::whereIn('id', $postIds)
                           ->where('status', 'draft')
                           ->update([
                               'status' => 'published',
                               'published_at' => now()
                           ]);
                    break;
                    
                case 'draft':
                    $count = $posts->where('status', 'published')->count();
                    BlogPost::whereIn('id', $postIds)->update(['status' => 'draft']);
                    break;
                    
                case 'feature':
                    $count = $posts->where('is_featured', false)->count();
                    BlogPost::whereIn('id', $postIds)->update(['is_featured' => true]);
                    break;
                    
                case 'unfeature':
                    $count = $posts->where('is_featured', true)->count();
                    BlogPost::whereIn('id', $postIds)->update(['is_featured' => false]);
                    break;
                    
                case 'delete':
                    $count = $posts->count();
                    // Görselleri sil
                    foreach ($posts as $post) {
                        if ($post->featured_image) {
                            \Storage::disk('public')->delete($post->featured_image);
                        }
                    }
                    BlogPost::whereIn('id', $postIds)->delete();
                    break;
            }

            $actionText = match($action) {
                'publish' => 'yayınlandı',
                'draft' => 'taslağa alındı',
                'feature' => 'öne çıkarıldı',
                'unfeature' => 'öne çıkarmadan kaldırıldı',
                'delete' => 'silindi',
            };

            return $this->successResponse("{$count} blog yazısı başarıyla {$actionText}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'perform bulk action');
        }
    }

    /**
     * Blog yazısı görselini sil
     */
    public function deleteImage(BlogPost $post)
    {
        try {
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
                $post->update(['featured_image' => null]);
                
                return $this->successResponse('Görsel başarıyla silindi.');
            }
            
            return $this->errorResponse('Silinecek görsel bulunamadı.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete image');
        }
    }

    /**
     * Blog yazısı önizleme
     */
    public function preview(BlogPost $post)
    {
        // Frontend preview URL'si döndür
        $previewUrl = config('app.frontend_url') . '/blog/preview/' . $post->slug . '?token=' . $post->preview_token;
        
        return redirect($previewUrl);
    }

    /**
     * Blog istatistikleri
     */
    public function stats()
    {
        try {
            $stats = $this->blogService->getBlogStats();
            
            return view('admin.blog.stats', compact('stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load blog stats');
        }
    }

    /**
     * Blog yazılarını dışa aktar
     */
    public function export(Request $request)
    {
        try {
            $filters = $request->only(['status', 'category_id', 'author_id', 'date_from', 'date_to']);
            
            $posts = $this->blogService->exportPosts($filters);
            
            $filename = 'blog_posts_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];
            
            $callback = function() use ($posts) {
                $file = fopen('php://output', 'w');
                
                // CSV başlıkları
                fputcsv($file, [
                    'ID', 'Başlık', 'Slug', 'Durum', 'Kategori', 'Yazar', 
                    'Görüntülenme', 'Öne Çıkan', 'Oluşturma Tarihi', 'Yayın Tarihi'
                ]);
                
                // Veri satırları
                foreach ($posts as $post) {
                    fputcsv($file, $post);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return $this->handleException($e, 'export blog posts');
        }
    }
}