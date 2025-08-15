@extends('admin.layouts.app')

@section('title', 'Hata - ' . ($status ?? '500'))

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <!-- Error Icon -->
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
        </div>

        <!-- Error Code -->
        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $status ?? '500' }}</h1>
        
        <!-- Error Message -->
        <p class="text-gray-600 mb-6">
            {{ $message ?? 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.' }}
        </p>

        <!-- Actions -->
        <div class="space-y-3">
            <button onclick="history.back()" class="w-full btn btn-primary">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </button>
            
            <a href="{{ route('admin.dashboard') }}" class="w-full btn btn-secondary">
                <i class="fas fa-home mr-2"></i>
                Ana Sayfa
            </a>
        </div>

        <!-- Additional Info (only in development) -->
        @if(app()->environment('local') && isset($exception))
        <div class="mt-8 p-4 bg-gray-100 rounded-lg text-left">
            <h3 class="font-semibold text-gray-900 mb-2">Debug Bilgisi:</h3>
            <p class="text-sm text-gray-700 mb-2">
                <strong>Dosya:</strong> {{ $exception->getFile() }}
            </p>
            <p class="text-sm text-gray-700 mb-2">
                <strong>Satır:</strong> {{ $exception->getLine() }}
            </p>
            <p class="text-sm text-gray-700">
                <strong>Mesaj:</strong> {{ $exception->getMessage() }}
            </p>
        </div>
        @endif
    </div>
</div>
@endsection