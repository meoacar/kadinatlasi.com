<?php

namespace App\Http\Controllers\Admin;

use App\Services\ExportService;
use App\Services\AdminActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminExportController extends AdminController
{
    protected $exportService;
    protected $activityService;

    public function __construct(ExportService $exportService, AdminActivityService $activityService)
    {
        parent::__construct();
        $this->exportService = $exportService;
        $this->activityService = $activityService;
    }

    /**
     * Export ana sayfası
     */
    public function index()
    {
        try {
            $exportOptions = $this->exportService->getBulkExportOptions();
            
            return view('admin.exports.index', compact('exportOptions'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load export options');
        }
    }

    /**
     * Kullanıcıları dışa aktar
     */
    public function exportUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:active,inactive',
            'membership_type' => 'nullable|string',
            'search' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Aktivite logla
            $this->activityService->log(
                action: 'export',
                severity: 'medium',
                description: 'Kullanıcı verileri dışa aktarıldı',
                newValues: [
                    'type' => 'users',
                    'format' => $request->format,
                    'filters' => $request->except(['_token', 'format'])
                ],
                tags: ['export', 'users']
            );

            switch ($request->format) {
                case 'csv':
                    return $this->exportService->exportUsersCSV($request);
                case 'excel':
                    return $this->exportService->exportUsersExcel($request);
                case 'pdf':
                    return $this->exportService->generatePDFReport('users', $request);
                default:
                    return back()->with('error', 'Geçersiz format.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'export users');
        }
    }

    /**
     * Blog yazılarını dışa aktar
     */
    public function exportBlogPosts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Aktivite logla
            $this->activityService->log(
                action: 'export',
                severity: 'medium',
                description: 'Blog yazıları dışa aktarıldı',
                newValues: [
                    'type' => 'blog',
                    'format' => $request->format,
                    'filters' => $request->except(['_token', 'format'])
                ],
                tags: ['export', 'blog']
            );

            switch ($request->format) {
                case 'csv':
                    return $this->exportService->exportBlogPostsCSV($request);
                case 'excel':
                    return $this->exportService->exportBlogPostsExcel($request);
                case 'pdf':
                    return $this->exportService->generatePDFReport('blog', $request);
                default:
                    return back()->with('error', 'Geçersiz format.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'export blog posts');
        }
    }

    /**
     * Ürünleri dışa aktar
     */
    public function exportProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:active,inactive',
            'category_id' => 'nullable|exists:product_categories,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Aktivite logla
            $this->activityService->log(
                action: 'export',
                severity: 'medium',
                description: 'Ürün verileri dışa aktarıldı',
                newValues: [
                    'type' => 'products',
                    'format' => $request->format,
                    'filters' => $request->except(['_token', 'format'])
                ],
                tags: ['export', 'products']
            );

            switch ($request->format) {
                case 'csv':
                    return $this->exportService->exportProductsCSV($request);
                case 'excel':
                    return $this->exportService->exportProductsExcel($request);
                case 'pdf':
                    return $this->exportService->generatePDFReport('products', $request);
                default:
                    return back()->with('error', 'Geçersiz format.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'export products');
        }
    }

    /**
     * Forum konularını dışa aktar
     */
    public function exportForumTopics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:active,inactive',
            'group_id' => 'nullable|exists:forum_groups,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Aktivite logla
            $this->activityService->log(
                action: 'export',
                severity: 'medium',
                description: 'Forum konuları dışa aktarıldı',
                newValues: [
                    'type' => 'forum',
                    'format' => $request->format,
                    'filters' => $request->except(['_token', 'format'])
                ],
                tags: ['export', 'forum']
            );

            switch ($request->format) {
                case 'csv':
                    return $this->exportService->exportForumTopicsCSV($request);
                case 'excel':
                    return $this->exportService->exportForumTopicsCSV($request);
                case 'pdf':
                    return $this->exportService->generatePDFReport('forum', $request);
                default:
                    return back()->with('error', 'Geçersiz format.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'export forum topics');
        }
    }

    /**
     * Admin aktivitelerini dışa aktar
     */
    public function exportActivities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:csv,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'admin_id' => 'nullable|exists:users,id',
            'action' => 'nullable|string',
            'severity' => 'nullable|in:low,medium,high'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Aktivite logla
            $this->activityService->log(
                action: 'export',
                severity: 'high',
                description: 'Admin aktiviteleri dışa aktarıldı',
                newValues: [
                    'type' => 'activities',
                    'format' => $request->format,
                    'filters' => $request->except(['_token', 'format'])
                ],
                tags: ['export', 'activities', 'security']
            );

            switch ($request->format) {
                case 'csv':
                case 'excel':
                    return $this->exportService->exportActivitiesCSV($request);
                default:
                    return back()->with('error', 'Geçersiz format.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'export activities');
        }
    }

    /**
     * Toplu export işlemi
     */
    public function bulkExport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'export_types' => 'required|array',
            'export_types.*' => 'in:users,blog,products,forum,activities',
            'format' => 'required|in:csv,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $exportTypes = $request->export_types;
            $format = $request->format;
            
            // Tek dosya ise direkt export et
            if (count($exportTypes) === 1) {
                $type = $exportTypes[0];
                return $this->handleSingleExport($type, $format, $request);
            }

            // Çoklu export için ZIP oluştur
            return $this->createBulkExportZip($exportTypes, $format, $request);
        } catch (\Exception $e) {
            return $this->handleException($e, 'bulk export');
        }
    }

    /**
     * Özel export formu
     */
    public function customExport(Request $request)
    {
        try {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'export_type' => 'required|in:users,blog,products,forum,activities',
                    'format' => 'required|in:csv,excel,pdf',
                    'columns' => 'required|array',
                    'columns.*' => 'string',
                    'date_from' => 'nullable|date',
                    'date_to' => 'nullable|date|after_or_equal:date_from'
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                // Özel export işlemi
                return $this->handleCustomExport($request);
            }

            // Form göster
            $exportOptions = $this->exportService->getBulkExportOptions();
            return view('admin.exports.custom', compact('exportOptions'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'custom export');
        }
    }

    /**
     * Export geçmişi
     */
    public function history(Request $request)
    {
        try {
            $activities = $this->activityService->getActivities(
                $request->merge(['action' => 'export'])
            );

            return view('admin.exports.history', compact('activities'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load export history');
        }
    }

    /**
     * Tek export işlemi
     */
    private function handleSingleExport(string $type, string $format, Request $request)
    {
        switch ($type) {
            case 'users':
                return $this->exportUsers($request->merge(['format' => $format]));
            case 'blog':
                return $this->exportBlogPosts($request->merge(['format' => $format]));
            case 'products':
                return $this->exportProducts($request->merge(['format' => $format]));
            case 'forum':
                return $this->exportForumTopics($request->merge(['format' => $format]));
            case 'activities':
                return $this->exportActivities($request->merge(['format' => $format]));
            default:
                throw new \Exception('Geçersiz export türü.');
        }
    }

    /**
     * Toplu export için ZIP oluştur
     */
    private function createBulkExportZip(array $exportTypes, string $format, Request $request)
    {
        $zipFileName = 'bulk_export_' . now()->format('Y_m_d_H_i_s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Temp klasörü oluştur
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('ZIP dosyası oluşturulamadı.');
        }

        foreach ($exportTypes as $type) {
            try {
                $tempFile = $this->generateTempExportFile($type, $format, $request);
                if ($tempFile && file_exists($tempFile)) {
                    $zip->addFile($tempFile, basename($tempFile));
                }
            } catch (\Exception $e) {
                // Hata durumunda devam et
                continue;
            }
        }

        $zip->close();

        // Aktivite logla
        $this->activityService->log(
            action: 'bulk_export',
            severity: 'high',
            description: 'Toplu veri dışa aktarma gerçekleştirildi',
            newValues: [
                'types' => $exportTypes,
                'format' => $format,
                'file_count' => count($exportTypes)
            ],
            tags: ['export', 'bulk']
        );

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend();
    }

    /**
     * Geçici export dosyası oluştur
     */
    private function generateTempExportFile(string $type, string $format, Request $request): ?string
    {
        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $fileName = $type . '_export_' . now()->format('Y_m_d_H_i_s') . '.' . $format;
        $filePath = $tempDir . '/' . $fileName;

        // Export işlemini gerçekleştir ve dosyaya kaydet
        switch ($type) {
            case 'users':
                $content = $this->exportService->exportUsersCSV($request)->getContent();
                break;
            case 'blog':
                $content = $this->exportService->exportBlogPostsCSV($request)->getContent();
                break;
            case 'products':
                $content = $this->exportService->exportProductsCSV($request)->getContent();
                break;
            case 'forum':
                $content = $this->exportService->exportForumTopicsCSV($request)->getContent();
                break;
            case 'activities':
                $content = $this->exportService->exportActivitiesCSV($request)->getContent();
                break;
            default:
                return null;
        }

        file_put_contents($filePath, $content);
        return $filePath;
    }

    /**
     * Özel export işlemi
     */
    private function handleCustomExport(Request $request)
    {
        $type = $request->export_type;
        $format = $request->format;
        $columns = $request->columns;
        
        // Aktivite logla
        $this->activityService->log(
            action: 'custom_export',
            severity: 'medium',
            description: 'Özel export gerçekleştirildi',
            newValues: [
                'type' => $type,
                'format' => $format,
                'columns' => $columns
            ],
            tags: ['export', 'custom']
        );

        // Şimdilik standart export'a yönlendir
        return $this->handleSingleExport($type, $format, $request);
    }
}