<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\User;
use App\Models\DailyCheckin;
use App\Models\EventParticipant;
use App\Models\ForumPost;

class AchievementService
{
    public function checkAndAwardAchievements(User $user, string $action, $value = 1)
    {
        $achievements = Achievement::active()->get();
        $newAchievements = [];

        foreach ($achievements as $achievement) {
            if ($this->shouldCheckAchievement($achievement, $action)) {
                $userAchievement = UserAchievement::firstOrCreate([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id
                ]);

                if (!$userAchievement->is_completed) {
                    $currentProgress = $this->calculateProgress($user, $achievement);
                    
                    if ($currentProgress >= $achievement->target_value) {
                        $userAchievement->update([
                            'progress' => $currentProgress,
                            'is_completed' => true,
                            'completed_at' => now()
                        ]);

                        // Award points
                        $user->increment('points', $achievement->points);
                        
                        $newAchievements[] = $achievement;
                    } else {
                        $userAchievement->update(['progress' => $currentProgress]);
                    }
                }
            }
        }

        return $newAchievements;
    }

    private function shouldCheckAchievement(Achievement $achievement, string $action): bool
    {
        $actionMap = [
            'checkin' => ['checkin_count', 'checkin_streak'],
            'event_register' => ['event_participation'],
            'forum_post' => ['forum_posts'],
            'profile_update' => ['profile_complete'],
            'follow' => ['following_count'],
            'points_earned' => ['total_points'],
            'membership' => ['membership_days']
        ];

        return in_array($achievement->condition, $actionMap[$action] ?? []);
    }

    private function calculateProgress(User $user, Achievement $achievement): int
    {
        switch ($achievement->condition) {
            case 'checkin_count':
                return DailyCheckin::where('user_id', $user->id)->count();
                
            case 'checkin_streak':
                return $this->calculateCheckinStreak($user);
                
            case 'event_participation':
                return EventParticipant::where('user_id', $user->id)->count();
                
            case 'forum_posts':
                return ForumPost::where('user_id', $user->id)->count();
                
            case 'profile_complete':
                return $this->isProfileComplete($user) ? 1 : 0;
                
            case 'following_count':
                // Implement following system later
                return 0;
                
            case 'total_points':
                return $user->points ?? 0;
                
            case 'membership_days':
                return $user->created_at->diffInDays(now());
                
            default:
                return 0;
        }
    }

    private function calculateCheckinStreak(User $user): int
    {
        $checkins = DailyCheckin::where('user_id', $user->id)
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

    private function isProfileComplete(User $user): bool
    {
        return !empty($user->name) && 
               !empty($user->email) && 
               !empty($user->birth_date) &&
               $user->profile !== null;
    }

    public function getUserAchievements(User $user)
    {
        return UserAchievement::with('achievement')
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->orderBy('completed_at', 'desc')
            ->get();
    }

    public function getUserProgress(User $user)
    {
        $achievements = Achievement::active()->get();
        $progress = [];

        foreach ($achievements as $achievement) {
            $userAchievement = UserAchievement::where('user_id', $user->id)
                ->where('achievement_id', $achievement->id)
                ->first();

            $currentProgress = $this->calculateProgress($user, $achievement);
            
            $progress[] = [
                'achievement' => $achievement,
                'progress' => $currentProgress,
                'is_completed' => $userAchievement ? $userAchievement->is_completed : false,
                'completed_at' => $userAchievement ? $userAchievement->completed_at : null,
                'percentage' => min(100, ($currentProgress / $achievement->target_value) * 100)
            ];
        }

        return collect($progress)->sortBy(function($item) {
            return $item['is_completed'] ? 1 : 0;
        });
    }
}