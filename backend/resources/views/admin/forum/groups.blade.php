@extends('admin.layouts.app')

@section('title', 'Forum Grupları')

@section('content')
<div class="space-y-6" x-data="groupManagement()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Forum Grupları</h1>
            <p class="mt-1 text-sm text-gray-600">Forum gruplarını görüntüleyin ve yönetin</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <button @click="openCreateModal" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Grup
            </button>
            <a href="{{ route('admin.forum.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Forum Ana Sayfa
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Toplam Grup</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aktif Grup</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pasif Grup</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['inactive']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Konulu Grup</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['with_topics']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arama</label>
                    <input type="text" name="search" value="{{ $search }}" 
                           placeholder="Grup adı ara..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sıralama</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>İsim</option>
                        <option value="topics_count" {{ $sort === 'topics_count' ? 'selected' : '' }}>Konu Sayısı</option>
                        <option value="posts_count" {{ $sort === 'posts_count' ? 'selected' : '' }}>Gönderi Sayısı</option>
                        <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>Oluşturma Tarihi</option>
                        <option value="is_active" {{ $sort === 'is_active' ? 'selected' : '' }}>Durum</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Filtrele
                    </button>
                    <a href="{{ route('admin.forum.groups') }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Groups Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($groups as $group)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" 
                                 style="background-color: {{ $group->color ?? '#e5e7eb' }}20;">
                                @if($group->icon)
                                    <span class="text-lg">{{ $group->icon }}</span>
                                @else
                                    <svg class="w-5 h-5" style="color: {{ $group->color ?? '#6b7280' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 truncate">{{ $group->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $group->slug }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $group->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                <div class="py-1">
                                    <button @click="editGroup({{ $group->id }}, '{{ $group->name }}', '{{ $group->slug }}', '{{ $group->description }}', '{{ $group->color }}', '{{ $group->icon }}', {{ $group->is_active ? 'true' : 'false' }}, {{ $group->sort_order ?? 0 }})" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Düzenle
                                    </button>
                                    <form method="POST" action="{{ route('admin.forum.destroy-group', $group) }}" 
                                          onsubmit="return confirm('Bu grubu silmek istediğinizden emin misiniz?')" class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                            Sil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($group->description)
                    <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $group->description }}</p>
                @endif

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            {{ $group->topics_count }} konu
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            {{ $group->posts_count }} gönderi
                        </div>
                    </div>
                    <div class="text-xs text-gray-400">
                        Sıra: {{ $group->sort_order ?? 0 }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Forum grubu bulunamadı</h3>
                    <p class="mt-1 text-sm text-gray-500">İlk forum grubunuzu oluşturmaya başlayın.</p>
                    <div class="mt-6">
                        <button @click="openCreateModal" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Yeni Grup
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($groups->hasPages())
        <div class="flex justify-center">
            {{ $groups->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Group Modal -->
<div x-show="showModal" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="showModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="modalTitle"></h3>
        
        <form :action="formAction" method="POST">
            @csrf
            <div x-show="editMode" style="display: none;">
                @method('PUT')
            </div>
            
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Grup Adı <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" x-model="form.name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                        URL Slug
                    </label>
                    <input type="text" id="slug" name="slug" x-model="form.slug"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Açıklama
                    </label>
                    <textarea id="description" name="description" x-model="form.description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
                            Renk
                        </label>
                        <input type="color" id="color" name="color" x-model="form.color"
                               class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">
                            İkon (Emoji)
                        </label>
                        <input type="text" id="icon" name="icon" x-model="form.icon" maxlength="2"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="🏷️">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                            Sıra
                        </label>
                        <input type="number" id="sort_order" name="sort_order" x-model="form.sort_order" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Status -->
                    <div class="flex items-center pt-6">
                        <input type="checkbox" id="is_active" name="is_active" value="1" x-model="form.is_active"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <span x-text="editMode ? 'Güncelle' : 'Oluştur'"></span>
                    </button>
                    <button type="button" @click="closeModal" 
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        İptal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function groupManagement() {
    return {
        showModal: false,
        editMode: false,
        modalTitle: '',
        formAction: '',
        form: {
            name: '',
            slug: '',
            description: '',
            color: '#3b82f6',
            icon: '',
            is_active: true,
            sort_order: 0
        },
        
        openCreateModal() {
            this.editMode = false;
            this.modalTitle = 'Yeni Forum Grubu';
            this.formAction = '{{ route("admin.forum.store-group") }}';
            this.resetForm();
            this.showModal = true;
        },
        
        editGroup(id, name, slug, description, color, icon, isActive, sortOrder) {
            this.editMode = true;
            this.modalTitle = 'Forum Grubu Düzenle';
            this.formAction = `/admin/forum/groups/${id}`;
            this.form = {
                name: name,
                slug: slug,
                description: description || '',
                color: color || '#3b82f6',
                icon: icon || '',
                is_active: isActive,
                sort_order: sortOrder || 0
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.resetForm();
        },
        
        resetForm() {
            this.form = {
                name: '',
                slug: '',
                description: '',
                color: '#3b82f6',
                icon: '',
                is_active: true,
                sort_order: 0
            };
        }
    }
}
</script>
@endpush