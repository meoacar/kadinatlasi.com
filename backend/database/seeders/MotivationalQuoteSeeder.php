<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MotivationalQuote;

class MotivationalQuoteSeeder extends Seeder
{
    public function run()
    {
        $quotes = [
            [
                'quote' => 'Güçlü kadınlar birbirini destekler, yıkmaz.',
                'author' => null,
                'category' => 'strength'
            ],
            [
                'quote' => 'Sen kendi hikayenin yazarısın. Sayfaları güzelleştirmek senin elinde.',
                'author' => null,
                'category' => 'motivation'
            ],
            [
                'quote' => 'Kendini sevmek, başkalarının seni sevmesinden daha önemlidir.',
                'author' => null,
                'category' => 'self_love'
            ],
            [
                'quote' => 'Başarı, hazır olduğunda değil, cesaret ettiğinde gelir.',
                'author' => null,
                'category' => 'success'
            ],
            [
                'quote' => 'Mutluluk bir hedef değil, bir yolculuktur.',
                'author' => null,
                'category' => 'happiness'
            ],
            [
                'quote' => 'Her yeni gün, yeni bir başlangıç yapma şansıdır.',
                'author' => null,
                'category' => 'motivation'
            ],
            [
                'quote' => 'Güzelliğin gerçek kaynağı, içindeki ışıktır.',
                'author' => null,
                'category' => 'self_love'
            ],
            [
                'quote' => 'Zorluklarla karşılaştığında, onları fırsata çevirebilirsin.',
                'author' => null,
                'category' => 'strength'
            ],
            [
                'quote' => 'Başarı, düştüğün yerden kalkma sanatıdır.',
                'author' => null,
                'category' => 'success'
            ],
            [
                'quote' => 'Gülümsemek, ruhuna verdiğin en güzel hediyedir.',
                'author' => null,
                'category' => 'happiness'
            ],
            [
                'quote' => 'Sen olduğun gibi mükemmelsin, değişmen gereken hiçbir şey yok.',
                'author' => null,
                'category' => 'self_love'
            ],
            [
                'quote' => 'Hayallerinin peşinden koşmaktan asla vazgeçme.',
                'author' => null,
                'category' => 'motivation'
            ]
        ];

        foreach ($quotes as $quote) {
            MotivationalQuote::create($quote);
        }
    }
}