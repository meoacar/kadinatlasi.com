<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPremiumAccess
{
    public function handle(Request $request, Closure $next, $feature = null)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Giriş yapmanız gerekiyor'], 401);
        }

        if (!$user->hasPremiumAccess($feature)) {
            return response()->json([
                'message' => 'Bu özellik premium üyeler içindir',
                'premium_required' => true,
                'feature' => $feature
            ], 403);
        }

        return $next($request);
    }
}