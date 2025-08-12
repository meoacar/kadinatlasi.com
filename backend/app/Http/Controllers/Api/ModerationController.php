<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumTopic;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function getPendingContent()
    {
        $pendingTopics = ForumTopic::where('is_approved', false)
            ->with(['user', 'category'])
            ->latest()
            ->get();

        $pendingPosts = BlogPost::where('is_published', false)
            ->with(['user', 'category'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'forum_topics' => $pendingTopics,
                'blog_posts' => $pendingPosts,
                'total_pending' => $pendingTopics->count() + $pendingPosts->count()
            ]
        ]);
    }

    public function moderateForumTopic(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,feature,unfeature,pin,unpin,lock,unlock',
            'note' => 'nullable|string|max:500'
        ]);

        $topic = ForumTopic::findOrFail($id);

        switch ($request->action) {
            case 'approve':
                $topic->update([
                    'is_approved' => true,
                    'moderator_id' => auth()->id(),
                    'moderated_at' => now(),
                    'moderation_note' => $request->note
                ]);
                $message = 'Konu onaylandı';
                break;

            case 'reject':
                $topic->delete();
                $message = 'Konu reddedildi ve silindi';
                break;

            case 'feature':
                $topic->update([
                    'is_featured' => true,
                    'moderator_id' => auth()->id(),
                    'moderated_at' => now(),
                    'moderation_note' => $request->note
                ]);
                $message = 'Konu öne çıkarıldı';
                break;

            case 'unfeature':
                $topic->update([
                    'is_featured' => false,
                    'moderator_id' => auth()->id(),
                    'moderated_at' => now(),
                    'moderation_note' => $request->note
                ]);
                $message = 'Konu öne çıkarma kaldırıldı';
                break;

            case 'pin':
                $topic->update([
                    'is_pinned' => true,
                    'moderator_id' => auth()->id(),
                    'moderated_at' => now(),
                    'moderation_note' => $request->note
                ]);
                $message = 'Konu sabitlendi';
                break;

            case 'unpin':
                $topic->update([
                    'is_pinned' => false,
                    'moderator_id' => auth()->id(),
                    'moderated_at' => now(),
                    'moderation_note' => $request->note
                ]);
                $message = 'Konu sabitleme kaldırıldı';
                break;

            case 'lock':
                $topic->update([
                    'is_locked' => true,
                    'moderator_id' => auth()->id(),
                    'moderated_at' => now(),
                    'moderation_note' => $request->note
                ]);
                $message = 'Konu kilitlendi';
                break;

            case 'unlock':
                $topic->update([
                    'is_locked' => false,
                    'moderator_id' => auth()->id(),
                    'moderated_at' => now(),
                    'moderation_note' => $request->note
                ]);
                $message = 'Konu kilidi kaldırıldı';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function getStats()
    {
        $stats = [
            'users_total' => \App\Models\User::count(),
            'users_today' => \App\Models\User::whereDate('created_at', today())->count(),
            'blog_posts_total' => BlogPost::count(),
            'blog_posts_published' => BlogPost::where('is_published', true)->count(),
            'forum_topics_total' => ForumTopic::count(),
            'forum_topics_pending' => ForumTopic::where('is_approved', false)->count(),
            'forum_groups_total' => \App\Models\ForumGroup::count(),
            'horoscopes_total' => \App\Models\Horoscope::count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}