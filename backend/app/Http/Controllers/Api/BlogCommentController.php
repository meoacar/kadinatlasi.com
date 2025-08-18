<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function store(Request $request, $blogPostId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id'
        ]);

        $blogPost = BlogPost::published()->findOrFail($blogPostId);

        $comment = BlogComment::create([
            'blog_post_id' => $blogPost->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'is_approved' => true, // Auto-approve for now
        ]);

        $comment->load('user');

        // Blog yazarına bildirim gönder (kendi yorumu değilse)
        if ($blogPost->user_id !== auth()->id()) {
            $this->notificationService->notifyBlogComment($blogPost->user_id, [
                'blog_id' => $blogPost->id,
                'blog_title' => $blogPost->title,
                'comment_id' => $comment->id,
                'author_name' => auth()->user()->name
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Yorumunuz başarıyla eklendi',
            'data' => $comment
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $comment = BlogComment::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment->update([
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yorumunuz başarıyla güncellendi',
            'data' => $comment
        ]);
    }

    public function destroy($id)
    {
        $comment = BlogComment::where('user_id', auth()->id())->findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Yorumunuz başarıyla silindi'
        ]);
    }
}