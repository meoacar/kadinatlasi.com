<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ForumTopic;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminSearchController extends AdminController
{
    /**
     * Global arama sayfası
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $results = collect();
        $totalResults = 0;

        if (!empty($query) && strlen($query) >= 2) {
            $results = $this->performSearch($query, $type);
            $totalResults = $results->sum(fn($group) => $group['items']->count());
        }

        return view('admin.search.index', compact('query', 'type', 'results', 'totalResults'));
    }

    /**
     * AJAX arama API'si
     */
    public function api(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'type' => 'nullable|string|in:all,users,posts,products,topics,categories',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        $query = $request->get('q');
        $type = $request->get('type', 'all');
        $limit = $request->get('limit', 10);

        $results = $this->performSearch($query, $type, $limit);

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $results,
            'total' => $results->sum(fn($group) => $group['items']->count())
        ]);
    }

    /**
     * Arama işlemini gerçekleştir
     */
    private function performSearch(string $query, string $type = 'all', int $limit = 20): Collection
    {
        $results = collect();

        // Kullanıcı arama
        if ($type === 'all' || $type === 'users') {
            $users = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit($limit)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'title' => $user->name,
                        'subtitle' => $user->email,
                        'description' => "Üyelik: " . ($user->membership_type ?? 'Standart'),
                        'url' => route('admin.users.show', $user),
                        'status' => $user->is_active ? 'Aktif' : 'Pasif',
                        'status_class' => $user->is_active ? 'success' : 'danger',
                        'created_at' => $user->created_at->format('d.m.Y'),
                        'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null
                    ];
                });

            if ($users->isNotEmpty()) {
                $results->push([
                    'type' => 'users',
                    'title' => 'Kullanıcılar',
                    'icon' => 'users',
                    'items' => $users
                ]);
            }
        }

        // Blog yazıları arama
        if ($type === 'all' || $type === 'posts') {
            $posts = BlogPost::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->orWhere('excerpt', 'LIKE', "%{$query}%")
                ->with('author')
                ->limit($limit)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'subtitle' => 'Yazar: ' . ($post->author->name ?? 'Bilinmiyor'),
                        'description' => \Str::limit(strip_tags($post->excerpt ?? $post->content), 100),
                        'url' => route('admin.blog.show', $post),
                        'status' => $post->status === 'published' ? 'Yayında' : 'Taslak',
                        'status_class' => $post->status === 'published' ? 'success' : 'warning',
                        'created_at' => $post->created_at->format('d.m.Y'),
                        'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null
                    ];
                });

            if ($posts->isNotEmpty()) {
                $results->push([
                    'type' => 'posts',
                    'title' => 'Blog Yazıları',
                    'icon' => 'document-text',
                    'items' => $posts
                ]);
            }
        }

        // Ürün arama
        if ($type === 'all' || $type === 'products') {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhere('sku', 'LIKE', "%{$query}%")
                ->with('category')
                ->limit($limit)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->name,
                        'subtitle' => 'Kategori: ' . ($product->category->name ?? 'Kategorisiz'),
                        'description' => \Str::limit(strip_tags($product->description), 100),
                        'url' => route('admin.products.show', $product),
                        'status' => $product->is_active ? 'Aktif' : 'Pasif',
                        'status_class' => $product->is_active ? 'success' : 'danger',
                        'created_at' => $product->created_at->format('d.m.Y'),
                        'price' => $product->price ? number_format($product->price, 2) . ' ₺' : null,
                        'image' => $product->images && count($product->images) > 0 ? 
                                  asset('storage/' . $product->images[0]) : null
                    ];
                });

            if ($products->isNotEmpty()) {
                $results->push([
                    'type' => 'products',
                    'title' => 'Ürünler',
                    'icon' => 'shopping-bag',
                    'items' => $products
                ]);
            }
        }

        // Forum konuları arama
        if ($type === 'all' || $type === 'topics') {
            $topics = ForumTopic::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->with(['author', 'group'])
                ->limit($limit)
                ->get()
                ->map(function ($topic) {
                    return [
                        'id' => $topic->id,
                        'title' => $topic->title,
                        'subtitle' => 'Yazar: ' . ($topic->author->name ?? 'Bilinmiyor'),
                        'description' => \Str::limit(strip_tags($topic->content), 100),
                        'url' => route('admin.forum.topics.show', $topic),
                        'status' => $topic->is_active ? 'Aktif' : 'Pasif',
                        'status_class' => $topic->is_active ? 'success' : 'danger',
                        'created_at' => $topic->created_at->format('d.m.Y'),
                        'group' => $topic->group->name ?? 'Genel'
                    ];
                });

            if ($topics->isNotEmpty()) {
                $results->push([
                    'type' => 'topics',
                    'title' => 'Forum Konuları',
                    'icon' => 'chat-bubble-left-right',
                    'items' => $topics
                ]);
            }
        }

        // Kategori arama
        if ($type === 'all' || $type === 'categories') {
            $categories = ProductCategory::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->withCount('products')
                ->limit($limit)
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'title' => $category->name,
                        'subtitle' => $category->products_count . ' ürün',
                        'description' => \Str::limit($category->description, 100),
                        'url' => route('admin.categories.show', $category),
                        'status' => $category->is_active ? 'Aktif' : 'Pasif',
                        'status_class' => $category->is_active ? 'success' : 'danger',
                        'created_at' => $category->created_at->format('d.m.Y')
                    ];
                });

            if ($categories->isNotEmpty()) {
                $results->push([
                    'type' => 'categories',
                    'title' => 'Kategoriler',
                    'icon' => 'tag',
                    'items' => $categories
                ]);
            }
        }

        return $results;
    }

    /**
     * Arama önerilerini getir (autocomplete için)
     */
    public function suggestions(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:50',
            'limit' => 'nullable|integer|min:1|max:20'
        ]);

        $query = $request->get('q');
        $limit = $request->get('limit', 10);

        $suggestions = collect();

        // Kullanıcı önerileri
        $userSuggestions = User::where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->pluck('name')
            ->map(fn($name) => ['text' => $name, 'type' => 'user']);

        // Blog yazısı önerileri
        $postSuggestions = BlogPost::where('title', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->pluck('title')
            ->map(fn($title) => ['text' => $title, 'type' => 'post']);

        // Ürün önerileri
        $productSuggestions = Product::where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->pluck('name')
            ->map(fn($name) => ['text' => $name, 'type' => 'product']);

        $suggestions = $suggestions
            ->concat($userSuggestions)
            ->concat($postSuggestions)
            ->concat($productSuggestions)
            ->unique('text')
            ->take($limit);

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions->values()
        ]);
    }
}