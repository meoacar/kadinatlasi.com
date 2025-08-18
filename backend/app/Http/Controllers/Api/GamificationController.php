<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GamificationService;
use App\Models\Task;
use App\Models\UserTask;
use App\Models\UserLevel;
use App\Models\ActivityLog;
use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GamificationController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function getUserStats(Request $request)
    {
        $stats = $this->gamificationService->getUserStats($request->user()->id);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function getDailyTasks(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $tasks = $this->gamificationService->getDailyTasks($request->user()->id, $date);
        
        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    public function getWeeklyTasks(Request $request)
    {
        $startOfWeek = now()->startOfWeek()->toDateString();
        
        $tasks = Task::active()
            ->where('type', 'weekly')
            ->orderBy('sort_order')
            ->get()
            ->map(function($task) use ($request, $startOfWeek) {
                // For weekly tasks, we check progress from start of week
                $userTask = UserTask::where('user_id', $request->user()->id)
                    ->where('task_id', $task->id)
                    ->whereDate('task_date', '>=', $startOfWeek)
                    ->orderBy('created_at', 'desc')
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

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    public function getAchievements(Request $request)
    {
        $achievements = Achievement::with('category')
            ->where('is_active', true)
            ->orderBy('difficulty')
            ->orderBy('sort_order')
            ->get()
            ->map(function($achievement) use ($request) {
                $userAchievement = UserAchievement::where('user_id', $request->user()->id)
                    ->where('achievement_id', $achievement->id)
                    ->first();
                    
                $progress = $userAchievement ? $userAchievement->progress : 0;
                $target = $achievement->target_value ?? 1;
                $progressPercentage = $target > 0 ? min(100, ($progress / $target) * 100) : 0;
                
                return [
                    'id' => $achievement->id,
                    'name' => $achievement->name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                    'badge_color' => $achievement->badge_color,
                    'type' => $achievement->type,
                    'difficulty' => $achievement->difficulty,
                    'points' => $achievement->points,
                    'category' => $achievement->category->name ?? null,
                    'progress' => $progress,
                    'target' => $target,
                    'is_completed' => $userAchievement ? $userAchievement->is_completed : false,
                    'completed_at' => $userAchievement ? $userAchievement->completed_at : null,
                    'progress_percentage' => $progressPercentage,
                    'is_hidden' => $achievement->is_hidden
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $achievements
        ]);
    }

    public function getLeaderboard(Request $request)
    {
        $type = $request->get('type', 'points'); // points, level, achievements, streak
        $period = $request->get('period', 'all_time'); // daily, weekly, monthly, all_time
        $limit = $request->get('limit', 50);

        $query = UserLevel::with('user:id,name,email')
            ->orderBy($type, 'desc')
            ->limit($limit);

        if ($period !== 'all_time') {
            // For time-based leaderboards, we'd need to implement period-specific logic
            // For now, we'll use all_time data
        }

        $leaderboard = $query->get()->map(function($userLevel, $index) use ($type) {
            return [
                'rank' => $index + 1,
                'user' => [
                    'id' => $userLevel->user->id,
                    'name' => $userLevel->user->name,
                ],
                'score' => $userLevel->$type,
                'level' => $userLevel->level,
                'points' => $userLevel->points,
                'achievements_count' => $userLevel->achievements_count,
                'daily_streak' => $userLevel->daily_streak
            ];
        });

        // Get current user's rank
        $currentUserRank = null;
        $currentUser = $request->user();
        if ($currentUser) {
            $currentUserLevel = UserLevel::where('user_id', $currentUser->id)->first();
            if ($currentUserLevel) {
                $betterUsers = UserLevel::where($type, '>', $currentUserLevel->$type)->count();
                $currentUserRank = $betterUsers + 1;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'leaderboard' => $leaderboard,
                'current_user_rank' => $currentUserRank,
                'type' => $type,
                'period' => $period
            ]
        ]);
    }

    public function getActivityHistory(Request $request)
    {
        $limit = $request->get('limit', 20);
        $activities = ActivityLog::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'action_type' => $activity->action_type,
                    'action_target' => $activity->action_target,
                    'points_earned' => $activity->points_earned,
                    'xp_earned' => $activity->xp_earned,
                    'created_at' => $activity->created_at,
                    'metadata' => $activity->metadata
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    public function trackAction(Request $request)
    {
        $request->validate([
            'action_type' => 'required|string',
            'action_target' => 'nullable|string',
            'action_target_id' => 'nullable|integer',
            'metadata' => 'nullable|array'
        ]);

        $activityLog = $this->gamificationService->trackUserAction(
            $request->user()->id,
            $request->action_type,
            $request->action_target,
            $request->action_target_id,
            $request->metadata ?? []
        );

        return response()->json([
            'success' => true,
            'message' => 'Action tracked successfully',
            'data' => $activityLog
        ]);
    }

    public function completeTask(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $date = $request->get('date', now()->toDateString());
        
        $userTask = UserTask::where('user_id', $request->user()->id)
            ->where('task_id', $taskId)
            ->where('task_date', $date)
            ->first();

        if (!$userTask) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found for this date'
            ], 404);
        }

        if ($userTask->is_completed) {
            return response()->json([
                'success' => false,
                'message' => 'Task already completed'
            ], 400);
        }

        // Mark as completed
        $userTask->progress = $userTask->target;
        $userTask->is_completed = true;
        $userTask->completed_at = now();
        $userTask->save();

        // Award points and XP
        $this->gamificationService->awardPointsAndXp(
            $request->user()->id,
            $task->points,
            $task->xp_reward
        );

        return response()->json([
            'success' => true,
            'message' => 'Task completed successfully',
            'data' => $userTask
        ]);
    }
}