<?php

namespace Database\Seeders;

use App\Models\SecondHandProduct;
use Illuminate\Database\Seeder;

class SecondHandProductSeeder extends Seeder
{
    public function run(): void
    {
        // Önce mevcut kayıtları sil
        SecondHandProduct::truncate();
        
        $products = [
            [
                'title' => 'Hamile Kıyafeti - M Beden',
                'price' => 150,
                'original_price' => 300,
                'condition' => 'excellent',
                'category' => 'maternity',
                'seller_name' => 'Ayşe K.',
                'seller_email' => 'ayse@example.com',
                'seller_phone' => '0532 123 4567',
                'location' => 'İstanbul',
                'description' => 'Çok az kullanılmış hamile kıyafeti, temiz durumda',
                'status' => 'active',
                'images' => []
            ],
            [
                'title' => 'Bebek Arabası - Joie Marka',
                'price' => 800,
                'original_price' => 1500,
                'condition' => 'good',
                'category' => 'baby_gear',
                'seller_name' => 'Fatma M.',
                'seller_email' => 'fatma@example.com',
                'seller_phone' => '0533 234 5678',
                'location' => 'Ankara',
                'description' => 'Temiz ve bakımlı bebek arabası, tüm aksesuarları mevcut',
                'status' => 'active',
                'images' => []
            ],
            [
                'title' => 'Çocuk Kitapları Seti',
                'price' => 75,
                'original_price' => 120,
                'condition' => 'good',
                'category' => 'books',
                'seller_name' => 'Zeynep A.',
                'seller_email' => 'zeynep@example.com',
                'seller_phone' => '0534 345 6789',
                'location' => 'İzmir',
                'description' => '10 adet çocuk kitabı, temiz durumda, yaş grubu 3-7',
                'status' => 'active',
                'images' => []
            ],
            [
                'title' => 'Oyuncak Araba Koleksiyonu',
                'price' => 200,
                'original_price' => 350,
                'condition' => 'fair',
                'category' => 'toys',
                'seller_name' => 'Mehmet B.',
                'seller_email' => 'mehmet@example.com',
                'seller_phone' => '0535 456 7890',
                'location' => 'Bursa',
                'description' => '15 adet metal oyuncak araba, bazılarında küçük çizikler var',
                'status' => 'pending',
                'images' => []
            ],
            [
                'title' => 'Emzirme Yastığı',
                'price' => 80,
                'original_price' => 150,
                'condition' => 'excellent',
                'category' => 'baby_gear',
                'seller_name' => 'Elif S.',
                'seller_email' => 'elif@example.com',
                'seller_phone' => '0536 567 8901',
                'location' => 'Antalya',
                'description' => 'Hiç kullanılmamış emzirme yastığı, orijinal ambalajında',
                'status' => 'active',
                'images' => []
            ]
        ];

        foreach ($products as $product) {
            SecondHandProduct::create($product);
        }
    }
}