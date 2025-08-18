<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - KadınAtlası Admin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom styles -->
    <style>
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }
        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .nav-item-active {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .nav-item:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateX(4px);
        }
        .nav-item {
            transition: all 0.2s ease;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50" x-data="adminApp()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar-gradient w-64 flex-shrink-0 transition-all duration-300" 
             :class="{ '-ml-64': !sidebarOpen }" 
             x-show="sidebarOpen || !isMobile" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg">KadınAtlası</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-item flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.dashboard*') ? 'nav-item-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Search -->
                <a href="{{ route('admin.search.index') }}" 
                   class="nav-item flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.search*') ? 'nav-item-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Arama
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}" 
                   class="nav-item flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.users*') ? 'nav-item-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Kullanıcılar
                </a>

                <!-- Blog -->
                <a href="{{ route('admin.blog.index') }}" 
                   class="nav-item flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.blog*') ? 'nav-item-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    Blog Yazıları
                </a>

                <!-- Products -->
                <div x-data="{ open: {{ request()->routeIs('admin.products*', 'admin.categories*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="nav-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.products*', 'admin.categories*') ? 'nav-item-active text-white' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Ürünler
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                        <a href="{{ route('admin.products.index') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm {{ request()->routeIs('admin.products*') ? 'nav-item-active text-white' : '' }}">
                            Tüm Ürünler
                        </a>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm {{ request()->routeIs('admin.categories*') ? 'nav-item-active text-white' : '' }}">
                            Kategoriler
                        </a>
                    </div>
                </div>

                <!-- Forum -->
                <div x-data="{ open: {{ request()->routeIs('admin.forum*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="nav-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.forum*') ? 'nav-item-active text-white' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"></path>
                            </svg>
                            Forum
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                        <a href="{{ route('admin.forum.topics') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm">
                            Konular
                        </a>
                        <a href="{{ route('admin.forum.groups.index') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm">
                            Gruplar
                        </a>
                    </div>
                </div>

                <!-- Reports -->
                <a href="{{ route('admin.reports.index') }}" 
                   class="nav-item flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.reports*') ? 'nav-item-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Raporlar
                </a>

                <!-- Activities -->
                <a href="{{ route('admin.activities.index') }}" 
                   class="nav-item flex items-center px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.activities*') ? 'nav-item-active text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Aktivite Logları
                </a>

                <!-- Exports -->
                <div x-data="{ open: {{ request()->routeIs('admin.exports*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="nav-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.exports*') ? 'nav-item-active text-white' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Veri Dışa Aktarma
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                        <a href="{{ route('admin.exports.index') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm {{ request()->routeIs('admin.exports.index') ? 'text-white bg-gray-700' : '' }}">
                            <i class="fas fa-download w-4 mr-2"></i>
                            Hızlı Export
                        </a>
                        <a href="{{ route('admin.exports.custom') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm {{ request()->routeIs('admin.exports.custom') ? 'text-white bg-gray-700' : '' }}">
                            <i class="fas fa-cog w-4 mr-2"></i>
                            Özel Export
                        </a>
                        <a href="{{ route('admin.exports.history') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm {{ request()->routeIs('admin.exports.history') ? 'text-white bg-gray-700' : '' }}">
                            <i class="fas fa-history w-4 mr-2"></i>
                            Export Geçmişi
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <div x-data="{ open: {{ request()->routeIs('admin.settings*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="nav-item w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-lg {{ request()->routeIs('admin.settings*') ? 'nav-item-active text-white' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ayarlar
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                        <a href="{{ route('admin.settings.site') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm">
                            Site Ayarları
                        </a>
                        <a href="{{ route('admin.settings.payment') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm">
                            Ödeme Ayarları
                        </a>
                        <a href="{{ route('admin.settings.footer') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm">
                            Footer Linkleri
                        </a>
                        <a href="{{ route('admin.cache.index') }}" 
                           class="nav-item flex items-center px-4 py-2 text-gray-400 rounded-lg text-sm {{ request()->routeIs('admin.cache*') ? 'text-white bg-gray-700' : '' }}">
                            <i class="fas fa-tachometer-alt w-4 mr-2"></i>
                            Cache & Performans
                        </a>
                    </div>
                </div>
            </nav>

            <!-- User Info -->
            <div class="px-4 py-4 border-t border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-medium">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-medium truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-gray-400 text-xs truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Page Title -->
                        <h1 class="text-2xl font-semibold text-gray-900">
                            @yield('title', 'Dashboard')
                        </h1>
                    </div>

                    <!-- Header Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Global Search -->
                        <div class="hidden md:block relative" x-data="{ open: false }">
                            <form action="{{ route('admin.search.index') }}" method="GET" class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="q" 
                                       placeholder="Ara..." 
                                       class="block w-64 pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       autocomplete="off">
                            </form>
                        </div>

                        <!-- Mobile Search Button -->
                        <a href="{{ route('admin.search.index') }}" class="md:hidden p-2 text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </a>

                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-gray-500 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.82l3.12 3.12M7.05 5.84l3.12 3.12M4.03 8.86l3.12 3.12M1.01 11.88l3.12 3.12"></path>
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Ayarlar
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-6">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show">
                            <div class="flex items-center justify-between">
                                <span>{{ session('success') }}</span>
                                <button @click="show = false" class="text-green-500 hover:text-green-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show">
                            <div class="flex items-center justify-between">
                                <span>{{ session('error') }}</span>
                                <button @click="show = false" class="text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen && isMobile" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"></div>

    <script>
        function adminApp() {
            return {
                sidebarOpen: true,
                isMobile: false,
                
                init() {
                    this.checkScreenSize();
                    window.addEventListener('resize', () => {
                        this.checkScreenSize();
                    });
                },
                
                checkScreenSize() {
                    this.isMobile = window.innerWidth < 1024;
                    if (this.isMobile) {
                        this.sidebarOpen = false;
                    } else {
                        this.sidebarOpen = true;
                    }
                }
            }
        }
    </script>

    @stack('scripts')
</body>
</html>