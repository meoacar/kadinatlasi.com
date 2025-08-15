<?php

namespace App\Services;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ForumTopic;
use App\Models\AdminActivity;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExportService
{
    /**
     * Kullanıcıları CSV olarak dışa aktar
     */
    public function exportUsersCSV(Request $request)
    {
        $query = User::query();
        
        // Filtreleri uygula
        $this->applyUserFilters($query, $request);
        
        $users = $query->with(['profile'])->get();
        
        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM ekle (Excel için)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV başlıkları
            fputcsv($file, [
                'ID',
                'Ad Soyad',
                'E-posta',
                'Üyelik Türü',
                'Durum',
                'Doğum Tarihi',
                'Burç',
                'Puan',
                'Kayıt Tarihi',
                'Son Güncelleme'
            ]);

            // Veri satırları
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->membership_type ?? 'Standart',
                    $user->is_active ? 'Aktif' : 'Pasif',
                    $user->birth_date ? $user->birth_date->format('d.m.Y') : '',
                    $user->zodiac_sign ?? '',
                    $user->points ?? 0,
                    $user->created_at->format('d.m.Y H:i:s'),
                    $user->updated_at->format('d.m.Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Blog yazılarını CSV olarak dışa aktar
     */
    public function exportBlogPostsCSV(Request $request)
    {
        $query = BlogPost::with(['author', 'category']);
        
        // Filtreleri uygula
        $this->applyBlogFilters($query, $request);
        
        $posts = $query->get();
        
        $filename = 'blog_posts_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($posts) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV başlıkları
            fputcsv($file, [
                'ID',
                'Başlık',
                'Yazar',
                'Kategori',
                'Durum',
                'Görüntülenme',
                'Beğeni',
                'Yayın Tarihi',
                'Oluşturulma Tarihi'
            ]);

            foreach ($posts as $post) {
                fputcsv($file, [
                    $post->id,
                    $post->title,
                    $post->author->name ?? 'Bilinmiyor',
                    $post->category->name ?? 'Kategorisiz',
                    $post->status === 'published' ? 'Yayında' : 'Taslak',
                    $post->views_count ?? 0,
                    $post->likes_count ?? 0,
                    $post->published_at ? $post->published_at->format('d.m.Y H:i:s') : '',
                    $post->created_at->format('d.m.Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ürünleri CSV olarak dışa aktar
     */
    public function exportProductsCSV(Request $request)
    {
        $query = Product::with(['category']);
        
        // Filtreleri uygula
        $this->applyProductFilters($query, $request);
        
        $products = $query->get();
        
        $filename = 'products_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV başlıkları
            fputcsv($file, [
                'ID',
                'Ürün Adı',
                'SKU',
                'Kategori',
                'Fiyat',
                'İndirimli Fiyat',
                'Marka',
                'Durum',
                'Stok Durumu',
                'Görüntülenme',
                'Oluşturulma Tarihi'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku ?? '',
                    $product->category->name ?? 'Kategorisiz',
                    $product->price ? number_format($product->price, 2) . ' ₺' : '',
                    $product->sale_price ? number_format($product->sale_price, 2) . ' ₺' : '',
                    $product->brand ?? '',
                    $product->is_active ? 'Aktif' : 'Pasif',
                    $product->in_stock ? 'Stokta' : 'Stok Yok',
                    $product->views_count ?? 0,
                    $product->created_at->format('d.m.Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Forum konularını CSV olarak dışa aktar
     */
    public function exportForumTopicsCSV(Request $request)
    {
        $query = ForumTopic::with(['author', 'group']);
        
        // Filtreleri uygula
        $this->applyForumFilters($query, $request);
        
        $topics = $query->get();
        
        $filename = 'forum_topics_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($topics) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV başlıkları
            fputcsv($file, [
                'ID',
                'Başlık',
                'Yazar',
                'Grup',
                'Durum',
                'Görüntülenme',
                'Yanıt Sayısı',
                'Oluşturulma Tarihi'
            ]);

            foreach ($topics as $topic) {
                fputcsv($file, [
                    $topic->id,
                    $topic->title,
                    $topic->author->name ?? 'Bilinmiyor',
                    $topic->group->name ?? 'Genel',
                    $topic->is_active ? 'Aktif' : 'Pasif',
                    $topic->views_count ?? 0,
                    $topic->replies_count ?? 0,
                    $topic->created_at->format('d.m.Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Admin aktivitelerini CSV olarak dışa aktar
     */
    public function exportActivitiesCSV(Request $request)
    {
        $query = AdminActivity::with('admin');
        
        // Filtreleri uygula
        $this->applyActivityFilters($query, $request);
        
        $activities = $query->latest()->limit(10000)->get();
        
        $filename = 'admin_activities_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV başlıkları
            fputcsv($file, [
                'ID',
                'Admin',
                'Aksiyon',
                'Model',
                'Açıklama',
                'IP Adresi',
                'Önem Derecesi',
                'Tarih'
            ]);

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
    }

    /**
     * Excel formatında dışa aktarma (PhpSpreadsheet kullanarak)
     */
    public function exportUsersExcel(Request $request)
    {
        // PhpSpreadsheet kurulu değilse CSV'ye yönlendir
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return $this->exportUsersCSV($request);
        }

        $query = User::query();
        $this->applyUserFilters($query, $request);
        $users = $query->with(['profile'])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Başlıkları ayarla
        $headers = ['ID', 'Ad Soyad', 'E-posta', 'Üyelik Türü', 'Durum', 'Doğum Tarihi', 'Burç', 'Puan', 'Kayıt Tarihi'];
        $sheet->fromArray($headers, null, 'A1');
        
        // Başlık stilini ayarla
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Veri satırları
        $row = 2;
        foreach ($users as $user) {
            $sheet->fromArray([
                $user->id,
                $user->name,
                $user->email,
                $user->membership_type ?? 'Standart',
                $user->is_active ? 'Aktif' : 'Pasif',
                $user->birth_date ? $user->birth_date->format('d.m.Y') : '',
                $user->zodiac_sign ?? '',
                $user->points ?? 0,
                $user->created_at->format('d.m.Y H:i:s')
            ], null, 'A' . $row);
            $row++;
        }

        // Sütun genişliklerini ayarla
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Blog yazıları Excel export
     */
    public function exportBlogPostsExcel(Request $request)
    {
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return $this->exportBlogPostsCSV($request);
        }

        $query = BlogPost::with(['author', 'category']);
        $this->applyBlogFilters($query, $request);
        $posts = $query->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['ID', 'Başlık', 'Yazar', 'Kategori', 'Durum', 'Görüntülenme', 'Beğeni', 'Yayın Tarihi', 'Oluşturulma Tarihi'];
        $sheet->fromArray($headers, null, 'A1');
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($posts as $post) {
            $sheet->fromArray([
                $post->id,
                $post->title,
                $post->author->name ?? 'Bilinmiyor',
                $post->category->name ?? 'Kategorisiz',
                $post->status === 'published' ? 'Yayında' : 'Taslak',
                $post->views_count ?? 0,
                $post->likes_count ?? 0,
                $post->published_at ? $post->published_at->format('d.m.Y H:i:s') : '',
                $post->created_at->format('d.m.Y H:i:s')
            ], null, 'A' . $row);
            $row++;
        }

        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'blog_posts_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Ürünler Excel export
     */
    public function exportProductsExcel(Request $request)
    {
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return $this->exportProductsCSV($request);
        }

        $query = Product::with(['category']);
        $this->applyProductFilters($query, $request);
        $products = $query->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['ID', 'Ürün Adı', 'SKU', 'Kategori', 'Fiyat', 'İndirimli Fiyat', 'Marka', 'Durum', 'Stok Durumu', 'Görüntülenme', 'Oluşturulma Tarihi'];
        $sheet->fromArray($headers, null, 'A1');
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($products as $product) {
            $sheet->fromArray([
                $product->id,
                $product->name,
                $product->sku ?? '',
                $product->category->name ?? 'Kategorisiz',
                $product->price ? number_format($product->price, 2) . ' ₺' : '',
                $product->sale_price ? number_format($product->sale_price, 2) . ' ₺' : '',
                $product->brand ?? '',
                $product->is_active ? 'Aktif' : 'Pasif',
                $product->in_stock ? 'Stokta' : 'Stok Yok',
                $product->views_count ?? 0,
                $product->created_at->format('d.m.Y H:i:s')
            ], null, 'A' . $row);
            $row++;
        }

        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'products_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * PDF raporu oluştur
     */
    public function generatePDFReport(string $type, Request $request)
    {
        // DomPDF kurulu değilse hata döndür
        if (!class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            throw new \Exception('PDF export için DomPDF paketi gerekli.');
        }

        $data = $this->getReportData($type, $request);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.pdf-report', [
            'type' => $type,
            'data' => $data,
            'filters' => $request->all(),
            'generated_at' => now()
        ]);

        $filename = $type . '_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Kullanıcı filtreleri uygula
     */
    private function applyUserFilters($query, Request $request)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->filled('membership_type')) {
            $query->where('membership_type', $request->membership_type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
    }

    /**
     * Blog filtreleri uygula
     */
    private function applyBlogFilters($query, Request $request)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('author_id')) {
            $query->where('user_id', $request->author_id);
        }
    }

    /**
     * Ürün filtreleri uygula
     */
    private function applyProductFilters($query, Request $request)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
    }

    /**
     * Forum filtreleri uygula
     */
    private function applyForumFilters($query, Request $request)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
    }

    /**
     * Aktivite filtreleri uygula
     */
    private function applyActivityFilters($query, Request $request)
    {
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
        
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
    }

    /**
     * Rapor verilerini getir
     */
    private function getReportData(string $type, Request $request): array
    {
        switch ($type) {
            case 'users':
                $query = User::query();
                $this->applyUserFilters($query, $request);
                return [
                    'items' => $query->with(['profile'])->get(),
                    'title' => 'Kullanıcı Raporu'
                ];
                
            case 'blog':
                $query = BlogPost::with(['author', 'category']);
                $this->applyBlogFilters($query, $request);
                return [
                    'items' => $query->get(),
                    'title' => 'Blog Yazıları Raporu'
                ];
                
            case 'products':
                $query = Product::with(['category']);
                $this->applyProductFilters($query, $request);
                return [
                    'items' => $query->get(),
                    'title' => 'Ürün Raporu'
                ];
                
            case 'forum':
                $query = ForumTopic::with(['author', 'group']);
                $this->applyForumFilters($query, $request);
                return [
                    'items' => $query->get(),
                    'title' => 'Forum Raporu'
                ];
                
            default:
                return ['items' => collect(), 'title' => 'Bilinmeyen Rapor'];
        }
    }

    /**
     * Toplu export seçenekleri
     */
    public function getBulkExportOptions(): array
    {
        return [
            'users' => [
                'name' => 'Kullanıcılar',
                'description' => 'Tüm kullanıcı bilgileri',
                'formats' => ['csv', 'excel', 'pdf']
            ],
            'blog' => [
                'name' => 'Blog Yazıları',
                'description' => 'Blog yazıları ve istatistikleri',
                'formats' => ['csv', 'excel', 'pdf']
            ],
            'products' => [
                'name' => 'Ürünler',
                'description' => 'Ürün kataloğu ve fiyat bilgileri',
                'formats' => ['csv', 'excel', 'pdf']
            ],
            'forum' => [
                'name' => 'Forum',
                'description' => 'Forum konuları ve aktiviteleri',
                'formats' => ['csv', 'excel', 'pdf']
            ],
            'activities' => [
                'name' => 'Admin Aktiviteleri',
                'description' => 'Admin panel aktivite logları',
                'formats' => ['csv', 'excel']
            ]
        ];
    }
}