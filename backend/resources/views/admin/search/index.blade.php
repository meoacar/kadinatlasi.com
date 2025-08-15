@extends('admin.layouts.app')

@section('title', 'Arama Sonuçları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Arama</h1>
            <p class="mt-1 text-sm text-gray-600">Tüm içeriklerde arama yapın</p>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.search.index') }}" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}"
                           placeholder="Kullanıcı, blog yazısı, ürün, forum konusu ara..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           autocomplete="off"
                           id="search-input">
                    
                    <!-- Autocomplete Suggestions -->
                    <div id="suggestions" class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden">
                        <!-- Suggestions will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="md:w-48">
                    <select name="type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Tümü</option>
                        <option value="users" {{ $type === 'users' ? 'selected' : '' }}>Kullanıcılar</option>
                        <option value="posts" {{ $type === 'posts' ? 'selected' : '' }}>Blog Yazıları</option>
                        <option value="products" {{ $type === 'products' ? 'selected' : '' }}>Ürünler</option>
                        <option value="topics" {{ $type === 'topics' ? 'selected' : '' }}>Forum Konuları</option>
                        <option value="categories" {{ $type === 'categories' ? 'selected' : '' }}>Kategoriler</option>
                    </select>
                </div>

                <!-- Search Button -->
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Ara
                </button>
            </div>
        </form>
    </div>

    @if(!empty($query))
        <!-- Search Results -->
        <div class="space-y-6">
            <!-- Results Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-blue-800">
                        <strong>"{{ $query }}"</strong> için <strong>{{ $totalResults }}</strong> sonuç bulundu
                    </span>
                </div>
            </div>

            @if($results->isEmpty())
                <!-- No Results -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sonuç bulunamadı</h3>
                    <p class="mt-1 text-sm text-gray-500">Farklı anahtar kelimeler deneyin veya filtreleri değiştirin.</p>
                </div>
            @else
                <!-- Results by Category -->
                @foreach($results as $group)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <!-- Group Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($group['icon'] === 'users')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    @elseif($group['icon'] === 'document-text')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    @elseif($group['icon'] === 'shopping-bag')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                                    @elseif($group['icon'] === 'chat-bubble-left-right')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    @elseif($group['icon'] === 'tag')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    @endif
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900">{{ $group['title'] }}</h3>
                                <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $group['items']->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Group Items -->
                        <div class="divide-y divide-gray-200">
                            @foreach($group['items'] as $item)
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start space-x-4">
                                        <!-- Item Image/Avatar -->
                                        @if(isset($item['image']) || isset($item['avatar']))
                                            <div class="flex-shrink-0">
                                                <img src="{{ $item['image'] ?? $item['avatar'] ?? '/images/default-avatar.png' }}" 
                                                     alt="{{ $item['title'] }}"
                                                     class="w-12 h-12 rounded-lg object-cover">
                                            </div>
                                        @endif

                                        <!-- Item Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-medium text-gray-900 hover:text-blue-600">
                                                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                                                    </h4>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $item['subtitle'] }}</p>
                                                    @if($item['description'])
                                                        <p class="text-sm text-gray-500 mt-2">{{ $item['description'] }}</p>
                                                    @endif
                                                </div>

                                                <!-- Item Status & Meta -->
                                                <div class="flex flex-col items-end space-y-2 ml-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $item['status_class'] === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $item['status_class'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $item['status_class'] === 'danger' ? 'bg-red-100 text-red-800' : '' }}">
                                                        {{ $item['status'] }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $item['created_at'] }}</span>
                                                    @if(isset($item['price']))
                                                        <span class="text-sm font-medium text-green-600">{{ $item['price'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsContainer = document.getElementById('suggestions');
    let debounceTimer;

    // Autocomplete functionality
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            suggestionsContainer.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchSuggestions(query);
        }, 300);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.classList.add('hidden');
        }
    });

    // Fetch suggestions from API
    async function fetchSuggestions(query) {
        try {
            const response = await fetch(`{{ route('admin.search.suggestions') }}?q=${encodeURIComponent(query)}&limit=8`);
            const data = await response.json();
            
            if (data.success && data.suggestions.length > 0) {
                displaySuggestions(data.suggestions);
            } else {
                suggestionsContainer.classList.add('hidden');
            }
        } catch (error) {
            console.error('Suggestion fetch error:', error);
            suggestionsContainer.classList.add('hidden');
        }
    }

    // Display suggestions
    function displaySuggestions(suggestions) {
        const html = suggestions.map(suggestion => `
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer suggestion-item" data-text="${suggestion.text}">
                <div class="flex items-center">
                    <span class="flex-1">${suggestion.text}</span>
                    <span class="text-xs text-gray-500 capitalize">${suggestion.type}</span>
                </div>
            </div>
        `).join('');

        suggestionsContainer.innerHTML = html;
        suggestionsContainer.classList.remove('hidden');

        // Add click handlers to suggestions
        suggestionsContainer.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', function() {
                searchInput.value = this.dataset.text;
                suggestionsContainer.classList.add('hidden');
                // Optionally trigger search
                searchInput.closest('form').submit();
            });
        });
    }

    // Keyboard navigation for suggestions
    searchInput.addEventListener('keydown', function(e) {
        const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item');
        const activeSuggestion = suggestionsContainer.querySelector('.bg-blue-100');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (activeSuggestion) {
                activeSuggestion.classList.remove('bg-blue-100');
                const next = activeSuggestion.nextElementSibling;
                if (next) {
                    next.classList.add('bg-blue-100');
                } else {
                    suggestions[0]?.classList.add('bg-blue-100');
                }
            } else {
                suggestions[0]?.classList.add('bg-blue-100');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (activeSuggestion) {
                activeSuggestion.classList.remove('bg-blue-100');
                const prev = activeSuggestion.previousElementSibling;
                if (prev) {
                    prev.classList.add('bg-blue-100');
                } else {
                    suggestions[suggestions.length - 1]?.classList.add('bg-blue-100');
                }
            } else {
                suggestions[suggestions.length - 1]?.classList.add('bg-blue-100');
            }
        } else if (e.key === 'Enter') {
            if (activeSuggestion) {
                e.preventDefault();
                searchInput.value = activeSuggestion.dataset.text;
                suggestionsContainer.classList.add('hidden');
                this.closest('form').submit();
            }
        } else if (e.key === 'Escape') {
            suggestionsContainer.classList.add('hidden');
        }
    });
});
</script>
@endpush