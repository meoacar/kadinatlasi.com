@extends('admin.layouts.app')

@section('title', 'Export Geçmişi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Export Geçmişi</h1>
            <p class="text-gray-600">Daha önce yapılan dışa aktarma işlemlerini görüntüleyin</p>
        </div>
        <a href="{{ route('admin.exports.index') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Yeni Export
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tarih Aralığı</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin</label>
                <select name="admin_id" class="form-select">
                    <option value="">Tüm Adminler</option>
                    <!-- Admin listesi buraya eklenecek -->
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn btn-secondary mr-2">
                    <i class="fas fa-search mr-2"></i>
                    Filtrele
                </button>
                <a href="{{ route('admin.exports.history') }}" class="btn btn-outline">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Activities Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Export Aktiviteleri</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tarih
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Admin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Detaylar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durum
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $activity->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-gray-600 text-xs"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $activity->admin->name ?? 'Bilinmeyen' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($activity->action === 'export') bg-blue-100 text-blue-800
                                @elseif($activity->action === 'bulk_export') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($activity->action === 'export')
                                    <i class="fas fa-download mr-1"></i>
                                    Export
                                @elseif($activity->action === 'bulk_export')
                                    <i class="fas fa-layer-group mr-1"></i>
                                    Toplu Export
                                @else
                                    {{ ucfirst($activity->action) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                <p class="truncate">{{ $activity->formatted_description }}</p>
                                @if($activity->new_values && isset($activity->new_values['type']))
                                <p class="text-xs text-gray-500 mt-1">
                                    Tür: {{ ucfirst($activity->new_values['type']) }}
                                    @if(isset($activity->new_values['format']))
                                        | Format: {{ strtoupper($activity->new_values['format']) }}
                                    @endif
                                </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($activity->severity === 'low') bg-green-100 text-green-800
                                @elseif($activity->severity === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $activity->severity_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>Henüz export işlemi yapılmamış</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>
@endsection