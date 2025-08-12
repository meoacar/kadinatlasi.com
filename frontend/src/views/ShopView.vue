<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-900">🛍️ Alışveriş</h1>
        <p class="text-gray-600 mt-2">Kadınlar için özel seçilmiş ürünler</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- İkinci El Banner -->
      <div class="bg-orange-500 text-white rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold">🔄 İkinci El Pazar</h2>
            <p class="mt-1">Kaliteli ikinci el ürünleri keşfedin</p>
          </div>
          <RouterLink 
            to="/ikinci-el-pazar"
            class="bg-white text-orange-500 px-4 py-2 rounded font-semibold hover:bg-gray-100"
          >
            Keşfet
          </RouterLink>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="md:col-span-2">
            <input 
              v-model="searchQuery"
              @input="searchProducts"
              type="text" 
              placeholder="Ürün ara..."
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
            >
          </div>
          <div>
            <select 
              v-model="sortBy"
              @change="fetchProducts"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
            >
              <option value="newest">En Yeni</option>
              <option value="price_low">Fiyat: Düşük → Yüksek</option>
              <option value="price_high">Fiyat: Yüksek → Düşük</option>
              <option value="popular">En Popüler</option>
            </select>
          </div>
        </div>

        <!-- Categories -->
        <div>
          <h3 class="text-lg font-semibold mb-3">Kategoriler</h3>
          <div class="flex flex-wrap gap-2">
            <button
              @click="selectCategory(null)"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium transition-colors',
                !selectedCategory ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
              ]"
            >
              Tümü
            </button>
            <button
              v-for="category in categories"
              :key="category.id"
              @click="selectCategory(category.slug)"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium transition-colors',
                selectedCategory === category.slug ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
              ]"
            >
              {{ category.name }}
            </button>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-pink-500 mx-auto"></div>
        <p class="mt-4 text-gray-600">Ürünler yükleniyor...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="products.length === 0" class="text-center py-12">
        <div class="text-6xl mb-4">📦</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Ürün bulunamadı</h3>
        <p class="text-gray-600">Arama kriterlerinizi değiştirmeyi deneyin</p>
      </div>

      <!-- Products Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden"
        >
          <!-- Product Image -->
          <div class="relative">
            <img
              src="https://via.placeholder.com/300x200/ec4899/ffffff?text=Defacto+Şort"
              :alt="product.name"
              class="w-full h-48 object-cover"
            >
            <!-- Discount Badge -->
            <div v-if="product.discount_percentage" class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-bold">
              -%{{ product.discount_percentage }}
            </div>
            <!-- Stock Status -->
            <div v-if="product.stock_quantity === 0" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
              <span class="bg-red-500 text-white px-3 py-1 rounded font-semibold">Stokta Yok</span>
            </div>
          </div>
          
          <!-- Product Info -->
          <div class="p-4">
            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
              {{ product.name }}
            </h3>
            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
              {{ product.short_description }}
            </p>
            
            <!-- Price & Stock -->
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center space-x-2">
                <span class="text-lg font-bold text-pink-600">
                  {{ product.final_price }}₺
                </span>
                <span v-if="product.sale_price" class="text-sm text-gray-400 line-through">
                  {{ product.price }}₺
                </span>
              </div>
              <span class="text-sm text-gray-500">
                {{ product.stock_quantity }} stok
              </span>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-2">
              <RouterLink
                :to="`/shop/product/${product.id}`"
                class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded text-center text-sm font-medium hover:bg-gray-200 transition-colors"
              >
                İncele
              </RouterLink>
              <button
                @click="addToCart(product)"
                :disabled="product.stock_quantity === 0"
                class="flex-1 bg-pink-500 text-white py-2 px-3 rounded text-sm font-medium hover:bg-pink-600 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
              >
                Sepete Ekle
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

// Reactive data
const products = ref([])
const categories = ref([])
const selectedCategory = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const sortBy = ref('newest')

// Methods
const fetchProducts = async () => {
  loading.value = true
  try {
    const params = {}
    if (selectedCategory.value) {
      params.category = selectedCategory.value
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    if (sortBy.value) {
      params.sort = sortBy.value
    }
    
    const response = await api.get('/products', { params })
    products.value = response.data.data || []
  } catch (error) {
    console.error('Ürünler yüklenirken hata:', error)
    products.value = []
  } finally {
    loading.value = false
  }
}

const fetchCategories = async () => {
  try {
    const response = await api.get('/product-categories')
    categories.value = response.data || []
  } catch (error) {
    console.error('Kategoriler yüklenirken hata:', error)
    categories.value = []
  }
}

const selectCategory = (categorySlug) => {
  selectedCategory.value = categorySlug
  fetchProducts()
}

const searchProducts = () => {
  fetchProducts()
}

const addToCart = async (product) => {
  try {
    await api.post('/cart/items', {
      product_id: product.id,
      quantity: 1
    })
    
    // Show success message
    alert('Ürün sepete eklendi!')
    
  } catch (error) {
    console.error('Sepete eklenirken hata:', error)
    alert('Sepete eklenirken bir hata oluştu.')
  }
}



// Lifecycle
onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>