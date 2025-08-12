<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumPoll;
use App\Models\ForumPollVote;
use App\Models\ForumTopic;
use Illuminate\Http\Request;

class ForumPollController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:forum_topics,id',
            'group_id' => 'nullable|exists:forum_groups,id',
            'question' => 'required|string|max:500',
            'options' => 'required|array|min:2|max:10',
            'options.*' => 'required|string|max:200',
            'multiple_choice' => 'boolean',
            'anonymous' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $poll = ForumPoll::create([
            'topic_id' => $request->topic_id,
            'group_id' => $request->group_id,
            'user_id' => auth()->id(),
            'question' => $request->question,
            'options' => $request->options,
            'multiple_choice' => $request->multiple_choice ?? false,
            'anonymous' => $request->anonymous ?? false,
            'expires_at' => $request->expires_at,
        ]);

        return response()->json([
            'success' => true,
            'data' => $poll->load(['user', 'votes']),
            'message' => 'Anket başarıyla oluşturuldu'
        ], 201);
    }

    public function show($id)
    {
        $poll = ForumPoll::with(['user', 'topic', 'group', 'votes.user'])
            ->findOrFail($id);

        $results = $poll->results;
        $userVote = null;
        
        if (auth()->check()) {
            $userVote = $poll->getUserVote(auth()->user());
        }

        return response()->json([
            'success' => true,
            'data' => [
                'poll' => $poll,
                'results' => $results,
                'user_vote' => $userVote,
                'total_votes' => $poll->votes->count(),
                'can_vote' => $poll->canVote() && (!auth()->check() || !$poll->hasUserVoted(auth()->user()))
            ]
        ]);
    }

    public function vote(Request $request, $id)
    {
        $request->validate([
            'selected_options' => 'required|array|min:1',
            'selected_options.*' => 'integer|min:0',
        ]);

        $poll = ForumPoll::findOrFail($id);
        $user = auth()->user();

        if (!$poll->canVote()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu ankete oy verilemez'
            ], 400);
        }

        if ($poll->hasUserVoted($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Bu ankete zaten oy verdiniz'
            ], 400);
        }

        // Seçenek indekslerini doğrula
        $maxIndex = count($poll->options) - 1;
        foreach ($request->selected_options as $optionIndex) {
            if ($optionIndex > $maxIndex) {
                return response()->json([
                    'success' => false,
                    'message' => 'Geçersiz seçenek'
                ], 400);
            }
        }

        // Çoklu seçim kontrolü
        if (!$poll->multiple_choice && count($request->selected_options) > 1) {
            return response()->json([
                'success' => false,
                'message' => 'Bu ankette sadece bir seçenek seçebilirsiniz'
            ], 400);
        }

        ForumPollVote::create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'selected_options' => $request->selected_options,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Oyunuz kaydedildi',
            'data' => [
                'results' => $poll->fresh()->results,
                'total_votes' => $poll->votes()->count() + 1
            ]
        ]);
    }

    public function index(Request $request)
    {
        $query = ForumPoll::with(['user', 'topic', 'group'])
            ->withCount('votes')
            ->orderBy('created_at', 'desc');

        if ($request->has('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->has('active_only')) {
            $query->where('is_active', true)
                  ->where(function($q) {
                      $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                  });
        }

        $polls = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $polls
        ]);
    }
}