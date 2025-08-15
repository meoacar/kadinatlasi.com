@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6" x-data="dashboardApp()" x-init="init()">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">
                    Hoş geldiniz, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-blue-100">
                    Bugün {{ now()->format('d F Y, l') }} - KadınAtlası Admin Paneli
                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Toplam Kullanıcı</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <span class="font-medium">+{{ $stats['new_users_today'] }}</span> bugün
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Blog Posts -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Blog Yazıları</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_posts']) }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <span class="font-medium">{{ $stats['published_posts'] }}</span> yayında
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ürünler</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</p>
                    <p class="text-sm text-purple-600 mt-1">
                        <span class="font-medium">{{ $stats['active_products'] }}</span> aktif
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Forum Activity -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Forum Konuları</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_forum_topics']) }}</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <span class="font-medium">{{ $stats['forum_posts'] }}</span> gönderi
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Registrations Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Kullanıcı Kayıtları (Son 7 Gün)</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Yeni Kayıtlar</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userRegistrationsChart"></canvas>
            </div>
        </div>

        <!-- Membership Distribution -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Üyelik Dağılımı</h3>
            </div>
            <div class="h-64">
                <canvas id="membershipChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Son Kullanıcılar</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Tümünü Gör
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                @if($user['avatar'])
                                    <img src="{{ asset('storage/' . $user['avatar']) }}" alt="{{ $user['name'] }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <span class="text-blue-600 font-medium text-sm">
                                        {{ substr($user['name'], 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user['name'] }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user['email'] }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user['is_active'] ? 'Aktif' : 'Pasif' }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $user['created_at_human'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Henüz kullanıcı yok</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Blog Posts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Son Blog Yazıları</h3>
                    <a href="{{ route('admin.blog.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Tümünü Gör
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentPosts as $post)
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $post['title'] }}</h4>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $post['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $post['status'] === 'published' ? 'Yayında' : 'Taslak' }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $post['views'] }} görüntüleme</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $post['author'] }} • {{ $post['created_at_human'] }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Henüz blog yazısı yok</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Forum Topics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Son Forum Konuları</h3>
                    <a href="{{ route('admin.forum.topics') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Tümünü Gör
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentTopics as $topic)
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $topic['title'] }}</h4>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">{{ $topic['posts_count'] }} yanıt</span>
                                    <span class="text-xs text-gray-500">{{ $topic['views'] }} görüntüleme</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $topic['author'] }} • {{ $topic['created_at_human'] }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Henüz forum konusu yok</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hızlı İşlemler</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}" class="flex items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-600">Kullanıcı Ekle</span>
                </div>
            </a>

            <a href="{{ route('admin.blog.create') }}" class="flex items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-600">Blog Yazısı</span>
                </div>
            </a>

            <a href="{{ route('admin.products.create') }}" class="flex items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-sm font-medium text-purple-600">Ürün Ekle</span>
                </div>
            </a>

            <a href="{{ route('admin.settings.site') }}" class="flex items-center justify-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 text-orange-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-orange-600">Ayarlar</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dashboardApp() {
    return {
        charts: {},
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            // User Registrations Chart
            const userCtx = document.getElementById('userRegistrationsChart').getContext('2d');
            this.charts.userRegistrations = new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: @json(collect($chartData['user_registrations'])->pluck('day')),
                    datasets: [{
                        label: 'Yeni Kayıtlar',
                        data: @json(collect($chartData['user_registrations'])->pluck('count')),
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

            // Membership Distribution Chart
            const membershipCtx = document.getElementById('membershipChart').getContext('2d');
            this.charts.membership = new Chart(membershipCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Normal', 'Basic', 'Premium', 'VIP'],
                    datasets: [{
                        data: [
                            @json($chartData['membership_distribution']['normal']),
                            @json($chartData['membership_distribution']['basic']),
                            @json($chartData['membership_distribution']['premium']),
                            @json($chartData['membership_distribution']['vip'])
                        ],
                        backgroundColor: [
                            'rgb(156, 163, 175)',
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
}
</script>
@endpush