<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FitnessData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FitnessController extends Controller
{
    public function saveWorkoutTimer(Request $request)
    {
        $request->validate([
            'duration' => 'required|integer|min:1',
            'type' => 'required|string',
            'calories_burned' => 'integer|min:0'
        ]);

        $data = FitnessData::create([
            'user_id' => Auth::id(),
            'type' => 'workout_timer',
            'data' => [
                'duration' => $request->duration,
                'workout_type' => $request->type,
                'calories_burned' => $request->calories_burned ?? 0,
                'completed_at' => now()
            ],
            'date' => now()->toDateString()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Antrenman kaydedildi!',
            'data' => $data
        ]);
    }

    public function saveBodyMeasurement(Request $request)
    {
        $request->validate([
            'weight' => 'nullable|numeric|min:20|max:300',
            'height' => 'nullable|numeric|min:100|max:250',
            'chest' => 'nullable|numeric|min:50|max:200',
            'waist' => 'nullable|numeric|min:40|max:200',
            'hips' => 'nullable|numeric|min:50|max:200',
            'arms' => 'nullable|numeric|min:15|max:60',
            'thighs' => 'nullable|numeric|min:30|max:100'
        ]);

        $data = FitnessData::create([
            'user_id' => Auth::id(),
            'type' => 'body_measurement',
            'data' => $request->only(['weight', 'height', 'chest', 'waist', 'hips', 'arms', 'thighs']),
            'date' => now()->toDateString()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vücut ölçümleri kaydedildi!',
            'data' => $data
        ]);
    }

    public function getRandomExercise(Request $request)
    {
        $category = $request->get('category', 'all');
        
        $exercises = [
            'cardio' => [
                ['name' => 'Koşu Bandı', 'duration' => 20, 'calories' => 200, 'description' => 'Orta tempoda koşu'],
                ['name' => 'Bisiklet', 'duration' => 30, 'calories' => 250, 'description' => 'Sabit bisiklet antrenmanı'],
                ['name' => 'Zumba', 'duration' => 45, 'calories' => 300, 'description' => 'Eğlenceli dans antrenmanı']
            ],
            'strength' => [
                ['name' => 'Squat', 'duration' => 15, 'calories' => 120, 'description' => '3 set x 15 tekrar'],
                ['name' => 'Push-up', 'duration' => 10, 'calories' => 80, 'description' => '3 set x 10 tekrar'],
                ['name' => 'Plank', 'duration' => 5, 'calories' => 50, 'description' => '3 set x 1 dakika']
            ],
            'flexibility' => [
                ['name' => 'Yoga Flow', 'duration' => 25, 'calories' => 100, 'description' => 'Temel yoga pozları'],
                ['name' => 'Stretching', 'duration' => 15, 'calories' => 60, 'description' => 'Tam vücut germe'],
                ['name' => 'Pilates', 'duration' => 30, 'calories' => 150, 'description' => 'Core güçlendirme']
            ],
            'hiit' => [
                ['name' => 'Tabata', 'duration' => 20, 'calories' => 180, 'description' => '4 dakika yüksek yoğunluk'],
                ['name' => 'Circuit Training', 'duration' => 25, 'calories' => 220, 'description' => '5 istasyon devre'],
                ['name' => 'HIIT Cardio', 'duration' => 15, 'calories' => 160, 'description' => 'Interval koşu']
            ]
        ];

        if ($category === 'all') {
            $allExercises = array_merge(...array_values($exercises));
            $exercise = $allExercises[array_rand($allExercises)];
        } else {
            $categoryExercises = $exercises[$category] ?? $exercises['cardio'];
            $exercise = $categoryExercises[array_rand($categoryExercises)];
        }

        return response()->json([
            'success' => true,
            'exercise' => $exercise
        ]);
    }

    public function getFitnessStats()
    {
        $userId = Auth::id();
        $today = now()->toDateString();
        $thisWeek = now()->startOfWeek();

        // Today's stats
        $todayWorkouts = FitnessData::where('user_id', $userId)
            ->where('type', 'workout_timer')
            ->where('date', $today)
            ->get();

        $todayCalories = $todayWorkouts->sum(function($workout) {
            return $workout->data['calories_burned'] ?? 0;
        });

        $todayMinutes = $todayWorkouts->sum(function($workout) {
            return $workout->data['duration'] ?? 0;
        });

        // Weekly streak
        $weeklyWorkouts = FitnessData::where('user_id', $userId)
            ->where('type', 'workout_timer')
            ->where('created_at', '>=', $thisWeek)
            ->selectRaw('DATE(date) as workout_date')
            ->groupBy('workout_date')
            ->get();

        $weeklyStreak = $weeklyWorkouts->count();

        // Total completed workouts
        $completedWorkouts = FitnessData::where('user_id', $userId)
            ->where('type', 'workout_timer')
            ->count();

        // Latest body measurements
        $latestMeasurement = FitnessData::where('user_id', $userId)
            ->where('type', 'body_measurement')
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'stats' => [
                'today_calories' => $todayCalories,
                'today_minutes' => $todayMinutes,
                'weekly_streak' => $weeklyStreak,
                'completed_workouts' => $completedWorkouts,
                'latest_measurement' => $latestMeasurement ? $latestMeasurement->data : null
            ]
        ]);
    }
}