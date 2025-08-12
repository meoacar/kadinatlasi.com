<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\UserReputation;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::where('is_active', true)->get();
        
        return response()->json([
            'success' => true,
            'data' => $badges
        ]);
    }

    public function userBadges()
    {
        $user = auth()->user();
        $badges = $user->badges()->get();
        
        return response()->json([
            'success' => true,
            'data' => $badges
        ]);
    }

    public function userReputation()
    {
        $user = auth()->user();
        $reputations = $user->reputations()
                          ->latest()
                          ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_score' => $user->reputation_score,
                'level' => $user->reputation_level,
                'history' => $reputations
            ]
        ]);
    }

    public function awardBadge(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_id' => 'required|exists:badges,id'
        ]);

        $user = \App\Models\User::find($request->user_id);
        $badge = Badge::find($request->badge_id);

        if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id);
            
            // Add reputation points for earning badge
            UserReputation::addPoints(
                $user->id,
                'badge_earned',
                50,
                $badge,
                "'{$badge->name}' rozeti kazanıldı"
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Rozet başarıyla verildi'
        ]);
    }
}