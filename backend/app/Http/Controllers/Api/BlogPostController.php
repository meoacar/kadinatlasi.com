<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->latest('created_at');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by category slug
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->orderBy('views_count', 'desc');
                    break;
                case 'liked':
                    $query->orderBy('likes_count', 'desc');
                    break;
                default:
                    $query->latest('published_at');
            }
        }

        $posts = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function show($id)
    {
        $post = BlogPost::with(['user', 'category', 'comments'])
            ->where('status', 'published')
            ->findOrFail($id);

        $user = auth()->user();
        
        // Check premium access
        if (!$post->canUserAccess($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Bu içerik premium üyeler içindir',
                'premium_required' => true,
                'premium_type' => $post->premium_type
            ], 403);
        }

        // Increment view count
        $post->incrementViews();

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|string',
            'tags' => 'nullable|array',
            'status' => 'in:draft,published',
        ]);

        $post = BlogPost::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $request->featured_image,
            'tags' => $request->tags ?? [],
            'status' => $request->status ?? 'draft',
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog yazısı başarıyla oluşturuldu',
            'data' => $post->load(['user', 'category'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|string',
            'tags' => 'nullable|array',
            'status' => 'in:draft,published',
        ]);

        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $request->featured_image,
            'tags' => $request->tags ?? [],
            'status' => $request->status ?? 'draft',
            'published_at' => $request->status === 'published' && !$post->published_at ? now() : $post->published_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog yazısı başarıyla güncellendi',
            'data' => $post->load(['user', 'category'])
        ]);
    }

    public function destroy($id)
    {
        $post = BlogPost::where('user_id', auth()->id())->findOrFail($id);
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog yazısı başarıyla silindi'
        ]);
    }

    // Comments Methods
    public function getComments($id)
    {
        $post = BlogPost::findOrFail($id);
        $comments = $post->comments()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    public function storeComment($id, Request $request)
    {
        $post = BlogPost::findOrFail($id);
        
        $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'is_approved' => true, // Auto-approve for now
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'data' => $comment,
            'message' => 'Yorum başarıyla eklendi'
        ]);
    }

    // Likes Methods
    public function getLikeStatus($id)
    {
        $post = BlogPost::findOrFail($id);
        $userId = auth()->id();
        
        $isLiked = $post->likes()->where('user_id', $userId)->exists();

        return response()->json([
            'success' => true,
            'isLiked' => $isLiked,
            'likesCount' => $post->likes_count
        ]);
    }

    public function toggleLike($id)
    {
        $post = BlogPost::findOrFail($id);
        $userId = auth()->id();
        
        $existingLike = $post->likes()->where('user_id', $userId)->first();
        
        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $post->decrement('likes_count');
            $isLiked = false;
        } else {
            // Like
            $post->likes()->create(['user_id' => $userId]);
            $post->increment('likes_count');
            $isLiked = true;
        }

        return response()->json([
            'success' => true,
            'isLiked' => $isLiked,
            'likesCount' => $post->fresh()->likes_count
        ]);
    }

    // Stats Method
    public function getStats()
    {
        // Blog yazı sayıları
        $totalPosts = BlogPost::where('status', 'published')->count();
        $todayPosts = BlogPost::where('status', 'published')
            ->whereDate('published_at', today())
            ->count();
            
        // Görüntülenme sayıları - null değerleri 0 olarak say
        $totalViews = BlogPost::where('status', 'published')->sum('views_count') ?? 0;
        $todayViews = BlogPost::where('status', 'published')
            ->whereDate('updated_at', today())
            ->sum('views_count') ?? 0;
            
        // Aktif kategori sayısı
        $totalCategories = \App\Models\Category::where('is_active', true)->count();
        
        // Expert yazı sayısı (is_expert alanı yoksa 0 döndür)
        $expertPosts = 0;
        try {
            $expertPosts = BlogPost::where('status', 'published')
                ->whereHas('user', function($q) {
                    $q->where('is_expert', true);
                })->count();
        } catch (\Exception $e) {
            // is_expert alanı yoksa 0 döndür
            $expertPosts = 0;
        }
        
        // Toplam beğeni sayısı
        $totalLikes = BlogPost::where('status', 'published')->sum('likes_count') ?? 0;
        
        // Bu hafta yayınlanan yazılar
        $weeklyPosts = BlogPost::where('status', 'published')
            ->whereBetween('published_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'totalPosts' => $totalPosts,
                'todayPosts' => $todayPosts,
                'weeklyPosts' => $weeklyPosts,
                'totalViews' => $totalViews,
                'todayViews' => $todayViews,
                'totalLikes' => $totalLikes,
                'totalCategories' => $totalCategories,
                'expertPosts' => $expertPosts
            ]
        ]);
    }
}