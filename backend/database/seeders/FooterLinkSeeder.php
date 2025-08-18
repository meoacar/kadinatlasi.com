<?php

namespace Database\Seeders;

use App\Models\FooterLink;
use Illuminate\Database\Seeder;

class FooterLinkSeeder extends Seeder
{
    public function run(): void
    {
        $quickLinks = [
            ['name' => 'Hakkımızda', 'url' => '/hakkimizda', 'sort_order' => 1],
            ['name' => 'İletişim', 'url' => '/iletisim', 'sort_order' => 2],
            ['name' => 'Blog', 'url' => '/blog', 'sort_order' => 3],
            ['name' => 'Forum', 'url' => '/forum', 'sort_order' => 4],
            ['name' => 'Premium', 'url' => '/premium', 'sort_order' => 5],
        ];

        $categoryLinks = [
            ['name' => 'Sağlık', 'url' => '/saglik', 'sort_order' => 1],
            ['name' => 'Gebelik & Anne', 'url' => '/gebelik', 'sort_order' => 2],
            ['name' => 'Güzellik', 'url' => '/guzellik', 'sort_order' => 3],
            ['name' => 'Fitness', 'url' => '/fitness', 'sort_order' => 4],
            ['name' => 'Hesaplama Araçları', 'url' => '/hesaplama', 'sort_order' => 5],
        ];

        foreach ($quickLinks as $link) {
            FooterLink::create([
                'name' => $link['name'],
                'url' => $link['url'],
                'section' => 'quick_links',
                'sort_order' => $link['sort_order'],
                'is_active' => true
            ]);
        }

        foreach ($categoryLinks as $link) {
            FooterLink::create([
                'name' => $link['name'],
                'url' => $link['url'],
                'section' => 'category_links',
                'sort_order' => $link['sort_order'],
                'is_active' => true
            ]);
        }
    }
}