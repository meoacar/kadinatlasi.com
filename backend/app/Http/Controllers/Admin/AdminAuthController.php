<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Admin login sayfasını göster
     */
    public function showLogin()
    {
        // Zaten giriş yapmışsa dashboard'a yönlendir
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Admin login işlemi
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
        ]);

        // Rate limiting - brute force saldırılarına karşı
        $key = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Çok fazla giriş denemesi. {$seconds} saniye sonra tekrar deneyin.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Kullanıcı aktif mi?
            if (!$user->is_active) {
                Auth::logout();
                RateLimiter::hit($key);
                return back()->with('error', 'Hesabınız aktif değil. Lütfen yönetici ile iletişime geçin.');
            }

            // Admin yetkisi var mı?
            if (!$this->isAdmin($user)) {
                Auth::logout();
                RateLimiter::hit($key);
                return back()->with('error', 'Bu panele erişim yetkiniz bulunmamaktadır.');
            }

            // Başarılı giriş
            RateLimiter::clear($key);
            $request->session()->regenerate();
            
            // Login aktivitesini logla
            app(AdminActivityService::class)->logLogin();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Hoş geldiniz, ' . $user->name . '!');
        }

        // Başarısız giriş
        RateLimiter::hit($key);
        
        // Başarısız giriş aktivitesini logla
        app(AdminActivityService::class)->logFailedLogin($request->email);
        
        return back()->withErrors([
            'email' => 'Girilen bilgiler hatalı.',
        ])->withInput($request->except('password'));
    }

    /**
     * Admin logout işlemi
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Logout aktivitesini logla
        if ($user) {
            app(AdminActivityService::class)->logLogout();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Başarıyla çıkış yaptınız.');
    }

    /**
     * Kullanıcının admin yetkisi var mı?
     */
    private function isAdmin($user): bool
    {
        // Admin email'leri
        $adminEmails = [
            'admin@kadinatlasi.com',
            'test@test.com',
            'superadmin@kadinatlasi.com'
        ];

        // Email kontrolü veya role kontrolü
        return in_array($user->email, $adminEmails) || 
               (method_exists($user, 'hasRole') && $user->hasRole('admin'));
    }


    private function logLogoutActivity($user, Request $request)
    {
        try {
            \Log::info('Admin logout', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            // Log hatası sistem çalışmasını etkilemesin
        }
    }
}