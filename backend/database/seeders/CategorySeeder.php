<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kadın Sağlığı',
                'slug' => 'kadin-sagligi',
                'description' => 'Regl döngüsü, gebelik, menopoz ve cinsel sağlık konuları',
                'color' => '#E57399',
                'icon' => 'heart',
                'sort_order' => 1,
            ],
            [
                'name' => 'Gebelik & Annelik',
                'slug' => 'gebelik-annelik',
                'description' => 'Gebelik süreci, doğum ve anne-bebek sağlığı',
                'color' => '#F5A9BC',
                'icon' => 'baby',
                'sort_order' => 2,
            ],
            [
                'name' => 'Güzellik & Moda',
                'slug' => 'guzellik-moda',
                'description' => 'Güzellik ipuçları, moda trendleri ve stil önerileri',
                'color' => '#FFB6C1',
                'icon' => 'sparkles',
                'sort_order' => 3,
            ],
            [
                'name' => 'Diyet & Fitness',
                'slug' => 'diyet-fitness',
                'description' => 'Sağlıklı beslenme, egzersiz ve fitness programları',
                'color' => '#98FB98',
                'icon' => 'dumbbell',
                'sort_order' => 4,
            ],
            [
                'name' => 'Kariyer & Girişimcilik',
                'slug' => 'kariyer-girisimcilik',
                'description' => 'İş hayatı, kariyer gelişimi ve girişimcilik hikayeleri',
                'color' => '#87CEEB',
                'icon' => 'briefcase',
                'sort_order' => 5,
            ],
            [
                'name' => 'Psikoloji & Kişisel Gelişim',
                'slug' => 'psikoloji-kisisel-gelisim',
                'description' => 'Ruh sağlığı, motivasyon ve kişisel gelişim',
                'color' => '#DDA0DD',
                'icon' => 'brain',
                'sort_order' => 6,
            ],
            [
                'name' => 'Astroloji',
                'slug' => 'astroloji',
                'description' => 'Burç yorumları ve astrolojik analizler',
                'color' => '#FFD700',
                'icon' => 'star',
                'sort_order' => 7,
            ],
            [
                'name' => 'İlişkiler & Aile',
                'slug' => 'iliskiler-aile',
                'description' => 'İlişki tavsiyeleri ve aile yaşamı',
                'color' => '#FFA07A',
                'icon' => 'users',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}