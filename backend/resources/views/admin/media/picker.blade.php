<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medya Seçici</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-4" x-data="mediaPicker()">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-900">
                    @if($type === 'images')
                        Resim Seç
                    @elseif($type === 'documents')
                        Döküman Seç
                    @else
                        Medya Seç
                    @endif
                </h1>
                <div class="flex space-x-2">
                    <button @click="selectFiles" 
                            :disabled="selectedFiles.length === 0"
                            :class="selectedFiles.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                            class="px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors">
                        Seç (<span x-text="selectedFiles.length"></span>)
                    </button>
                    <button onclick="window.close()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        İptal
                    </button>
                </div>
            </div>
        </div>

        <!-- Upload Area -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors"
                 x-data="fileUpload()" @drop.prevent="handleDrop" @dragover.prevent @dragenter.prevent>
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="mt-4">
                    <label for="file-upload" class="cursor-pointer">
                        <span class="mt-2 block text-sm font-medium text-gray-900">
                            Dosyaları buraya sürükleyin veya seçmek için tıklayın
                        </span>
                        <input id="file-upload" name="file-upload" type="file" multiple class="sr-only" 
                               @change="handleFileSelect" accept="{{ $type === 'images' ? 'image/*' : '*' }}">
                    </label>
                    <p class="mt-2 text-xs text-gray-500">
                        @if($type === 'images')
                            PNG, JPG, GIF, WebP dosyaları desteklenir
                        @else
                            Tüm dosya türleri desteklenir
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Files Grid -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @if(count($files) > 0)
                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($files as $file)
                    <div class="group relative cursor-pointer" 
                         @click="toggleSelection('{{ $file['path'] }}', '{{ $file['url'] }}', '{{ $file['basename'] }}')"
                         :class="selectedFiles.some(f => f.path === '{{ $file['path'] }}') ? 'ring-2 ring-blue-500' : ''">
                        
                        <div class="aspect-square border border-gray-200 rounded-lg overflow-hidden hover:border-blue-300 transition-colors">
                            @if($file['is_image'])
                                <img src="{{ $file['url'] }}" alt="{{ $file['basename'] }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Selection Indicator -->
                        <div x-show="selectedFiles.some(f => f.path === '{{ $file['path'] }}')" 
                             class="absolute top-2 right-2 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        
                        <!-- File Name -->
                        <p class="mt-2 text-xs text-gray-700 text-center truncate">{{ $file['basename'] }}</p>
                        <p class="text-xs text-gray-500 text-center">{{ $file['size_formatted'] }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Dosya bulunamadı</h3>
                    <p class="mt-1 text-sm text-gray-500">Henüz yüklenmiş dosya yok.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
    function mediaPicker() {
        return {
            selectedFiles: [],
            multiple: {{ $multiple ? 'true' : 'false' }},
            
            toggleSelection(path, url, name) {
                const file = { path, url, name };
                const index = this.selectedFiles.findIndex(f => f.path === path);
                
                if (index > -1) {
                    this.selectedFiles.splice(index, 1);
                } else {
                    if (this.multiple) {
                        this.selectedFiles.push(file);
                    } else {
                        this.selectedFiles = [file];
                    }
                }
            },
            
            selectFiles() {
                if (this.selectedFiles.length === 0) return;
                
                // Parent window'a seçilen dosyaları gönder
                if (window.opener && window.opener.handleMediaSelection) {
                    window.opener.handleMediaSelection(this.selectedFiles);
                }
                
                window.close();
            }
        }
    }

    function fileUpload() {
        return {
            handleFileSelect(event) {
                this.uploadFiles(event.target.files);
            },
            
            handleDrop(event) {
                this.uploadFiles(event.dataTransfer.files);
            },
            
            async uploadFiles(files) {
                const formData = new FormData();
                
                Array.from(files).forEach(file => {
                    formData.append('files[]', file);
                });
                
                formData.append('directory', '{{ $directory }}');
                formData.append('type', '{{ $type }}');
                
                try {
                    const response = await fetch('/admin/media/upload', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Dosya yükleme başarısız: ' + (result.message || 'Bilinmeyen hata'));
                    }
                } catch (error) {
                    alert('Dosya yükleme sırasında hata oluştu: ' + error.message);
                }
            }
        }
    }
    </script>
</body>
</html>