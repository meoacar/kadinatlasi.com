@extends('admin.layouts.app')

@section('title', 'Kullanıcı Raporları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kullanıcı Raporları</h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $filters['date_from']->format('d.m.Y') }} - {{ $filters['date_to']->format('d.m.Y') }} dönemi
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.users', array_merge(request()->query(), ['format' => 'csv'])) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                CSV İndir
            </a>
            <a href="{{ route('admin.reports.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.reports.users') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Tarihi</label>
                <input type="date" name="date_from" value="{{ $filters['date_from']->format('Y-m-d') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş Tarihi</label>
                <input type="date" name="date_to" value="{{ $filters['date_to']->format('Y-m-d') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Filtrele
            </button>
        </form>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Toplam Kullanıcı</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($reports['overview']['total_users']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Yeni Kullanıcı</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($reports['overview']['new_users']) }}</p>
                    <div class="flex items-center mt-1">
                        @if($reports['overview']['growth_rate'] > 0)
                            <svg class="w-3 h-3 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-green-600">+{{ number_format($reports['overview']['growth_rate'], 1) }}%</span>
                        @elseif($reports['overview']['growth_rate'] < 0)
                            <svg class="w-3 h-3 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-red-600">{{ number_format($reports['overview']['growth_rate'], 1) }}%</span>
                        @else
                            <span class="text-xs text-gray-500">Değişim yok</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aktif Kullanıcı</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($reports['overview']['active_users']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($reports['overview']['active_percentage'], 1) }}% oranında</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Premium Üye</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($reports['overview']['premium_users']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($reports['overview']['premium_percentage'], 1) }}% oranında</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Registrations Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Günlük Kayıtlar</h3>
            <div class="h-64">
                <canvas id="registrationsChart"></canvas>
            </div>
        </div>

        <!-- Demographics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Demografik Dağılım</h3>
            <div class="space-y-4">
                <!-- Membership Types -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Üyelik Türleri</h4>
                    @foreach($reports['demographics']['membership_types'] as $type => $count)
                        <div class="flex items-center justify-between py-1">
                            <span class="text-sm text-gray-600">{{ ucfirst($type ?? 'Belirtilmemiş') }}</span>
                            <span class="text-sm font-medium">{{ number_format($count) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Age Groups -->
                @if(!empty($reports['demographics']['age_groups']))
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Yaş Grupları</h4>
                        @foreach($reports['demographics']['age_groups'] as $group => $count)
                            <div class="flex items-center justify-between py-1">
                                <span class="text-sm text-gray-600">{{ $group }}</span>
                                <span class="text-sm font-medium">{{ number_format($count) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity & Engagement -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kullanıcı Aktivitesi</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Blog Yazıları</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ number_format($reports['activity']['blog_posts']) }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Forum Konuları</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ number_format($reports['activity']['forum_topics']) }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Forum Mesajları</span>
                    </div>
                    <span class="text-lg font-bold text-purple-600">{{ number_format($reports['activity']['forum_posts']) }}</span>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">Toplam Aktivite</span>
                        <span class="text-lg font-bold text-gray-900">{{ number_format($reports['activity']['total_activity']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">En Aktif Kullanıcılar</h3>
            @if($reports['top_users']->isEmpty())
                <p class="text-sm text-gray-500 text-center py-8">Bu dönemde aktif kullanıcı bulunmuyor.</p>
            @else
                <div class="space-y-3">
                    @foreach($reports['top_users'] as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-blue-600">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $user->blog_posts_count + $user->forum_topics_count }} aktivite
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $user->blog_posts_count }} yazı, {{ $user->forum_topics_count }} konu
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Registrations Chart
    const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsData = @json($reports['registrations']);
    
    new Chart(registrationsCtx, {
        type: 'line',
        data: {
            labels: registrationsData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('tr-TR', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Günlük Kayıtlar',
                data: registrationsData.map(item => item.count),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection