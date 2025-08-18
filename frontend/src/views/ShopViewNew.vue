<template>
  <div style="min-height: 100vh; background: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">🛍️ Alışveriş</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Kadınlar için özel seçilmiş ürünler</p>
      </div>

      <!-- İkinci El Banner -->
      <div style="background: linear-gradient(135deg, #f97316, #ea580c); color: white; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <div>
            <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 8px;">🔄 İkinci El Pazar</h2>
            <p style="opacity: 0.9;">Kaliteli ikinci el ürünleri keşfedin ve çevreye katkıda bulunun</p>
          </div>
          <RouterLink 
            to="/ikinci-el-pazar"
            style="background: white; color: #f97316; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s;"
            @mouseover="$event.target.style.transform = 'translateY(-2px)'"
            @mouseleave="$event.target.style.transform = 'translateY(0)'"
          >
            Keşfet →
          </RouterLink>
        </div>
      </div>

      <!-- Search & Categories -->
      <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 32px;">
        <!-- Search -->
        <div style="margin-bottom: 24px;">
          <div style="display: flex; gap: 16px; margin-bottom: 16px;">
            <input 
              v-model="searchQuery"
              @input="handleSearch"
              type="text" 
              placeholder="Ürün ara..."
              style="flex: 1; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;"
            >
            <select 
              v-model="sortBy"
              @change="loadProducts"
              style="padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; background: white;"
            >
              <option value="newest">En Yeni</option>
              <option value="price_low">Fiyat: Düşük → Yüksek</option>
              <option value="price_high">Fiyat: Yüksek → Düşük</option>
            </select>
          </div>
        </div>

        <!-- Categories -->
        <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;">
          <button 
            @click="selectCategory(null)"
            :style="{
              padding: '8px 16px',
              borderRadius: '20px',
              border: 'none',
              cursor: 'pointer',
              fontWeight: '500',
              background: !selectedCategory ? '#ec4899' : '#f3f4f6',
              color: !selectedCategory ? 'white' : '#374151'
            }"
          >
            Tümü
          </button>
          <button 
            v-for="category in categories" 
            :key="category.id"
            @click="selectCategory(category.slug)"
            :style="{
              padding: '8px 16px',
              borderRadius: '20px',
              border: 'none',
              cursor: 'pointer',
              fontWeight: '500',
              background: selectedCategory === category.slug ? '#ec4899' : '#f3f4f6',
              color: selectedCategory === category.slug ? 'white' : '#374151'
            }"
          >
            {{ category.name }}
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" style="text-align: center; padding: 64px;">
        <div style="width: 48px; height: 48px; border: 3px solid #f3f4f6; border-top: 3px solid #ec4899; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        <p style="margin-top: 16px; color: #6b7280;">Ürünler yükleniyor...</p>
      </div>

      <!-- Products Grid -->
      <div v-else-if="products.length" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        <div 
          v-for="product in products" 
          :key="product.id"
          style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;"
          @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
          @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)'"
        >
          <!-- Product Image -->
          <div style="position: relative;">
            <img 
              :src="product.image_urls?.[0] || 'https://via.placeholder.com/300x200?text=Ürün'" 
              :alt="product.name"
              style="width: 100%; height: 200px; object-fit: cover;"
            >
            <!-- Discount Badge -->
            <div v-if="product.discount_percentage" style="position: absolute; top: 12px; right: 12px; background: #dc2626; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">
              -%{{ product.discount_percentage }}
            </div>
            <!-- Stock Status -->
            <div v-if="product.stock_quantity === 0" style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
              <span style="background: #dc2626; color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600;">Stokta Yok</span>
            </div>
          </div>
          
          <!-- Product Info -->
          <div style="padding: 20px;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px; line-height: 1.4;">
              {{ product.name }}
            </h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 16px;">
              {{ product.short_description }}
            </p>
            
            <!-- Price & Stock -->
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 1.25rem; font-weight: bold; color: #ec4899;">
                  {{ product.final_price }}₺
                </span>
                <span v-if="product.sale_price" style="font-size: 0.875rem; color: #9ca3af; text-decoration: line-through;">
                  {{ product.price }}₺
                </span>
              </div>
              <span style="font-size: 0.75rem; color: #6b7280; background: #f3f4f6; padding: 4px 8px; border-radius: 12px;">
                {{ product.stock_quantity }} stok
              </span>
            </div>
            
            <!-- Actions -->
            <div style="display: flex; gap: 8px;">
              <RouterLink
                :to="`/shop/product/${product.id}`"
                style="flex: 1; background: #f3f4f6; color: #374151; padding: 10px; border-radius: 8px; text-align: center; text-decoration: none; font-weight: 500; transition: background-color 0.2s;"
                @mouseover="$event.target.style.background = '#e5e7eb'"
                @mouseleave="$event.target.style.background = '#f3f4f6'"
              >
                İncele
              </RouterLink>
              <button
                @click="addToCart(product)"
                :disabled="product.stock_quantity === 0"
                :style="{ 
                  flex: 1, 
                  background: '#ec4899', 
                  color: 'white', 
                  padding: '10px', 
                  border: 'none', 
                  borderRadius: '8px', 
                  fontWeight: '500', 
                  cursor: 'pointer', 
                  transition: 'background-color 0.2s',
                  opacity: product.stock_quantity === 0 ? 0.5 : 1 
                }"
              >
                Sepete Ekle
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else style="text-center; padding: 64px;">
        <div style="font-size: 4rem; margin-bottom: 16px;">🛍️</div>
        <p style="color: #6b7280; font-size: 1.125rem;">Ürün bulunamadı.</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import axios from 'axios'

const products = ref([])
const categories = ref([])
const selectedCategory = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const sortBy = ref('newest')

const loadProducts = async () => {
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
    
    const response = await axios.get('/api/products', { params })
    products.value = response.data.data || []
  } catch (error) {
    console.error('Ürünler yüklenirken hata:', error)
    products.value = []
  } finally {
    loading.value = false
  }
}

const loadCategories = async () => {
  try {
    const response = await axios.get('/api/product-categories')
    categories.value = response.data || []
  } catch (error) {
    console.error('Kategoriler yüklenirken hata:', error)
    categories.value = []
  }
}

const selectCategory = (categorySlug) => {
  selectedCategory.value = categorySlug
  loadProducts()
}

const handleSearch = () => {
  loadProducts()
}

const addToCart = async (product) => {
  try {
    await axios.post('/api/cart/items', {
      product_id: product.id,
      quantity: 1
    })
    alert('Ürün sepete eklendi!')
  } catch (error) {
    console.error('Sepete eklenirken hata:', error)
    alert('Sepete eklenirken bir hata oluştu.')
  }
}

onMounted(() => {
  loadProducts()
  loadCategories()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>