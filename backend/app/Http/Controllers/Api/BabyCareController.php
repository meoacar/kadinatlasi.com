<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BabyCareController extends Controller
{
    public function getTips()
    {
        $tips = [
            [
                'id' => 1,
                'category' => 'feeding',
                'title' => 'Emzirme İpuçları',
                'content' => 'Doğru emzirme pozisyonu ve teknikleri',
                'age_range' => '0-6 ay',
                'tips' => [
                    'Bebeği göğsünüze yakın tutun',
                    'Bebeğin ağzı meme ucunu tamamen kaplamalı',
                    'Sakin ve rahat bir ortam oluşturun',
                    'Her iki göğsü de kullanın'
                ]
            ],
            [
                'id' => 2,
                'category' => 'sleep',
                'title' => 'Uyku Düzeni',
                'content' => 'Bebek uyku rutini oluşturma',
                'age_range' => '0-12 ay',
                'tips' => [
                    'Düzenli uyku saatleri belirleyin',
                    'Uyku öncesi sakinleştirici aktiviteler',
                    'Güvenli uyku ortamı hazırlayın',
                    'Gece beslenme rutini oluşturun'
                ]
            ],
            [
                'id' => 3,
                'category' => 'hygiene',
                'title' => 'Bebek Hijyeni',
                'content' => 'Günlük bakım ve temizlik',
                'age_range' => '0-24 ay',
                'tips' => [
                    'Günlük banyo rutini',
                    'Bez değişimi teknikleri',
                    'Göbek bakımı',
                    'Tırnak kesimi'
                ]
            ],
            [
                'id' => 4,
                'category' => 'development',
                'title' => 'Gelişim Takibi',
                'content' => 'Bebek gelişim aşamaları',
                'age_range' => '0-36 ay',
                'tips' => [
                    'Motor gelişim aktiviteleri',
                    'Dil gelişimi destekleme',
                    'Sosyal etkileşim',
                    'Oyun ve öğrenme'
                ]
            ],
            [
                'id' => 5,
                'category' => 'health',
                'title' => 'Sağlık Takibi',
                'content' => 'Bebek sağlığı ve kontroller',
                'age_range' => '0-24 ay',
                'tips' => [
                    'Düzenli doktor kontrolleri',
                    'Aşı takvimi takibi',
                    'Hastalık belirtileri',
                    'İlk yardım bilgileri'
                ]
            ],
            [
                'id' => 6,
                'category' => 'nutrition',
                'title' => 'Ek Gıda Geçişi',
                'content' => 'Anne sütünden ek gıdaya geçiş',
                'age_range' => '6-12 ay',
                'tips' => [
                    '6. aydan sonra ek gıdaya başlayın',
                    'Tek tek yiyecekleri deneyin',
                    'Alerjik reaksiyonları takip edin',
                    'Çeşitli tatlar sunun'
                ]
            ]
        ];

        return response()->json(['success' => true, 'data' => $tips]);
    }

    public function getTipsByCategory($category)
    {
        $allTips = $this->getTips()->getData()->data;
        $filteredTips = array_filter($allTips, function($tip) use ($category) {
            return $tip['category'] === $category;
        });

        return response()->json(['success' => true, 'data' => array_values($filteredTips)]);
    }

    public function getTip($id)
    {
        $allTips = $this->getTips()->getData()->data;
        $tip = array_filter($allTips, function($t) use ($id) {
            return $t['id'] == $id;
        });

        if (empty($tip)) {
            return response()->json(['success' => false, 'message' => 'İpucu bulunamadı'], 404);
        }

        return response()->json(['success' => true, 'data' => array_values($tip)[0]]);
    }

    public function getCategories()
    {
        $categories = [
            ['id' => 'feeding', 'name' => 'Beslenme', 'icon' => '🍼'],
            ['id' => 'sleep', 'name' => 'Uyku', 'icon' => '😴'],
            ['id' => 'hygiene', 'name' => 'Hijyen', 'icon' => '🛁'],
            ['id' => 'development', 'name' => 'Gelişim', 'icon' => '👶'],
            ['id' => 'health', 'name' => 'Sağlık', 'icon' => '🏥'],
            ['id' => 'nutrition', 'name' => 'Beslenme Geçişi', 'icon' => '🥄']
        ];

        return response()->json(['success' => true, 'data' => $categories]);
    }
}