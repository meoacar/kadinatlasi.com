@extends('admin.layouts.app')

@section('title', 'Forum Konuları')

@section('content')
<div class="space-y-6" x-data="topicManagement()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Forum Konuları</h1>
            <p class="mt-1 text-sm text-gray-600">Forum konularını görüntüleyin ve yönetin</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.forum.export') }}?type=topics" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Dışa Aktar
            </a>
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
    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Toplam</p>
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
                    <p class="text-sm font-medium text-gray-600">Aktif</p>
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
                    <p class="text-sm font-medium text-gray-600">Pasif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['inactive']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sabitlenmiş</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pinned']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kilitli</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['locked']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Bugün</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['today']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arama</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" 
                           placeholder="Konu başlığı ara..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tümü</option>
                        <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $filters['status'] === 'inactive' ? 'selected' : '' }}>Pasif</option>
                        <option value="pinned" {{ $filters['status'] === 'pinned' ? 'selected' : '' }}>Sabitlenmiş</option>
                        <option value="locked" {{ $filters['status'] === 'locked' ? 'selected' : '' }}>Kilitli</option>
                    </select>
                </div>

                <!-- Group -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grup</label>
                    <select name="group_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tüm Gruplar</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $filters['group_id'] == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Filtrele
                    </button>
                    <a href="{{ route('admin.forum.topics') }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div x-show="selectedTopics.length > 0" 
         x-transition
         class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <span class="text-sm text-blue-700">
                <span x-text="selectedTopics.length"></span> konu seçildi
            </span>
            <div class="flex space-x-2">
                <button @click="bulkAction('activate')" 
                        class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    Aktif Yap
                </button>
                <button @click="bulkAction('deactivate')" 
                        class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">
                    Pasif Yap
                </button>
                <button @click="bulkAction('pin')" 
                        class="px-3 py-1 bg-purple-600 text-white text-sm rounded hover:bg-purple-700">
                    Sabitle
                </button>
                <button @click="bulkAction('lock')" 
                        class="px-3 py-1 bg-orange-600 text-white text-sm rounded hover:bg-orange-700">
                    Kilitle
                </button>
                <button @click="bulkAction('delete')" 
                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                    Sil
                </button>
            </div>
        </div>
    </div>

    <!-- Topics Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" @change="toggleAll" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Konu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grup
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kullanıcı
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Gönderi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tarih
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topics as $topic)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <input type="checkbox" :value="{{ $topic->id }}" 
                                       x-model="selectedTopics"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                            {{ $topic->title }}
                                            <div class="flex items-center space-x-2 mt-1">
                                                @if($topic->is_pinned)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        📌 Sabit
                                                    </span>
                                                @endif
                                                @if($topic->is_locked)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        🔒 Kilitli
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($topic->description)
                                            <div class="text-sm text-gray-500 mt-1 line-clamp-1">
                                                {{ Str::limit($topic->description, 100) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                      style="background-color: {{ $topic->group->color ?? '#e5e7eb' }}20; color: {{ $topic->group->color ?? '#6b7280' }}">
                                    @if($topic->group->icon)
                                        {{ $topic->group->icon }}
                                    @endif
                                    {{ $topic->group->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($topic->user->avatar)
                                            <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . $topic->user->avatar) }}" alt="{{ $topic->user->name }}">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-600">{{ substr($topic->user->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $topic->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $topic->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $topic->posts_count ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $topic->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $topic->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $topic->created_at->format('d.m.Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $topic->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <form method="POST" action="{{ route('admin.forum.toggle-topic-status', $topic) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-{{ $topic->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $topic->is_active ? 'yellow' : 'green' }}-900">
                                            {{ $topic->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.forum.toggle-topic-pin', $topic) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-purple-600 hover:text-purple-900">
                                            {{ $topic->is_pinned ? 'Sabitlemeyi Kaldır' : 'Sabitle' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.forum.toggle-topic-lock', $topic) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-orange-600 hover:text-orange-900">
                                            {{ $topic->is_locked ? 'Kilidi Aç' : 'Kilitle' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Konu bulunamadı</h3>
                                <p class="mt-1 text-sm text-gray-500">Arama kriterlerinize uygun konu bulunmuyor.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($topics->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $topics->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function topicManagement() {
    return {
        selectedTopics: [],
        
        toggleAll(event) {
            if (event.target.checked) {
                this.selectedTopics = @json($topics->pluck('id')->toArray());
            } else {
                this.selectedTopics = [];
            }
        },
        
        async bulkAction(action) {
            if (this.selectedTopics.length === 0) {
                alert('Lütfen en az bir konu seçin.');
                return;
            }
            
            const actionText = {
                'activate': 'aktif yapmak',
                'deactivate': 'pasif yapmak',
                'pin': 'sabitlemek',
                'unpin': 'sabitlemeden kaldırmak',
                'lock': 'kilitlemek',
                'unlock': 'kilidini açmak',
                'delete': 'silmek'
            };
            
            if (!confirm(`Seçili ${this.selectedTopics.length} konuyu ${actionText[action]} istediğinizden emin misiniz?`)) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("admin.forum.bulk-topic-action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        action: action,
                        topics: this.selectedTopics
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    alert(result.message || 'İşlem başarısız oldu.');
                }
            } catch (error) {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        }
    }
}
</script>
@endpush