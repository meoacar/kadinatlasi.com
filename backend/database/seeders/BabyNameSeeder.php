<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BabyName;

class BabyNameSeeder extends Seeder
{
    public function run()
    {
        // Önce mevcut verileri temizle
        BabyName::truncate();
        
        $names = [
            // Popüler Kız İsimleri
            [
                'name' => 'Zeynep',
                'gender' => 'kiz',
                'origin' => 'Arapça',
                'meaning' => 'Güzel kokulu çiçek, Hz. Muhammed\'in kızının ismi',
                'description' => 'Türkiye\'de en popüler kız isimlerinden biri. Zarafet ve güzellik simgesi.',
                'popularity_rank' => 1,
                'is_popular' => true
            ],
            [
                'name' => 'Elif',
                'gender' => 'kiz',
                'origin' => 'Arapça',
                'meaning' => 'Arap alfabesinin ilk harfi, zarif ve güzel',
                'description' => 'Sadelik ve zarafeti temsil eden klasik isim.',
                'popularity_rank' => 2,
                'is_popular' => true
            ],
            [
                'name' => 'Defne',
                'gender' => 'kiz',
                'origin' => 'Yunanca',
                'meaning' => 'Defne ağacı, zafer simgesi',
                'description' => 'Antik Yunan\'da zafer ve başarı simgesi olan defne yaprağından gelir.',
                'popularity_rank' => 3,
                'is_popular' => true
            ],
            [
                'name' => 'Miray',
                'gender' => 'kiz',
                'origin' => 'Türkçe-Farsça',
                'meaning' => 'Ay ışığı, prenses',
                'description' => 'Modern ve şık bir isim. Ay\'ın güzelliğini ve asaleti ifade eder.',
                'popularity_rank' => 4,
                'is_popular' => true
            ],
            [
                'name' => 'Azra',
                'gender' => 'kiz',
                'origin' => 'Arapça',
                'meaning' => 'Bakire, temiz, saf',
                'description' => 'Saflık ve temizliği temsil eden güzel bir isim.',
                'popularity_rank' => 5,
                'is_popular' => true
            ],
            [
                'name' => 'Ela',
                'gender' => 'kiz',
                'origin' => 'Türkçe',
                'meaning' => 'Ela gözlü, fındık rengi gözlü',
                'description' => 'Güzel gözleri ifade eden sevimli isim.',
                'popularity_rank' => 6,
                'is_popular' => true
            ],
            [
                'name' => 'Asya',
                'gender' => 'kiz',
                'origin' => 'Yunanca',
                'meaning' => 'Doğu, Asya kıtası',
                'description' => 'Büyüklük ve genişliği ifade eden coğrafi isim.',
                'popularity_rank' => 7,
                'is_popular' => true
            ],
            [
                'name' => 'Nehir',
                'gender' => 'kiz',
                'origin' => 'Türkçe',
                'meaning' => 'Akarsu, nehir',
                'description' => 'Doğal güzellik ve sürekli akışı temsil eder.',
                'popularity_rank' => 8,
                'is_popular' => true
            ],
            [
                'name' => 'Lina',
                'gender' => 'kiz',
                'origin' => 'Arapça-Latince',
                'meaning' => 'Yumuşak, nazik, ince',
                'description' => 'Zarafet ve inceliği ifade eden uluslararası isim.',
                'popularity_rank' => 9,
                'is_popular' => true
            ],
            [
                'name' => 'Derin',
                'gender' => 'kiz',
                'origin' => 'Türkçe',
                'meaning' => 'Derin, engin, derin düşünceli',
                'description' => 'Derinlik ve bilgeliği temsil eden modern isim.',
                'popularity_rank' => 10,
                'is_popular' => true
            ],
            [
                'name' => 'Selin',
                'gender' => 'kiz',
                'origin' => 'Yunanca',
                'meaning' => 'Ay ışığı, ay tanrıçası',
                'description' => 'Yunan mitolojisindeki ay tanrıçası Selene\'den türemiş.',
                'popularity_rank' => 11,
                'is_popular' => true
            ],
            [
                'name' => 'Naz',
                'gender' => 'kiz',
                'origin' => 'Farsça',
                'meaning' => 'Nazlı, şımarık, sevimli',
                'description' => 'Zarafet ve kadınsı inceliği ifade eder.',
                'popularity_rank' => 12,
                'is_popular' => false
            ],
            [
                'name' => 'Ece',
                'gender' => 'kiz',
                'origin' => 'Türkçe',
                'meaning' => 'Kraliçe, hanım',
                'description' => 'Asalet ve liderliği temsil eden güçlü isim.',
                'popularity_rank' => 13,
                'is_popular' => false
            ],
            [
                'name' => 'Yasemin',
                'gender' => 'kiz',
                'origin' => 'Farsça',
                'meaning' => 'Yasemin çiçeği',
                'description' => 'Güzel kokulu beyaz çiçeği temsil eden klasik isim.',
                'popularity_rank' => 14,
                'is_popular' => false
            ],
            [
                'name' => 'Melis',
                'gender' => 'kiz',
                'origin' => 'Yunanca',
                'meaning' => 'Bal, tatlı',
                'description' => 'Tatlılık ve sevimliği ifade eden modern isim.',
                'popularity_rank' => 15,
                'is_popular' => false
            ],

            // Popüler Erkek İsimleri
            [
                'name' => 'Yusuf',
                'gender' => 'erkek',
                'origin' => 'İbranice',
                'meaning' => 'Allah arttırsın, çoğaltsın',
                'description' => 'Hz. Yusuf peygamberin ismi. Türkiye\'de en popüler erkek isimlerinden.',
                'popularity_rank' => 1,
                'is_popular' => true
            ],
            [
                'name' => 'Emir',
                'gender' => 'erkek',
                'origin' => 'Arapça',
                'meaning' => 'Komutan, önder, emir veren',
                'description' => 'Liderlik ve yöneticilik özelliklerini temsil eder.',
                'popularity_rank' => 2,
                'is_popular' => true
            ],
            [
                'name' => 'Ömer',
                'gender' => 'erkek',
                'origin' => 'Arapça',
                'meaning' => 'Yaşayan, uzun ömürlü',
                'description' => 'Hz. Ömer\'in ismi. Uzun ve bereketli yaşam dileği.',
                'popularity_rank' => 3,
                'is_popular' => true
            ],
            [
                'name' => 'Mustafa',
                'gender' => 'erkek',
                'origin' => 'Arapça',
                'meaning' => 'Seçilmiş, tercih edilen',
                'description' => 'Hz. Muhammed\'in isimlerinden biri. Seçkinlik ifade eder.',
                'popularity_rank' => 4,
                'is_popular' => true
            ],
            [
                'name' => 'Ali',
                'gender' => 'erkek',
                'origin' => 'Arapça',
                'meaning' => 'Yüce, yüksek, asil',
                'description' => 'Hz. Ali\'nin ismi. Yücelik ve asaleti temsil eder.',
                'popularity_rank' => 5,
                'is_popular' => true
            ],
            [
                'name' => 'Efe',
                'gender' => 'erkek',
                'origin' => 'Türkçe',
                'meaning' => 'Ağabey, büyük, lider',
                'description' => 'Ege bölgesinde kullanılan geleneksel liderlik ismi.',
                'popularity_rank' => 6,
                'is_popular' => true
            ],
            [
                'name' => 'Kaan',
                'gender' => 'erkek',
                'origin' => 'Türkçe',
                'meaning' => 'Hükümdar, kağan, han',
                'description' => 'Türk tarihinde hükümdar unvanı. Güç ve liderlik simgesi.',
                'popularity_rank' => 7,
                'is_popular' => true
            ],
            [
                'name' => 'Mert',
                'gender' => 'erkek',
                'origin' => 'Türkçe',
                'meaning' => 'Cesur, yiğit, kahraman',
                'description' => 'Cesaret ve kahramanlığı temsil eden güçlü isim.',
                'popularity_rank' => 8,
                'is_popular' => true
            ],
            [
                'name' => 'Arda',
                'gender' => 'erkek',
                'origin' => 'Türkçe',
                'meaning' => 'Arka, destek, yardımcı',
                'description' => 'Güven ve desteği temsil eden modern isim.',
                'popularity_rank' => 9,
                'is_popular' => true
            ],
            [
                'name' => 'Berat',
                'gender' => 'erkek',
                'origin' => 'Arapça',
                'meaning' => 'Temize çıkarma belgesi, beraat',
                'description' => 'Temizlik ve masumiyeti ifade eden dini isim.',
                'popularity_rank' => 10,
                'is_popular' => true
            ],
            [
                'name' => 'Cem',
                'gender' => 'erkek',
                'origin' => 'Farsça',
                'meaning' => 'Toplayan, bir araya getiren',
                'description' => 'Birlik ve beraberliği temsil eden klasik isim.',
                'popularity_rank' => 11,
                'is_popular' => true
            ],
            [
                'name' => 'Eren',
                'gender' => 'erkek',
                'origin' => 'Türkçe',
                'meaning' => 'Ermiş, olgun kişi',
                'description' => 'Olgunluk ve bilgeliği temsil eden modern isim.',
                'popularity_rank' => 12,
                'is_popular' => false
            ],
            [
                'name' => 'Kerem',
                'gender' => 'erkek',
                'origin' => 'Arapça',
                'meaning' => 'Cömertlik, kerem',
                'description' => 'Cömertlik ve asaleti ifade eden güzel isim.',
                'popularity_rank' => 13,
                'is_popular' => false
            ],
            [
                'name' => 'Berk',
                'gender' => 'erkek',
                'origin' => 'Türkçe',
                'meaning' => 'Sağlam, güçlü, dayanıklı',
                'description' => 'Güç ve dayanıklılığı temsil eden modern isim.',
                'popularity_rank' => 14,
                'is_popular' => false
            ],
            [
                'name' => 'Alp',
                'gender' => 'erkek',
                'origin' => 'Türkçe',
                'meaning' => 'Kahraman, yiğit, dağ',
                'description' => 'Türk tarihinde kahraman unvanı. Cesaret simgesi.',
                'popularity_rank' => 15,
                'is_popular' => false
            ],

            // Unisex İsimler
            [
                'name' => 'Deniz',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Deniz, okyanus',
                'description' => 'Sonsuzluk, özgürlük ve derinliği temsil eden doğa ismi.',
                'popularity_rank' => null,
                'is_popular' => true
            ],
            [
                'name' => 'Ege',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Ege denizi, Ege bölgesi',
                'description' => 'Ege bölgesinin güzelliğini ve tarihini temsil eder.',
                'popularity_rank' => null,
                'is_popular' => true
            ],
            [
                'name' => 'Nil',
                'gender' => 'unisex',
                'origin' => 'Mısırca',
                'meaning' => 'Nil nehri, bereket',
                'description' => 'Bereket ve hayat kaynağını temsil eden antik isim.',
                'popularity_rank' => null,
                'is_popular' => false
            ],
            [
                'name' => 'Rüzgar',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Rüzgar, esinti',
                'description' => 'Özgürlük, hareket ve değişimi temsil eder.',
                'popularity_rank' => null,
                'is_popular' => false
            ],
            [
                'name' => 'Umut',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Umut, beklenti',
                'description' => 'Gelecek için olumlu beklenti ve iyimserlik.',
                'popularity_rank' => null,
                'is_popular' => false
            ],
            [
                'name' => 'Tan',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Şafak, tan vakti',
                'description' => 'Yeni başlangıç ve umudun simgesi.',
                'popularity_rank' => null,
                'is_popular' => false
            ],
            [
                'name' => 'Işık',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Işık, aydınlık, nur',
                'description' => 'Aydınlanma, bilgelik ve rehberliği temsil eder.',
                'popularity_rank' => null,
                'is_popular' => false
            ],
            [
                'name' => 'Duru',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Berrak, temiz, saf',
                'description' => 'Saflık ve berraklığı ifade eden modern isim.',
                'popularity_rank' => null,
                'is_popular' => false
            ],
            [
                'name' => 'Esen',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Sağlıklı, esenlik içinde',
                'description' => 'Sağlık ve huzuru temsil eden geleneksel isim.',
                'popularity_rank' => null,
                'is_popular' => false
            ],
            [
                'name' => 'Özgür',
                'gender' => 'unisex',
                'origin' => 'Türkçe',
                'meaning' => 'Özgür, hür, bağımsız',
                'description' => 'Özgürlük ve bağımsızlığı temsil eden güçlü isim.',
                'popularity_rank' => null,
                'is_popular' => false
            ]
        ];

        foreach ($names as $name) {
            BabyName::create($name);
        }
    }
}