<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PregnancyWeek;

class PregnancyWeekSeeder extends Seeder
{
    public function run()
    {
        $weeks = [
            [
                'week_number' => 1,
                'title' => '1. Hafta - Başlangıç',
                'description' => 'Gebeliğin ilk haftası, son adet tarihinden itibaren hesaplanır.',
                'baby_development' => 'Henüz döllenme gerçekleşmemiştir.',
                'mother_changes' => 'Vücudunuz gebeliğe hazırlanmaya başlar.',
                'tips' => ['Folik asit almaya başlayın', 'Sağlıklı beslenin', 'Sigara ve alkolü bırakın'],
                'baby_size' => null,
                'baby_weight' => null
            ],
            [
                'week_number' => 4,
                'title' => '4. Hafta - İlk Belirtiler',
                'description' => 'Gebelik testinin pozitif çıkabileceği hafta.',
                'baby_development' => 'Embriyonun kalbi atmaya başlar. Boyut yaklaşık 2mm.',
                'mother_changes' => 'Adet gecikmesi, mide bulantısı başlayabilir.',
                'tips' => ['Doktora başvurun', 'Prenatal vitamin alın', 'Kafein tüketimini azaltın'],
                'baby_size' => 'Haşhaş tanesi',
                'baby_weight' => '1g'
            ],
            [
                'week_number' => 8,
                'title' => '8. Hafta - Organ Gelişimi',
                'description' => 'Bebeğin temel organları şekillenmeye başlar.',
                'baby_development' => 'Kalp, beyin, böbrekler gelişir. Parmaklar belirginleşir.',
                'mother_changes' => 'Bulantı artabilir, göğüslerde hassasiyet.',
                'tips' => ['İlk ultrason randevusu', 'Bol su için', 'Küçük öğünler halinde beslenin'],
                'baby_size' => 'Böğürtlen',
                'baby_weight' => '1g'
            ],
            [
                'week_number' => 12,
                'title' => '12. Hafta - İlk Trimester Sonu',
                'description' => 'İlk trimester tamamlanır, düşük riski azalır.',
                'baby_development' => 'Tüm organlar oluşmuştur. Parmak izleri belirginleşir.',
                'mother_changes' => 'Bulantı azalmaya başlar, enerji artar.',
                'tips' => ['Gebeliği açıklayabilirsiniz', 'Düzenli egzersiz', 'Kalsiyum alımını artırın'],
                'baby_size' => 'Erik',
                'baby_weight' => '14g'
            ],
            [
                'week_number' => 20,
                'title' => '20. Hafta - Yarı Yol',
                'description' => 'Gebeliğin yarısı tamamlandı.',
                'baby_development' => 'Cinsiyet belirlenebilir. Saç ve tırnaklar çıkar.',
                'mother_changes' => 'Karın belirginleşir, ilk hareketler hissedilebilir.',
                'tips' => ['Anomali taraması', 'Rahat giysiler', 'Yan yatış pozisyonu'],
                'baby_size' => 'Muz',
                'baby_weight' => '300g'
            ],
            [
                'week_number' => 28,
                'title' => '28. Hafta - Üçüncü Trimester',
                'description' => 'Son trimester başlar.',
                'baby_development' => 'Akciğerler gelişir, göz kapakları açılır.',
                'mother_changes' => 'Nefes darlığı, sırt ağrısı başlayabilir.',
                'tips' => ['Doğum kursu', 'Hastane çantası hazırlığı', 'Dinlenme'],
                'baby_size' => 'Patlıcan',
                'baby_weight' => '1100g'
            ],
            [
                'week_number' => 36,
                'title' => '36. Hafta - Doğuma Hazırlık',
                'description' => 'Bebek doğuma hazır hale gelir.',
                'baby_development' => 'Akciğerler olgunlaşır, immün sistem güçlenir.',
                'mother_changes' => 'Braxton Hicks kasılmaları artar.',
                'tips' => ['Doğum planı', 'Nefes egzersizleri', 'Hastane yolu'],
                'baby_size' => 'Kavun',
                'baby_weight' => '2700g'
            ],
            [
                'week_number' => 40,
                'title' => '40. Hafta - Doğum Zamanı',
                'description' => 'Tahmini doğum tarihi.',
                'baby_development' => 'Bebek tamamen gelişmiş ve doğuma hazır.',
                'mother_changes' => 'Doğum belirtileri: kasılmalar, su gelmesi.',
                'tips' => ['Sakin kalın', 'Doktor ile iletişim', 'Hastaneye gitme zamanı'],
                'baby_size' => 'Karpuz',
                'baby_weight' => '3400g'
            ]
        ];

        foreach ($weeks as $week) {
            PregnancyWeek::create($week);
        }
    }
}