<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Yeni Üye',
                'description' => 'Platforma hoş geldin!',
                'icon' => '🌟',
                'color' => '#10b981',
                'criteria' => ['action' => 'register'],
                'is_active' => true
            ],
            [
                'name' => 'İlk Yazı',
                'description' => 'İlk forum yazını yazdın',
                'icon' => '✍️',
                'color' => '#3b82f6',
                'criteria' => ['action' => 'first_post'],
                'is_active' => true
            ],
            [
                'name' => 'Popüler Yazar',
                'description' => '10 beğeni aldın',
                'icon' => '👑',
                'color' => '#f59e0b',
                'criteria' => ['action' => 'likes_received', 'count' => 10],
                'is_active' => true
            ],
            [
                'name' => 'Aktif Üye',
                'description' => '7 gün üst üste giriş yaptın',
                'icon' => '🔥',
                'color' => '#ef4444',
                'criteria' => ['action' => 'daily_streak', 'count' => 7],
                'is_active' => true
            ],
            [
                'name' => 'Yardımsever',
                'description' => '5 soruya cevap verdin',
                'icon' => '🤝',
                'color' => '#8b5cf6',
                'criteria' => ['action' => 'answers_given', 'count' => 5],
                'is_active' => true
            ],
            [
                'name' => 'Uzman',
                'description' => '1000 reputation puanına ulaştın',
                'icon' => '🎓',
                'color' => '#06b6d4',
                'criteria' => ['action' => 'reputation_score', 'count' => 1000],
                'is_active' => true
            ]
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}