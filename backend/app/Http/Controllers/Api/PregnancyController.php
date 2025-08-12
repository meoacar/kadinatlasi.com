<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PregnancyWeek;
use App\Models\BabyName;
use App\Models\PregnancyTracker;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PregnancyController extends Controller
{
    public function getWeeklyGuide($week)
    {
        $pregnancyWeek = PregnancyWeek::where('week_number', $week)->first();
        
        if (!$pregnancyWeek) {
            return response()->json(['error' => 'Bu hafta için rehber bulunamadı'], 404);
        }

        return response()->json($pregnancyWeek);
    }

    public function getAllWeeks()
    {
        $weeks = PregnancyWeek::orderBy('week_number')->get();
        return response()->json($weeks);
    }

    public function getBabyNames(Request $request)
    {
        $query = BabyName::query();

        if ($request->has('gender') && $request->gender !== 'all') {
            $query->byGender($request->gender);
        }

        if ($request->has('popular') && $request->popular) {
            $query->popular();
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $names = $query->orderBy('name')->paginate(20);
        return response()->json($names);
    }

    public function getPregnancyTracker(Request $request)
    {
        $tracker = PregnancyTracker::where('user_id', auth()->id())
                                  ->where('is_active', true)
                                  ->first();

        if (!$tracker) {
            return response()->json(['message' => 'Gebelik takibi bulunamadı'], 404);
        }

        // Güncel hafta ve gün hesaplama
        $lmp = Carbon::parse($tracker->last_menstrual_period);
        $today = Carbon::now();
        $daysDiff = $lmp->diffInDays($today);
        
        $currentWeek = intval($daysDiff / 7) + 1;
        $currentDay = ($daysDiff % 7) + 1;

        $tracker->update([
            'current_week' => min($currentWeek, 42),
            'current_day' => $currentDay
        ]);

        // Haftalık rehber bilgisi
        $weeklyGuide = PregnancyWeek::where('week_number', $tracker->current_week)->first();

        return response()->json([
            'tracker' => $tracker,
            'weekly_guide' => $weeklyGuide,
            'days_pregnant' => $daysDiff,
            'weeks_remaining' => max(0, 40 - $currentWeek)
        ]);
    }

    public function createPregnancyTracker(Request $request)
    {
        $request->validate([
            'last_menstrual_period' => 'required|date',
        ]);

        $lmp = Carbon::parse($request->last_menstrual_period);
        $dueDate = $lmp->addDays(280); // 40 hafta

        $tracker = PregnancyTracker::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'last_menstrual_period' => $request->last_menstrual_period,
                'due_date' => $dueDate,
                'is_active' => true
            ]
        );

        return response()->json([
            'message' => 'Gebelik takibi başarıyla oluşturuldu',
            'tracker' => $tracker
        ]);
    }
}