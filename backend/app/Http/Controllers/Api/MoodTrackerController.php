<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodEntry;
use Illuminate\Http\Request;

class MoodTrackerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mood' => 'required|string|in:very-sad,sad,neutral,happy,very-happy',
            'note' => 'nullable|string|max:500',
            'date' => 'required|date'
        ]);

        $moodEntry = MoodEntry::create([
            'user_id' => $request->user()->id,
            'mood' => $request->mood,
            'note' => $request->note,
            'date' => $request->date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ruh haliniz kaydedildi',
            'data' => $moodEntry
        ]);
    }

    public function index(Request $request)
    {
        $moods = MoodEntry::where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }

    public function stats(Request $request)
    {
        $userId = $request->user()->id;
        
        $stats = [
            'total_entries' => MoodEntry::where('user_id', $userId)->count(),
            'this_week' => MoodEntry::where('user_id', $userId)
                ->where('date', '>=', now()->subWeek())
                ->count(),
            'mood_distribution' => MoodEntry::where('user_id', $userId)
                ->selectRaw('mood, COUNT(*) as count')
                ->groupBy('mood')
                ->pluck('count', 'mood')
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}