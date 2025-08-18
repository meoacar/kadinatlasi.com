<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class AdminHandler extends ExceptionHandler
{
    /**
     * Admin panel için özel exception handling
     */
    public function render($request, Throwable $e)
    {
        // Admin panel route'ları için özel handling
        if ($request->is('admin/*')) {
            return $this->handleAdminException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Admin exception'ları handle et
     */
    private function handleAdminException(Request $request, Throwable $e)
    {
        // Production'da detaylı hata mesajlarını gizle
        if (app()->environment('production')) {
            $this->logAdminError($request, $e);
            
            // HTTP exception'ları için özel sayfalar
            if ($e instanceof HttpException) {
                return $this->renderHttpException($e, $request);
            }

            // Genel hatalar için generic sayfa
            return $this->renderGenericError($request);
        }

        return parent::render($request, $e);
    }

    /**
     * Admin hatalarını logla
     */
    private function logAdminError(Request $request, Throwable $e): void
    {
        Log::error('Admin Panel Error', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'input' => $request->except(['password', 'password_confirmation', '_token'])
        ]);
    }

    /**
     * HTTP exception'ları render et
     */
    private function renderHttpException(HttpException $e, Request $request)
    {
        $status = $e->getStatusCode();
        
        // AJAX istekleri için JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'error' => true,
                'message' => $this->getErrorMessage($status),
                'status' => $status
            ], $status);
        }

        // Admin panel için özel error sayfaları
        $view = "admin.errors.{$status}";
        
        if (view()->exists($view)) {
            return response()->view($view, [
                'exception' => $e,
                'status' => $status
            ], $status);
        }

        // Fallback generic error page
        return response()->view('admin.errors.generic', [
            'status' => $status,
            'message' => $this->getErrorMessage($status)
        ], $status);
    }

    /**
     * Generic error render et
     */
    private function renderGenericError(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => true,
                'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                'status' => 500
            ], 500);
        }

        return response()->view('admin.errors.generic', [
            'status' => 500,
            'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        ], 500);
    }

    /**
     * Status code'a göre error mesajı
     */
    private function getErrorMessage(int $status): string
    {
        return match($status) {
            401 => 'Bu sayfaya erişim yetkiniz bulunmuyor.',
            403 => 'Bu işlemi gerçekleştirme yetkiniz bulunmuyor.',
            404 => 'Aradığınız sayfa bulunamadı.',
            419 => 'Oturum süresi doldu. Lütfen sayfayı yenileyin.',
            429 => 'Çok fazla istek gönderdiniz. Lütfen bekleyin.',
            500 => 'Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.',
            503 => 'Sistem bakımda. Lütfen daha sonra tekrar deneyin.',
            default => 'Bir hata oluştu.'
        };
    }

    /**
     * Reportable exception'ları tanımla
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Critical hatalar için özel alerting
            if ($this->isCriticalError($e)) {
                $this->sendCriticalAlert($e);
            }
        });
    }

    /**
     * Critical error kontrolü
     */
    private function isCriticalError(Throwable $e): bool
    {
        // Database connection errors
        if (str_contains($e->getMessage(), 'Connection refused') ||
            str_contains($e->getMessage(), 'Access denied')) {
            return true;
        }

        // File system errors
        if (str_contains($e->getMessage(), 'Permission denied') ||
            str_contains($e->getMessage(), 'No space left')) {
            return true;
        }

        // Memory errors
        if (str_contains($e->getMessage(), 'Allowed memory size')) {
            return true;
        }

        return false;
    }

    /**
     * Critical alert gönder
     */
    private function sendCriticalAlert(Throwable $e): void
    {
        try {
            // Email, Slack, SMS vb. alert sistemleri
            Log::critical('CRITICAL ERROR DETECTED', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => now()->toISOString(),
                'server' => gethostname(),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true)
            ]);
            
            // Burada Slack, email vb. notification servisleri çağrılabilir
            
        } catch (Throwable $alertException) {
            // Alert gönderiminde hata olursa logla
            Log::emergency('Failed to send critical alert', [
                'original_error' => $e->getMessage(),
                'alert_error' => $alertException->getMessage()
            ]);
        }
    }
}