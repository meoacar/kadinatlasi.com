<?php

namespace App\Http\Controllers\Admin;

use App\Services\PerformanceService;
use App\Services\AdminActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class AdminCacheController extends AdminController
{
    protected $performanceService;
    protected $activityService;

    public function __construct(PerformanceService $performanceService, AdminActivityService $activityService)
    {
        parent::__construct();
        $this->performanceService = $performanceService;
        $this->activityService = $activityService;
    }

    /**
     * Cache yönetim sayfası
     */
    public function index()
    {
        try {
            $performanceReport = $this->performanceService->generatePerformanceReport();
            
            return view('admin.cache.index', compact('performanceReport'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load cache management');
        }
    }

    /**
     * Cache temizle
     */
    public function clear(Request $request)
    {
        try {
            $type = $request->input('type', 'all');
            $clearedKeys = [];

            switch ($type) {
                case 'dashboard':
                    $clearedKeys = $this->performanceService->clearCache([
                        'admin_dashboard_stats',
                        'total_views',
                        'monthly_registrations'
                    ]);
                    break;

                case 'application':
                    Artisan::call('cache:clear');
                    $clearedKeys = ['application_cache'];
                    break;

                case 'config':
                    Artisan::call('config:clear');
                    $clearedKeys = ['config_cache'];
                    break;

                case 'route':
                    Artisan::call('route:clear');
                    $clearedKeys = ['route_cache'];
                    break;

                case 'view':
                    Artisan::call('view:clear');
                    $clearedKeys = ['view_cache'];
                    break;

                case 'all':
                default:
                    // Tüm cache'leri temizle
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    
                    $clearedKeys = $this->performanceService->clearCache();
                    $clearedKeys = array_merge($clearedKeys, [
                        'application_cache',
                        'config_cache',
                        'route_cache',
                        'view_cache'
                    ]);
                    break;
            }

            // Aktivite logla
            $this->activityService->log(
                action: 'cache_clear',
                severity: 'medium',
                description: 'Cache temizlendi',
                newValues: [
                    'type' => $type,
                    'cleared_keys' => $clearedKeys,
                    'count' => count($clearedKeys)
                ],
                tags: ['cache', 'performance']
            );

            return response()->json([
                'success' => true,
                'message' => 'Cache başarıyla temizlendi',
                'cleared_keys' => $clearedKeys,
                'count' => count($clearedKeys)
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'clear cache');
        }
    }

    /**
     * Cache rebuild
     */
    public function rebuild(Request $request)
    {
        try {
            $type = $request->input('type', 'dashboard');

            switch ($type) {
                case 'dashboard':
                    // Dashboard cache'ini yeniden oluştur
                    $this->performanceService->clearCache(['admin_dashboard_stats']);
                    $stats = $this->performanceService->getCachedDashboardStats();
                    break;

                case 'config':
                    Artisan::call('config:cache');
                    break;

                case 'route':
                    Artisan::call('route:cache');
                    break;

                case 'view':
                    Artisan::call('view:cache');
                    break;

                default:
                    throw new \InvalidArgumentException('Geçersiz cache türü');
            }

            // Aktivite logla
            $this->activityService->log(
                action: 'cache_rebuild',
                severity: 'medium',
                description: 'Cache yeniden oluşturuldu',
                newValues: ['type' => $type],
                tags: ['cache', 'performance']
            );

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' cache başarıyla yeniden oluşturuldu'
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'rebuild cache');
        }
    }

    /**
     * Performans raporu
     */
    public function performanceReport()
    {
        try {
            $report = $this->performanceService->generatePerformanceReport();
            
            return response()->json([
                'success' => true,
                'report' => $report
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'generate performance report');
        }
    }

    /**
     * Database optimizasyonu
     */
    public function optimizeDatabase(Request $request)
    {
        try {
            $action = $request->input('action', 'analyze');
            $results = [];

            switch ($action) {
                case 'analyze':
                    $results['index_recommendations'] = $this->performanceService->checkDatabaseIndexes();
                    break;

                case 'optimize':
                    // Sadece development ortamında çalıştır
                    if (!app()->environment('production')) {
                        Artisan::call('optimize');
                        $results['message'] = 'Database optimizasyonu tamamlandı';
                    } else {
                        throw new \Exception('Production ortamında database optimizasyonu yapılamaz');
                    }
                    break;

                default:
                    throw new \InvalidArgumentException('Geçersiz aksiyon');
            }

            // Aktivite logla
            $this->activityService->log(
                action: 'database_optimize',
                severity: 'high',
                description: 'Database optimizasyonu yapıldı',
                newValues: [
                    'action' => $action,
                    'results' => $results
                ],
                tags: ['database', 'performance', 'optimization']
            );

            return response()->json([
                'success' => true,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'optimize database');
        }
    }

    /**
     * Query logging toggle
     */
    public function toggleQueryLogging(Request $request)
    {
        try {
            $enable = $request->boolean('enable', true);
            
            if ($enable) {
                $this->performanceService->enableQueryLogging();
                $message = 'Query logging etkinleştirildi';
            } else {
                // Query logging'i devre dışı bırak
                // Bu genellikle session veya cache ile yönetilir
                session(['query_logging_enabled' => false]);
                $message = 'Query logging devre dışı bırakıldı';
            }

            // Aktivite logla
            $this->activityService->log(
                action: 'query_logging_toggle',
                severity: 'medium',
                description: $message,
                newValues: ['enabled' => $enable],
                tags: ['performance', 'logging']
            );

            return response()->json([
                'success' => true,
                'message' => $message,
                'enabled' => $enable
            ]);

        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle query logging');
        }
    }
}