<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SecondHandCategory;

class SecondHandCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Giyim & Aksesuar',
                'slug' => 'giyim-aksesuar',
                'description' => 'Kadın giyim, ayakkabı, çanta ve aksesuarlar',
                'icon' => '👗',
                'sort_order' => 1
            ],
            [
                'name' => 'Kozmetik & Bakım',
                'slug' => 'kozmetik-bakim',
                'description' => 'Makyaj malzemeleri, cilt bakım ürünleri',
                'icon' => '💄',
                'sort_order' => 2
            ],
            [
                'name' => 'Anne & Bebek',
                'slug' => 'anne-bebek',
                'description' => 'Bebek giyim, oyuncak, anne ürünleri',
                'icon' => '👶',
                'sort_order' => 3
            ],
            [
                'name' => 'Ev & Yaşam',
                'slug' => 'ev-yasam',
                'description' => 'Ev dekorasyonu, mutfak eşyaları',
                'icon' => '🏠',
                'sort_order' => 4
            ],
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'description' => 'Telefon, tablet, küçük ev aletleri',
                'icon' => '📱',
                'sort_order' => 5
            ],
            [
                'name' => 'Kitap & Hobi',
                'slug' => 'kitap-hobi',
                'description' => 'Kitaplar, el işi malzemeleri, hobi ürünleri',
                'icon' => '📚',
                'sort_order' => 6
            ],
            [
                'name' => 'Spor & Outdoor',
                'slug' => 'spor-outdoor',
                'description' => 'Spor giyim, fitness ekipmanları',
                'icon' => '🏃‍♀️',
                'sort_order' => 7
            ],
            [
                'name' => 'Takı & Saat',
                'slug' => 'taki-saat',
                'description' => 'Takılar, saatler, değerli eşyalar',
                'icon' => '💍',
                'sort_order' => 8
            ]
        ];

        foreach ($categories as $category) {
            SecondHandCategory::create($category);
        }
    }
}