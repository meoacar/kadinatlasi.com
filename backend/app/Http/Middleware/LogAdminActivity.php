<?php

namespace App\Http\Middleware;

use App\Services\AdminActivityService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    protected $activityService;

    public function __construct(AdminActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Sadece admin kullanıcıları için log tut
        if (!Auth::check() || !$request->is('admin/*')) {
            return $response;
        }

        // GET istekleri için sadece önemli sayfaları logla
        if ($request->isMethod('GET')) {
            $this->logGetRequest($request);
        }

        // POST, PUT, DELETE istekleri için detaylı log
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->logModifyingRequest($request, $response);
        }

        return $response;
    }

    /**
     * GET isteklerini logla
     */
    protected function logGetRequest(Request $request)
    {
        $importantRoutes = [
            'admin.settings',
            'admin.users.show',
            'admin.products.show',
            'admin.blog.show'
        ];

        $routeName = $request->route()?->getName();
        
        if ($routeName && collect($importantRoutes)->some(fn($route) => str_contains($routeName, $route))) {
            $this->activityService->log(
                action: 'view',
                severity: 'low',
                description: "Sayfa görüntülendi: {$routeName}",
                tags: ['view', 'page']
            );
        }
    }

    /**
     * Değiştirici istekleri logla
     */
    protected function logModifyingRequest(Request $request, Response $response)
    {
        $routeName = $request->route()?->getName();
        
        if (!$routeName) {
            return;
        }

        // Başarılı işlemler için log
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->logSuccessfulAction($request, $routeName);
        }

        // Hata durumları için log
        if ($response->getStatusCode() >= 400) {
            $this->logFailedAction($request, $routeName, $response->getStatusCode());
        }
    }

    /**
     * Başarılı aksiyonları logla
     */
    protected function logSuccessfulAction(Request $request, string $routeName)
    {
        $action = $this->determineAction($request->method(), $routeName);
        $severity = $this->determineSeverity($action);
        
        $this->activityService->log(
            action: $action,
            severity: $severity,
            description: $this->generateDescription($action, $routeName),
            newValues: $this->sanitizeRequestData($request->all()),
            tags: $this->generateTags($action, $routeName)
        );
    }

    /**
     * Başarısız aksiyonları logla
     */
    protected function logFailedAction(Request $request, string $routeName, int $statusCode)
    {
        $this->activityService->log(
            action: 'failed_request',
            severity: 'medium',
            description: "Başarısız istek: {$routeName} (HTTP {$statusCode})",
            newValues: [
                'route' => $routeName,
                'status_code' => $statusCode,
                'request_data' => $this->sanitizeRequestData($request->all())
            ],
            tags: ['error', 'failed_request']
        );
    }

    /**
     * HTTP metoduna ve route'a göre aksiyon belirle
     */
    protected function determineAction(string $method, string $routeName): string
    {
        if (str_contains($routeName, '.store')) {
            return 'create';
        }
        
        if (str_contains($routeName, '.update')) {
            return 'update';
        }
        
        if (str_contains($routeName, '.destroy')) {
            return 'delete';
        }

        if (str_contains($routeName, 'login')) {
            return 'login';
        }

        if (str_contains($routeName, 'logout')) {
            return 'logout';
        }

        return strtolower($method);
    }

    /**
     * Aksiyona göre önem derecesi belirle
     */
    protected function determineSeverity(string $action): string
    {
        $highSeverityActions = ['delete', 'login', 'logout', 'settings_update'];
        $mediumSeverityActions = ['create', 'update'];

        if (in_array($action, $highSeverityActions)) {
            return 'high';
        }

        if (in_array($action, $mediumSeverityActions)) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Aksiyon açıklaması oluştur
     */
    protected function generateDescription(string $action, string $routeName): string
    {
        $routeParts = explode('.', $routeName);
        $resource = $routeParts[1] ?? 'kaynak';

        $descriptions = [
            'create' => "Yeni {$resource} oluşturuldu",
            'update' => "{$resource} güncellendi",
            'delete' => "{$resource} silindi",
            'login' => 'Admin paneline giriş yapıldı',
            'logout' => 'Admin panelinden çıkış yapıldı'
        ];

        return $descriptions[$action] ?? "İşlem gerçekleştirildi: {$action}";
    }

    /**
     * Etiketler oluştur
     */
    protected function generateTags(string $action, string $routeName): array
    {
        $tags = [$action];
        
        $routeParts = explode('.', $routeName);
        if (isset($routeParts[1])) {
            $tags[] = $routeParts[1];
        }

        if (in_array($action, ['create', 'update', 'delete'])) {
            $tags[] = 'crud';
        }

        return $tags;
    }

    /**
     * Request verisini temizle (hassas bilgileri kaldır)
     */
    protected function sanitizeRequestData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'token',
            '_token',
            'api_key',
            'secret',
            'private_key'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[HIDDEN]';
            }
        }

        return $data;
    }
}