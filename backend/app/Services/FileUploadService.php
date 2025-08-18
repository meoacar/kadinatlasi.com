<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
    const MAX_DOCUMENT_SIZE = 20 * 1024 * 1024; // 20MB

    const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    const ALLOWED_DOCUMENT_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
    const ALLOWED_ARCHIVE_TYPES = ['zip', 'rar', '7z', 'tar', 'gz'];

    protected $disk;
    protected $basePath;

    public function __construct($disk = 'public')
    {
        $this->disk = $disk;
        $this->basePath = '';
    }

    /**
     * Dosya yükle
     */
    public function upload(UploadedFile $file, array $options = [])
    {
        try {
            // Dosya validasyonu
            $this->validateFile($file, $options);

            // Yükleme dizinini belirle
            $directory = $options['directory'] ?? 'uploads';
            $filename = $this->generateFilename($file, $options);
            $path = $directory . '/' . $filename;

            // Dosyayı yükle
            $uploadedPath = Storage::disk($this->disk)->putFileAs($directory, $file, $filename);

            // Resim ise işle
            if ($this->isImage($file) && isset($options['resize'])) {
                $this->processImage($uploadedPath, $options['resize']);
            }

            // Thumbnail oluştur
            if ($this->isImage($file) && ($options['thumbnail'] ?? false)) {
                $this->createThumbnail($uploadedPath, $options['thumbnail']);
            }

            // Dosya bilgilerini logla
            $this->logUpload($file, $uploadedPath);

            return [
                'success' => true,
                'path' => $uploadedPath,
                'url' => Storage::disk($this->disk)->url($uploadedPath),
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
            ];
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Çoklu dosya yükle
     */
    public function uploadMultiple(array $files, array $options = [])
    {
        $results = [];
        $successCount = 0;
        $errors = [];

        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                $result = $this->upload($file, $options);
                $results[] = $result;

                if ($result['success']) {
                    $successCount++;
                } else {
                    $errors[] = [
                        'index' => $index,
                        'filename' => $file->getClientOriginalName(),
                        'error' => $result['error'],
                    ];
                }
            }
        }

        return [
            'success' => $successCount > 0,
            'total' => count($files),
            'uploaded' => $successCount,
            'failed' => count($errors),
            'results' => $results,
            'errors' => $errors,
        ];
    }

    /**
     * Resim yükle ve işle
     */
    public function uploadImage(UploadedFile $file, array $options = [])
    {
        if (!$this->isImage($file)) {
            throw new \InvalidArgumentException('Yüklenen dosya bir resim değil.');
        }

        $options['type'] = 'image';
        return $this->upload($file, $options);
    }

    /**
     * Avatar yükle
     */
    public function uploadAvatar(UploadedFile $file, $userId = null)
    {
        $options = [
            'directory' => 'avatars',
            'resize' => ['width' => 200, 'height' => 200, 'crop' => true],
            'thumbnail' => ['width' => 50, 'height' => 50],
            'prefix' => $userId ? "user_{$userId}_" : 'avatar_',
        ];

        return $this->uploadImage($file, $options);
    }

    /**
     * Ürün resmi yükle
     */
    public function uploadProductImage(UploadedFile $file, $productId = null)
    {
        $options = [
            'directory' => 'products',
            'resize' => ['width' => 800, 'height' => 600, 'maintain_ratio' => true],
            'thumbnail' => ['width' => 200, 'height' => 150],
            'prefix' => $productId ? "product_{$productId}_" : 'product_',
        ];

        return $this->uploadImage($file, $options);
    }

    /**
     * Blog resmi yükle
     */
    public function uploadBlogImage(UploadedFile $file, $postId = null)
    {
        $options = [
            'directory' => 'blog',
            'resize' => ['width' => 1200, 'height' => 630, 'maintain_ratio' => true],
            'thumbnail' => ['width' => 300, 'height' => 200],
            'prefix' => $postId ? "post_{$postId}_" : 'blog_',
        ];

        return $this->uploadImage($file, $options);
    }

    /**
     * Kategori resmi yükle
     */
    public function uploadCategoryImage(UploadedFile $file, $categoryId = null)
    {
        $options = [
            'directory' => 'categories',
            'resize' => ['width' => 400, 'height' => 300, 'maintain_ratio' => true],
            'thumbnail' => ['width' => 100, 'height' => 75],
            'prefix' => $categoryId ? "category_{$categoryId}_" : 'category_',
        ];

        return $this->uploadImage($file, $options);
    }

    /**
     * Dosya sil
     */
    public function delete($path)
    {
        try {
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);

                // Thumbnail varsa onu da sil
                $thumbnailPath = $this->getThumbnailPath($path);
                if (Storage::disk($this->disk)->exists($thumbnailPath)) {
                    Storage::disk($this->disk)->delete($thumbnailPath);
                }

                Log::info('File deleted successfully', ['path' => $path]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Çoklu dosya sil
     */
    public function deleteMultiple(array $paths)
    {
        $deleted = 0;
        $errors = [];

        foreach ($paths as $path) {
            if ($this->delete($path)) {
                $deleted++;
            } else {
                $errors[] = $path;
            }
        }

        return [
            'deleted' => $deleted,
            'total' => count($paths),
            'errors' => $errors,
        ];
    }

    /**
     * Dosya validasyonu
     */
    protected function validateFile(UploadedFile $file, array $options = [])
    {
        // Dosya boyutu kontrolü
        $maxSize = $options['max_size'] ?? $this->getMaxSizeForType($file);
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException(
                'Dosya boyutu çok büyük. Maksimum: ' . $this->formatBytes($maxSize)
            );
        }

        // Dosya tipi kontrolü
        $allowedTypes = $options['allowed_types'] ?? $this->getAllowedTypesForFile($file);
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedTypes)) {
            throw new \InvalidArgumentException(
                'Desteklenmeyen dosya tipi: ' . $extension . '. İzin verilen: ' . implode(', ', $allowedTypes)
            );
        }

        // MIME type kontrolü
        $mimeType = $file->getMimeType();
        if (!$this->isValidMimeType($mimeType, $extension)) {
            throw new \InvalidArgumentException('Geçersiz dosya formatı.');
        }

        // Güvenlik kontrolleri
        $this->performSecurityChecks($file);

        // Resim için ek kontroller
        if ($this->isImage($file)) {
            $this->validateImage($file, $options);
        }
    }

    /**
     * Güvenlik kontrolleri
     */
    protected function performSecurityChecks(UploadedFile $file)
    {
        // Dosya adında tehlikeli karakterler kontrolü
        $originalName = $file->getClientOriginalName();
        $dangerousPatterns = [
            '/\.\./', // Path traversal
            '/[<>:"|?*]/', // Windows invalid chars
            '/[\x00-\x1f]/', // Control characters
            '/\.(php|phtml|php3|php4|php5|phar|exe|bat|cmd|com|scr|vbs|js|jar)$/i', // Executable files
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $originalName)) {
                throw new \InvalidArgumentException('Dosya adı güvenlik riski içeriyor.');
            }
        }

        // Dosya içeriği güvenlik kontrolü
        $this->scanFileContent($file);

        // Dosya boyutu sıfır kontrolü
        if ($file->getSize() === 0) {
            throw new \InvalidArgumentException('Boş dosya yüklenemez.');
        }

        // Geçici dosya kontrolü
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('Dosya yükleme sırasında hata oluştu.');
        }
    }

    /**
     * Dosya içeriği güvenlik taraması
     */
    protected function scanFileContent(UploadedFile $file)
    {
        // Sadece küçük dosyalar için içerik taraması (performans için)
        if ($file->getSize() > 1024 * 1024) { // 1MB'dan büyükse atla
            return;
        }

        try {
            $content = file_get_contents($file->getRealPath());
            
            // PHP kodu kontrolü
            if (strpos($content, '<?php') !== false || strpos($content, '<?=') !== false) {
                throw new \InvalidArgumentException('Dosya PHP kodu içeriyor.');
            }

            // Script tag kontrolü
            if (preg_match('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi', $content)) {
                throw new \InvalidArgumentException('Dosya JavaScript kodu içeriyor.');
            }

            // Executable magic bytes kontrolü
            $magicBytes = substr($content, 0, 4);
            $executableSignatures = [
                "\x4D\x5A", // PE executable
                "\x7F\x45\x4C\x46", // ELF executable
                "\xCA\xFE\xBA\xBE", // Java class file
                "\xFE\xED\xFA\xCE", // Mach-O executable
            ];

            foreach ($executableSignatures as $signature) {
                if (strpos($magicBytes, $signature) === 0) {
                    throw new \InvalidArgumentException('Çalıştırılabilir dosya yüklenemez.');
                }
            }

        } catch (\Exception $e) {
            if ($e instanceof \InvalidArgumentException) {
                throw $e;
            }
            // İçerik okunamadıysa devam et
            Log::warning('Could not scan file content for security', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Resim validasyonu
     */
    protected function validateImage(UploadedFile $file, array $options = [])
    {
        try {
            $image = Image::make($file);
            
            // Minimum boyut kontrolü
            if (isset($options['min_width']) && $image->width() < $options['min_width']) {
                throw new \InvalidArgumentException('Resim genişliği en az ' . $options['min_width'] . 'px olmalıdır.');
            }

            if (isset($options['min_height']) && $image->height() < $options['min_height']) {
                throw new \InvalidArgumentException('Resim yüksekliği en az ' . $options['min_height'] . 'px olmalıdır.');
            }

            // Maksimum boyut kontrolü
            if (isset($options['max_width']) && $image->width() > $options['max_width']) {
                throw new \InvalidArgumentException('Resim genişliği en fazla ' . $options['max_width'] . 'px olabilir.');
            }

            if (isset($options['max_height']) && $image->height() > $options['max_height']) {
                throw new \InvalidArgumentException('Resim yüksekliği en fazla ' . $options['max_height'] . 'px olabilir.');
            }

            // Aspect ratio kontrolü
            if (isset($options['aspect_ratio'])) {
                $ratio = $image->width() / $image->height();
                $expectedRatio = $options['aspect_ratio'];
                $tolerance = 0.1;

                if (abs($ratio - $expectedRatio) > $tolerance) {
                    throw new \InvalidArgumentException('Resim oranı ' . $expectedRatio . ' olmalıdır.');
                }
            }

        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Resim işlenirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Resim işle
     */
    protected function processImage($path, array $resizeOptions)
    {
        try {
            $fullPath = Storage::disk($this->disk)->path($path);
            $image = Image::make($fullPath);

            $width = $resizeOptions['width'] ?? null;
            $height = $resizeOptions['height'] ?? null;
            $crop = $resizeOptions['crop'] ?? false;
            $maintainRatio = $resizeOptions['maintain_ratio'] ?? true;
            $quality = $resizeOptions['quality'] ?? 85;

            if ($crop) {
                $image->fit($width, $height);
            } elseif ($maintainRatio) {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $image->resize($width, $height);
            }

            // Kalite ayarla
            $image->save($fullPath, $quality);

            Log::info('Image processed successfully', [
                'path' => $path,
                'width' => $width,
                'height' => $height,
                'quality' => $quality,
            ]);

        } catch (\Exception $e) {
            Log::error('Image processing failed', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Thumbnail oluştur
     */
    protected function createThumbnail($originalPath, array $thumbnailOptions)
    {
        try {
            $thumbnailPath = $this->getThumbnailPath($originalPath);
            $fullOriginalPath = Storage::disk($this->disk)->path($originalPath);
            $fullThumbnailPath = Storage::disk($this->disk)->path($thumbnailPath);

            // Thumbnail dizinini oluştur
            $thumbnailDir = dirname($fullThumbnailPath);
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            $image = Image::make($fullOriginalPath);
            
            $width = $thumbnailOptions['width'] ?? 150;
            $height = $thumbnailOptions['height'] ?? 150;
            $quality = $thumbnailOptions['quality'] ?? 80;

            $image->fit($width, $height);
            $image->save($fullThumbnailPath, $quality);

            Log::info('Thumbnail created successfully', [
                'original' => $originalPath,
                'thumbnail' => $thumbnailPath,
            ]);

            return $thumbnailPath;

        } catch (\Exception $e) {
            Log::error('Thumbnail creation failed', [
                'path' => $originalPath,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Dosya adı oluştur
     */
    protected function generateFilename(UploadedFile $file, array $options = [])
    {
        $extension = $file->getClientOriginalExtension();
        $prefix = $options['prefix'] ?? '';
        $suffix = $options['suffix'] ?? '';
        
        if ($options['keep_original_name'] ?? false) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $name = Str::slug($name);
        } else {
            $name = Str::random(32);
        }

        $timestamp = $options['add_timestamp'] ?? true ? '_' . time() : '';
        
        return $prefix . $name . $suffix . $timestamp . '.' . $extension;
    }

    /**
     * Thumbnail path'i al
     */
    protected function getThumbnailPath($originalPath)
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
    }

    /**
     * Dosya tipine göre maksimum boyut
     */
    protected function getMaxSizeForType(UploadedFile $file)
    {
        if ($this->isImage($file)) {
            return self::MAX_IMAGE_SIZE;
        } elseif ($this->isDocument($file)) {
            return self::MAX_DOCUMENT_SIZE;
        }

        return self::MAX_FILE_SIZE;
    }

    /**
     * Dosya tipine göre izin verilen uzantılar
     */
    protected function getAllowedTypesForFile(UploadedFile $file)
    {
        if ($this->isImage($file)) {
            return self::ALLOWED_IMAGE_TYPES;
        } elseif ($this->isDocument($file)) {
            return self::ALLOWED_DOCUMENT_TYPES;
        } elseif ($this->isArchive($file)) {
            return self::ALLOWED_ARCHIVE_TYPES;
        }

        return array_merge(
            self::ALLOWED_IMAGE_TYPES,
            self::ALLOWED_DOCUMENT_TYPES,
            self::ALLOWED_ARCHIVE_TYPES
        );
    }

    /**
     * Resim dosyası mı kontrol et
     */
    protected function isImage(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();
        return strpos($mimeType, 'image/') === 0;
    }

    /**
     * Döküman dosyası mı kontrol et
     */
    protected function isDocument(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, self::ALLOWED_DOCUMENT_TYPES);
    }

    /**
     * Arşiv dosyası mı kontrol et
     */
    protected function isArchive(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, self::ALLOWED_ARCHIVE_TYPES);
    }

    /**
     * MIME type validasyonu
     */
    protected function isValidMimeType($mimeType, $extension)
    {
        $validMimeTypes = [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
            'svg' => ['image/svg+xml'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'ppt' => ['application/vnd.ms-powerpoint'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            'txt' => ['text/plain'],
            'zip' => ['application/zip'],
            'rar' => ['application/x-rar-compressed'],
        ];

        if (!isset($validMimeTypes[$extension])) {
            return true; // Bilinmeyen uzantılar için geç
        }

        return in_array($mimeType, $validMimeTypes[$extension]);
    }

    /**
     * Dosya yükleme logla
     */
    protected function logUpload(UploadedFile $file, $path)
    {
        Log::info('File uploaded successfully', [
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ]);
    }

    /**
     * Byte'ları okunabilir formata çevir
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Dosya bilgilerini al
     */
    public function getFileInfo($path)
    {
        try {
            if (!Storage::disk($this->disk)->exists($path)) {
                return null;
            }

            $fullPath = Storage::disk($this->disk)->path($path);
            $url = Storage::disk($this->disk)->url($path);
            $size = Storage::disk($this->disk)->size($path);
            $lastModified = Storage::disk($this->disk)->lastModified($path);

            return [
                'path' => $path,
                'url' => $url,
                'size' => $size,
                'size_formatted' => $this->formatBytes($size),
                'last_modified' => $lastModified,
                'last_modified_formatted' => date('d.m.Y H:i', $lastModified),
                'extension' => pathinfo($path, PATHINFO_EXTENSION),
                'filename' => pathinfo($path, PATHINFO_FILENAME),
                'basename' => pathinfo($path, PATHINFO_BASENAME),
                'is_image' => $this->isImagePath($path),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get file info', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Path'e göre resim kontrolü
     */
    protected function isImagePath($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, self::ALLOWED_IMAGE_TYPES);
    }

    /**
     * Dizindeki dosyaları listele
     */
    public function listFiles($directory = '', $recursive = false)
    {
        try {
            $files = $recursive 
                ? Storage::disk($this->disk)->allFiles($directory)
                : Storage::disk($this->disk)->files($directory);

            $fileList = [];
            foreach ($files as $file) {
                $fileInfo = $this->getFileInfo($file);
                if ($fileInfo) {
                    $fileList[] = $fileInfo;
                }
            }

            return $fileList;
        } catch (\Exception $e) {
            Log::error('Failed to list files', [
                'directory' => $directory,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Disk kullanımını al
     */
    public function getDiskUsage($directory = '')
    {
        try {
            $files = Storage::disk($this->disk)->allFiles($directory);
            $totalSize = 0;
            $fileCount = 0;

            foreach ($files as $file) {
                $totalSize += Storage::disk($this->disk)->size($file);
                $fileCount++;
            }

            return [
                'total_size' => $totalSize,
                'total_size_formatted' => $this->formatBytes($totalSize),
                'file_count' => $fileCount,
                'directory' => $directory ?: 'root',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get disk usage', [
                'directory' => $directory,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Eski dosyaları temizle
     */
    public function cleanupOldFiles($directory = '', $olderThanDays = 30)
    {
        try {
            $files = Storage::disk($this->disk)->allFiles($directory);
            $cutoffTime = time() - ($olderThanDays * 24 * 60 * 60);
            $deletedCount = 0;
            $deletedSize = 0;

            foreach ($files as $file) {
                $lastModified = Storage::disk($this->disk)->lastModified($file);
                
                if ($lastModified < $cutoffTime) {
                    $size = Storage::disk($this->disk)->size($file);
                    
                    if (Storage::disk($this->disk)->delete($file)) {
                        $deletedCount++;
                        $deletedSize += $size;
                    }
                }
            }

            Log::info('Old files cleanup completed', [
                'directory' => $directory,
                'older_than_days' => $olderThanDays,
                'deleted_count' => $deletedCount,
                'deleted_size' => $this->formatBytes($deletedSize),
            ]);

            return [
                'deleted_count' => $deletedCount,
                'deleted_size' => $deletedSize,
                'deleted_size_formatted' => $this->formatBytes($deletedSize),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to cleanup old files', [
                'directory' => $directory,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}