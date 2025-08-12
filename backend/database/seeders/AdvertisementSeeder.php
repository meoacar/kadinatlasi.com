<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $advertisements = [
            [
                'title' => 'Doğal Cilt Bakım Ürünleri',
                'type' => 'banner',
                'position' => 'header',
                'content' => 'Doğal ve organik cilt bakım ürünleri ile cildinizi besleyin. %20 indirim fırsatını kaçırmayın!',
                'image_url' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=800&h=200&fit=crop',
                'link_url' => 'https://example.com/cilt-bakim',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'price' => 500.00,
                'clicks' => 45,
                'impressions' => 1250,
                'is_active' => true,
                'client_name' => 'Doğal Güzellik Merkezi',
                'client_email' => 'info@dogalguzellik.com'
            ],
            [
                'title' => 'Hamile Kıyafetleri',
                'type' => 'sidebar',
                'position' => 'sidebar',
                'content' => 'Hamilelik döneminde rahat ve şık kıyafetler. Özel tasarım hamile koleksiyonu.',
                'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=300&h=250&fit=crop',
                'link_url' => 'https://example.com/hamile-kiyafetleri',
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(27),
                'price' => 300.00,
                'clicks' => 23,
                'impressions' => 890,
                'is_active' => true,
                'client_name' => 'Anne & Bebek Mağazası',
                'client_email' => 'satis@annebebek.com'
            ],
            [
                'title' => 'Fitness Koçluğu Hizmeti',
                'type' => 'sponsored_content',
                'position' => 'content',
                'content' => 'Kişiye özel fitness programları ve beslenme danışmanlığı. İlk konsültasyon ücretsiz!',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop',
                'link_url' => 'https://example.com/fitness-koclugu',
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => Carbon::now()->addDays(23),
                'price' => 750.00,
                'clicks' => 67,
                'impressions' => 1890,
                'is_active' => true,
                'client_name' => 'FitLife Koçluk',
                'client_email' => 'info@fitlife.com'
            ],
            [
                'title' => 'Özel İndirim Fırsatı!',
                'type' => 'popup',
                'position' => 'popup',
                'content' => 'Sadece bugün! Tüm ürünlerde %30 indirim. Hemen alışverişe başlayın!',
                'image_url' => 'https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?w=500&h=400&fit=crop',
                'link_url' => 'https://example.com/ozel-indirim',
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(29),
                'price' => 200.00,
                'clicks' => 12,
                'impressions' => 456,
                'is_active' => true,
                'client_name' => 'Kadın Giyim Mağazası',
                'client_email' => 'kampanya@kadinmoda.com'
            ],
            [
                'title' => 'Yoga Dersleri',
                'type' => 'sidebar',
                'position' => 'sidebar',
                'content' => 'Online yoga dersleri ile evinizde rahatlayın. İlk ders ücretsiz deneme!',
                'image_url' => 'https://images.unsplash.com/photo-1506629905607-d9c297d3d45b?w=300&h=250&fit=crop',
                'link_url' => 'https://example.com/yoga-dersleri',
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(28),
                'price' => 400.00,
                'clicks' => 34,
                'impressions' => 1120,
                'is_active' => true,
                'client_name' => 'Zen Yoga Studio',
                'client_email' => 'info@zenyoga.com'
            ],
            [
                'title' => 'Organik Bebek Ürünleri',
                'type' => 'banner',
                'position' => 'footer',
                'content' => 'Bebeğiniz için en doğal ve güvenli ürünler. Organik bebek bakım serisi.',
                'image_url' => 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=800&h=150&fit=crop',
                'link_url' => 'https://example.com/organik-bebek',
                'start_date' => Carbon::now()->subDays(4),
                'end_date' => Carbon::now()->addDays(26),
                'price' => 600.00,
                'clicks' => 56,
                'impressions' => 1670,
                'is_active' => true,
                'client_name' => 'Organik Bebek Dünyası',
                'client_email' => 'satis@organikbebek.com'
            ]
        ];

        foreach ($advertisements as $ad) {
            Advertisement::create($ad);
        }
    }
}