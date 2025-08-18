<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminActivity;
use App\Models\User;
use App\Services\AdminActivityService;
use Illuminate\Http\Request;

class AdminActivityController extends AdminController
{
    protected $activityService;

    public function __construct(AdminActivityService $activityService)
    {
        parent::__construct();
        $this->activityService = $activityService;
    }

    /**
     * Aktivite listesi
     */
    public function index(Request $request)
    {
        try {
            $activities = $this->activityService->getActivities($request);
            $stats = $this->activityService->getStats();
            
            // Filtre seçenekleri
            $admins = User::whereHas('adminActivities')
                ->select('id', 'name')
                ->get();

            $actions = AdminActivity::distinct('action')
                ->pluck('action')
                ->mapWithKeys(fn($action) => [$action => ucfirst(str_replace('_', ' ', $action))])
                ->toArray();

            $modelTypes = AdminActivity::distinct('model_type')
                ->whereNotNull('model_type')
                ->pluck('model_type')
                ->mapWithKeys(function($type) {
                    $name = class_basename($type);
                    return [$type => $name];
                })
                ->toArray();

            $severityOptions = [
                'low' => 'Düşük',
                'medium' => 'Orta',
                'high' => 'Yüksek'
            ];

            return view('admin.activities.index', compact(
                'activities', 
                'stats', 
                'admins', 
                'actions', 
                'modelTypes', 
                'severityOptions'
            ));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load activities');
        }
    }

    /**
     * Aktivite detayı
     */
    public function show(AdminActivity $activity)
    {
        try {
            $activity->load('admin');
            
            return view('admin.activities.show', compact('activity'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'show activity');
        }
    }

    /**
     * Şüpheli aktiviteler
     */
    public function suspicious()
    {
        try {
            $suspicious = $this->activityService->detectSuspiciousActivities();
            
            return view('admin.activities.suspicious', compact('suspicious'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load suspicious activities');
        }
    }

    /**
     * Aktivite istatistikleri (AJAX)
     */
    public function stats(Request $request)
    {
        try {
            $stats = $this->activityService->getStats();
            
            // Günlük aktivite grafiği için veri
            $dailyActivities = AdminActivity::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray();

            // Aksiyon dağılımı
            $actionDistribution = AdminActivity::selectRaw('action, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('action')
                ->orderByDesc('count')
                ->get()
                ->pluck('count', 'action')
                ->toArray();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'daily_activities' => $dailyActivities,
                'action_distribution' => $actionDistribution
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'İstatistikler yüklenirken hata oluştu.'
            ], 500);
        }
    }

    /**
     * Eski aktiviteleri temizle
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        try {
            $deleted = $this->activityService->cleanOldActivities($request->days);
            
            // Temizleme aktivitesini kaydet
            $this->activityService->log(
                action: 'cleanup',
                severity: 'medium',
                description: "{$deleted} eski aktivite kaydı temizlendi",
                newValues: ['deleted_count' => $deleted, 'days_kept' => $request->days],
                tags: ['maintenance', 'cleanup']
            );

            return $this->successResponse("{$deleted} aktivite kaydı başarıyla temizlendi.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'cleanup activities');
        }
    }

    /**
     * Aktiviteyi sil
     */
    public function destroy(AdminActivity $activity)
    {
        try {
            $activity->delete();
            
            return $this->successResponse('Aktivite kaydı başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete activity');
        }
    }

    /**
     * Toplu silme
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:admin_activities,id'
        ]);

        try {
            $deleted = AdminActivity::whereIn('id', $request->ids)->delete();
            
            return $this->successResponse("{$deleted} aktivite kaydı başarıyla silindi.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'bulk delete activities');
        }
    }

    /**
     * Aktiviteleri dışa aktar
     */
    public function export(Request $request)
    {
        try {
            $query = AdminActivity::with('admin')->latest();

            // Filtreleri uygula
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            if ($request->filled('admin_id')) {
                $query->where('admin_id', $request->admin_id);
            }
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            $activities = $query->limit(10000)->get();

            $filename = 'admin_activities_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($activities) {
                $file = fopen('php://output', 'w');
                
                // CSV başlıkları
                fputcsv($file, [
                    'ID',
                    'Admin',
                    'Aksiyon',
                    'Model',
                    'Açıklama',
                    'IP Adresi',
                    'Önem',
                    'Tarih'
                ]);

                // Veri satırları
                foreach ($activities as $activity) {
                    fputcsv($file, [
                        $activity->id,
                        $activity->admin->name ?? 'Bilinmeyen',
                        $activity->action,
                        $activity->model_type ? class_basename($activity->model_type) : '',
                        $activity->formatted_description,
                        $activity->ip_address,
                        $activity->severity_label,
                        $activity->created_at->format('d.m.Y H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return $this->handleException($e, 'export activities');
        }
    }
}