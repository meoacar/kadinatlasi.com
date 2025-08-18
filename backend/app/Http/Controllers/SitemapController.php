<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\ForumTopic;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Static pages
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/blog', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/forum', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/hesaplama', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/gebelik-takibi', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/astroloji', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/fitness', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => '/psikoloji', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => '/bebek-isimleri', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/premium', 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . config('app.url') . $page['url'] . '</loc>';
            $sitemap .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $sitemap .= '<priority>' . $page['priority'] . '</priority>';
            $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
            $sitemap .= '</url>';
        }

        // Blog posts
        BlogPost::published()->chunk(100, function ($posts) use (&$sitemap) {
            foreach ($posts as $post) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . config('app.url') . '/blog/' . $post->id . '</loc>';
                $sitemap .= '<changefreq>weekly</changefreq>';
                $sitemap .= '<priority>0.8</priority>';
                $sitemap .= '<lastmod>' . $post->updated_at->toISOString() . '</lastmod>';
                $sitemap .= '</url>';
            }
        });

        // Categories
        Category::active()->chunk(50, function ($categories) use (&$sitemap) {
            foreach ($categories as $category) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . config('app.url') . '/kategori/' . $category->slug . '</loc>';
                $sitemap .= '<changefreq>weekly</changefreq>';
                $sitemap .= '<priority>0.7</priority>';
                $sitemap .= '<lastmod>' . $category->updated_at->toISOString() . '</lastmod>';
                $sitemap .= '</url>';
            }
        });

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /login\n";
        $robots .= "Disallow: /register\n";
        $robots .= "\n";
        $robots .= "Sitemap: " . config('app.url') . "/sitemap.xml\n";

        return response($robots, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}