<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Horoscope extends Model
{
    use HasFactory;

    protected $fillable = [
        'zodiac_sign',
        'date',
        'type',
        'category',
        'content',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function getZodiacSigns()
    {
        return [
            'aries' => 'Koç',
            'taurus' => 'Boğa',
            'gemini' => 'İkizler',
            'cancer' => 'Yengeç',
            'leo' => 'Aslan',
            'virgo' => 'Başak',
            'libra' => 'Terazi',
            'scorpio' => 'Akrep',
            'sagittarius' => 'Yay',
            'capricorn' => 'Oğlak',
            'aquarius' => 'Kova',
            'pisces' => 'Balık',
        ];
    }

    public static function getTodayHoroscope($zodiacSign, $category = 'general')
    {
        return self::where('zodiac_sign', $zodiacSign)
            ->where('date', Carbon::today())
            ->where('type', 'daily')
            ->where('category', $category)
            ->first();
    }

    public function getZodiacNameAttribute()
    {
        return self::getZodiacSigns()[$this->zodiac_sign] ?? $this->zodiac_sign;
    }
}