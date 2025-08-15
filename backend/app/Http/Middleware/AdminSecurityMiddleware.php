<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class AdminSecurityMiddleware
{
    /**
     * Admin panel güvenlik kontrolü
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rate limiting - Admin paneli için
        if ($this->isRateLimited($request)) {
            Log::warning('Admin panel rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->url()
            ]);
            
            return response()->json(['message' => 'Too many requests'], 429);
        }

        // Suspicious activity detection
        $this->detectSuspiciousActivity($request);

        // Security headers will be added in response
        $response = $next($request);

        // Add security headers
        return $this->addSecurityHeaders($response);
    }

    /**
     * Rate limiting kontrolü
     */
    private function isRateLimited(Request $request): bool
    {
        $key = 'admin_panel:' . $request->ip();
        
        // Admin paneli için dakikada 60 istek limiti
        return RateLimiter::tooManyAttempts($key, 60);
    }

    /**
     * Şüpheli aktivite tespiti
     */
    private function detectSuspiciousActivity(Request $request): void
    {
        $suspiciousPatterns = [
            // SQL Injection patterns
            '/(\bunion\b|\bselect\b|\binsert\b|\bdelete\b|\bdrop\b|\bupdate\b)/i',
            // XSS patterns
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi',
            // Path traversal
            '/\.\.[\/\\\\]/',
            // Command injection
            '/(\b(exec|system|shell_exec|passthru|eval)\b)/i'
        ];

        $userInput = json_encode($request->all());
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userInput)) {
                Log::alert('Suspicious activity detected in admin panel', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->url(),
                    'input' => $userInput,
                    'pattern' => $pattern,
                    'user_id' => auth()->id()
                ]);
                break;
            }
        }
    }

    /**
     * Güvenlik başlıklarını ekle
     */
    private function addSecurityHeaders(Response $response): Response
    {
        $headers = [
            // XSS Protection
            'X-XSS-Protection' => '1; mode=block',
            
            // Content Type Options
            'X-Content-Type-Options' => 'nosniff',
            
            // Frame Options
            'X-Frame-Options' => 'DENY',
            
            // Referrer Policy
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            
            // Content Security Policy
            'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self';",
            
            // HSTS (only in production)
            'Strict-Transport-Security' => app()->environment('production') ? 'max-age=31536000; includeSubDomains' : null,
        ];

        foreach ($headers as $key => $value) {
            if ($value !== null) {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}