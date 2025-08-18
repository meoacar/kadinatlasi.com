@extends('admin.layouts.app')

@section('title', 'Yeni Kategori Oluştur')

@section('content')
<div class="space-y-6" x-data="categoryForm()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Yeni Kategori Oluştur</h1>
            <p class="mt-1 text-sm text-gray-600">Yeni bir ürün kategorisi ekleyin</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Temel Bilgiler</h3>
                    
                    <div class="space-y-4">
                        <!-- Category Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Kategori Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   x-model="form.name" @input="generateSlug"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                                   placeholder="Kategori adını girin">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                URL Slug
                            </label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                                   x-model="form.slug"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-300 @enderror"
                                   placeholder="url-slug (otomatik oluşturulur)">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Üst Kategori
                            </label>
                            <select id="parent_id" name="parent_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('parent_id') border-red-300 @enderror">
                                <option value="">Ana Kategori</option>
                                @foreach($parentCategories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Boş bırakırsanız ana kategori olarak oluşturulur</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Açıklama
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                                      placeholder="Kategori açıklaması (opsiyonel)">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Category Image -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kategori Resmi</h3>
                    
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                             x-data="imageUpload()" @drop.prevent="handleDrop" @dragover.prevent @dragenter.prevent>
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <label for="image" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Resmi buraya sürükleyin veya seçmek için tıklayın
                                    </span>
                                    <input id="image" name="image" type="file" accept="image/*" class="sr-only" @change="handleFileSelect">
                                </label>
                                <p class="mt-2 text-xs text-gray-500">
                                    PNG, JPG, GIF dosyaları desteklenir. Maksimum 2MB.
                                </p>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div x-show="selectedImage" class="mt-4">
                            <div class="relative inline-block">
                                <img x-show="selectedImage" :src="selectedImage" alt="Preview" class="w-32 h-32 object-cover rounded-lg">
                                <button type="button" @click="removeImage" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Ayarları</h3>
                    
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Başlık
                            </label>
                            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="SEO başlığı">
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Meta Açıklama
                            </label>
                            <textarea id="meta_description" name="meta_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="SEO açıklaması">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Yayın Ayarları</h3>
                    
                    <div class="space-y-4">
                        <!-- Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">
                                Kategoriyi aktif yap
                            </label>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sıra Numarası
                            </label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Küçük sayılar önce görünür</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Kategoriyi Kaydet
                        </button>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                            İptal
                        </a>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">💡 İpucu</h4>
                    <div class="text-sm text-blue-700 space-y-2">
                        <p>• Kategori adı SEO için önemlidir</p>
                        <p>• Alt kategoriler sadece ana kategorilerin altında oluşturulabilir</p>
                        <p>• Kategori resmi opsiyoneldir ancak görsel çekicilik sağlar</p>
                        <p>• Sıra numarası ile kategorilerin görünüm sırasını belirleyebilirsiniz</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function categoryForm() {
    return {
        form: {
            name: '',
            slug: ''
        },
        
        generateSlug() {
            if (this.form.name) {
                this.form.slug = this.form.name
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
            }
        }
    }
}

function imageUpload() {
    return {
        selectedImage: null,
        
        handleFileSelect(event) {
            this.processFile(event.target.files[0]);
        },
        
        handleDrop(event) {
            this.processFile(event.dataTransfer.files[0]);
        },
        
        processFile(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.selectedImage = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        removeImage() {
            this.selectedImage = null;
            document.getElementById('image').value = '';
        }
    }
}
</script>
@endpush