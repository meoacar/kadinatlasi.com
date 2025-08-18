<?php

namespace App\Services;

use App\Models\User;
use App\Models\Task;
use App\Models\UserTask;
use App\Models\UserLevel;
use App\Models\ActivityLog;
use App\Models\Achievement;
use App\Models\UserAchievement;
use Carbon\Carbon;

class GamificationService
{
    public function trackUserAction($userId, $actionType, $actionTarget = null, $actionTargetId = null, $metadata = [])
    {
        // Log the activity
        $activityLog = ActivityLog::create([
            'user_id' => $userId,
            'action_type' => $actionType,
            'action_target' => $actionTarget,
            'action_target_id' => $actionTargetId,
            'metadata' => $metadata,
            'points_earned' => 0,
            'xp_earned' => 0
        ]);

        // Process tasks
        $this->processTasks($userId, $actionType);
        
        // Process achievements
        $this->processAchievements($userId, $actionType);
        
        // Update daily streak
        $this->updateDailyStreak($userId);

        return $activityLog;
    }

    public function processTasks($userId, $actionType, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $tasks = Task::active()
            ->where('action_type', $actionType)
            ->get();

        foreach ($tasks as $task) {
            $userTask = UserTask::where('user_id', $userId)
                ->where('task_id', $task->id)
                ->where('task_date', $date)
                ->first();
                
            if (!$userTask) {
                $userTask = UserTask::create([
                    'user_id' => $userId,
                    'task_id' => $task->id,
                    'task_date' => $date,
                    'progress' => 1,
                    'target' => $task->target_count,
                    'is_completed' => false
                ]);
            } else if (!$userTask->is_completed) {
                $userTask->progress++;
            }

            if (!$userTask->is_completed && $userTask->progress >= $userTask->target) {
                $userTask->is_completed = true;
                $userTask->completed_at = now();
                
                // Award points and XP
                $this->awardPointsAndXp($userId, $task->points, $task->xp_reward);
                
                // Update activity log
                ActivityLog::where('user_id', $userId)
                    ->where('action_type', $actionType)
                    ->latest()
                    ->first()
                    ?->update([
                        'points_earned' => $task->points,
                        'xp_earned' => $task->xp_reward
                    ]);
            }
            
            $userTask->save();
        }
    }

    public function processAchievements($userId, $actionType)
    {
        $achievements = Achievement::where('is_active', true)
            ->where('target_metric', $actionType)
            ->get();

        foreach ($achievements as $achievement) {
            $userAchievement = UserAchievement::firstOrCreate([
                'user_id' => $userId,
                'achievement_id' => $achievement->id
            ], [
                'progress' => 0,
                'target' => $achievement->target_value ?? 1,
                'is_completed' => false
            ]);

            if (!$userAchievement->is_completed) {
                $userAchievement->progress++;
                
                if ($userAchievement->progress >= $userAchievement->target) {
                    $userAchievement->is_completed = true;
                    $userAchievement->completed_at = now();
                    
                    // Award achievement points
                    $this->awardPointsAndXp($userId, $achievement->points, $achievement->points / 2);
                }
                
                $userAchievement->save();
            }
        }
    }

    public function awardPointsAndXp($userId, $points, $xp)
    {
        $userLevel = UserLevel::firstOrCreate(['user_id' => $userId]);
        
        $oldLevel = $userLevel->level;
        $userLevel->addPoints($points);
        $userLevel->addXp($xp);
        
        // Check for level up notification
        if ($userLevel->level > $oldLevel) {
            // Could trigger level up event/notification here
            // event(new \App\Events\UserLevelUp($userId, $userLevel->level, $oldLevel));
        }
        
        return $userLevel;
    }

    public function updateDailyStreak($userId)
    {
        $userLevel = UserLevel::firstOrCreate(['user_id' => $userId]);
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        
        if ($userLevel->last_activity_date === null) {
            // First activity
            $userLevel->daily_streak = 1;
            $userLevel->max_streak = 1;
        } elseif ($userLevel->last_activity_date === $yesterday) {
            // Consecutive day
            $userLevel->daily_streak++;
            $userLevel->max_streak = max($userLevel->max_streak, $userLevel->daily_streak);
        } elseif ($userLevel->last_activity_date !== $today) {
            // Streak broken
            $userLevel->daily_streak = 1;
        }
        
        $userLevel->last_activity_date = $today;
        $userLevel->save();
        
        return $userLevel;
    }

    public function getDailyTasks($userId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return Task::active()
            ->where('type', 'daily')
            ->orderBy('sort_order')
            ->get()
            ->map(function($task) use ($userId, $date) {
                $userTask = UserTask::where('user_id', $userId)
                    ->where('task_id', $task->id)
                    ->whereDate('task_date', $date)
                    ->first();
                    
                $progress = $userTask ? $userTask->progress : 0;
                $target = $task->target_count;
                $progressPercentage = $target > 0 ? min(100, ($progress / $target) * 100) : 0;
                
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'icon' => $task->icon,
                    'category' => $task->category,
                    'points' => $task->points,
                    'xp_reward' => $task->xp_reward,
                    'target_count' => $target,
                    'progress' => $progress,
                    'is_completed' => $userTask ? $userTask->is_completed : false,
                    'progress_percentage' => $progressPercentage
                ];
            });
    }

    public function getUserStats($userId)
    {
        $userLevel = UserLevel::firstOrCreate(['user_id' => $userId], [
            'level' => 1,
            'current_xp' => 0,
            'total_xp' => 0,
            'points' => 0,
            'achievements_count' => 0,
            'tasks_completed' => 0,
            'daily_streak' => 0,
            'max_streak' => 0,
            'last_activity_date' => now()->toDateString()
        ]);
        
        $todayTasks = UserTask::where('user_id', $userId)
            ->whereDate('task_date', now()->toDateString())
            ->count();
            
        $completedTodayTasks = UserTask::where('user_id', $userId)
            ->whereDate('task_date', now()->toDateString())
            ->completed()
            ->count();
            
        $totalAchievements = UserAchievement::where('user_id', $userId)
            ->where('is_completed', true)
            ->count();

        return [
            'level' => $userLevel->level,
            'current_xp' => $userLevel->current_xp,
            'total_xp' => $userLevel->total_xp,
            'xp_for_next_level' => $userLevel->xp_for_next_level,
            'xp_progress' => $userLevel->xp_progress,
            'points' => $userLevel->points,
            'daily_streak' => $userLevel->daily_streak,
            'max_streak' => $userLevel->max_streak,
            'achievements_count' => $totalAchievements,
            'tasks_completed_today' => $completedTodayTasks,
            'total_tasks_today' => $todayTasks,
            'tasks_completion_rate' => $todayTasks > 0 ? ($completedTodayTasks / $todayTasks) * 100 : 0
        ];
    }
}