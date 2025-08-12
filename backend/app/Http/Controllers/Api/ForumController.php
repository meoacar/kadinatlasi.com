<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\ForumPost;
use App\Models\ForumCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function topics(Request $request)
    {
        $query = ForumTopic::with(['user', 'forumCategory', 'lastPostUser'])
            ->active()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_post_at', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->has('category_id')) {
            $query->where('forum_category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $topics = $query->paginate(20);

        // Transform data for frontend
        $transformedTopics = $topics->getCollection()->map(function ($topic) {
            return [
                'id' => $topic->id,
                'title' => $topic->title,
                'excerpt' => substr(strip_tags($topic->content), 0, 150) . '...',
                'category' => $topic->forumCategory ? $topic->forumCategory->name : 'Genel',
                'author' => [
                    'name' => $topic->user->name,
                    'avatar' => strtoupper(substr($topic->user->name, 0, 2)),
                    'isExpert' => $topic->user->is_expert,
                    'expertRank' => $topic->user->expert_rank
                ],
                'createdAt' => $topic->created_at->toISOString(),
                'repliesCount' => $topic->replies_count,
                'viewsCount' => $topic->views_count,
                'likesCount' => $topic->likes_count,
                'isPinned' => $topic->is_pinned,
                'isHot' => $topic->views_count > 1000,
                'hasExpertReply' => $topic->posts()->where('is_expert_answer', true)->exists(),
                'lastReply' => $topic->lastPostUser ? [
                    'author' => $topic->lastPostUser->name,
                    'createdAt' => $topic->last_post_at->toISOString()
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformedTopics,
            'pagination' => [
                'current_page' => $topics->currentPage(),
                'last_page' => $topics->lastPage(),
                'total' => $topics->total()
            ]
        ]);
    }

    public function show(ForumTopic $topic)
    {
        $topic->increment('views_count');
        
        $topic->load([
            'user',
            'forumCategory',
            'posts' => function($query) {
                $query->with('user')->active()->orderBy('created_at', 'asc');
            }
        ]);

        return response()->json([
            'success' => true,
            'data' => $topic
        ]);
    }

    public function store(Request $request)
    {
        // Check forum limits
        if (!\App\Models\ForumLimit::canCreateTopic(auth()->id())) {
            $limits = \App\Models\ForumLimit::getUserLimits(auth()->id());
            return response()->json([
                'success' => false,
                'message' => "Aylık konu oluşturma limitinizi aştınız! ({$limits['topics_used']}/{$limits['topic_limit']})",
                'requires_premium' => true
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categoryId' => 'required|exists:forum_categories,id'
        ]);

        $topic = ForumTopic::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'forum_category_id' => $request->categoryId,
            'user_id' => auth()->id(),
            'last_post_at' => now(),
            'last_post_user_id' => auth()->id()
        ]);

        // Increment topic count
        \App\Models\ForumLimit::incrementTopicCount(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $topic->load(['user', 'forumCategory'])
        ]);
    }

    public function reply(Request $request, ForumTopic $topic)
    {
        // Check forum limits
        if (!\App\Models\ForumLimit::canCreatePost(auth()->id())) {
            $limits = \App\Models\ForumLimit::getUserLimits(auth()->id());
            return response()->json([
                'success' => false,
                'message' => "Aylık mesaj gönderme limitinizi aştınız! ({$limits['posts_used']}/{$limits['post_limit']})",
                'requires_premium' => true
            ], 403);
        }

        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_posts,id'
        ]);

        $post = ForumPost::create([
            'forum_topic_id' => $topic->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'is_expert_answer' => auth()->user()->hasRole('expert'),
            'status' => 'active'
        ]);

        // Increment post count
        \App\Models\ForumLimit::incrementPostCount(auth()->id());

        $topic->increment('replies_count');
        $topic->update([
            'last_post_at' => now(),
            'last_post_user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $post->load('user')
        ]);
    }

    public function categories()
    {
        $categories = ForumCategory::active()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'icon' => $category->icon,
                    'description' => $category->description,
                    'topicsCount' => $category->topics_count,
                    'membersCount' => rand(1000, 20000), // Mock data
                    'recentMembers' => ['AK', 'MZ', 'SY'] // Mock data
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function stats()
    {
        $totalTopics = ForumTopic::active()->count();
        $todayTopics = ForumTopic::active()->whereDate('created_at', today())->count();
        $totalMessages = ForumPost::active()->count();
        $todayMessages = ForumPost::active()->whereDate('created_at', today())->count();
        $activeMembers = User::whereHas('forumTopics')->orWhereHas('forumPosts')->count();
        $expertAnswers = ForumPost::active()->where('is_expert_answer', true)->count();

        $stats = [
            'totalTopics' => $totalTopics ?: 12847,
            'todayTopics' => $todayTopics ?: 23,
            'activeMembers' => $activeMembers ?: 52341,
            'onlineNow' => rand(800, 1500),
            'totalMessages' => $totalMessages ?: 186592,
            'todayMessages' => $todayMessages ?: 342,
            'expertAnswers' => $expertAnswers ?: 8934
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function limits()
    {
        $limits = \App\Models\ForumLimit::getUserLimits(auth()->id());
        
        return response()->json([
            'success' => true,
            'data' => $limits
        ]);
    }
}