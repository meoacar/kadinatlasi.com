<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AchievementService;

class AchievementController extends Controller
{
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    public function myAchievements()
    {
        $user = auth()->user();
        $achievements = $this->achievementService->getUserAchievements($user);
        
        return response()->json([
            'success' => true,
            'data' => $achievements
        ]);
    }

    public function progress()
    {
        $user = auth()->user();
        $progress = $this->achievementService->getUserProgress($user);
        
        return response()->json([
            'success' => true,
            'data' => $progress
        ]);
    }
}