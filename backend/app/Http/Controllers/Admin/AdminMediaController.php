<?php

namespace App\Http\Controllers\Admin;

use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMediaController extends AdminController
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        parent::__construct();
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Medya yönetimi ana sayfası
     */
    public function index(Request $request)
    {
        try {
            $directory = $request->get('directory', '');
            $type = $request->get('type', 'all'); // all, images, documents, archives
            
            $files = $this->fileUploadService->listFiles($directory);
            
            // Tip filtreleme
            if ($type !== 'all') {
                $files = array_filter($files, function($file) use ($type) {
                    switch ($type) {
                        case 'images':
                            return $file['is_image'];
                        case 'documents':
                            return in_array(strtolower($file['extension']), ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']);
                        case 'archives':
                            return in_array(strtolower($file['extension']), ['zip', 'rar', '7z', 'tar', 'gz']);
                        default:
                            return true;
                    }
                });
            }

            // Disk kullanım bilgileri
            $diskUsage = $this->fileUploadService->getDiskUsage($directory);
            
            // Dizin listesi
            $directories = Storage::disk('public')->directories($directory);
            
            return view('admin.media.index', compact('files', 'directories', 'diskUsage', 'directory', 'type'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load media files');
        }
    }

    /**
     * Dosya yükle
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:10240', // 10MB
            'directory' => 'nullable|string|max:255',
            'type' => 'nullable|in:image,document,archive',
        ], [
            'files.required' => 'En az bir dosya seçmelisiniz.',
            'files.*.file' => 'Geçerli bir dosya yükleyin.',
            'files.*.max' => 'Dosya boyutu en fazla 10MB olabilir.',
        ]);

        try {
            $files = $request->file('files');
            $directory = $request->get('directory', 'uploads');
            $type = $request->get('type');

            $options = [
                'directory' => $directory,
            ];

            // Tip özel ayarları
            if ($type === 'image') {
                $options['thumbnail'] = ['width' => 200, 'height' => 200];
                $options['resize'] = ['width' => 1200, 'height' => 800, 'maintain_ratio' => true];
            }

            $result = $this->fileUploadService->uploadMultiple($files, $options);

            if ($result['success']) {
                return $this->successResponse(
                    "{$result['uploaded']} dosya başarıyla yüklendi." . 
                    ($result['failed'] > 0 ? " {$result['failed']} dosya yüklenemedi." : "")
                )->with('upload_results', $result);
            } else {
                return $this->errorResponse('Dosya yükleme başarısız oldu.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'upload files');
        }
    }

    /**
     * Tek dosya yükle (AJAX)
     */
    public function uploadSingle(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'directory' => 'nullable|string|max:255',
            'type' => 'nullable|in:image,document,archive',
        ]);

        try {
            $file = $request->file('file');
            $directory = $request->get('directory', 'uploads');
            $type = $request->get('type');

            $options = [
                'directory' => $directory,
            ];

            // Tip özel ayarları
            if ($type === 'image') {
                $options['thumbnail'] = ['width' => 200, 'height' => 200];
                $options['resize'] = ['width' => 1200, 'height' => 800, 'maintain_ratio' => true];
            }

            $result = $this->fileUploadService->upload($file, $options);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dosya başarıyla yüklendi.',
                    'file' => $result,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'],
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dosya sil
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->get('path');
            
            if ($this->fileUploadService->delete($path)) {
                return $this->successResponse('Dosya başarıyla silindi.');
            } else {
                return $this->errorResponse('Dosya silinemedi.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete file');
        }
    }

    /**
     * Çoklu dosya sil
     */
    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'paths' => 'required|array|min:1',
            'paths.*' => 'string',
        ]);

        try {
            $paths = $request->get('paths');
            $result = $this->fileUploadService->deleteMultiple($paths);

            return $this->successResponse(
                "{$result['deleted']} dosya başarıyla silindi." .
                (count($result['errors']) > 0 ? " " . count($result['errors']) . " dosya silinemedi." : "")
            );
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete multiple files');
        }
    }

    /**
     * Dosya bilgilerini al
     */
    public function getFileInfo(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->get('path');
            $fileInfo = $this->fileUploadService->getFileInfo($path);

            if ($fileInfo) {
                return response()->json([
                    'success' => true,
                    'file' => $fileInfo,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosya bulunamadı.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dizin oluştur
     */
    public function createDirectory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'parent' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Dizin adı gereklidir.',
            'name.regex' => 'Dizin adı sadece harf, rakam, tire ve alt çizgi içerebilir.',
        ]);

        try {
            $name = $request->get('name');
            $parent = $request->get('parent', '');
            $path = $parent ? $parent . '/' . $name : $name;

            if (Storage::disk('public')->exists($path)) {
                return $this->errorResponse('Bu isimde bir dizin zaten mevcut.');
            }

            Storage::disk('public')->makeDirectory($path);

            return $this->successResponse('Dizin başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'create directory');
        }
    }

    /**
     * Dizin sil
     */
    public function deleteDirectory(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->get('path');

            if (!Storage::disk('public')->exists($path)) {
                return $this->errorResponse('Dizin bulunamadı.');
            }

            // Dizin boş mu kontrol et
            $files = Storage::disk('public')->allFiles($path);
            $directories = Storage::disk('public')->allDirectories($path);

            if (count($files) > 0 || count($directories) > 0) {
                return $this->errorResponse('Dizin boş değil. Önce içeriği silin.');
            }

            Storage::disk('public')->deleteDirectory($path);

            return $this->successResponse('Dizin başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete directory');
        }
    }

    /**
     * Eski dosyaları temizle
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'directory' => 'nullable|string|max:255',
            'older_than_days' => 'nullable|integer|min:1|max:365',
        ]);

        try {
            $directory = $request->get('directory', '');
            $olderThanDays = $request->get('older_than_days', 30);

            $result = $this->fileUploadService->cleanupOldFiles($directory, $olderThanDays);

            if ($result) {
                return $this->successResponse(
                    "{$result['deleted_count']} dosya temizlendi. " .
                    "Toplam {$result['deleted_size_formatted']} alan boşaltıldı."
                );
            } else {
                return $this->errorResponse('Temizlik işlemi başarısız oldu.');
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'cleanup old files');
        }
    }

    /**
     * Disk kullanım istatistikleri
     */
    public function diskUsage(Request $request)
    {
        try {
            $directory = $request->get('directory', '');
            $usage = $this->fileUploadService->getDiskUsage($directory);

            return response()->json([
                'success' => true,
                'usage' => $usage,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resim boyutlandır
     */
    public function resizeImage(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'width' => 'nullable|integer|min:1|max:3000',
            'height' => 'nullable|integer|min:1|max:3000',
            'quality' => 'nullable|integer|min:1|max:100',
            'crop' => 'nullable|boolean',
        ]);

        try {
            $path = $request->get('path');
            $width = $request->get('width');
            $height = $request->get('height');
            $quality = $request->get('quality', 85);
            $crop = $request->get('crop', false);

            if (!Storage::disk('public')->exists($path)) {
                return $this->errorResponse('Dosya bulunamadı.');
            }

            // Resim mi kontrol et
            $fileInfo = $this->fileUploadService->getFileInfo($path);
            if (!$fileInfo['is_image']) {
                return $this->errorResponse('Sadece resim dosyaları boyutlandırılabilir.');
            }

            // Yeni dosya adı oluştur
            $pathInfo = pathinfo($path);
            $newPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_resized.' . $pathInfo['extension'];

            // Resmi kopyala ve boyutlandır
            Storage::disk('public')->copy($path, $newPath);

            $options = [
                'width' => $width,
                'height' => $height,
                'quality' => $quality,
                'crop' => $crop,
                'maintain_ratio' => !$crop,
            ];

            // Resmi işle (private method'u kullanamayız, bu yüzden service'e public method eklemek gerekir)
            // Şimdilik basit bir çözüm
            $fullPath = Storage::disk('public')->path($newPath);
            $image = \Intervention\Image\Facades\Image::make($fullPath);

            if ($crop && $width && $height) {
                $image->fit($width, $height);
            } elseif ($width || $height) {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $image->save($fullPath, $quality);

            return $this->successResponse('Resim başarıyla boyutlandırıldı.')
                ->with('new_path', $newPath);
        } catch (\Exception $e) {
            return $this->handleException($e, 'resize image');
        }
    }

    /**
     * Medya seçici (popup)
     */
    public function picker(Request $request)
    {
        try {
            $type = $request->get('type', 'all'); // all, images, documents
            $multiple = $request->get('multiple', false);
            $directory = $request->get('directory', '');

            $files = $this->fileUploadService->listFiles($directory);
            
            // Tip filtreleme
            if ($type === 'images') {
                $files = array_filter($files, function($file) {
                    return $file['is_image'];
                });
            } elseif ($type === 'documents') {
                $files = array_filter($files, function($file) {
                    return !$file['is_image'];
                });
            }

            return view('admin.media.picker', compact('files', 'type', 'multiple', 'directory'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load media picker');
        }
    }
}