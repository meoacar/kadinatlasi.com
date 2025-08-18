<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kozmetik & Bakım',
                'slug' => 'kozmetik-bakim',
                'description' => 'Güzellik ve kişisel bakım ürünleri',
                'icon' => '💄',
                'sort_order' => 1,
                'subcategories' => [
                    ['name' => 'Makyaj', 'slug' => 'makyaj', 'icon' => '💋'],
                    ['name' => 'Cilt Bakımı', 'slug' => 'cilt-bakimi', 'icon' => '🧴'],
                    ['name' => 'Saç Bakımı', 'slug' => 'sac-bakimi', 'icon' => '💇‍♀️'],
                    ['name' => 'Parfüm', 'slug' => 'parfum', 'icon' => '🌸'],
                ]
            ],
            [
                'name' => 'Giyim & Moda',
                'slug' => 'giyim-moda',
                'description' => 'Kadın giyim ve moda ürünleri',
                'icon' => '👗',
                'sort_order' => 2,
                'subcategories' => [
                    ['name' => 'Elbise', 'slug' => 'elbise', 'icon' => '👗'],
                    ['name' => 'Bluz & Gömlek', 'slug' => 'bluz-gomlek', 'icon' => '👚'],
                    ['name' => 'Pantolon', 'slug' => 'pantolon', 'icon' => '👖'],
                    ['name' => 'İç Giyim', 'slug' => 'ic-giyim', 'icon' => '🩱'],
                ]
            ],
            [
                'name' => 'Aksesuar & Takı',
                'slug' => 'aksesuar-taki',
                'description' => 'Takı ve aksesuar ürünleri',
                'icon' => '💍',
                'sort_order' => 3,
                'subcategories' => [
                    ['name' => 'Kolye', 'slug' => 'kolye', 'icon' => '📿'],
                    ['name' => 'Küpe', 'slug' => 'kupe', 'icon' => '💎'],
                    ['name' => 'Bilezik', 'slug' => 'bilezik', 'icon' => '⌚'],
                    ['name' => 'Çanta', 'slug' => 'canta', 'icon' => '👜'],
                ]
            ],
            [
                'name' => 'Sağlık & Wellness',
                'slug' => 'saglik-wellness',
                'description' => 'Sağlık ve wellness ürünleri',
                'icon' => '🌿',
                'sort_order' => 4,
                'subcategories' => [
                    ['name' => 'Vitamin & Takviye', 'slug' => 'vitamin-takviye', 'icon' => '💊'],
                    ['name' => 'Doğal Ürünler', 'slug' => 'dogal-urunler', 'icon' => '🌱'],
                    ['name' => 'Fitness Ekipmanları', 'slug' => 'fitness-ekipmanlari', 'icon' => '🏋️‍♀️'],
                ]
            ],
            [
                'name' => 'Anne & Bebek',
                'slug' => 'anne-bebek',
                'description' => 'Anne ve bebek ürünleri',
                'icon' => '👶',
                'sort_order' => 5,
                'subcategories' => [
                    ['name' => 'Bebek Giyim', 'slug' => 'bebek-giyim', 'icon' => '👶'],
                    ['name' => 'Bebek Bakımı', 'slug' => 'bebek-bakimi', 'icon' => '🍼'],
                    ['name' => 'Hamilelik', 'slug' => 'hamilelik', 'icon' => '🤱'],
                ]
            ],
            [
                'name' => 'Ev & Yaşam',
                'slug' => 'ev-yasam',
                'description' => 'Ev dekorasyonu ve yaşam ürünleri',
                'icon' => '🏠',
                'sort_order' => 6,
                'subcategories' => [
                    ['name' => 'Dekorasyon', 'slug' => 'dekorasyon', 'icon' => '🕯️'],
                    ['name' => 'Mutfak', 'slug' => 'mutfak', 'icon' => '🍳'],
                    ['name' => 'Banyo', 'slug' => 'banyo', 'icon' => '🛁'],
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'] ?? [];
            unset($categoryData['subcategories']);

            $category = ProductCategory::create($categoryData);

            foreach ($subcategories as $subcategoryData) {
                $subcategoryData['parent_id'] = $category->id;
                $subcategoryData['sort_order'] = 1;
                ProductCategory::create($subcategoryData);
            }
        }
    }
}