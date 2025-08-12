<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumGroup;
use Illuminate\Http\Request;

class ForumGroupController extends Controller
{
    public function index()
    {
        $groups = ForumGroup::with(['creator', 'members'])
            ->withCount('members')
            ->orderBy('member_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $groups
        ]);
    }

    public function show($id)
    {
        $group = ForumGroup::with(['creator', 'members', 'topics.user'])
            ->withCount(['members', 'topics'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $group
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string|size:7',
            'is_private' => 'boolean',
            'requires_approval' => 'boolean',
        ]);

        $group = ForumGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color ?? '#E57399',
            'is_private' => $request->is_private ?? false,
            'requires_approval' => $request->requires_approval ?? false,
            'creator_id' => auth()->id(),
            'member_count' => 1,
        ]);

        // Yaratıcıyı admin olarak ekle
        $group->members()->attach(auth()->id(), [
            'role' => 'admin',
            'is_approved' => true,
            'joined_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $group->load('creator'),
            'message' => 'Grup başarıyla oluşturuldu'
        ], 201);
    }

    public function join(Request $request, $id)
    {
        $group = ForumGroup::findOrFail($id);
        $user = auth()->user();

        if ($group->isMember($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Zaten bu grubun üyesisiniz'
            ], 400);
        }

        $isApproved = !$group->requires_approval;

        $group->members()->attach($user->id, [
            'role' => 'member',
            'is_approved' => $isApproved,
            'joined_at' => now()
        ]);

        if ($isApproved) {
            $group->increment('member_count');
        }

        return response()->json([
            'success' => true,
            'message' => $isApproved ? 'Gruba katıldınız' : 'Üyelik başvurunuz gönderildi'
        ]);
    }

    public function leave($id)
    {
        $group = ForumGroup::findOrFail($id);
        $user = auth()->user();

        if (!$group->isMember($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Bu grubun üyesi değilsiniz'
            ], 400);
        }

        $group->members()->detach($user->id);
        $group->decrement('member_count');

        return response()->json([
            'success' => true,
            'message' => 'Gruptan ayrıldınız'
        ]);
    }

    public function moderate(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,feature,unfeature',
            'user_id' => 'required_if:action,approve,reject|exists:users,id'
        ]);

        $group = ForumGroup::findOrFail($id);
        $user = auth()->user();

        if (!$group->isModerator($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz yok'
            ], 403);
        }

        switch ($request->action) {
            case 'approve':
                $group->members()->updateExistingPivot($request->user_id, [
                    'is_approved' => true
                ]);
                $group->increment('member_count');
                $message = 'Üye onaylandı';
                break;

            case 'reject':
                $group->members()->detach($request->user_id);
                $message = 'Üye reddedildi';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}