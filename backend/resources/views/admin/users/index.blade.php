@extends('admin.layouts.app')

@section('title', 'Kullanıcı Yönetimi')

@section('content')
<div class="space-y-6" x-data="userManagement()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kullanıcı Yönetimi</h1>
            <p class="mt-1 text-sm text-gray-600">Tüm kullanıcıları görüntüleyin ve yönetin</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Kullanıcı
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Toplam Kullanıcı</p>
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
                    <p class="text-sm font-medium text-gray-600">Aktif Kullanıcı</p>
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
                    <p class="text-sm font-medium text-gray-600">Pasif Kullanıcı</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['inactive']) }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Bugün Kayıt</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['today']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <x-advanced-filters 
        :action="route('admin.users.index')"
        :show-date-filter="true"
        :show-status-filter="true"
        :show-category-filter="false"
        :show-author-filter="false"
        :show-price-filter="false"
        :status-options="$statusOptions"
    />

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Kullanıcı adı, e-posta adresi ara..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Ara
            </button>
                        <option value="vip" {{ $filters['membership'] === 'vip' ? 'selected' : '' }}>VIP</option>
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
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div x-show="selectedUsers.length > 0" 
         x-transition
         class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <span class="text-sm text-blue-700">
                <span x-text="selectedUsers.length"></span> kullanıcı seçildi
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
                <button @click="bulkAction('delete')" 
                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                    Sil
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
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
                            Kullanıcı
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Üyelik
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kayıt Tarihi
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <input type="checkbox" :value="{{ $user->id }}" 
                                       x-model="selectedUsers"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ asset('storage/' . $user->avatar) }}" 
                                                 alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium text-sm">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ match($user->membership_type ?? 'normal') {
                                        'vip' => 'bg-purple-100 text-purple-800',
                                        'premium' => 'bg-yellow-100 text-yellow-800',
                                        'basic' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } }}">
                                    {{ match($user->membership_type ?? 'normal') {
                                        'vip' => 'VIP',
                                        'premium' => 'Premium',
                                        'basic' => 'Basic',
                                        default => 'Normal'
                                    } }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $user->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        Görüntüle
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Düzenle
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" 
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $user->is_active ? 'yellow' : 'green' }}-900">
                                            {{ $user->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Kullanıcı bulunamadı</h3>
                                <p class="mt-1 text-sm text-gray-500">Filtreleri değiştirmeyi deneyin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function userManagement() {
    return {
        selectedUsers: [],
        
        toggleAll(event) {
            if (event.target.checked) {
                this.selectedUsers = @json($users->pluck('id')->toArray());
            } else {
                this.selectedUsers = [];
            }
        },
        
        async bulkAction(action) {
            if (this.selectedUsers.length === 0) {
                alert('Lütfen en az bir kullanıcı seçin.');
                return;
            }
            
            const actionText = {
                'activate': 'aktif yapmak',
                'deactivate': 'pasif yapmak',
                'delete': 'silmek'
            };
            
            if (!confirm(`Seçili ${this.selectedUsers.length} kullanıcıyı ${actionText[action]} istediğinizden emin misiniz?`)) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("admin.users.bulk-action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        action: action,
                        users: this.selectedUsers
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