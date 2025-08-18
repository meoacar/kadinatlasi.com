@props([
    'name' => 'file',
    'multiple' => false,
    'accept' => '*',
    'maxSize' => '10MB',
    'directory' => 'uploads',
    'type' => 'all',
    'preview' => true,
    'thumbnail' => false
])

<div x-data="fileUploadComponent()" class="space-y-4">
    <!-- Upload Area -->
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
         @drop.prevent="handleDrop" @dragover.prevent @dragenter.prevent
         :class="{ 'border-blue-500 bg-blue-50': isDragging }">
        
        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        
        <div class="mt-4">
            <label for="{{ $name }}" class="cursor-pointer">
                <span class="mt-2 block text-sm font-medium text-gray-900">
                    Dosyaları buraya sürükleyin veya seçmek için tıklayın
                </span>
                <input id="{{ $name }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
                       type="file" {{ $multiple ? 'multiple' : '' }} 
                       accept="{{ $accept }}" class="sr-only" 
                       @change="handleFileSelect">
            </label>
            <p class="mt-2 text-xs text-gray-500">
                Maksimum dosya boyutu: {{ $maxSize }}
                @if($type === 'image')
                    • Sadece resim dosyaları
                @elseif($type === 'document')
                    • PDF, DOC, XLS, PPT dosyaları
                @endif
            </p>
        </div>
    </div>

    <!-- Upload Progress -->
    <div x-show="uploading" class="space-y-2">
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Yükleniyor...</span>
            <span class="text-gray-600" x-text="uploadProgress + '%'"></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                 :style="'width: ' + uploadProgress + '%'"></div>
        </div>
    </div>

    <!-- File Preview -->
    @if($preview)
    <div x-show="selectedFiles.length > 0" class="space-y-4">
        <h4 class="text-sm font-medium text-gray-900">Seçilen Dosyalar</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <template x-for="(file, index) in selectedFiles" :key="index">
                <div class="relative group">
                    <!-- Image Preview -->
                    <div x-show="file.type.startsWith('image/')" class="aspect-square">
                        <img :src="file.preview" :alt="file.name" 
                             class="w-full h-full object-cover rounded-lg">
                    </div>
                    
                    <!-- File Icon -->
                    <div x-show="!file.type.startsWith('image/')" 
                         class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    
                    <!-- Remove Button -->
                    <button type="button" @click="removeFile(index)" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                        ×
                    </button>
                    
                    <!-- File Info -->
                    <div class="mt-2">
                        <p class="text-xs text-gray-700 truncate" x-text="file.name"></p>
                        <p class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
    @endif

    <!-- Upload Button -->
    <div x-show="selectedFiles.length > 0 && !uploading" class="flex justify-end">
        <button type="button" @click="uploadFiles" 
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <span x-text="selectedFiles.length === 1 ? 'Dosyayı Yükle' : selectedFiles.length + ' Dosyayı Yükle'"></span>
        </button>
    </div>

    <!-- Success Message -->
    <div x-show="uploadSuccess" x-transition 
         class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm text-green-700" x-text="successMessage"></p>
        </div>
    </div>

    <!-- Error Message -->
    <div x-show="uploadError" x-transition 
         class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <p class="text-sm text-red-700" x-text="errorMessage"></p>
        </div>
    </div>
</div>

<script>
function fileUploadComponent() {
    return {
        selectedFiles: [],
        isDragging: false,
        uploading: false,
        uploadProgress: 0,
        uploadSuccess: false,
        uploadError: false,
        successMessage: '',
        errorMessage: '',
        
        handleFileSelect(event) {
            this.processFiles(event.target.files);
        },
        
        handleDrop(event) {
            this.isDragging = false;
            this.processFiles(event.dataTransfer.files);
        },
        
        processFiles(files) {
            this.selectedFiles = [];
            this.uploadSuccess = false;
            this.uploadError = false;
            
            Array.from(files).forEach(file => {
                // File validation
                if (!this.validateFile(file)) return;
                
                // Create preview for images
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        file.preview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
                
                this.selectedFiles.push(file);
            });
        },
        
        validateFile(file) {
            const maxSize = this.parseSize('{{ $maxSize }}');
            
            if (file.size > maxSize) {
                this.showError(`Dosya boyutu çok büyük: ${file.name} (Maksimum: {{ $maxSize }})`);
                return false;
            }
            
            @if($type === 'image')
            if (!file.type.startsWith('image/')) {
                this.showError(`Sadece resim dosyaları kabul edilir: ${file.name}`);
                return false;
            }
            @endif
            
            return true;
        },
        
        removeFile(index) {
            this.selectedFiles.splice(index, 1);
        },
        
        async uploadFiles() {
            if (this.selectedFiles.length === 0) return;
            
            this.uploading = true;
            this.uploadProgress = 0;
            this.uploadSuccess = false;
            this.uploadError = false;
            
            const formData = new FormData();
            
            this.selectedFiles.forEach(file => {
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
                
                this.uploadProgress = 100;
                const result = await response.json();
                
                if (result.success) {
                    this.showSuccess(`${result.uploaded} dosya başarıyla yüklendi.`);
                    this.selectedFiles = [];
                    
                    // Trigger custom event
                    this.$dispatch('files-uploaded', result);
                } else {
                    this.showError(result.message || 'Dosya yükleme başarısız oldu.');
                }
            } catch (error) {
                this.showError('Dosya yükleme sırasında hata oluştu: ' + error.message);
            } finally {
                this.uploading = false;
            }
        },
        
        showSuccess(message) {
            this.successMessage = message;
            this.uploadSuccess = true;
            setTimeout(() => {
                this.uploadSuccess = false;
            }, 5000);
        },
        
        showError(message) {
            this.errorMessage = message;
            this.uploadError = true;
            setTimeout(() => {
                this.uploadError = false;
            }, 5000);
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        parseSize(sizeStr) {
            const units = { 'B': 1, 'KB': 1024, 'MB': 1024*1024, 'GB': 1024*1024*1024 };
            const match = sizeStr.match(/^(\d+(?:\.\d+)?)\s*([A-Z]+)$/i);
            if (!match) return 0;
            return parseFloat(match[1]) * (units[match[2].toUpperCase()] || 1);
        }
    }
}
</script>