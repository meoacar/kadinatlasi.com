<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogService
{
    /**
     * Filtrelere göre blog yazılarını getir
     */
    public function getPostsWithFilters(array $filters): LengthAwarePaginator
    {
        $query = BlogPost::with(['user', 'category']);

        // Arama filtresi
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Durum filtresi
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Kategori filtresi
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Yazar filtresi
        if (!empty($filters['author_id'])) {
            $query->where('user_id', $filters['author_id']);
        }

        // Tarih filtreleri
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sıralama
        $sortField = $filters['sort'] ?? 'created_at';
        $sortDirection = $filters['direction'] ?? 'desc';
        
        $allowedSortFields = ['title', 'created_at', 'published_at', 'views', 'status'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Sayfalama
        $perPage = min($filters['per_page'] ?? 15, 100);
        
        return $query->paginate($perPage);
    }

    /**
     * Yeni blog yazısı oluştur
     */
    public function createPost(array $data): BlogPost
    {
        DB::beginTransaction();
        
        try {
            // Slug benzersizliğini kontrol et
            $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);
            
            // Preview token oluştur
            $data['preview_token'] = Str::random(32);
            
            $post = BlogPost::create($data);
            
            // SEO verilerini otomatik oluştur
            $this->generateSeoData($post);
            
            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Blog yazısı güncelle
     */
    public function updatePost(BlogPost $post, array $data): BlogPost
    {
        DB::beginTransaction();
        
        try {
            // Slug benzersizliğini kontrol et (mevcut post hariç)
            if (isset($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['slug'], $post->id);
            }
            
            $post->update($data);
            
            // SEO verilerini güncelle
            if (empty($post->meta_title) || empty($post->meta_description)) {
                $this->generateSeoData($post);
            }
            
            DB::commit();
            return $post->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Görsel yükleme işlemi
     */
    public function handleImageUpload(UploadedFile $file): string
    {
        // Dosya adını oluştur
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'blog/' . $filename;
        
        // Görseli yeniden boyutlandır ve optimize et
        $image = Image::make($file);
        
        // Ana görsel (max 1200px genişlik)
        $image->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Kaliteyi optimize et
        $image->encode('jpg', 85);
        
        // Storage'a kaydet
        Storage::disk('public')->put($path, $image->stream());
        
        // Thumbnail oluştur (300x200)
        $thumbnailPath = 'blog/thumbnails/' . $filename;
        $thumbnail = Image::make($file);
        $thumbnail->fit(300, 200);
        $thumbnail->encode('jpg', 80);
        Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());
        
        return $path;
    }

    /**
     * Blog yazısı istatistikleri
     */
    public function getPostStats(BlogPost $post): array
    {
        return [
            'views' => $post->views ?? 0,
            'comments_count' => $post->comments()->count() ?? 0,
            'likes_count' => $this->getLikesCount($post),
            'shares_count' => $this->getSharesCount($post),
            'reading_time' => $this->calculateReadingTime($post->content),
            'word_count' => str_word_count(strip_tags($post->content)),
            'character_count' => strlen(strip_tags($post->content)),
            'seo_score' => $this->calculateSeoScore($post),
        ];
    }

    /**
     * Blog genel istatistikleri
     */
    public function getBlogStats(): array
    {
        $totalPosts = BlogPost::count();
        $publishedPosts = BlogPost::where('status', 'published')->count();
        $draftPosts = BlogPost::where('status', 'draft')->count();
        
        return [
            'overview' => [
                'total_posts' => $totalPosts,
                'published_posts' => $publishedPosts,
                'draft_posts' => $draftPosts,
                'featured_posts' => BlogPost::where('is_featured', true)->count(),
                'posts_this_month' => BlogPost::whereMonth('created_at', now()->month)->count(),
                'total_views' => BlogPost::sum('views') ?? 0,
                'avg_views_per_post' => $totalPosts > 0 ? round((BlogPost::sum('views') ?? 0) / $totalPosts, 2) : 0,
            ],
            'categories' => $this->getCategoryStats(),
            'authors' => $this->getAuthorStats(),
            'monthly_posts' => $this->getMonthlyPostsData(),
            'popular_posts' => $this->getPopularPosts(),
            'recent_posts' => $this->getRecentPosts(),
        ];
    }

    /**
     * Blog yazılarını dışa aktar
     */
    public function exportPosts(array $filters = []): array
    {
        $posts = $this->getPostsWithFilters(array_merge($filters, ['per_page' => 10000]));
        
        return $posts->map(function ($post) {
            return [
                'ID' => $post->id,
                'Başlık' => $post->title,
                'Slug' => $post->slug,
                'Durum' => $post->status === 'published' ? 'Yayında' : 'Taslak',
                'Kategori' => $post->category->name ?? 'Kategori Yok',
                'Yazar' => $post->user->name ?? 'Bilinmeyen',
                'Görüntülenme' => $post->views ?? 0,
                'Öne Çıkan' => $post->is_featured ? 'Evet' : 'Hayır',
                'Oluşturma Tarihi' => $post->created_at->format('d.m.Y H:i'),
                'Yayın Tarihi' => $post->published_at?->format('d.m.Y H:i') ?? '-',
            ];
        })->toArray();
    }

    /**
     * İçerik önerileri
     */
    public function getContentSuggestions(BlogPost $post): array
    {
        $suggestions = [];
        
        // SEO önerileri
        if (empty($post->meta_title)) {
            $suggestions[] = [
                'type' => 'seo',
                'title' => 'Meta Başlık Eksik',
                'description' => 'SEO için meta başlık eklemeniz önerilir.',
                'priority' => 'high'
            ];
        }
        
        if (empty($post->meta_description)) {
            $suggestions[] = [
                'type' => 'seo',
                'title' => 'Meta Açıklama Eksik',
                'description' => 'SEO için meta açıklama eklemeniz önerilir.',
                'priority' => 'high'
            ];
        }
        
        // İçerik önerileri
        $wordCount = str_word_count(strip_tags($post->content));
        if ($wordCount < 300) {
            $suggestions[] = [
                'type' => 'content',
                'title' => 'İçerik Kısa',
                'description' => 'SEO için en az 300 kelime önerilir. Mevcut: ' . $wordCount,
                'priority' => 'medium'
            ];
        }
        
        if (empty($post->featured_image)) {
            $suggestions[] = [
                'type' => 'visual',
                'title' => 'Öne Çıkan Görsel Yok',
                'description' => 'Sosyal medya paylaşımları için öne çıkan görsel ekleyin.',
                'priority' => 'medium'
            ];
        }
        
        if (empty($post->excerpt)) {
            $suggestions[] = [
                'type' => 'content',
                'title' => 'Özet Eksik',
                'description' => 'Yazı listelerinde gösterilecek özet metni ekleyin.',
                'priority' => 'low'
            ];
        }
        
        return $suggestions;
    }

    // Private helper methods

    /**
     * Benzersiz slug oluştur
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $query = BlogPost::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * SEO verilerini otomatik oluştur
     */
    private function generateSeoData(BlogPost $post): void
    {
        $updates = [];
        
        // Meta title oluştur
        if (empty($post->meta_title)) {
            $updates['meta_title'] = Str::limit($post->title, 55);
        }
        
        // Meta description oluştur
        if (empty($post->meta_description)) {
            $content = strip_tags($post->content);
            $updates['meta_description'] = Str::limit($content, 155);
        }
        
        if (!empty($updates)) {
            $post->update($updates);
        }
    }

    /**
     * Okuma süresi hesapla (dakika)
     */
    private function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $wordsPerMinute = 200; // Ortalama okuma hızı
        
        return max(1, ceil($wordCount / $wordsPerMinute));
    }

    /**
     * SEO skoru hesapla (0-100)
     */
    private function calculateSeoScore(BlogPost $post): int
    {
        $score = 0;
        
        // Başlık kontrolü (20 puan)
        if (!empty($post->title) && strlen($post->title) >= 10) {
            $score += 20;
        }
        
        // Meta title kontrolü (15 puan)
        if (!empty($post->meta_title) && strlen($post->meta_title) <= 60) {
            $score += 15;
        }
        
        // Meta description kontrolü (15 puan)
        if (!empty($post->meta_description) && strlen($post->meta_description) <= 160) {
            $score += 15;
        }
        
        // İçerik uzunluğu (20 puan)
        $wordCount = str_word_count(strip_tags($post->content));
        if ($wordCount >= 300) {
            $score += 20;
        } elseif ($wordCount >= 150) {
            $score += 10;
        }
        
        // Öne çıkan görsel (10 puan)
        if (!empty($post->featured_image)) {
            $score += 10;
        }
        
        // Kategori (10 puan)
        if ($post->category_id) {
            $score += 10;
        }
        
        // Özet (10 puan)
        if (!empty($post->excerpt)) {
            $score += 10;
        }
        
        return min(100, $score);
    }

    /**
     * Beğeni sayısı (gelecekte implement edilecek)
     */
    private function getLikesCount(BlogPost $post): int
    {
        // Like sistemi implement edildiğinde burası doldurulacak
        return 0;
    }

    /**
     * Paylaşım sayısı (gelecekte implement edilecek)
     */
    private function getSharesCount(BlogPost $post): int
    {
        // Share tracking sistemi implement edildiğinde burası doldurulacak
        return 0;
    }

    /**
     * Kategori istatistikleri
     */
    private function getCategoryStats(): array
    {
        return Category::withCount(['blogPosts'])
            ->orderBy('blog_posts_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'posts_count' => $category->blog_posts_count,
                    'percentage' => BlogPost::count() > 0 ? 
                        round(($category->blog_posts_count / BlogPost::count()) * 100, 1) : 0
                ];
            })
            ->toArray();
    }

    /**
     * Yazar istatistikleri
     */
    private function getAuthorStats(): array
    {
        return BlogPost::select('user_id', DB::raw('count(*) as posts_count'), DB::raw('sum(views) as total_views'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($stat) {
                return [
                    'name' => $stat->user->name ?? 'Bilinmeyen',
                    'posts_count' => $stat->posts_count,
                    'total_views' => $stat->total_views ?? 0,
                    'avg_views' => $stat->posts_count > 0 ? 
                        round(($stat->total_views ?? 0) / $stat->posts_count, 1) : 0
                ];
            })
            ->toArray();
    }

    /**
     * Aylık yazı verileri (son 12 ay)
     */
    private function getMonthlyPostsData(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = BlogPost::whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month)
                           ->count();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        return $data;
    }

    /**
     * Popüler yazılar
     */
    private function getPopularPosts(int $limit = 10): array
    {
        return BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'views' => $post->views ?? 0,
                    'author' => $post->user->name ?? 'Bilinmeyen',
                    'category' => $post->category->name ?? 'Kategori Yok',
                    'published_at' => $post->published_at?->format('d.m.Y'),
                ];
            })
            ->toArray();
    }

    /**
     * Son yazılar
     */
    private function getRecentPosts(int $limit = 10): array
    {
        return BlogPost::with(['user', 'category'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'status' => $post->status,
                    'author' => $post->user->name ?? 'Bilinmeyen',
                    'category' => $post->category->name ?? 'Kategori Yok',
                    'created_at' => $post->created_at->format('d.m.Y H:i'),
                ];
            })
            ->toArray();
    }
}