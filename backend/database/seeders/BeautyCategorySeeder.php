<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeautyCategory;

class BeautyCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Makyaj',
                'slug' => 'makyaj',
                'description' => 'Makyaj teknikleri ve ürün incelemeleri',
                'is_active' => true
            ],
            [
                'name' => 'Cilt Bakımı',
                'slug' => 'cilt-bakimi',
                'description' => 'Cilt bakım rutinleri ve ürün önerileri',
                'is_active' => true
            ],
            [
                'name' => 'Saç Bakımı',
                'slug' => 'sac-bakimi',
                'description' => 'Saç bakım ipuçları ve stil önerileri',
                'is_active' => true
            ],
            [
                'name' => 'Moda',
                'slug' => 'moda',
                'description' => 'Moda trendleri ve stil rehberleri',
                'is_active' => true
            ],
            [
                'name' => 'Parfüm',
                'slug' => 'parfum',
                'description' => 'Parfüm incelemeleri ve önerileri',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            BeautyCategory::create($category);
        }
    }
}