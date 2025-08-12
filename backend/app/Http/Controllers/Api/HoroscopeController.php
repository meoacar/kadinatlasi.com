<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horoscope;
use App\Services\AstrologyApiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HoroscopeController extends Controller
{
    protected $astrologyService;

    public function __construct(AstrologyApiService $astrologyService)
    {
        $this->astrologyService = $astrologyService;
    }
    public function getZodiacSigns()
    {
        return response()->json([
            'success' => true,
            'data' => Horoscope::getZodiacSigns()
        ]);
    }

    public function getTodayHoroscope(Request $request)
    {
        $request->validate([
            'zodiac_sign' => 'required|string',
            'category' => 'sometimes|in:general,love,career,health'
        ]);

        $category = $request->get('category', 'general');
        $horoscope = Horoscope::getTodayHoroscope($request->zodiac_sign, $category);

        if (!$horoscope) {
            // API'den fresh data çek
            $horoscope = $this->astrologyService->fetchHoroscope($request->zodiac_sign, 'daily', $category);
        }

        return response()->json([
            'success' => true,
            'data' => $horoscope
        ]);
    }

    public function getHoroscope(Request $request)
    {
        $request->validate([
            'zodiac_sign' => 'required|string',
            'type' => 'sometimes|in:daily,weekly,monthly',
            'category' => 'sometimes|in:general,love,career,health'
        ]);

        $type = $request->get('type', 'daily');
        $category = $request->get('category', 'general');
        
        // API'den fresh data çek
        $horoscope = $this->astrologyService->fetchHoroscope(
            $request->zodiac_sign, 
            $type, 
            $category
        );

        return response()->json([
            'success' => true,
            'data' => $horoscope
        ]);
    }

    public function getWeeklyHoroscope(Request $request)
    {
        $request->validate([
            'zodiac_sign' => 'required|string',
            'category' => 'sometimes|in:general,love,career,health'
        ]);

        $category = $request->get('category', 'general');
        $horoscope = $this->astrologyService->fetchHoroscope(
            $request->zodiac_sign, 
            'weekly', 
            $category
        );

        return response()->json([
            'success' => true,
            'data' => $horoscope
        ]);
    }

    public function getMonthlyHoroscope(Request $request)
    {
        $request->validate([
            'zodiac_sign' => 'required|string',
            'category' => 'sometimes|in:general,love,career,health'
        ]);

        $category = $request->get('category', 'general');
        $horoscope = $this->astrologyService->fetchHoroscope(
            $request->zodiac_sign, 
            'monthly', 
            $category
        );

        return response()->json([
            'success' => true,
            'data' => $horoscope
        ]);
    }

    public function refreshAllHoroscopes(Request $request)
    {
        $request->validate([
            'type' => 'sometimes|in:daily,weekly,monthly',
            'category' => 'sometimes|in:general,love,career,health'
        ]);

        $type = $request->get('type', 'daily');
        $category = $request->get('category', 'general');
        
        $horoscopes = $this->astrologyService->fetchAllSigns($type, $category);

        return response()->json([
            'success' => true,
            'data' => $horoscopes,
            'message' => count($horoscopes) . ' burç yorumu güncellendi'
        ]);
    }

    private function generateSampleHoroscope($zodiacSign, $category)
    {
        $sampleContents = [
            'general' => [
                'Bugün enerjiniz yüksek olacak. Yeni fırsatlar sizi bekliyor.',
                'Sabırlı olmanız gereken bir gün. Her şey zamanında gelecek.',
                'Yaratıcılığınızı ön plana çıkarın. Sanatsal aktiviteler size iyi gelecek.',
                'Sosyal çevrenizle güzel vakit geçireceğiniz bir gün.',
                'Kendinize zaman ayırın. İç huzurunuzu bulmanın zamanı.'
            ],
            'love' => [
                'Aşk hayatınızda güzel gelişmeler olabilir.',
                'Partnerinizle iletişiminizi güçlendirin.',
                'Yeni tanışıklıklar hayatınıza renk katabilir.',
                'Duygularınızı açık bir şekilde ifade edin.'
            ],
            'career' => [
                'İş hayatınızda yeni fırsatlar doğabilir.',
                'Projelerinizde başarılı olacağınız bir dönem.',
                'Mesleki gelişiminiz için adımlar atın.',
                'Ekip çalışmasında öne çıkacaksınız.'
            ],
            'health' => [
                'Sağlığınıza dikkat etmeniz gereken bir dönem.',
                'Düzenli egzersiz yapmanın faydalarını göreceksiniz.',
                'Beslenme alışkanlıklarınızı gözden geçirin.',
                'Stres seviyenizi kontrol altında tutun.'
            ]
        ];

        $content = $sampleContents[$category][array_rand($sampleContents[$category])];

        return Horoscope::create([
            'zodiac_sign' => $zodiacSign,
            'date' => Carbon::today(),
            'type' => 'daily',
            'category' => $category,
            'content' => $content,
            'source' => 'sample'
        ]);
    }
}