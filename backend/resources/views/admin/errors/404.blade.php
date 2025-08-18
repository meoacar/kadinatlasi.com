@extends('admin.layouts.app')

@section('title', 'Sayfa Bulunamadı - 404')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <!-- 404 Icon -->
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-search text-blue-600 text-2xl"></i>
            </div>
        </div>

        <!-- Error Code -->
        <h1 class="text-4xl font-bold text-gray-900 mb-2">404</h1>
        
        <!-- Error Message -->
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Sayfa Bulunamadı</h2>
        <p class="text-gray-600 mb-6">
            Aradığınız sayfa mevcut değil veya taşınmış olabilir.
        </p>

        <!-- Search Box -->
        <div class="mb-6">
            <form action="{{ route('admin.search.index') }}" method="GET" class="flex">
                <input type="text" name="q" placeholder="Aradığınızı buradan bulabilirsiniz..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="space-y-3">
            <a href="{{ route('admin.dashboard') }}" class="w-full btn btn-primary">
                <i class="fas fa-home mr-2"></i>
                Ana Sayfa
            </a>
            
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-users mr-1"></i>
                    Kullanıcılar
                </a>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-blog mr-1"></i>
                    Blog
                </a>
            </div>
        </div>

        <!-- Help Text -->
        <p class="text-xs text-gray-500 mt-6">
            Sorun devam ederse sistem yöneticisi ile iletişime geçin.
        </p>
    </div>
</div>
@endsection