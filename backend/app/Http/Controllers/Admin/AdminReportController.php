<?php

namespace App\Http\Controllers\Admin;

use App\Services\ReportService;
use App\Services\AdminActivityService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReportController extends AdminController
{
    protected $reportService;
    protected $activityService;

    public function __construct(ReportService $reportService, AdminActivityService $activityService)
    {
        parent::__construct();
        $this->reportService = $reportService;
        $this->activityService = $activityService;
    }

    /**
     * Raporlar ana sayfası
     */
    public function index()
    {
        try {
            // Son 30 günün özet istatistikleri
            $dateFrom = now()->subDays(30);
            $dateTo = now();

            $userOverview = $this->reportService->getUserReports([
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ])['overview'];

            $contentOverview = $this->reportService->getContentReports([
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ])['overview'];

            $systemOverview = $this->reportService->getSystemReports([
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]);

            return view('admin.reports.index', compact(
                'userOverview',
                'contentOverview', 
                'systemOverview'
            ));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load reports overview');
        }
    }

    /**
     * Kullanıcı raporları
     */
    public function users(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,json,csv'
        ]);

        try {
            $filters = [
                'date_from' => $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth(),
                'date_to' => $request->date_to ? Carbon::parse($request->date_to) : now()
            ];

            $reports = $this->reportService->getUserReports($filters);

            // Log aktivitesi
            $this->activityService->log(
                action: 'report_view',
                severity: 'low',
                description: 'Kullanıcı raporları görüntülendi',
                newValues: $filters,
                tags: ['report', 'users']
            );

            if ($request->format === 'json') {
                return response()->json([
                    'success' => true,
                    'data' => $reports,
                    'filters' => $filters
                ]);
            }

            if ($request->format === 'csv') {
                return $this->exportUserReportsCsv($reports, $filters);
            }

            return view('admin.reports.users', compact('reports', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'generate user reports');
        }
    }

    /**
     * İçerik raporları
     */
    public function content(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,json,csv'
        ]);

        try {
            $filters = [
                'date_from' => $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth(),
                'date_to' => $request->date_to ? Carbon::parse($request->date_to) : now()
            ];

            $reports = $this->reportService->getContentReports($filters);

            // Log aktivitesi
            $this->activityService->log(
                action: 'report_view',
                severity: 'low',
                description: 'İçerik raporları görüntülendi',
                newValues: $filters,
                tags: ['report', 'content']
            );

            if ($request->format === 'json') {
                return response()->json([
                    'success' => true,
                    'data' => $reports,
                    'filters' => $filters
                ]);
            }

            if ($request->format === 'csv') {
                return $this->exportContentReportsCsv($reports, $filters);
            }

            return view('admin.reports.content', compact('reports', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'generate content reports');
        }
    }

    /**
     * Satış raporları
     */
    public function sales(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,json,csv'
        ]);

        try {
            $filters = [
                'date_from' => $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth(),
                'date_to' => $request->date_to ? Carbon::parse($request->date_to) : now()
            ];

            $reports = $this->reportService->getSalesReports($filters);

            // Log aktivitesi
            $this->activityService->log(
                action: 'report_view',
                severity: 'low',
                description: 'Satış raporları görüntülendi',
                newValues: $filters,
                tags: ['report', 'sales']
            );

            if ($request->format === 'json') {
                return response()->json([
                    'success' => true,
                    'data' => $reports,
                    'filters' => $filters
                ]);
            }

            if ($request->format === 'csv') {
                return $this->exportSalesReportsCsv($reports, $filters);
            }

            return view('admin.reports.sales', compact('reports', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'generate sales reports');
        }
    }

    /**
     * Sistem raporları
     */
    public function system(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,json,csv'
        ]);

        try {
            $filters = [
                'date_from' => $request->date_from ? Carbon::parse($request->date_from) : now()->subWeek(),
                'date_to' => $request->date_to ? Carbon::parse($request->date_to) : now()
            ];

            $reports = $this->reportService->getSystemReports($filters);

            // Log aktivitesi
            $this->activityService->log(
                action: 'report_view',
                severity: 'medium',
                description: 'Sistem raporları görüntülendi',
                newValues: $filters,
                tags: ['report', 'system']
            );

            if ($request->format === 'json') {
                return response()->json([
                    'success' => true,
                    'data' => $reports,
                    'filters' => $filters
                ]);
            }

            return view('admin.reports.system', compact('reports', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'generate system reports');
        }
    }

    /**
     * Özel rapor oluşturucu
     */
    public function custom(Request $request)
    {
        try {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'report_type' => 'required|in:users,content,sales,system',
                    'date_from' => 'required|date',
                    'date_to' => 'required|date|after_or_equal:date_from',
                    'metrics' => 'required|array',
                    'metrics.*' => 'string'
                ]);

                $filters = [
                    'date_from' => Carbon::parse($request->date_from),
                    'date_to' => Carbon::parse($request->date_to)
                ];

                $reportData = [];
                switch ($request->report_type) {
                    case 'users':
                        $reportData = $this->reportService->getUserReports($filters);
                        break;
                    case 'content':
                        $reportData = $this->reportService->getContentReports($filters);
                        break;
                    case 'sales':
                        $reportData = $this->reportService->getSalesReports($filters);
                        break;
                    case 'system':
                        $reportData = $this->reportService->getSystemReports($filters);
                        break;
                }

                // Seçilen metrikleri filtrele
                $filteredData = [];
                foreach ($request->metrics as $metric) {
                    if (isset($reportData[$metric])) {
                        $filteredData[$metric] = $reportData[$metric];
                    }
                }

                // Log aktivitesi
                $this->activityService->log(
                    action: 'custom_report',
                    severity: 'medium',
                    description: 'Özel rapor oluşturuldu',
                    newValues: [
                        'type' => $request->report_type,
                        'metrics' => $request->metrics,
                        'filters' => $filters
                    ],
                    tags: ['report', 'custom']
                );

                return view('admin.reports.custom-result', compact(
                    'filteredData', 
                    'filters', 
                    'request'
                ));
            }

            return view('admin.reports.custom');
        } catch (\Exception $e) {
            return $this->handleException($e, 'generate custom report');
        }
    }

    /**
     * Rapor karşılaştırması
     */
    public function compare(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:users,content,sales',
            'period1_from' => 'required|date',
            'period1_to' => 'required|date|after_or_equal:period1_from',
            'period2_from' => 'required|date',
            'period2_to' => 'required|date|after_or_equal:period2_from'
        ]);

        try {
            $period1 = [
                'date_from' => Carbon::parse($request->period1_from),
                'date_to' => Carbon::parse($request->period1_to)
            ];

            $period2 = [
                'date_from' => Carbon::parse($request->period2_from),
                'date_to' => Carbon::parse($request->period2_to)
            ];

            $reports1 = [];
            $reports2 = [];

            switch ($request->report_type) {
                case 'users':
                    $reports1 = $this->reportService->getUserReports($period1);
                    $reports2 = $this->reportService->getUserReports($period2);
                    break;
                case 'content':
                    $reports1 = $this->reportService->getContentReports($period1);
                    $reports2 = $this->reportService->getContentReports($period2);
                    break;
                case 'sales':
                    $reports1 = $this->reportService->getSalesReports($period1);
                    $reports2 = $this->reportService->getSalesReports($period2);
                    break;
            }

            // Karşılaştırma hesaplamaları
            $comparison = $this->calculateComparison($reports1, $reports2);

            return view('admin.reports.compare', compact(
                'reports1',
                'reports2', 
                'period1',
                'period2',
                'comparison',
                'request'
            ));
        } catch (\Exception $e) {
            return $this->handleException($e, 'compare reports');
        }
    }

    /**
     * Kullanıcı raporlarını CSV olarak dışa aktar
     */
    private function exportUserReportsCsv($reports, $filters)
    {
        $filename = 'user_reports_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // CSV başlıkları
            fputcsv($file, [
                'Metrik',
                'Değer',
                'Açıklama'
            ]);

            // Genel bakış verileri
            $overview = $reports['overview'];
            fputcsv($file, ['Toplam Kullanıcı', $overview['total_users'], 'Sistemdeki toplam kullanıcı sayısı']);
            fputcsv($file, ['Yeni Kullanıcı', $overview['new_users'], 'Seçilen dönemde kayıt olan kullanıcılar']);
            fputcsv($file, ['Aktif Kullanıcı', $overview['active_users'], 'Aktif durumda olan kullanıcılar']);
            fputcsv($file, ['Premium Kullanıcı', $overview['premium_users'], 'Premium üyeliği olan kullanıcılar']);
            fputcsv($file, ['Büyüme Oranı', number_format($overview['growth_rate'], 2) . '%', 'Önceki döneme göre büyüme']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * İçerik raporlarını CSV olarak dışa aktar
     */
    private function exportContentReportsCsv($reports, $filters)
    {
        $filename = 'content_reports_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // Blog istatistikleri
            fputcsv($file, ['Blog İstatistikleri']);
            fputcsv($file, ['Toplam Yazı', $reports['overview']['blog_posts']['total']]);
            fputcsv($file, ['Yeni Yazı', $reports['overview']['blog_posts']['new']]);
            fputcsv($file, ['Yayınlanan', $reports['overview']['blog_posts']['published']]);
            
            fputcsv($file, []); // Boş satır
            
            // Ürün istatistikleri
            fputcsv($file, ['Ürün İstatistikleri']);
            fputcsv($file, ['Toplam Ürün', $reports['overview']['products']['total']]);
            fputcsv($file, ['Yeni Ürün', $reports['overview']['products']['new']]);
            fputcsv($file, ['Aktif Ürün', $reports['overview']['products']['active']]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Satış raporlarını CSV olarak dışa aktar
     */
    private function exportSalesReportsCsv($reports, $filters)
    {
        $filename = 'sales_reports_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Satış Genel Bakış']);
            fputcsv($file, ['Toplam Ürün', $reports['overview']['total_products']]);
            fputcsv($file, ['Öne Çıkan Ürün', $reports['overview']['featured_products']]);
            fputcsv($file, ['Ortalama Fiyat', number_format($reports['overview']['avg_price'], 2) . ' ₺']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Karşılaştırma hesaplamaları
     */
    private function calculateComparison($reports1, $reports2): array
    {
        $comparison = [];

        // Kullanıcı karşılaştırması
        if (isset($reports1['overview']) && isset($reports2['overview'])) {
            $overview1 = $reports1['overview'];
            $overview2 = $reports2['overview'];

            $comparison['users'] = [
                'new_users' => $this->calculatePercentageChange(
                    $overview2['new_users'], 
                    $overview1['new_users']
                ),
                'active_users' => $this->calculatePercentageChange(
                    $overview2['active_users'], 
                    $overview1['active_users']
                ),
                'premium_users' => $this->calculatePercentageChange(
                    $overview2['premium_users'], 
                    $overview1['premium_users']
                )
            ];
        }

        return $comparison;
    }

    /**
     * Yüzde değişim hesapla
     */
    private function calculatePercentageChange($current, $previous): array
    {
        if ($previous == 0) {
            $percentage = $current > 0 ? 100 : 0;
        } else {
            $percentage = (($current - $previous) / $previous) * 100;
        }

        return [
            'current' => $current,
            'previous' => $previous,
            'change' => $current - $previous,
            'percentage' => round($percentage, 2),
            'trend' => $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'stable')
        ];
    }
}