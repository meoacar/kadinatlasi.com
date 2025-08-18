<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class SiteSettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected static ?string $title = 'Site Ayarları';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'site-settings';

    protected static string $view = 'filament.pages.site-settings-page';

    public function save(Request $request): RedirectResponse
    {
        $settings = [
            // Genel ayarlar
            'site_name' => $request->input('site_name', ''),
            'admin_email' => $request->input('admin_email', ''),
            'site_description' => $request->input('site_description', ''),
            'maintenance_mode' => $request->has('maintenance_mode') ? '1' : '0',
            'user_registration' => $request->has('user_registration') ? '1' : '0',
            'enable_comments' => $request->has('enable_comments') ? '1' : '0',
            'enable_forum' => $request->has('enable_forum') ? '1' : '0',
            
            // SEO ayarları
            'seo_title' => $request->input('seo_title', ''),
            'seo_description' => $request->input('seo_description', ''),
            'seo_keywords' => $request->input('seo_keywords', ''),
            'seo_canonical' => $request->input('seo_canonical', ''),
            'google_analytics' => $request->input('google_analytics', ''),
            'google_search_console' => $request->input('google_search_console', ''),
            'og_image' => $request->input('og_image', ''),
            'seo_sitemap_enabled' => $request->has('seo_sitemap_enabled') ? '1' : '0',
            'seo_robots_enabled' => $request->has('seo_robots_enabled') ? '1' : '0',
            
            // E-posta ayarları
            'mail_host' => $request->input('mail_host', ''),
            'mail_port' => $request->input('mail_port', '587'),
            'mail_username' => $request->input('mail_username', ''),
            'mail_password' => $request->input('mail_password', ''),
            'mail_from_name' => $request->input('mail_from_name', ''),
            'mail_encryption' => $request->input('mail_encryption', 'tls'),
            'welcome_email_subject' => $request->input('welcome_email_subject', ''),
            'reset_password_subject' => $request->input('reset_password_subject', ''),
            'email_notifications' => $request->has('email_notifications') ? '1' : '0',
            'newsletter_enabled' => $request->has('newsletter_enabled') ? '1' : '0',
            
            // Sosyal medya ayarları
            'facebook_url' => $request->input('facebook_url', ''),
            'instagram_url' => $request->input('instagram_url', ''),
            'twitter_url' => $request->input('twitter_url', ''),
            'youtube_url' => $request->input('youtube_url', ''),
            'linkedin_url' => $request->input('linkedin_url', ''),
            'tiktok_url' => $request->input('tiktok_url', ''),
            'facebook_app_id' => $request->input('facebook_app_id', ''),
            'twitter_site' => $request->input('twitter_site', ''),
            'social_login_facebook' => $request->has('social_login_facebook') ? '1' : '0',
            'social_login_google' => $request->has('social_login_google') ? '1' : '0',
            'social_sharing' => $request->has('social_sharing') ? '1' : '0',
            
            // Güvenlik ayarları
            'min_password_length' => $request->input('min_password_length', '8'),
            'session_lifetime' => $request->input('session_lifetime', '120'),
            'max_login_attempts' => $request->input('max_login_attempts', '5'),
            'lockout_duration' => $request->input('lockout_duration', '15'),
            'allowed_file_types' => $request->input('allowed_file_types', ''),
            'max_file_size' => $request->input('max_file_size', '10'),
            'rate_limit' => $request->input('rate_limit', '60'),
            'two_factor_auth' => $request->has('two_factor_auth') ? '1' : '0',
            'email_verification' => $request->has('email_verification') ? '1' : '0',
            'captcha_enabled' => $request->has('captcha_enabled') ? '1' : '0',
            'force_https' => $request->has('force_https') ? '1' : '0',
            'recaptcha_site_key' => $request->input('recaptcha_site_key', ''),
            'recaptcha_secret_key' => $request->input('recaptcha_secret_key', ''),
            
            // Özellik ayarları
            'enable_blog' => $request->has('enable_blog') ? '1' : '0',
            'enable_calculators' => $request->has('enable_calculators') ? '1' : '0',
            'enable_astrology' => $request->has('enable_astrology') ? '1' : '0',
            'enable_notifications' => $request->has('enable_notifications') ? '1' : '0',
            'posts_per_page' => $request->input('posts_per_page', '10'),
            'topics_per_page' => $request->input('topics_per_page', '15'),
            'cache_duration' => $request->input('cache_duration', '60'),
            'enable_cache' => $request->has('enable_cache') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Site ayarları başarıyla kaydedildi!');
    }
}