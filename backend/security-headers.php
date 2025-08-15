<?php
/**
 * KadınAtlası Admin Panel - Security Headers
 * Bu dosya her PHP request'inde otomatik olarak çalışır
 */

// Sadece production ortamında çalışsın
if (getenv('APP_ENV') === 'production') {
    
    // Security headers
    if (!headers_sent()) {
        
        // HSTS
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        
        // XSS Protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Content Type Options
        header('X-Content-Type-Options: nosniff');
        
        // Frame Options
        header('X-Frame-Options: DENY');
        
        // Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Permissions Policy
        header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()');
        
        // Remove server information
        header_remove('X-Powered-By');
        header_remove('Server');
        
        // Custom server header
        header('Server: KadınAtlası');
        
        // Admin panel specific headers
        if (strpos($_SERVER['REQUEST_URI'] ?? '', '/admin') === 0) {
            
            // No cache for admin pages
            header('Cache-Control: no-cache, no-store, must-revalidate, private');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Additional security for admin
            header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet');
            
        }
    }
    
    // Session security
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_secure', '1');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.use_strict_mode', '1');
    }
    
    // Input filtering
    if (!empty($_POST)) {
        array_walk_recursive($_POST, function(&$value) {
            if (is_string($value)) {
                // Basic XSS protection
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        });
    }
    
    // Log suspicious activity
    $suspicious_patterns = [
        '/\b(union|select|insert|delete|drop|update|exec|script)\b/i',
        '/\b(eval|base64_decode|gzinflate)\b/i',
        '/\.\.[\/\\\\]/',
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi'
    ];
    
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $query_string = $_SERVER['QUERY_STRING'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    foreach ($suspicious_patterns as $pattern) {
        if (preg_match($pattern, $request_uri . $query_string . $user_agent)) {
            error_log("Suspicious activity detected: " . json_encode([
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'uri' => $request_uri,
                'query' => $query_string,
                'user_agent' => $user_agent,
                'timestamp' => date('Y-m-d H:i:s')
            ]));
            break;
        }
    }
}