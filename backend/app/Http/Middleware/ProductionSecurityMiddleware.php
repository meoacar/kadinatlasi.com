<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductionSecurityMiddleware
{
    /**
     * Production güvenlik middleware'i
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Production ortamında çalışıyorsa
        if (app()->environment('production')) {
            
            // HTTPS zorunluluğu
            if (!$request->secure() && !$this->isLocalhost($request)) {
                return redirect()->secure($request->getRequestUri(), 301);
            }

            // Güvenlik başlıklarını ekle
            $response = $next($request);
            
            return $this->addProductionSecurityHeaders($response, $request);
        }

        return $next($request);
    }

    /**
     * Production güvenlik başlıklarını ekle
     */
    private function addProductionSecurityHeaders(Response $response, Request $request): Response
    {
        $headers = [
            // Strict Transport Security
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
            
            // Content Security Policy
            'Content-Security-Policy' => $this->getContentSecurityPolicy(),
            
            // X-Frame-Options
            'X-Frame-Options' => 'DENY',
            
            // X-Content-Type-Options
            'X-Content-Type-Options' => 'nosniff',
            
            // X-XSS-Protection
            'X-XSS-Protection' => '1; mode=block',
            
            // Referrer Policy
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            
            // Permissions Policy
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=(), payment=()',
            
            // Server header removal
            'Server' => 'KadınAtlası',
            
            // Cache Control for admin pages
            'Cache-Control' => $this->getCacheControl($request),
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        // Remove sensitive headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('X-Laravel-Version');

        return $response;
    }

    /**
     * Content Security Policy oluştur
     */
    private function getContentSecurityPolicy(): string
    {
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://www.googletagmanager.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://www.google-analytics.com",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "upgrade-insecure-requests"
        ];

        return implode('; ', $csp);
    }

    /**
     * Cache control başlığı oluştur
     */
    private function getCacheControl(Request $request): string
    {
        // Admin sayfaları için cache'leme
        if ($request->is('admin/*')) {
            return 'no-cache, no-store, must-revalidate, private';
        }

        // Static asset'ler için
        if ($request->is('assets/*') || $request->is('storage/*')) {
            return 'public, max-age=31536000, immutable';
        }

        return 'no-cache, private';
    }

    /**
     * Localhost kontrolü
     */
    private function isLocalhost(Request $request): bool
    {
        $host = $request->getHost();
        return in_array($host, ['localhost', '127.0.0.1', '::1']);
    }
}