@extends('admin.layouts.app')

@section('title', 'Medya Yönetimi')

@section('content')
<div class="space-y-6" x-data="mediaManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Medya Yönetimi</h1>
            <p class="mt-1 text-sm text-gray-600">Dosyalarınızı yönetin ve organize edin</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <button @click="openUploadModal" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8.5"></path>
                </svg>
                Dosya Yükle
            </button>
            <button @click="openCreateDirectoryModal" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Klasör
            </button>
        </div>
    </div>

    <!-- Stats and Filters -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Disk Usage -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Toplam Boyut</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $diskUsage['total_size_formatted'] ?? '0 B' }}</p>
                </div>
            </div>
        </div>

        <!-- File Count -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dosya Sayısı</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $diskUsage['file_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="GET" class="flex items-center space-x-4">
                    <div class="flex-1">
                        <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Tüm Dosyalar</option>
                            <option value="images" {{ $type === 'images' ? 'selected' : '' }}>Resimler</option>
                            <option value="documents" {{ $type === 'documents' ? 'selected' : '' }}>Dökümanlar</option>
                            <option value="archives" {{ $type === 'archives' ? 'selected' : '' }}>Arşivler</option>
                        </select>
                    </div>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Filtrele
                    </button>
                </form>
            </div>
        </div>
    </div> 
   <!-- Breadcrumb -->
    @if($directory)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.media.index') }}" class="text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0"></path>
                        </svg>
                        Ana Dizin
                    </a>
                </li>
                @foreach(explode('/', $directory) as $folder)
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500">{{ $folder }}</span>
                    </div>
                </li>
                @endforeach
            </ol>
        </nav>
    </div>
    @endif

    <!-- Bulk Actions -->
    <div x-show="selectedFiles.length > 0" 
         x-transition
         class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <span class="text-sm text-blue-700">
                <span x-text="selectedFiles.length"></span> dosya seçildi
            </span>
            <div class="flex space-x-2">
                <button @click="downloadSelected" 
                        class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    İndir
                </button>
                <button @click="deleteSelected" 
                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                    Sil
                </button>
            </div>
        </div>
    </div>

    <!-- Files Grid -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Directories -->
            @if(count($directories) > 0)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Klasörler</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($directories as $dir)
                    <div class="group cursor-pointer" onclick="navigateToDirectory('{{ $dir }}')">
                        <div class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-sm transition-all">
                            <svg class="w-12 h-12 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0"></path>
                            </svg>
                            <span class="text-sm text-gray-700 text-center truncate w-full">{{ basename($dir) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Files -->
            @if(count($files) > 0)
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">Dosyalar</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($files as $file)
                    <div class="group relative" x-data="{ selected: false }">
                        <div class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-sm transition-all cursor-pointer"
                             @click="toggleFileSelection('{{ $file['path'] }}')">
                            
                            <!-- Checkbox -->
                            <div class="absolute top-2 left-2">
                                <input type="checkbox" :value="'{{ $file['path'] }}'" 
                                       x-model="selectedFiles"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </div>

                            <!-- File Preview -->
                            @if($file['is_image'])
                                <img src="{{ $file['url'] }}" alt="{{ $file['basename'] }}" 
                                     class="w-16 h-16 object-cover rounded mb-2">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- File Info -->
                            <span class="text-xs text-gray-700 text-center truncate w-full mb-1">{{ $file['basename'] }}</span>
                            <span class="text-xs text-gray-500">{{ $file['size_formatted'] }}</span>

                            <!-- Actions -->
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="flex space-x-1">
                                    <button onclick="viewFile('{{ $file['path'] }}', '{{ $file['url'] }}', {{ $file['is_image'] ? 'true' : 'false' }})"
                                            class="p-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteFile('{{ $file['path'] }}')"
                                            class="p-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Dosya bulunamadı</h3>
                <p class="mt-1 text-sm text-gray-500">Bu dizinde henüz dosya yok.</p>
                <div class="mt-6">
                    <button @click="openUploadModal" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8.5"></path>
                        </svg>
                        İlk Dosyayı Yükle
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection@
push('scripts')
<script>
function mediaManager() {
    return {
        selectedFiles: [],
        
        toggleFileSelection(path) {
            const index = this.selectedFiles.indexOf(path);
            if (index > -1) {
                this.selectedFiles.splice(index, 1);
            } else {
                this.selectedFiles.push(path);
            }
        },
        
        openUploadModal() {
            // Upload modal açma kodu
            document.getElementById('uploadModal').classList.remove('hidden');
        },
        
        openCreateDirectoryModal() {
            // Directory modal açma kodu
            document.getElementById('createDirectoryModal').classList.remove('hidden');
        },
        
        async deleteSelected() {
            if (this.selectedFiles.length === 0) {
                alert('Lütfen silinecek dosyaları seçin.');
                return;
            }
            
            if (!confirm(`${this.selectedFiles.length} dosyayı silmek istediğinizden emin misiniz?`)) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("admin.media.delete-multiple") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        paths: this.selectedFiles
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    alert(result.message || 'Dosyalar silinemedi.');
                }
            } catch (error) {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        },
        
        downloadSelected() {
            // Download işlemi
            this.selectedFiles.forEach(path => {
                const link = document.createElement('a');
                link.href = `/storage/${path}`;
                link.download = path.split('/').pop();
                link.click();
            });
        }
    }
}

function navigateToDirectory(directory) {
    window.location.href = `{{ route('admin.media.index') }}?directory=${encodeURIComponent(directory)}`;
}

function viewFile(path, url, isImage) {
    if (isImage) {
        // Resim önizleme modal'ı aç
        document.getElementById('imagePreviewModal').classList.remove('hidden');
        document.getElementById('previewImage').src = url;
    } else {
        // Dosyayı yeni sekmede aç
        window.open(url, '_blank');
    }
}

async function deleteFile(path) {
    if (!confirm('Bu dosyayı silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("admin.media.delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ path: path })
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Dosya silinemedi.');
        }
    } catch (error) {
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    }
}
</script>
@endpush