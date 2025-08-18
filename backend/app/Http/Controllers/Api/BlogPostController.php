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
            ->published()
            ->latest('published_at');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
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
            ->published()
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
}