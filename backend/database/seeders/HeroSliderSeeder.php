<?php

namespace Database\Seeders;

use App\Models\HeroSlider;
use Illuminate\Database\Seeder;

class HeroSliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'KadınAtlası ile Hayatınızı Kolaylaştırın',
                'description' => 'Sağlık, güzellik, gebelik takibi ve daha fazlası... Kadınların ihtiyaç duyduğu her şey bir arada!',
                'button_text' => 'Hemen Başla',
                'button_link' => '/register',
                'background_color' => '#ec4899',
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Premium Üyelik ile Özel Avantajlar',
                'description' => 'Reklamsız deneyim, özel içerikler ve uzman desteği ile premium deneyimi yaşayın',
                'button_text' => 'Premium Ol',
                'button_link' => '/premium',
                'background_color' => '#f59e0b',
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Toplulukla Bağlantı Kurun',
                'description' => 'Binlerce kadınla deneyimlerinizi paylaşın, sorularınızı sorun ve destek alın',
                'button_text' => 'Forum\'a Katıl',
                'button_link' => '/forum',
                'background_color' => '#8b5cf6',
                'order' => 3,
                'is_active' => true
            ]
        ];

        foreach ($sliders as $slider) {
            HeroSlider::create($slider);
        }
    }
}