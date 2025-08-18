<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    public function handle(Request $request, Closure $next, $minutes = 60): Response
    {
        $response = $next($request);
        
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $response->headers->set('Cache-Control', 'public, max-age=' . ($minutes * 60));
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + ($minutes * 60)) . ' GMT');
        }
        
        return $response;
    }
}
