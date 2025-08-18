@extends('admin.layouts.app')

@section('title', 'Ödeme Ayarları')

@section('content')
<div class="space-y-6" x-data="paymentSettings()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ödeme Ayarları</h1>
            <p class="mt-1 text-sm text-gray-600">Ödeme sistemlerini ve para birimi ayarlarını yapılandırın</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Currency & Tax Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Para Birimi ve Vergi</h3>
                
                <form method="POST" action="{{ route('admin.settings.update-payment') }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                                Para Birimi <span class="text-red-500">*</span>
                            </label>
                            <select id="currency" name="currency" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('currency') border-red-300 @enderror">
                                <option value="TRY" {{ old('currency', $settings['payment_currency'] ?? 'TRY') === 'TRY' ? 'selected' : '' }}>Türk Lirası (TRY)</option>
                                <option value="USD" {{ old('currency', $settings['payment_currency'] ?? 'TRY') === 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                                <option value="EUR" {{ old('currency', $settings['payment_currency'] ?? 'TRY') === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                <option value="GBP" {{ old('currency', $settings['payment_currency'] ?? 'TRY') === 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency Symbol -->
                        <div>
                            <label for="currency_symbol" class="block text-sm font-medium text-gray-700 mb-1">
                                Para Birimi Sembolü <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="currency_symbol" name="currency_symbol" 
                                   value="{{ old('currency_symbol', $settings['payment_currency_symbol'] ?? '₺') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('currency_symbol') border-red-300 @enderror"
                                   placeholder="₺">
                            @error('currency_symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Rate -->
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">
                                Vergi Oranı (%)
                            </label>
                            <input type="number" id="tax_rate" name="tax_rate" 
                                   value="{{ old('tax_rate', $settings['payment_tax_rate'] ?? '') }}" 
                                   step="0.01" min="0" max="100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tax_rate') border-red-300 @enderror"
                                   placeholder="18.00">
                            @error('tax_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Shipping Cost -->
                        <div>
                            <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-1">
                                Kargo Ücreti
                            </label>
                            <input type="number" id="shipping_cost" name="shipping_cost" 
                                   value="{{ old('shipping_cost', $settings['payment_shipping_cost'] ?? '') }}" 
                                   step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shipping_cost') border-red-300 @enderror"
                                   placeholder="0.00">
                            @error('shipping_cost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Free Shipping Threshold -->
                        <div class="md:col-span-2">
                            <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-1">
                                Ücretsiz Kargo Limiti
                            </label>
                            <input type="number" id="free_shipping_threshold" name="free_shipping_threshold" 
                                   value="{{ old('free_shipping_threshold', $settings['payment_free_shipping_threshold'] ?? '') }}" 
                                   step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('free_shipping_threshold') border-red-300 @enderror"
                                   placeholder="Bu tutarın üzerinde ücretsiz kargo">
                            <p class="mt-1 text-xs text-gray-500">Bu tutar ve üzerindeki siparişlerde kargo ücretsiz olur</p>
                            @error('free_shipping_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Terms -->
                    <div class="mt-6">
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-1">
                            Ödeme Koşulları
                        </label>
                        <textarea id="payment_terms" name="payment_terms" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_terms') border-red-300 @enderror"
                                  placeholder="Ödeme koşullarını buraya yazın...">{{ old('payment_terms', $settings['payment_payment_terms'] ?? '') }}</textarea>
                        @error('payment_terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Refund Policy -->
                    <div class="mt-4">
                        <label for="refund_policy" class="block text-sm font-medium text-gray-700 mb-1">
                            İade Politikası
                        </label>
                        <textarea id="refund_policy" name="refund_policy" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('refund_policy') border-red-300 @enderror"
                                  placeholder="İade politikasını buraya yazın...">{{ old('refund_policy', $settings['payment_refund_policy'] ?? '') }}</textarea>
                        @error('refund_policy')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Ayarları Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Ödeme Yöntemleri</h3>
                    <button @click="openPaymentMethodModal" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Yeni Ödeme Yöntemi
                    </button>
                </div>
                
                <div class="p-6">
                    @if($paymentMethods->count() > 0)
                        <div class="space-y-4">
                            @foreach($paymentMethods as $method)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($method->icon)
                                            {!! $method->getIconHtml() !!}
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $method->name }}</h4>
                                        @if($method->description)
                                            <p class="text-sm text-gray-500">{{ $method->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $method->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $method->is_active ? 'Aktif' : 'Pasif' }}
                                    </span>
                                    <div class="flex space-x-2">
                                        <button @click="editPaymentMethod({{ $method->id }}, '{{ $method->name }}', '{{ $method->slug }}', '{{ $method->description }}', '{{ $method->icon }}', {{ $method->is_active ? 'true' : 'false' }}, {{ $method->sort_order ?? 0 }})" 
                                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            Düzenle
                                        </button>
                                        <form method="POST" action="{{ route('admin.settings.destroy-payment-method', $method) }}" 
                                              onsubmit="return confirm('Bu ödeme yöntemini silmek istediğinizden emin misiniz?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Ödeme yöntemi bulunamadı</h3>
                            <p class="mt-1 text-sm text-gray-500">İlk ödeme yönteminizi oluşturmaya başlayın.</p>
                            <div class="mt-6">
                                <button @click="openPaymentMethodModal" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Yeni Ödeme Yöntemi
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ödeme İstatistikleri</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Aktif Ödeme Yöntemi:</span>
                        <span class="text-sm text-gray-600">{{ $paymentMethods->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Toplam Ödeme Yöntemi:</span>
                        <span class="text-sm text-gray-600">{{ $paymentMethods->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Para Birimi:</span>
                        <span class="text-sm text-gray-600">{{ $settings['payment_currency'] ?? 'TRY' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Vergi Oranı:</span>
                        <span class="text-sm text-gray-600">%{{ $settings['payment_tax_rate'] ?? '0' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Hızlı İşlemler</h3>
                
                <div class="space-y-3">
                    <button @click="openPaymentMethodModal" 
                            class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Yeni Ödeme Yöntemi Ekle
                    </button>
                    <button onclick="testPaymentSystem()" 
                            class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Ödeme Sistemini Test Et
                    </button>
                </div>
            </div>

            <!-- Help -->
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h4 class="text-sm font-medium text-blue-900 mb-2">💡 İpucu</h4>
                <div class="text-sm text-blue-700 space-y-2">
                    <p>• Para birimi değişikliği mevcut fiyatları etkilemez</p>
                    <p>• Vergi oranı otomatik olarak hesaplanır</p>
                    <p>• Ücretsiz kargo limiti müşteri memnuniyetini artırır</p>
                    <p>• Ödeme yöntemlerini sıralayarak öncelik belirleyebilirsiniz</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Modal -->
<div x-show="showPaymentModal" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="showPaymentModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="paymentModalTitle"></h3>
        
        <form :action="paymentFormAction" method="POST">
            @csrf
            <div x-show="editPaymentMode" style="display: none;">
                @method('PUT')
            </div>
            
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="payment_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Ödeme Yöntemi Adı <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="payment_name" name="name" x-model="paymentForm.name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Slug -->
                <div>
                    <label for="payment_slug" class="block text-sm font-medium text-gray-700 mb-1">
                        URL Slug
                    </label>
                    <input type="text" id="payment_slug" name="slug" x-model="paymentForm.slug"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="payment_description" class="block text-sm font-medium text-gray-700 mb-1">
                        Açıklama
                    </label>
                    <textarea id="payment_description" name="description" x-model="paymentForm.description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Icon -->
                    <div>
                        <label for="payment_icon" class="block text-sm font-medium text-gray-700 mb-1">
                            İkon
                        </label>
                        <input type="text" id="payment_icon" name="icon" x-model="paymentForm.icon"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="💳">
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="payment_sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                            Sıra
                        </label>
                        <input type="number" id="payment_sort_order" name="sort_order" x-model="paymentForm.sort_order" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center">
                    <input type="checkbox" id="payment_is_active" name="is_active" value="1" x-model="paymentForm.is_active"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="payment_is_active" class="ml-2 text-sm text-gray-700">
                        Aktif
                    </label>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <span x-text="editPaymentMode ? 'Güncelle' : 'Oluştur'"></span>
                    </button>
                    <button type="button" @click="closePaymentMethodModal" 
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        İptal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function paymentSettings() {
    return {
        showPaymentModal: false,
        editPaymentMode: false,
        paymentModalTitle: '',
        paymentFormAction: '',
        paymentForm: {
            name: '',
            slug: '',
            description: '',
            icon: '',
            is_active: true,
            sort_order: 0
        },
        
        openPaymentMethodModal() {
            this.editPaymentMode = false;
            this.paymentModalTitle = 'Yeni Ödeme Yöntemi';
            this.paymentFormAction = '{{ route("admin.settings.store-payment-method") }}';
            this.resetPaymentForm();
            this.showPaymentModal = true;
        },
        
        editPaymentMethod(id, name, slug, description, icon, isActive, sortOrder) {
            this.editPaymentMode = true;
            this.paymentModalTitle = 'Ödeme Yöntemi Düzenle';
            this.paymentFormAction = `/admin/settings/payment-methods/${id}`;
            this.paymentForm = {
                name: name,
                slug: slug,
                description: description || '',
                icon: icon || '',
                is_active: isActive,
                sort_order: sortOrder || 0
            };
            this.showPaymentModal = true;
        },
        
        closePaymentMethodModal() {
            this.showPaymentModal = false;
            this.resetPaymentForm();
        },
        
        resetPaymentForm() {
            this.paymentForm = {
                name: '',
                slug: '',
                description: '',
                icon: '',
                is_active: true,
                sort_order: 0
            };
        }
    }
}

function testPaymentSystem() {
    alert('Ödeme sistemi test özelliği yakında eklenecek.');
}
</script>
@endpush