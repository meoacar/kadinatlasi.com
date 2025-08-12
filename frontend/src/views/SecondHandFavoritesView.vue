<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); padding: 32px 0;">
    <div style="max-width: 1152px; margin: 0 auto; padding: 0 16px;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #1f2937; margin-bottom: 8px;">❤️ Favori Ürünlerim</h1>
        <p style="color: #6b7280;">Beğendiğiniz ikinci el ürünler</p>
      </div>

      <div v-if="favorites.length === 0" style="text-align: center; padding: 64px 0;">
        <div style="font-size: 4rem; margin-bottom: 16px;">💔</div>
        <h3 style="font-size: 1.25rem; font-weight: bold; color: #1f2937; margin-bottom: 8px;">Henüz Favori Ürün Yok</h3>
        <p style="color: #6b7280; margin-bottom: 24px;">İkinci el pazarından beğendiğiniz ürünleri favorilere ekleyin!</p>
        <router-link to="/ikinci-el-pazar" 
                     style="background: #ea580c; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; text-decoration: none;">
          Pazarı Keşfet
        </router-link>
      </div>

      <div v-else style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
        <div v-for="product in favorites" :key="product.id" 
             style="background: white; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)'">
          
          <!-- Product Image -->
          <div style="height: 200px; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <img v-if="product.images && product.images.length > 0" 
                 :src="getImageUrl(product.images[0])" 
                 style="width: 100%; height: 100%; object-fit: cover;">
            <div v-else style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
              <span style="font-size: 3rem; color: #9ca3af;">📷</span>
            </div>
            
            <div v-if="getDiscountPercent(product) > 0" 
                 style="position: absolute; top: 12px; left: 12px; background: #dc2626; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">
              -%{{ getDiscountPercent(product) }}
            </div>
            
            <button @click.stop="removeFavorite(product)" 
                    style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; font-size: 1rem;">
              ❤️
            </button>
          </div>
          
          <!-- Product Info -->
          <div style="padding: 20px;">
            <h3 style="font-weight: bold; color: #1f2937; margin-bottom: 8px; font-size: 1.125rem;">{{ product.title }}</h3>
            
            <!-- Price -->
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
              <span style="font-size: 1.5rem; font-weight: bold; color: #ea580c;">{{ product.price }} ₺</span>
              <span v-if="product.original_price > product.price" 
                    style="font-size: 0.875rem; color: #6b7280; text-decoration: line-through;">{{ product.original_price }} ₺</span>
            </div>
            
            <!-- Condition and Location -->
            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem; color: #6b7280; margin-bottom: 16px;">
              <span style="padding: 4px 8px; background: #f3f4f6; border-radius: 6px;">{{ getConditionLabel(product.condition) }}</span>
              <span>📍 {{ product.location }}</span>
            </div>
            
            <!-- Description -->
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 16px; line-height: 1.4;">{{ product.description.substring(0, 100) }}...</p>
            
            <!-- Actions -->
            <div style="display: flex; gap: 8px;">
              <button @click="viewProduct(product)" 
                      style="flex: 1; background: #ea580c; color: white; padding: 8px 16px; border-radius: 6px; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
                Detay Gör
              </button>
              <button @click="removeFavorite(product)" 
                      style="background: #dc2626; color: white; padding: 8px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
                🗑️
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const favorites = ref([])

const getConditionLabel = (condition: string) => {
  const labels = {
    excellent: 'Mükemmel',
    good: 'İyi',
    fair: 'Orta',
    poor: 'Kötü'
  }
  return labels[condition] || condition
}

const getDiscountPercent = (product) => {
  if (!product.original_price || product.original_price <= product.price) return 0
  return Math.round(((product.original_price - product.price) / product.original_price) * 100)
}

const getImageUrl = (imagePath) => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  return `http://localhost:8000/storage/${imagePath}`
}

const viewProduct = (product) => {
  router.push(`/ikinci-el-pazar/urun/${product.id}`)
}

const removeFavorite = async (product) => {
  try {
    await api.post(`/second-hand/${product.id}/favorite`)
    favorites.value = favorites.value.filter(p => p.id !== product.id)
  } catch (error) {
    console.error('Favori kaldırılamadı:', error)
  }
}

onMounted(async () => {
  try {
    const response = await api.get('/second-hand/favorites/my')
    favorites.value = response.data.data
  } catch (error) {
    console.error('Favoriler yüklenemedi:', error)
  }
})
</script>