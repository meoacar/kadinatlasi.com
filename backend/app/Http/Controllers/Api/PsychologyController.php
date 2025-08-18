<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodTracker;
use App\Models\PsychologyTest;
use App\Models\MotivationalQuote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PsychologyController extends Controller
{
    public function getDailyQuote()
    {
        $quote = MotivationalQuote::active()->random()->first();
        return response()->json($quote);
    }

    public function getQuotesByCategory(Request $request)
    {
        $query = MotivationalQuote::active();
        
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }
        
        $quotes = $query->paginate(10);
        return response()->json($quotes);
    }

    public function getMoodTracker(Request $request)
    {
        $userId = auth()->id();
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $mood = MoodTracker::where('user_id', $userId)
                          ->where('date', $date)
                          ->first();
        
        return response()->json($mood);
    }

    public function saveMoodTracker(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'mood' => 'required|in:very_sad,sad,neutral,happy,very_happy',
            'energy_level' => 'required|integer|min:1|max:10',
            'stress_level' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string',
            'activities' => 'nullable|array'
        ]);

        $mood = MoodTracker::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date' => $request->date
            ],
            $request->only(['mood', 'energy_level', 'stress_level', 'notes', 'activities'])
        );

        return response()->json([
            'message' => 'Ruh hali kaydedildi',
            'mood' => $mood
        ]);
    }

    public function getMoodHistory(Request $request)
    {
        $userId = auth()->id();
        $days = $request->get('days', 30);
        
        $moods = MoodTracker::where('user_id', $userId)
                           ->where('date', '>=', Carbon::now()->subDays($days))
                           ->orderBy('date', 'desc')
                           ->get();
        
        return response()->json($moods);
    }

    public function getPsychologyTests()
    {
        $tests = PsychologyTest::active()->get();
        return response()->json($tests);
    }

    public function getPsychologyTest($id)
    {
        $test = PsychologyTest::active()->findOrFail($id);
        return response()->json($test);
    }

    public function submitPsychologyTest(Request $request, $id)
    {
        $request->validate([
            'answers' => 'required|array'
        ]);

        $test = PsychologyTest::active()->findOrFail($id);
        $result = $test->calculateResult($request->answers);

        return response()->json([
            'test' => $test,
            'result' => $result,
            'answers' => $request->answers
        ]);
    }

    public function getCategories()
    {
        return response()->json([
            'mood_categories' => [
                ['value' => 'very_sad', 'label' => 'Çok Üzgün', 'emoji' => '😢'],
                ['value' => 'sad', 'label' => 'Üzgün', 'emoji' => '😔'],
                ['value' => 'neutral', 'label' => 'Normal', 'emoji' => '😐'],
                ['value' => 'happy', 'label' => 'Mutlu', 'emoji' => '😊'],
                ['value' => 'very_happy', 'label' => 'Çok Mutlu', 'emoji' => '😄']
            ],
            'quote_categories' => [
                ['value' => 'motivation', 'label' => 'Motivasyon'],
                ['value' => 'self_love', 'label' => 'Öz Sevgi'],
                ['value' => 'success', 'label' => 'Başarı'],
                ['value' => 'happiness', 'label' => 'Mutluluk'],
                ['value' => 'strength', 'label' => 'Güç']
            ],
            'test_categories' => [
                ['value' => 'personality', 'label' => 'Kişilik'],
                ['value' => 'stress', 'label' => 'Stres'],
                ['value' => 'anxiety', 'label' => 'Kaygı'],
                ['value' => 'depression', 'label' => 'Depresyon'],
                ['value' => 'self_esteem', 'label' => 'Öz Güven']
            ]
        ]);
    }

    public function getSupportResources()
    {
        $resources = [
            [
                'id' => 1,
                'title' => 'Acil Durum Hatları',
                'type' => 'emergency',
                'items' => [
                    ['name' => 'İntihar Önleme Hattı', 'contact' => '182', 'available' => '7/24'],
                    ['name' => 'Kadın Danışma Hattı', 'contact' => '183', 'available' => '7/24'],
                    ['name' => 'Şiddet Önleme Hattı', 'contact' => '155', 'available' => '7/24']
                ]
            ],
            [
                'id' => 2,
                'title' => 'Online Terapi Platformları',
                'type' => 'therapy',
                'items' => [
                    ['name' => 'Terappin', 'description' => 'Online psikoloji danışmanlığı'],
                    ['name' => 'Ruh Sağlığı Merkezi', 'description' => 'Uzman psikolog desteği'],
                    ['name' => 'Mindfulness Uygulamaları', 'description' => 'Meditasyon ve farkındalık']
                ]
            ],
            [
                'id' => 3,
                'title' => 'Destek Grupları',
                'type' => 'support_groups',
                'items' => [
                    ['name' => 'Anneler Destek Grubu', 'description' => 'Annelik deneyimi paylaşımı'],
                    ['name' => 'Kaygı Yönetimi Grubu', 'description' => 'Kaygı ile başa çıkma teknikleri'],
                    ['name' => 'Öz Güven Geliştirme', 'description' => 'Kendine güven artırma']
                ]
            ]
        ];

        return response()->json(['success' => true, 'data' => $resources]);
    }
}