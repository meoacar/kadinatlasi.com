@extends('admin.layouts.app')

@section('title', 'Sosyal Medya Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sosyal Medya Ayarları</h1>
            <p class="mt-1 text-sm text-gray-600">Sosyal medya hesaplarınızı ve linklerinizi yönetin</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update-social') }}" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Ana Sosyal Medya Platformları -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ana Platformlar</h3>
                
                <div class="space-y-4">
                    <!-- Facebook -->
                    <div>
                        <label for="facebook_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </label>
                        <input type="url" id="facebook_url" name="facebook_url" 
                               value="{{ old('facebook_url', $settings['social_facebook_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('facebook_url') border-red-300 @enderror"
                               placeholder="https://facebook.com/kadinatlasi">
                        @error('facebook_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Twitter -->
                    <div>
                        <label for="twitter_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Twitter / X
                        </label>
                        <input type="url" id="twitter_url" name="twitter_url" 
                               value="{{ old('twitter_url', $settings['social_twitter_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('twitter_url') border-red-300 @enderror"
                               placeholder="https://twitter.com/kadinatlasi">
                        @error('twitter_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label for="instagram_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <svg class="w-4 h-4 mr-2 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.781c-.49 0-.928-.175-1.297-.49-.368-.315-.49-.753-.49-1.243 0-.49.122-.928.49-1.243.369-.315.807-.49 1.297-.49s.928.175 1.297.49c.315.315.49.753.49 1.243 0 .49-.175.928-.49 1.243-.369.315-.807.49-1.297.49z"/>
                            </svg>
                            Instagram
                        </label>
                        <input type="url" id="instagram_url" name="instagram_url" 
                               value="{{ old('instagram_url', $settings['social_instagram_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('instagram_url') border-red-300 @enderror"
                               placeholder="https://instagram.com/kadinatlasi">
                        @error('instagram_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- LinkedIn -->
                    <div>
                        <label for="linkedin_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <svg class="w-4 h-4 mr-2 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </label>
                        <input type="url" id="linkedin_url" name="linkedin_url" 
                               value="{{ old('linkedin_url', $settings['social_linkedin_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('linkedin_url') border-red-300 @enderror"
                               placeholder="https://linkedin.com/company/kadinatlasi">
                        @error('linkedin_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- YouTube -->
                    <div>
                        <label for="youtube_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <svg class="w-4 h-4 mr-2 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            YouTube
                        </label>
                        <input type="url" id="youtube_url" name="youtube_url" 
                               value="{{ old('youtube_url', $settings['social_youtube_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('youtube_url') border-red-300 @enderror"
                               placeholder="https://youtube.com/@kadinatlasi">
                        @error('youtube_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div> 
           <!-- Diğer Platformlar -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Diğer Platformlar</h3>
                
                <div class="space-y-4">
                    <!-- TikTok -->
                    <div>
                        <label for="tiktok_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <span class="w-4 h-4 mr-2 text-black">🎵</span>
                            TikTok
                        </label>
                        <input type="url" id="tiktok_url" name="tiktok_url" 
                               value="{{ old('tiktok_url', $settings['social_tiktok_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://tiktok.com/@kadinatlasi">
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label for="whatsapp_number" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <span class="w-4 h-4 mr-2 text-green-600">📱</span>
                            WhatsApp Numarası
                        </label>
                        <input type="tel" id="whatsapp_number" name="whatsapp_number" 
                               value="{{ old('whatsapp_number', $settings['social_whatsapp_number'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="+90 555 123 45 67">
                    </div>

                    <!-- Telegram -->
                    <div>
                        <label for="telegram_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <span class="w-4 h-4 mr-2 text-blue-500">✈️</span>
                            Telegram
                        </label>
                        <input type="url" id="telegram_url" name="telegram_url" 
                               value="{{ old('telegram_url', $settings['social_telegram_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://t.me/kadinatlasi">
                    </div>

                    <!-- Discord -->
                    <div>
                        <label for="discord_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <span class="w-4 h-4 mr-2 text-indigo-600">🎮</span>
                            Discord
                        </label>
                        <input type="url" id="discord_url" name="discord_url" 
                               value="{{ old('discord_url', $settings['social_discord_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://discord.gg/kadinatlasi">
                    </div>

                    <!-- GitHub -->
                    <div>
                        <label for="github_url" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            GitHub
                        </label>
                        <input type="url" id="github_url" name="github_url" 
                               value="{{ old('github_url', $settings['social_github_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://github.com/kadinatlasi">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Sosyal Medya Ayarlarını Kaydet
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection