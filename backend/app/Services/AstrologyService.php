<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AstrologyService
{
    private $signs = [
        'koc' => 'Koç',
        'boga' => 'Boğa', 
        'ikizler' => 'İkizler',
        'yengec' => 'Yengeç',
        'aslan' => 'Aslan',
        'basak' => 'Başak',
        'terazi' => 'Terazi',
        'akrep' => 'Akrep',
        'yay' => 'Yay',
        'oglak' => 'Oğlak',
        'kova' => 'Kova',
        'balik' => 'Balık'
    ];

    public function getDailyHoroscope($sign)
    {
        $cacheKey = "horoscope_daily_{$sign}";
        
        return Cache::remember($cacheKey, 3600, function () use ($sign) {
            // Mock data - gerçek API entegrasyonu için değiştirilebilir
            return $this->generateMockHoroscope($sign, 'daily');
        });
    }

    public function getWeeklyHoroscope($sign)
    {
        $cacheKey = "horoscope_weekly_{$sign}";
        
        return Cache::remember($cacheKey, 86400, function () use ($sign) {
            return $this->generateMockHoroscope($sign, 'weekly');
        });
    }

    public function getMonthlyHoroscope($sign)
    {
        $cacheKey = "horoscope_monthly_{$sign}";
        
        return Cache::remember($cacheKey, 604800, function () use ($sign) {
            return $this->generateMockHoroscope($sign, 'monthly');
        });
    }

    private function generateMockHoroscope($sign, $period)
    {
        $templates = [
            'daily' => [
                'love' => [
                    'Bugün aşk hayatınızda güzel gelişmeler yaşayabilirsiniz.',
                    'Partnerinizle aranızdaki bağ güçlenebilir.',
                    'Yeni bir aşka adım atma zamanı gelebilir.'
                ],
                'health' => [
                    'Sağlığınıza dikkat etmeniz gereken bir gün.',
                    'Enerji seviyeniz yüksek olacak.',
                    'Spor yapmak için ideal bir gün.'
                ],
                'career' => [
                    'İş hayatında önemli fırsatlar çıkabilir.',
                    'Projelerinizde başarılı sonuçlar alabilirsiniz.',
                    'Yeni iş teklifleri gelebilir.'
                ]
            ]
        ];

        $signName = $this->signs[$sign] ?? 'Bilinmeyen';
        
        return [
            'sign' => $signName,
            'period' => $period,
            'date' => now()->format('d.m.Y'),
            'love' => $templates['daily']['love'][array_rand($templates['daily']['love'])],
            'health' => $templates['daily']['health'][array_rand($templates['daily']['health'])],
            'career' => $templates['daily']['career'][array_rand($templates['daily']['career'])],
            'general' => "Bugün {$signName} burcu için genel olarak olumlu bir gün olacak."
        ];
    }

    public function getSignByBirthDate($birthDate)
    {
        $date = \Carbon\Carbon::parse($birthDate);
        $month = $date->month;
        $day = $date->day;

        if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) return 'koc';
        if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) return 'boga';
        if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 20)) return 'ikizler';
        if (($month == 6 && $day >= 21) || ($month == 7 && $day <= 22)) return 'yengec';
        if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) return 'aslan';
        if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) return 'basak';
        if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 22)) return 'terazi';
        if (($month == 10 && $day >= 23) || ($month == 11 && $day <= 21)) return 'akrep';
        if (($month == 11 && $day >= 22) || ($month == 12 && $day <= 21)) return 'yay';
        if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) return 'oglak';
        if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) return 'kova';
        if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) return 'balik';

        return 'koc';
    }
}