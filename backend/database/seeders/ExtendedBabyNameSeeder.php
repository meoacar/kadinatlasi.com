<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BabyName;

class ExtendedBabyNameSeeder extends Seeder
{
    public function run()
    {
        $names = [
            // Kız İsimleri - A harfi
            ['name' => 'Ayla', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Ay ışığı, ay halkası', 'description' => 'Ay\'ın güzelliğini ifade eden poetik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Aylin', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Ay ışığı', 'description' => 'Ay\'ın aydınlığını temsil eden modern isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Aslı', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Asıl, gerçek, orijinal', 'description' => 'Özgünlük ve gerçekliği ifade eden güçlü isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Arya', 'gender' => 'kiz', 'origin' => 'Sanskritçe', 'meaning' => 'Asil, soylu', 'description' => 'Asalet ve soyluluk ifade eden uluslararası isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Ada', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Ada, yer', 'description' => 'Coğrafi güzellik ve huzuru temsil eder.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Alya', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Yüce, ulu', 'description' => 'Yücelik ve asaleti ifade eden zarif isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Amine', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Güvenilir, emin', 'description' => 'Güven ve sadakati temsil eden klasik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Asel', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Bal, tatlı', 'description' => 'Tatlılık ve sevimliği ifade eden modern isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Ayşe', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Yaşayan, hayat dolu', 'description' => 'Hz. Ayşe\'nin ismi. Canlılık ve neşe simgesi.', 'popularity_rank' => 16, 'is_popular' => false],
            ['name' => 'Aleyna', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Bizden olan, üzerimizde', 'description' => 'Koruma ve sahiplenme anlamında güzel isim.', 'popularity_rank' => null, 'is_popular' => false],

            // Kız İsimleri - B harfi
            ['name' => 'Beren', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Güçlü, kuvvetli', 'description' => 'Güç ve dayanıklılığı temsil eden modern isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Beste', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Melodi, beste', 'description' => 'Müzik ve sanatı temsil eden yaratıcı isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Beyza', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Beyaz, temiz', 'description' => 'Saflık ve temizliği ifade eden güzel isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Bilge', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Bilgili, bilge', 'description' => 'Bilgelik ve zekayı temsil eden güçlü isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Burcu', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Kale, burç', 'description' => 'Güç ve koruma anlamında geleneksel isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Bahar', 'gender' => 'kiz', 'origin' => 'Farsça', 'meaning' => 'İlkbahar, bahar', 'description' => 'Yenilenme ve güzellik mevsimini temsil eder.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Begüm', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Hanım, bey\'in karısı', 'description' => 'Asalet ve saygınlığı ifade eden klasik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Betül', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Bakire, temiz', 'description' => 'Hz. Meryem\'in lakabı. Saflık simgesi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Büşra', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Müjde, iyi haber', 'description' => 'Sevindirici haber ve umut anlamında isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Buse', 'gender' => 'kiz', 'origin' => 'Farsça', 'meaning' => 'Öpücük', 'description' => 'Sevgi ve şefkati ifade eden sevimli isim.', 'popularity_rank' => null, 'is_popular' => false],

            // Kız İsimleri - C harfi
            ['name' => 'Ceren', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Genç geyik', 'description' => 'Zarafet ve çevikliği temsil eden doğa ismi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Ceyda', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Uzun boyunlu', 'description' => 'Zarafet ve güzelliği ifade eden isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Ceylan', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Ceylan, antilop', 'description' => 'Çeviklik ve zarafeti temsil eden hayvan ismi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Cansu', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Can suyu, hayat suyu', 'description' => 'Yaşam ve canlılığı ifade eden modern isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Cemre', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Kor, kıvılcım', 'description' => 'Ateş ve enerjiyi temsil eden güçlü isim.', 'popularity_rank' => null, 'is_popular' => false],

            // Kız İsimleri - D harfi
            ['name' => 'Damla', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Su damlası', 'description' => 'Saflık ve berraklığı temsil eden doğa ismi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Dila', 'gender' => 'kiz', 'origin' => 'Kürtçe', 'meaning' => 'Gönül, kalp', 'description' => 'Sevgi ve kalbi ifade eden duygusal isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Dilara', 'gender' => 'kiz', 'origin' => 'Farsça', 'meaning' => 'Gönül süsü, kalp süsü', 'description' => 'Güzellik ve zarafeti ifade eden klasik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Didem', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Gözüm, gözbebeğim', 'description' => 'Sevgi ve değer vermeyi ifade eden isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Deniz', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Deniz', 'description' => 'Sonsuzluk ve özgürlüğü temsil eden unisex isim.', 'popularity_rank' => null, 'is_popular' => true],
            ['name' => 'Derya', 'gender' => 'kiz', 'origin' => 'Farsça', 'meaning' => 'Okyanus, deniz', 'description' => 'Büyüklük ve derinliği ifade eden isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Duygu', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Duygu, his', 'description' => 'Duygusallık ve hassasiyeti temsil eder.', 'popularity_rank' => null, 'is_popular' => false],

            // Kız İsimleri - E harfi
            ['name' => 'Ebru', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Ebru sanatı, bulut', 'description' => 'Sanat ve güzelliği temsil eden kültürel isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Eda', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Edep, terbiye', 'description' => 'Nezaket ve zarafeti ifade eden klasik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Ekin', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Ekin, mahsul', 'description' => 'Bereket ve verimliliği temsil eden tarım ismi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Elçin', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Elçi, haberci', 'description' => 'İletişim ve mesajı temsil eden isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Emel', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Arzu, istek', 'description' => 'Umut ve arzuyu ifade eden duygusal isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Emine', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Güvenilir, emin', 'description' => 'Hz. Muhammed\'in annesi. Güven simgesi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Esma', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Yüce, ulu', 'description' => 'Allah\'ın güzel isimlerinden. Yücelik ifadesi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Esra', 'gender' => 'kiz', 'origin' => 'Arapça', 'meaning' => 'Gece yolculuğu', 'description' => 'Miraç hadisesini ifade eden dini isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Ezgi', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Melodi, ezgi', 'description' => 'Müzik ve sanatı temsil eden yaratıcı isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Eylül', 'gender' => 'kiz', 'origin' => 'Türkçe', 'meaning' => 'Eylül ayı', 'description' => 'Sonbahar güzelliğini temsil eden mevsim ismi.', 'popularity_rank' => null, 'is_popular' => false],

            // Erkek İsimleri - A harfi
            ['name' => 'Ahmet', 'gender' => 'erkek', 'origin' => 'Arapça', 'meaning' => 'Çok övülen, methiye layık', 'description' => 'Hz. Muhammed\'in isimlerinden. Övgü ifadesi.', 'popularity_rank' => 16, 'is_popular' => false],
            ['name' => 'Alperen', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Alp eren, kahraman ermiş', 'description' => 'Kahramanlık ve olgunluğu birleştiren güçlü isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Arif', 'gender' => 'erkek', 'origin' => 'Arapça', 'meaning' => 'Bilgili, arif', 'description' => 'Bilgelik ve anlayışı temsil eden klasik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Aslan', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Aslan', 'description' => 'Güç ve cesaret simgesi olan hayvan ismi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Adem', 'gender' => 'erkek', 'origin' => 'İbranice', 'meaning' => 'İnsan, toprak', 'description' => 'İlk insan Hz. Adem\'in ismi. Başlangıç simgesi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Altan', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Al tan, kızıl şafak', 'description' => 'Şafağın güzelliğini ifade eden poetik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Arda', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Arka, destek', 'description' => 'Güven ve desteği temsil eden modern isim.', 'popularity_rank' => 9, 'is_popular' => true],
            ['name' => 'Arman', 'gender' => 'erkek', 'origin' => 'Farsça', 'meaning' => 'Arzu, dilek', 'description' => 'Umut ve arzuyu ifade eden duygusal isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Aras', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Aras nehri', 'description' => 'Coğrafi güzellik ve akışı temsil eder.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Atakan', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Ata kan, ecdad kanı', 'description' => 'Soy ve mirası temsil eden geleneksel isim.', 'popularity_rank' => null, 'is_popular' => false],

            // Erkek İsimleri - B harfi
            ['name' => 'Barış', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Barış, huzur', 'description' => 'Barış ve huzuru temsil eden değerli isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Batuhan', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Batı han, batının hükümdarı', 'description' => 'Liderlik ve yönetimi ifade eden güçlü isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Berkay', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Sağlam ay, güçlü ay', 'description' => 'Güç ve istikrarı temsil eden modern isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Berkan', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Sağlam kan, güçlü soy', 'description' => 'Güç ve soyluluğu ifade eden isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Burak', 'gender' => 'erkek', 'origin' => 'Arapça', 'meaning' => 'Şimşek, parlak', 'description' => 'Hız ve parlaklığı temsil eden dinamik isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Baran', 'gender' => 'erkek', 'origin' => 'Kürtçe', 'meaning' => 'Yağmur', 'description' => 'Bereket ve yenilemeyi temsil eden doğa ismi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Başar', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Başarı, zafer', 'description' => 'Başarı ve zaferi ifade eden motivasyonel isim.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Bilal', 'gender' => 'erkek', 'origin' => 'Arapça', 'meaning' => 'Su, nem', 'description' => 'Hz. Bilal\'in ismi. Sadakat ve bağlılık simgesi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Bora', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Fırtına, kasırga', 'description' => 'Güç ve dinamizmi temsil eden doğa ismi.', 'popularity_rank' => null, 'is_popular' => false],
            ['name' => 'Buğra', 'gender' => 'erkek', 'origin' => 'Türkçe', 'meaning' => 'Deve yavrusu', 'description' => 'Dayanıklılık ve sabırı temsil eden hayvan ismi.', 'popularity_rank' => null, 'is_popular' => false]
        ];

        foreach ($names as $name) {
            BabyName::create($name);
        }
    }
}