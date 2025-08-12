<?php

namespace App\Services;

class SEOService
{
    public static function generateMetaTags($page, $data = [])
    {
        $defaults = [
            'title' => 'KadınAtlası.com - Kadınlar İçin Kapsamlı Yaşam Platformu',
            'description' => 'Kadın sağlığı, gebelik, güzellik, diyet, astroloji ve daha fazlası. Hesaplama araçları, forum ve uzman tavsiyeleri ile kadınların yanında.',
            'keywords' => 'kadın sağlığı, gebelik, güzellik, diyet, astroloji, hesaplama araçları, forum, kadın platformu',
            'image' => asset('images/og-default.jpg'),
            'url' => url()->current()
        ];

        return match($page) {
            'home' => array_merge($defaults, [
                'title' => 'KadınAtlası.com - Kadınlar İçin Kapsamlı Yaşam Platformu',
                'description' => 'Kadın sağlığı, gebelik takibi, güzellik ipuçları, diyet programları, astroloji yorumları ve hesaplama araçları. Türkiye\'nin en kapsamlı kadın platformu.',
                'keywords' => 'kadın sağlığı, gebelik takibi, güzellik, diyet, astroloji, VKİ hesaplama, regl takvimi, forum'
            ]),
            'blog' => array_merge($defaults, [
                'title' => ($data['title'] ?? 'Blog') . ' | KadınAtlası.com',
                'description' => $data['excerpt'] ?? 'Kadın sağlığı, güzellik, gebelik ve yaşam hakkında uzman yazıları ve tavsiyeleri.',
                'keywords' => implode(', ', $data['tags'] ?? []) . ', kadın blogu, sağlık yazıları'
            ]),
            'forum' => array_merge($defaults, [
                'title' => 'Forum - Kadın Topluluğu | KadınAtlası.com',
                'description' => 'Kadınların deneyimlerini paylaştığı, soru sorduğu ve birbirini desteklediği topluluk forumu.',
                'keywords' => 'kadın forumu, topluluk, soru cevap, deneyim paylaşımı, kadın desteği'
            ]),
            'calculators' => array_merge($defaults, [
                'title' => 'Hesaplama Araçları - VKİ, Regl, Gebelik | KadınAtlası.com',
                'description' => 'VKİ hesaplama, regl takvimi, gebelik haftası, kalori hesaplama ve daha fazla ücretsiz hesaplama aracı.',
                'keywords' => 'VKİ hesaplama, regl takvimi, gebelik hesaplama, kalori hesaplama, su ihtiyacı'
            ]),
            'pregnancy' => array_merge($defaults, [
                'title' => 'Gebelik Takibi ve Anne Rehberi | KadınAtlası.com',
                'description' => 'Haftalık gebelik takibi, bebek gelişimi, anne sağlığı ve gebelik döneminde bilinmesi gerekenler.',
                'keywords' => 'gebelik takibi, bebek gelişimi, anne sağlığı, gebelik haftası, doğum hazırlığı'
            ]),
            'astrology' => array_merge($defaults, [
                'title' => 'Astroloji - Günlük Burç Yorumları | KadınAtlası.com',
                'description' => 'Günlük, haftalık ve aylık burç yorumları. Aşk, kariyer ve sağlık konularında astrolojik rehberlik.',
                'keywords' => 'burç yorumları, astroloji, günlük burç, haftalık burç, aşk astrolojisi'
            ]),
            'fitness' => array_merge($defaults, [
                'title' => 'Fitness ve Diyet Programları | KadınAtlası.com',
                'description' => 'Kadınlara özel egzersiz programları, diyet listeleri, sağlıklı tarifler ve fitness ipuçları.',
                'keywords' => 'kadın fitness, diyet programı, egzersiz, sağlıklı tarifler, kilo verme'
            ]),
            default => $defaults
        };
    }

    public static function generateStructuredData($type, $data = [])
    {
        $baseData = [
            '@context' => 'https://schema.org',
            '@type' => $type
        ];

        return match($type) {
            'WebSite' => array_merge($baseData, [
                'name' => 'KadınAtlası.com',
                'url' => config('app.url'),
                'description' => 'Kadınlar için kapsamlı yaşam platformu',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => config('app.url') . '/search?q={search_term_string}',
                    'query-input' => 'required name=search_term_string'
                ]
            ]),
            'Article' => array_merge($baseData, [
                'headline' => $data['title'] ?? '',
                'description' => $data['excerpt'] ?? '',
                'author' => [
                    '@type' => 'Person',
                    'name' => $data['author'] ?? 'KadınAtlası Editörü'
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'KadınAtlası.com',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/logo.png')
                    ]
                ],
                'datePublished' => $data['published_at'] ?? now()->toISOString(),
                'dateModified' => $data['updated_at'] ?? now()->toISOString(),
                'image' => $data['image'] ?? asset('images/og-default.jpg')
            ]),
            'Organization' => array_merge($baseData, [
                'name' => 'KadınAtlası.com',
                'url' => config('app.url'),
                'logo' => asset('images/logo.png'),
                'description' => 'Kadınlar için kapsamlı yaşam platformu',
                'sameAs' => [
                    'https://www.facebook.com/kadinatlasi',
                    'https://www.instagram.com/kadinatlasi',
                    'https://www.twitter.com/kadinatlasi'
                ]
            ]),
            default => $baseData
        };
    }
}