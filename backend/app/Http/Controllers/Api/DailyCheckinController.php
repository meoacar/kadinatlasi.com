<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyCheckin;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class DailyCheckinController extends Controller
{
    public function todayStatus()
    {
        $user = auth()->user();
        $today = DailyCheckin::where('user_id', $user->id)
            ->whereDate('checkin_date', today())
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'checked_in' => !!$today,
                'checkin' => $today,
                'streak' => $this->getUserStreak($user->id)
            ]
        ]);
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'activities' => 'required|array',
            'mood_score' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        
        // Bugün zaten check-in yapılmış mı kontrol et
        $existing = DailyCheckin::where('user_id', $user->id)
            ->whereDate('checkin_date', today())
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Bugün zaten check-in yaptınız!'
            ], 400);
        }

        $checkin = DailyCheckin::create([
            'user_id' => $user->id,
            'checkin_date' => today(),
            'activities' => $request->activities,
            'mood_score' => $request->mood_score,
            'notes' => $request->notes,
            'points_earned' => $this->calculatePoints($request->activities)
        ]);

        // Kullanıcının puanını güncelle
        $user->increment('points', $checkin->points_earned);
        $user->refresh();

        // Achievement kontrolü
        $achievementService = new AchievementService();
        $newAchievements = $achievementService->checkAndAwardAchievements($user, 'checkin');

        $message = 'Check-in başarılı! ' . $checkin->points_earned . ' puan kazandınız.';
        if (!empty($newAchievements)) {
            $achievementNames = collect($newAchievements)->pluck('name')->join(', ');
            $message .= ' 🏆 Yeni rozet(ler): ' . $achievementNames;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'checkin' => $checkin,
                'streak' => $this->getUserStreak($user->id),
                'total_points' => $user->points,
                'new_achievements' => $newAchievements
            ]
        ]);
    }

    public function stats()
    {
        $user = auth()->user();
        
        $thisWeek = DailyCheckin::where('user_id', $user->id)->thisWeek()->count();
        $thisMonth = DailyCheckin::where('user_id', $user->id)->thisMonth()->count();
        $total = DailyCheckin::where('user_id', $user->id)->count();
        $streak = $this->getUserStreak($user->id);

        return response()->json([
            'success' => true,
            'data' => [
                'this_week' => $thisWeek,
                'this_month' => $thisMonth,
                'total' => $total,
                'current_streak' => $streak,
                'total_points' => $user->points ?? 0
            ]
        ]);
    }

    private function getUserStreak($userId)
    {
        $checkins = DailyCheckin::where('user_id', $userId)
            ->orderBy('checkin_date', 'desc')
            ->pluck('checkin_date')
            ->map(function($date) {
                return is_string($date) ? \Carbon\Carbon::parse($date) : $date;
            })
            ->toArray();

        if (empty($checkins)) {
            return 0;
        }

        $streak = 0;
        $currentDate = today();

        foreach ($checkins as $checkinDate) {
            if ($checkinDate->equalTo($currentDate)) {
                $streak++;
                $currentDate = $currentDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    private function calculatePoints($activities)
    {
        $basePoints = 10;
        $bonusPoints = count($activities) * 5; // Her aktivite için 5 bonus puan
        
        return $basePoints + $bonusPoints;
    }
}