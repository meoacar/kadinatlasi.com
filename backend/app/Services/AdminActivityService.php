<?php

namespace App\Services;

use App\Models\AdminActivity;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdminActivityService
{
    /**
     * Aktivite kaydet
     */
    public function log(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        string $severity = 'low',
        ?string $description = null,
        ?array $tags = null
    ): AdminActivity {
        $request = request();
        
        return AdminActivity::create([
            'admin_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'description' => $description,
            'severity' => $severity,
            'tags' => $tags
        ]);
    }

    /**
     * Giriş aktivitesi kaydet
     */
    public function logLogin(): AdminActivity
    {
        return $this->log(
            action: 'login',
            severity: 'medium',
            description: 'Admin paneline giriş yapıldı',
            tags: ['auth', 'login']
        );
    }

    /**
     * Çıkış aktivitesi kaydet
     */
    public function logLogout(): AdminActivity
    {
        return $this->log(
            action: 'logout',
            severity: 'low',
            description: 'Admin panelinden çıkış yapıldı',
            tags: ['auth', 'logout']
        );
    }

    /**
     * Oluşturma aktivitesi kaydet
     */
    public function logCreate(Model $model, ?string $description = null): AdminActivity
    {
        return $this->log(
            action: 'create',
            model: $model,
            newValues: $model->getAttributes(),
            severity: 'medium',
            description: $description,
            tags: ['crud', 'create']
        );
    }

    /**
     * Güncelleme aktivitesi kaydet
     */
    public function logUpdate(Model $model, array $oldValues, ?string $description = null): AdminActivity
    {
        return $this->log(
            action: 'update',
            model: $model,
            oldValues: $oldValues,
            newValues: $model->getAttributes(),
            severity: 'medium',
            description: $description,
            tags: ['crud', 'update']
        );
    }

    /**
     * Silme aktivitesi kaydet
     */
    public function logDelete(Model $model, ?string $description = null): AdminActivity
    {
        return $this->log(
            action: 'delete',
            model: $model,
            oldValues: $model->getAttributes(),
            severity: 'high',
            description: $description,
            tags: ['crud', 'delete']
        );
    }

    /**
     * Görüntüleme aktivitesi kaydet
     */
    public function logView(Model $model, ?string $description = null): AdminActivity
    {
        return $this->log(
            action: 'view',
            model: $model,
            severity: 'low',
            description: $description,
            tags: ['view']
        );
    }

    /**
     * Ayar değişikliği aktivitesi kaydet
     */
    public function logSettingsChange(array $oldSettings, array $newSettings, string $group = 'general'): AdminActivity
    {
        return $this->log(
            action: 'settings_update',
            oldValues: $oldSettings,
            newValues: $newSettings,
            severity: 'high',
            description: "'{$group}' ayarları güncellendi",
            tags: ['settings', $group]
        );
    }

    /**
     * Güvenlik olayı kaydet
     */
    public function logSecurityEvent(string $event, string $description, array $data = []): AdminActivity
    {
        return $this->log(
            action: 'security_event',
            severity: 'high',
            description: $description,
            newValues: array_merge($data, ['event' => $event]),
            tags: ['security', $event]
        );
    }

    /**
     * Başarısız giriş denemesi kaydet
     */
    public function logFailedLogin(string $email): AdminActivity
    {
        return $this->log(
            action: 'failed_login',
            severity: 'high',
            description: "Başarısız giriş denemesi: {$email}",
            newValues: ['email' => $email],
            tags: ['security', 'failed_login']
        );
    }

    /**
     * Toplu işlem aktivitesi kaydet
     */
    public function logBulkAction(string $action, string $modelType, array $ids, ?string $description = null): AdminActivity
    {
        return $this->log(
            action: "bulk_{$action}",
            severity: 'medium',
            description: $description ?? "Toplu {$action} işlemi gerçekleştirildi",
            newValues: [
                'model_type' => $modelType,
                'affected_ids' => $ids,
                'count' => count($ids)
            ],
            tags: ['bulk', $action]
        );
    }

    /**
     * Aktiviteleri filtrele ve getir
     */
    public function getActivities(Request $request)
    {
        $query = AdminActivity::with('admin')
            ->latest();

        // Arama
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Admin filtresi
        if ($request->filled('admin_id')) {
            $query->byAdmin($request->admin_id);
        }

        // Aksiyon filtresi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Model türü filtresi
        if ($request->filled('model_type')) {
            $query->forModel($request->model_type);
        }

        // Önem derecesi filtresi
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Hızlı tarih filtresi
        if ($request->filled('quick_date')) {
            $query->quickDateFilter($request->quick_date);
        }

        // Gelişmiş filtreler
        $query->advancedFilter($request);

        return $query->paginate($request->get('per_page', 25))
                    ->withQueryString();
    }

    /**
     * Aktivite istatistikleri
     */
    public function getStats(): array
    {
        return [
            'total' => AdminActivity::count(),
            'today' => AdminActivity::whereDate('created_at', today())->count(),
            'this_week' => AdminActivity::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'high_severity' => AdminActivity::where('severity', 'high')->count(),
            'failed_logins' => AdminActivity::where('action', 'failed_login')
                ->whereDate('created_at', today())
                ->count(),
            'unique_admins' => AdminActivity::distinct('admin_id')->count('admin_id'),
            'top_actions' => AdminActivity::selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->pluck('count', 'action')
                ->toArray()
        ];
    }

    /**
     * Eski aktiviteleri temizle
     */
    public function cleanOldActivities(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return AdminActivity::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Şüpheli aktiviteleri tespit et
     */
    public function detectSuspiciousActivities(): array
    {
        $suspicious = [];

        // Çok fazla başarısız giriş
        $failedLogins = AdminActivity::where('action', 'failed_login')
            ->where('created_at', '>=', now()->subHour())
            ->groupBy('ip_address')
            ->selectRaw('ip_address, COUNT(*) as count')
            ->having('count', '>', 5)
            ->get();

        if ($failedLogins->isNotEmpty()) {
            $suspicious[] = [
                'type' => 'multiple_failed_logins',
                'description' => 'Çok fazla başarısız giriş denemesi',
                'data' => $failedLogins
            ];
        }

        // Gece saatlerinde aktivite
        $nightActivities = AdminActivity::whereTime('created_at', '>=', '23:00')
            ->orWhereTime('created_at', '<=', '06:00')
            ->whereDate('created_at', today())
            ->count();

        if ($nightActivities > 10) {
            $suspicious[] = [
                'type' => 'night_activity',
                'description' => 'Gece saatlerinde yoğun aktivite',
                'count' => $nightActivities
            ];
        }

        // Çok fazla silme işlemi
        $deletions = AdminActivity::where('action', 'delete')
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($deletions > 20) {
            $suspicious[] = [
                'type' => 'mass_deletion',
                'description' => 'Çok fazla silme işlemi',
                'count' => $deletions
            ];
        }

        return $suspicious;
    }
}