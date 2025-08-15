@extends('admin.layouts.app')

@section('title', 'Footer Link Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Footer Link Yönetimi</h1>
            <p class="mt-1 text-sm text-gray-600">Site alt kısmında görünecek linkleri yönetin</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="openAddModal()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Yeni Link Ekle
            </button>
            <a href="{{ route('admin.settings.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Footer Links Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach(['company' => 'Şirket', 'support' => 'Destek', 'legal' => 'Yasal'] as $category => $categoryName)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">{{ $categoryName }}</h3>
                    <button type="button" onclick="openAddModal('{{ $category }}')" 
                            class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        + Ekle
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-3" id="links-{{ $category }}">
                    @forelse($footerLinks->where('category', $category) as $link)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg" data-link-id="{{ $link->id }}">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900">{{ $link->title }}</span>
                                @if(!$link->is_active)
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Pasif</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $link->url }}</p>
                            <p class="text-xs text-gray-400 mt-1">Sıra: {{ $link->sort_order }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="editLink({{ $link->id }})" 
                                    class="text-blue-600 hover:text-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="deleteLink({{ $link->id }})" 
                                    class="text-red-600 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <p class="text-sm">Bu kategoride henüz link yok</p>
                        <button type="button" onclick="openAddModal('{{ $category }}')" 
                                class="mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                            İlk linki ekle
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Footer Preview -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Footer Önizleme</h3>
        
        <div class="bg-gray-900 text-white p-6 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach(['company' => 'Şirket', 'support' => 'Destek', 'legal' => 'Yasal'] as $category => $categoryName)
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">{{ $categoryName }}</h4>
                    <ul class="space-y-2">
                        @foreach($footerLinks->where('category', $category)->where('is_active', true)->sortBy('sort_order') as $link)
                        <li>
                            <a href="{{ $link->url }}" class="text-sm text-gray-400 hover:text-white transition-colors">
                                {{ $link->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="linkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form id="linkForm" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Yeni Link Ekle</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label for="link_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Link Başlığı <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="link_title" name="title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Hakkımızda">
                        </div>

                        <!-- URL -->
                        <div>
                            <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">
                                URL <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="link_url" name="url" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://kadinatlasi.com/hakkimizda">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="link_category" class="block text-sm font-medium text-gray-700 mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="link_category" name="category" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="company">Şirket</option>
                                <option value="support">Destek</option>
                                <option value="legal">Yasal</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="link_sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sıra
                            </label>
                            <input type="number" id="link_sort_order" name="sort_order" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Küçük sayılar önce görünür</p>
                        </div>

                        <!-- Target -->
                        <div>
                            <label for="link_target" class="block text-sm font-medium text-gray-700 mb-1">
                                Hedef
                            </label>
                            <select id="link_target" name="target"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="_self">Aynı Sekme</option>
                                <option value="_blank">Yeni Sekme</option>
                            </select>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="link_is_active" name="is_active" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="link_is_active" class="ml-2 block text-sm text-gray-700">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        İptal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingLinkId = null;

function openAddModal(category = 'company') {
    editingLinkId = null;
    document.getElementById('modalTitle').textContent = 'Yeni Link Ekle';
    document.getElementById('linkForm').action = '{{ route("admin.settings.footer-links.store") }}';
    document.getElementById('linkForm').method = 'POST';
    
    // Reset form
    document.getElementById('linkForm').reset();
    document.getElementById('link_category').value = category;
    document.getElementById('link_is_active').checked = true;
    
    // Remove method field if exists
    const methodField = document.querySelector('input[name="_method"]');
    if (methodField) {
        methodField.remove();
    }
    
    document.getElementById('linkModal').classList.remove('hidden');
}

function editLink(linkId) {
    editingLinkId = linkId;
    document.getElementById('modalTitle').textContent = 'Link Düzenle';
    document.getElementById('linkForm').action = `/admin/settings/footer-links/${linkId}`;
    
    // Add method field for PUT request
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    document.getElementById('linkForm').appendChild(methodField);
    
    // Fetch link data and populate form
    fetch(`/admin/settings/footer-links/${linkId}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('link_title').value = data.title;
            document.getElementById('link_url').value = data.url;
            document.getElementById('link_category').value = data.category;
            document.getElementById('link_sort_order').value = data.sort_order;
            document.getElementById('link_target').value = data.target || '_self';
            document.getElementById('link_is_active').checked = data.is_active;
        });
    
    document.getElementById('linkModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('linkModal').classList.add('hidden');
    editingLinkId = null;
}

function deleteLink(linkId) {
    if (confirm('Bu linki silmek istediğinizden emin misiniz?')) {
        fetch(`/admin/settings/footer-links/${linkId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Link silinirken bir hata oluştu.');
            }
        })
        .catch(error => {
            alert('Bir hata oluştu: ' + error.message);
        });
    }
}

// Form submission
document.getElementById('linkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    const method = editingLinkId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('İşlem sırasında bir hata oluştu.');
        }
    })
    .catch(error => {
        alert('Bir hata oluştu: ' + error.message);
    });
});

// Close modal on outside click
document.getElementById('linkModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush