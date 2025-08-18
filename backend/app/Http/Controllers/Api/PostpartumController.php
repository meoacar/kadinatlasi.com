<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostpartumController extends Controller
{
    public function getGuides()
    {
        $guides = [
            [
                'id' => 1,
                'title' => 'İlk 24 Saat - Lohusalık Başlangıcı',
                'period' => '0-1 gün',
                'content' => 'Doğum sonrası ilk saatlerde vücudunuz iyileşme sürecine başlar. Bu dönemde dikkat edilmesi gerekenler.',
                'tips' => [
                    'Mümkün olduğunca dinlenin ve uyuyun',
                    'Bol sıvı tüketin (su, bitki çayları)',
                    'Bebeğinizle ten tene temas kurun',
                    'İlk emzirmeyi 1 saat içinde yapın',
                    'Kanama miktarını takip edin'
                ],
                'warnings' => ['Aşırı kanama', 'Yüksek ateş (38°C üzeri)', 'Şiddetli baş ağrısı', 'Nefes darlığı']
            ],
            [
                'id' => 2,
                'title' => 'İlk Hafta - Lohusalık Dönemi',
                'period' => '1-7 gün',
                'content' => 'Doğum sonrası ilk hafta vücudunuzun toparlanma süreci. Emzirme ve bebek bakımına alışma dönemi.',
                'tips' => [
                    'Emzirme rutini oluşturun (2-3 saatte bir)',
                    'Yardım isteyin ve kabul edin',
                    'Kendi bakımınızı ihmal etmeyin',
                    'Beslenmenize özen gösterin',
                    'Kısa yürüyüşler yapın'
                ],
                'warnings' => ['Mastit belirtileri', 'Depresif hisler', 'Aşırı yorgunluk', 'İdrar yaparken yanma']
            ],
            [
                'id' => 3,
                'title' => 'İlk Ay - Toparlanma Süreci',
                'period' => '1-4 hafta',
                'content' => 'Doğum sonrası ilk ay boyunca vücudunuz normale dönmeye başlar. Bebek bakımında deneyim kazanırsınız.',
                'tips' => [
                    'Bebek blues normal bir durumdur',
                    'Protein ve vitamin açısından zengin beslenin',
                    'Hafif egzersizlere başlayın (doktor onayı ile)',
                    'Sosyal destek alın',
                    'Düzenli kontrollere gidin'
                ],
                'warnings' => ['2 haftadan uzun süren üzüntü', 'Aşırı kaygı', 'Bebeğe zarar verme düşünceleri']
            ],
            [
                'id' => 4,
                'title' => '2-6 Ay - Adaptasyon Dönemi',
                'period' => '2-6 ay',
                'content' => 'Lohusalık dönemi sona erer, yeni yaşam düzeninize alışırsınız.',
                'tips' => [
                    'Egzersiz rutininizi artırın',
                    'Sosyal aktivitelere katılın',
                    'Kendi zamanınızı yaratın',
                    'Beslenme düzeninizi koruyun',
                    'Uyku düzeninizi optimize edin'
                ],
                'warnings' => ['Uzun süreli depresif hisler', 'Aşırı stres', 'Fiziksel şikayetler']
            ]
        ];

        return response()->json(['success' => true, 'data' => $guides]);
    }

    public function getGuide($id)
    {
        $allGuides = $this->getGuides()->getData()->data;
        $guide = array_filter($allGuides, function($g) use ($id) {
            return $g['id'] == $id;
        });

        if (empty($guide)) {
            return response()->json(['success' => false, 'message' => 'Rehber bulunamadı'], 404);
        }

        return response()->json(['success' => true, 'data' => array_values($guide)[0]]);
    }

    public function getBreastfeedingTips()
    {
        $tips = [
            [
                'id' => 1,
                'title' => 'Emzirme Pozisyonları',
                'content' => 'Doğru emzirme pozisyonları anne ve bebek için konforlu emzirme sağlar.',
                'tips' => [
                    'Beşik pozisyonu - En yaygın pozisyon',
                    'Çapraz beşik - Yenidoğanlar için ideal',
                    'Futbol pozisyonu - Sezaryen sonrası uygun',
                    'Yan yatma pozisyonu - Gece emzirme için'
                ]
            ],
            [
                'id' => 2,
                'title' => 'Emzirme Sorunları ve Çözümleri',
                'content' => 'Yaygın emzirme sorunları ve pratik çözüm önerileri.',
                'tips' => [
                    'Meme ucu çatlakları için lanolin krem',
                    'Mastit için sıcak kompres',
                    'Süt azlığı için sık emzirme',
                    'Süt fazlalığı için soğuk kompres'
                ]
            ]
        ];

        return response()->json(['success' => true, 'data' => $tips]);
    }

    public function getNutritionGuide()
    {
        $guide = [
            'title' => 'Lohusalık Dönemi Beslenme Rehberi',
            'daily_needs' => [
                'calories' => '2500-2700 kalori (emziren anneler için)',
                'protein' => '71 gram protein',
                'calcium' => '1300 mg kalsiyum',
                'iron' => '9 mg demir',
                'water' => '3-4 litre sıvı'
            ],
            'recommended_foods' => [
                'Protein kaynakları: Et, tavuk, balık, yumurta, baklagiller',
                'Kalsiyum kaynakları: Süt, peynir, yeşil yapraklı sebzeler',
                'Demir kaynakları: Kırmızı et, ıspanak, kuru meyve',
                'Omega-3: Balık, ceviz, keten tohumu',
                'Folat: Yeşil yapraklı sebzeler, turunçgiller'
            ],
            'avoid_foods' => [
                'Alkol',
                'Aşırı kafein',
                'İşlenmiş gıdalar',
                'Çiğ et ve balık',
                'Yüksek cıvalı balıklar'
            ]
        ];

        return response()->json(['success' => true, 'data' => $guide]);
    }
}