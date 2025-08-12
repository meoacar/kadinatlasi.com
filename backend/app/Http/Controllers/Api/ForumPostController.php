<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Services\NotificationService;
use App\Services\ReputationService;
use Illuminate\Http\Request;

class ForumPostController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function store(Request $request, $topicId)
    {
        $topic = ForumTopic::findOrFail($topicId);

        if ($topic->is_locked) {
            return response()->json([
                'success' => false,
                'message' => 'Bu konu kilitli, yeni mesaj ekleyemezsiniz'
            ], 403);
        }

        $request->validate([
            'content' => 'required|string|max:2000'
        ]);

        $post = ForumPost::create([
            'topic_id' => $topic->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        $post->load('user');
        
        // Add reputation points
        ReputationService::addPoints(auth()->id(), 'forum_post_created', $post);

        // Konu sahibine bildirim gönder (kendi mesajı değilse)
        if ($topic->user_id !== auth()->id()) {
            $this->notificationService->notifyForumReply($topic->user_id, [
                'topic_id' => $topic->id,
                'topic_title' => $topic->title,
                'post_id' => $post->id,
                'author_name' => auth()->user()->name
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mesajınız başarıyla eklendi',
            'data' => $post
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $post = ForumPost::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'content' => 'required|string|max:2000'
        ]);

        $post->update([
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mesajınız başarıyla güncellendi',
            'data' => $post
        ]);
    }

    public function destroy($id)
    {
        $post = ForumPost::where('user_id', auth()->id())->findOrFail($id);
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mesajınız başarıyla silindi'
        ]);
    }
}