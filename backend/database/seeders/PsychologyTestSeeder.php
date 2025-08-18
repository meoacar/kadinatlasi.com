<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PsychologyTest;

class PsychologyTestSeeder extends Seeder
{
    public function run()
    {
        $tests = [
            [
                'title' => 'Stres Seviyesi Testi',
                'description' => 'Günlük hayatınızdaki stres seviyenizi ölçen kısa test',
                'category' => 'stress',
                'duration_minutes' => 5,
                'questions' => [
                    [
                        'question' => 'Son bir hafta içinde ne sıklıkla kendinizi gergin hissettiniz?',
                        'options' => ['Hiç', 'Nadiren', 'Bazen', 'Sık sık', 'Her zaman'],
                        'weight' => 1
                    ],
                    [
                        'question' => 'Uyku problemi yaşıyor musunuz?',
                        'options' => ['Hiç', 'Nadiren', 'Bazen', 'Sık sık', 'Her zaman'],
                        'weight' => 1
                    ],
                    [
                        'question' => 'Günlük işlerinizi tamamlamakta zorlanıyor musunuz?',
                        'options' => ['Hiç', 'Nadiren', 'Bazen', 'Sık sık', 'Her zaman'],
                        'weight' => 1
                    ],
                    [
                        'question' => 'Konsantrasyon problemi yaşıyor musunuz?',
                        'options' => ['Hiç', 'Nadiren', 'Bazen', 'Sık sık', 'Her zaman'],
                        'weight' => 1
                    ],
                    [
                        'question' => 'Fiziksel belirtiler (baş ağrısı, mide problemi) yaşıyor musunuz?',
                        'options' => ['Hiç', 'Nadiren', 'Bazen', 'Sık sık', 'Her zaman'],
                        'weight' => 1
                    ]
                ],
                'results' => [
                    [
                        'min_score' => 0,
                        'max_score' => 5,
                        'title' => 'Düşük Stres',
                        'description' => 'Stres seviyeniz normal aralıkta. Mevcut yaşam tarzınızı sürdürmeye devam edin.'
                    ],
                    [
                        'min_score' => 6,
                        'max_score' => 10,
                        'title' => 'Orta Stres',
                        'description' => 'Orta seviyede stres yaşıyorsunuz. Rahatlama tekniklerini deneyebilirsiniz.'
                    ],
                    [
                        'min_score' => 11,
                        'max_score' => 15,
                        'title' => 'Yüksek Stres',
                        'description' => 'Yüksek stres seviyesi. Profesyonel destek almanızı öneririz.'
                    ],
                    [
                        'min_score' => 16,
                        'max_score' => 20,
                        'title' => 'Çok Yüksek Stres',
                        'description' => 'Acil profesyonel destek gerekiyor. Lütfen bir uzmana başvurun.'
                    ]
                ]
            ],
            [
                'title' => 'Öz Güven Testi',
                'description' => 'Kendinize olan güveninizi ölçen kişilik testi',
                'category' => 'self_esteem',
                'duration_minutes' => 7,
                'questions' => [
                    [
                        'question' => 'Yeni insanlarla tanışmaktan hoşlanır mısınız?',
                        'options' => ['Kesinlikle hayır', 'Hayır', 'Kararsızım', 'Evet', 'Kesinlikle evet'],
                        'weight' => 1
                    ],
                    [
                        'question' => 'Hatalarınızı kabul etmekte zorlanır mısınız?',
                        'options' => ['Kesinlikle evet', 'Evet', 'Kararsızım', 'Hayır', 'Kesinlikle hayır'],
                        'weight' => -1
                    ],
                    [
                        'question' => 'Başkalarının fikirlerini kendi fikirlerinizden daha değerli bulur musunuz?',
                        'options' => ['Kesinlikle evet', 'Evet', 'Kararsızım', 'Hayır', 'Kesinlikle hayır'],
                        'weight' => -1
                    ],
                    [
                        'question' => 'Kendinizi başarılı biri olarak görür müsünüz?',
                        'options' => ['Kesinlikle hayır', 'Hayır', 'Kararsızım', 'Evet', 'Kesinlikle evet'],
                        'weight' => 1
                    ]
                ],
                'results' => [
                    [
                        'min_score' => -8,
                        'max_score' => 0,
                        'title' => 'Düşük Öz Güven',
                        'description' => 'Öz güveninizi artırmak için çalışmanız gerekiyor. Küçük başarılarınızı kutlayın.'
                    ],
                    [
                        'min_score' => 1,
                        'max_score' => 8,
                        'title' => 'Orta Öz Güven',
                        'description' => 'Öz güveniniz orta seviyede. Kendinize daha çok inanmaya çalışın.'
                    ],
                    [
                        'min_score' => 9,
                        'max_score' => 16,
                        'title' => 'Yüksek Öz Güven',
                        'description' => 'Harika! Kendinize güveniniz yüksek. Bu durumu korumaya devam edin.'
                    ]
                ]
            ]
        ];

        foreach ($tests as $test) {
            PsychologyTest::create($test);
        }
    }
}