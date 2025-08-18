<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Admin paneli erişim kontrolü
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı giriş yapmış mı?
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel.');
        }

        $user = Auth::user();

        // Kullanıcı aktif mi?
        if (!$user->is_active) {
            Auth::logout();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account is inactive'], 403);
            }
            return redirect()->route('admin.login')->with('error', 'Your account is inactive.');
        }

        // Admin yetkisi kontrolü (şimdilik basit, daha sonra role sistemi ekleyebiliriz)
        if (!$this->isAdmin($user)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied'], 403);
            }
            return redirect('/')->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }

    /**
     * Kullanıcının admin yetkisi var mı?
     * Şimdilik email kontrolü, daha sonra role sistemi eklenebilir
     */
    private function isAdmin($user): bool
    {
        // Admin email'leri (daha sonra database'den alınabilir)
        $adminEmails = [
            'admin@kadinatlasi.com',
            'test@test.com',
            'superadmin@kadinatlasi.com'
        ];

        return in_array($user->email, $adminEmails) || $user->hasRole('admin');
    }
}