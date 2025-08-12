<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AstrologyService;
use Illuminate\Http\Request;

class AstrologyController extends Controller
{
    private $astrologyService;

    public function __construct(AstrologyService $astrologyService)
    {
        $this->astrologyService = $astrologyService;
    }

    public function daily(Request $request)
    {
        $sign = $request->get('sign');
        
        if (!$sign) {
            return response()->json(['error' => 'Burç bilgisi gerekli'], 400);
        }

        $horoscope = $this->astrologyService->getDailyHoroscope($sign);
        
        return response()->json($horoscope);
    }

    public function weekly(Request $request)
    {
        $sign = $request->get('sign');
        
        if (!$sign) {
            return response()->json(['error' => 'Burç bilgisi gerekli'], 400);
        }

        $horoscope = $this->astrologyService->getWeeklyHoroscope($sign);
        
        return response()->json($horoscope);
    }

    public function monthly(Request $request)
    {
        $sign = $request->get('sign');
        
        if (!$sign) {
            return response()->json(['error' => 'Burç bilgisi gerekli'], 400);
        }

        $horoscope = $this->astrologyService->getMonthlyHoroscope($sign);
        
        return response()->json($horoscope);
    }

    public function getSignByDate(Request $request)
    {
        $birthDate = $request->get('birth_date');
        
        if (!$birthDate) {
            return response()->json(['error' => 'Doğum tarihi gerekli'], 400);
        }

        $sign = $this->astrologyService->getSignByBirthDate($birthDate);
        
        return response()->json(['sign' => $sign]);
    }
}