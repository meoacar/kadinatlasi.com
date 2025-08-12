<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        $events = [
            [
                'title' => 'Kadın Girişimciliği Atölyesi',
                'description' => 'Kendi işinizi kurmak istiyorsanız bu atölye tam size göre! Girişimcilik sürecinin tüm aşamalarını öğrenin.',
                'type' => 'workshop',
                'start_date' => Carbon::now()->addDays(7)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(7)->setTime(17, 0),
                'location' => 'İstanbul Kadın Girişimciler Merkezi',
                'max_participants' => 25,
                'price' => 150.00,
                'is_free' => false,
                'is_active' => true,
                'tags' => ['girişimcilik', 'iş kurma', 'kadın']
            ],
            [
                'title' => 'Mindfulness ve Stres Yönetimi',
                'description' => 'Günlük hayatta stresi azaltmak ve mindfulness teknikleri öğrenmek için katılın.',
                'type' => 'seminar',
                'start_date' => Carbon::now()->addDays(10)->setTime(19, 0),
                'end_date' => Carbon::now()->addDays(10)->setTime(21, 0),
                'online_link' => 'https://zoom.us/j/123456789',
                'max_participants' => 50,
                'price' => 0,
                'is_free' => true,
                'is_active' => true,
                'tags' => ['mindfulness', 'stres', 'meditasyon']
            ],
            [
                'title' => 'Sağlıklı Beslenme Webinarı',
                'description' => 'Uzman diyetisyenlerden sağlıklı beslenme ipuçları ve pratik tarifler öğrenin.',
                'type' => 'webinar',
                'start_date' => Carbon::now()->addDays(5)->setTime(20, 0),
                'end_date' => Carbon::now()->addDays(5)->setTime(21, 30),
                'online_link' => 'https://zoom.us/j/987654321',
                'max_participants' => 100,
                'price' => 0,
                'is_free' => true,
                'is_active' => true,
                'tags' => ['beslenme', 'sağlık', 'diyet']
            ],
            [
                'title' => 'Kadın Dayanışması Kahve Buluşması',
                'description' => 'Diğer kadınlarla tanışın, deneyimlerinizi paylaşın ve yeni arkadaşlıklar kurun.',
                'type' => 'meetup',
                'start_date' => Carbon::now()->addDays(3)->setTime(15, 0),
                'end_date' => Carbon::now()->addDays(3)->setTime(17, 0),
                'location' => 'Starbucks Nişantaşı',
                'max_participants' => 15,
                'price' => 0,
                'is_free' => true,
                'is_active' => true,
                'tags' => ['buluşma', 'networking', 'kahve']
            ],
            [
                'title' => 'Yoga ve Meditasyon Atölyesi',
                'description' => 'Vücut ve zihin sağlığınız için yoga ve meditasyon teknikleri öğrenin.',
                'type' => 'workshop',
                'start_date' => Carbon::now()->addDays(14)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(14)->setTime(12, 0),
                'location' => 'Yoga Studio Beşiktaş',
                'max_participants' => 20,
                'price' => 75.00,
                'is_free' => false,
                'is_active' => true,
                'tags' => ['yoga', 'meditasyon', 'wellness']
            ],
            [
                'title' => 'Dijital Pazarlama Semineri',
                'description' => 'Sosyal medya ve dijital pazarlama stratejileri ile işinizi büyütün.',
                'type' => 'seminar',
                'start_date' => Carbon::now()->addDays(21)->setTime(18, 0),
                'end_date' => Carbon::now()->addDays(21)->setTime(20, 0),
                'online_link' => 'https://zoom.us/j/555666777',
                'max_participants' => 75,
                'price' => 100.00,
                'is_free' => false,
                'is_active' => true,
                'tags' => ['dijital pazarlama', 'sosyal medya', 'iş']
            ]
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}