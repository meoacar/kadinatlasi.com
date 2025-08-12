<template>
  <div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-purple-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white py-16">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl md:text-5xl font-display font-bold mb-4">
            🛒 Sepetim
          </h1>
          <p class="text-xl opacity-90">Seçtiğiniz ürünleri gözden geçirin</p>
        </div>
      </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Empty Cart State -->
      <div v-if="cartItems.length === 0" class="text-center py-16">
        <div class="max-w-md mx-auto">
          <div class="w-32 h-32 bg-gradient-to-br from-pink-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-8">
            <div class="text-6xl">🛒</div>
          </div>
          <h2 class="text-3xl font-bold text-gray-900 mb-4">Sepetiniz Boş</h2>
          <p class="text-xl text-gray-600 mb-8">Harika ürünlerimizi keşfetmeye hazır mısınız?</p>
          <div class="space-y-4">
            <RouterLink 
              to="/shop" 
              class="inline-block bg-gradient-to-r from-pink-500 to-purple-500 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:from-pink-600 hover:to-purple-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              🎆 Alışverişe Başla
            </RouterLink>
            <div class="flex justify-center space-x-6 text-sm text-gray-500">
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Güvenli Ödeme
              </div>
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Hızlı Teslimat
              </div>
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Kalite Garantisi
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cart with Items -->
      <div v-else class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <!-- Cart Items -->
        <div class="xl:col-span-3">
          <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center justify-between mb-8">
              <h2 class="text-2xl font-bold text-gray-900">Sepetinizdeki Ürünler</h2>
              <div class="bg-pink-100 text-pink-600 px-4 py-2 rounded-full text-sm font-semibold">
                {{ cartItems.length }} ürün
              </div>
            </div>
            
            <div class="space-y-6">
              <div 
                v-for="item in cartItems" 
                :key="item.id" 
                class="group bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-all duration-300"
              >
                <div class="flex items-center space-x-6">
                  <!-- Product Image -->
                  <div class="relative">
                    <img 
                      :src="item.product.images?.[0] || '/placeholder-product.jpg'" 
                      :alt="item.product.name" 
                      class="w-24 h-24 object-cover rounded-xl shadow-md group-hover:shadow-lg transition-shadow duration-300"
                    >
                    <div class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                      {{ item.quantity }}
                    </div>
                  </div>
                  
                  <!-- Product Info -->
                  <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-pink-600 transition-colors">
                      {{ item.product.name }}
                    </h3>
                    <p class="text-gray-600 mb-3 line-clamp-2">{{ item.product.short_description }}</p>
                    <div class="flex items-center space-x-4">
                      <span class="text-2xl font-bold text-pink-600">{{ item.product.final_price }}₺</span>
                      <span v-if="item.product.sale_price" class="text-lg text-gray-400 line-through">
                        {{ item.product.price }}₺
                      </span>
                    </div>
                  </div>
                  
                  <!-- Quantity Controls -->
                  <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white rounded-xl shadow-md">
                      <button 
                        @click="updateQuantity(item.id, item.quantity - 1)" 
                        class="w-12 h-12 rounded-l-xl bg-gray-100 hover:bg-pink-100 flex items-center justify-center transition-colors group"
                      >
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                      </button>
                      <div class="w-16 h-12 flex items-center justify-center bg-white font-bold text-lg">
                        {{ item.quantity }}
                      </div>
                      <button 
                        @click="updateQuantity(item.id, item.quantity + 1)" 
                        class="w-12 h-12 rounded-r-xl bg-gray-100 hover:bg-pink-100 flex items-center justify-center transition-colors group"
                      >
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                      </button>
                    </div>
                    
                    <!-- Remove Button -->
                    <button 
                      @click="removeItem(item.id)" 
                      class="w-12 h-12 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl transition-all duration-300 transform hover:scale-110 group"
                    >
                      <svg class="w-5 h-5 mx-auto group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Item Total -->
                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                  <span class="text-gray-600">Ara Toplam:</span>
                  <span class="text-xl font-bold text-gray-900">{{ (item.product.final_price * item.quantity).toFixed(2) }}₺</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="xl:col-span-1">
          <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 sticky top-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sipariş Özeti</h2>
            
            <!-- Summary Details -->
            <div class="space-y-4 mb-6">
              <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-600">Ara Toplam:</span>
                <span class="text-lg font-semibold">{{ subtotal.toFixed(2) }}₺</span>
              </div>
              
              <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <div class="flex items-center">
                  <span class="text-gray-600">Kargo:</span>
                  <div v-if="shippingCost === 0" class="ml-2 bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full font-semibold">
                    ÜCRETSİZ
                  </div>
                </div>
                <span class="text-lg font-semibold" :class="shippingCost === 0 ? 'text-green-600' : ''">
                  {{ shippingCost === 0 ? 'Ücretsiz' : shippingCost + '₺' }}
                </span>
              </div>
              
              <div v-if="shippingCost > 0" class="bg-blue-50 p-4 rounded-xl">
                <div class="flex items-center text-blue-600 text-sm">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ (200 - subtotal).toFixed(2) }}₺ daha ekleyin, kargo ücretsiz olsun!
                </div>
                <div class="mt-2 bg-blue-200 rounded-full h-2">
                  <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" :style="{ width: Math.min(100, (subtotal / 200) * 100) + '%' }"></div>
                </div>
              </div>
              
              <div class="bg-gradient-to-r from-pink-500 to-purple-500 text-white p-4 rounded-xl">
                <div class="flex justify-between items-center">
                  <span class="text-lg font-semibold">Toplam:</span>
                  <span class="text-2xl font-bold">{{ total.toFixed(2) }}₺</span>
                </div>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-4">
              <button 
                @click="proceedToCheckout" 
                class="w-full bg-gradient-to-r from-pink-500 to-purple-500 text-white py-4 rounded-2xl font-bold text-lg hover:from-pink-600 hover:to-purple-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
              >
                💳 Ödemeye Geç
              </button>
              
              <RouterLink 
                to="/shop" 
                class="block w-full text-center py-3 text-pink-600 hover:text-pink-700 font-semibold border-2 border-pink-200 hover:border-pink-300 rounded-2xl transition-all duration-300 hover:bg-pink-50"
              >
                ← Alışverişe Devam Et
              </RouterLink>
            </div>
            
            <!-- Trust Badges -->
            <div class="mt-6 pt-6 border-t border-gray-100">
              <div class="grid grid-cols-1 gap-3 text-center">
                <div class="flex items-center justify-center text-sm text-gray-600">
                  <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                  Güvenli Ödeme
                </div>
                <div class="flex items-center justify-center text-sm text-gray-600">
                  <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h8a2 2 0 002-2V8m-9 4h4"/>
                  </svg>
                  Hızlı Teslimat
                </div>
                <div class="flex items-center justify-center text-sm text-gray-600">
                  <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                  Müşteri Memnuniyeti
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const cartItems = ref([])
const loading = ref(false)

const subtotal = computed(() => {
  return cartItems.value.reduce((sum, item) => sum + (item.product.final_price * item.quantity), 0)
})

const shippingCost = computed(() => {
  return subtotal.value > 200 ? 0 : 15
})

const total = computed(() => {
  return subtotal.value + shippingCost.value
})

const fetchCartItems = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/cart/items')
    cartItems.value = response.data.data
  } catch (error) {
    console.error('Sepet yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const updateQuantity = async (itemId: number, newQuantity: number) => {
  if (newQuantity < 1) return
  
  try {
    await axios.put(`/api/cart/items/${itemId}`, { quantity: newQuantity })
    await fetchCartItems()
  } catch (error) {
    console.error('Miktar güncellenirken hata:', error)
  }
}

const removeItem = async (itemId: number) => {
  try {
    await axios.delete(`/api/cart/items/${itemId}`)
    await fetchCartItems()
  } catch (error) {
    console.error('Ürün silinirken hata:', error)
  }
}

const proceedToCheckout = () => {
  router.push('/checkout')
}

onMounted(() => {
  fetchCartItems()
})
</script>