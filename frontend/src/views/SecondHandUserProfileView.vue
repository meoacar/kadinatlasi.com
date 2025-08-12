<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); padding: 32px 0;">
    <div style="max-width: 1152px; margin: 0 auto; padding: 0 16px;">
      
      <!-- Back Button -->
      <button @click="$router.go(-1)" 
              style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; color: #ea580c; font-weight: 600; background: none; border: none; cursor: pointer;">
        ← Geri Dön
      </button>

      <div v-if="userProfile">
        <!-- User Info -->
        <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 32px;">
          <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
            <div style="width: 80px; height: 80px; background: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
              {{ userProfile.user.name.charAt(0).toUpperCase() }}
            </div>
            <div>
              <h1 style="font-size: 1.5rem; font-weight: bold; color: #1f2937; margin: 0 0 8px 0;">{{ userProfile.user.name }}</h1>
              <div style="color: #6b7280; margin-bottom: 8px;">
                Üyelik: {{ formatDate(userProfile.user.created_at) }}
              </div>
              <div v-if="userProfile.average_rating > 0" style="display: flex; align-items: center; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 4px;">
                  ⭐ {{ userProfile.average_rating }} 
                </div>
                <span style="color: #6b7280;">({{ userProfile.total_reviews }} değerlendirme)</span>
              </div>
            </div>
          </div>

          <!-- Stats -->
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 16px; background: #f9fafb; border-radius: 8px;">
            <div style="text-align: center;">
              <div style="font-size: 1.5rem; font-weight: bold; color: #ea580c;">{{ userProfile.total_products }}</div>
              <div style="font-size: 0.875rem; color: #6b7280;">Aktif İlan</div>
            </div>
            <div style="text-align: center;">
              <div style="font-size: 1.5rem; font-weight: bold; color: #10b981;">{{ userProfile.total_reviews }}</div>
              <div style="font-size: 0.875rem; color: #6b7280;">Değerlendirme</div>
            </div>
            <div style="text-align: center;">
              <div style="font-size: 1.5rem; font-weight: bold; color: #3b82f6;">{{ userProfile.average_rating.toFixed(1) }}</div>
              <div style="font-size: 0.875rem; color: #6b7280;">Ortalama Puan</div>
            </div>
          </div>
        </div>

        <!-- Tabs -->
        <div style="background: white; border-radius: 16px; overflow: hidden;">
          <div style="display: flex; border-bottom: 1px solid #e5e7eb;">
            <button @click="activeTab = 'products'" 
                    :style="{
                      flex: 1,
                      padding: '16px',
                      background: activeTab === 'products' ? '#ea580c' : 'transparent',
                      color: activeTab === 'products' ? 'white' : '#6b7280',
                      border: 'none',
                      cursor: 'pointer',
                      fontWeight: '600'
                    }">
              İlanları ({{ userProfile.products.length }})
            </button>
            <button @click="activeTab = 'reviews'" 
                    :style="{
                      flex: 1,
                      padding: '16px',
                      background: activeTab === 'reviews' ? '#ea580c' : 'transparent',
                      color: activeTab === 'reviews' ? 'white' : '#6b7280',
                      border: 'none',
                      cursor: 'pointer',
                      fontWeight: '600'
                    }">
              Değerlendirmeler ({{ userProfile.reviews.length }})
            </button>
          </div>

          <div style="padding: 24px;">
            <!-- Products Tab -->
            <div v-if="activeTab === 'products'">
              <div v-if="userProfile.products.length === 0" style="text-align: center; padding: 64px; color: #6b7280;">
                <div style="font-size: 3rem; margin-bottom: 16px;">📦</div>
                <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 8px;">Henüz İlan Yok</h3>
                <p>Bu kullanıcının aktif ilanı bulunmuyor.</p>
              </div>

              <div v-else style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <div v-for="product in userProfile.products" :key="product.id" 
                     @click="viewProduct(product.id)"
                     style="border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                     @mouseover="$event.currentTarget.style.transform = 'translateY(-2px)'; $event.currentTarget.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)'"
                     @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = 'none'">
                  
                  <!-- Product Image -->
                  <div style="height: 180px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; position: relative;">
                    <img v-if="product.images && product.images.length > 0" 
                         :src="getImageUrl(product.images[0])" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                    <span v-else style="font-size: 2rem; color: #9ca3af;">📷</span>
                    
                    <div v-if="getDiscountPercent(product) > 0" 
                         style="position: absolute; top: 8px; left: 8px; background: #dc2626; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">
                      -%{{ getDiscountPercent(product) }}
                    </div>
                  </div>
                  
                  <!-- Product Info -->
                  <div style="padding: 16px;">
                    <h3 style="font-weight: bold; color: #1f2937; margin-bottom: 8px; font-size: 1rem;">{{ product.title }}</h3>
                    
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                      <span style="font-size: 1.25rem; font-weight: bold; color: #ea580c;">{{ product.price }} ₺</span>
                      <span v-if="product.original_price > product.price" 
                            style="font-size: 0.75rem; color: #6b7280; text-decoration: line-through;">{{ product.original_price }} ₺</span>
                    </div>
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #6b7280;">
                      <span>{{ getConditionLabel(product.condition) }}</span>
                      <span>📍 {{ product.location }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Reviews Tab -->
            <div v-if="activeTab === 'reviews'">
              <div v-if="userProfile.reviews.length === 0" style="text-align: center; padding: 64px; color: #6b7280;">
                <div style="font-size: 3rem; margin-bottom: 16px;">⭐</div>
                <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 8px;">Henüz Değerlendirme Yok</h3>
                <p>Bu kullanıcı henüz değerlendirme almamış.</p>
              </div>

              <div v-else style="display: flex; flex-direction: column; gap: 16px;">
                <div v-for="review in userProfile.reviews" :key="review.id" 
                     style="padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px;">
                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                      <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        {{ review.reviewer.name.charAt(0).toUpperCase() }}
                      </div>
                      <div>
                        <div style="font-weight: 600; color: #1f2937;">{{ review.reviewer.name }}</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">{{ formatDate(review.created_at) }}</div>
                      </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px;">
                      <span v-for="i in 5" :key="i" :style="{ color: i <= review.rating ? '#fbbf24' : '#e5e7eb' }">⭐</span>
                    </div>
                  </div>
                  
                  <div style="margin-bottom: 8px;">
                    <span style="font-size: 0.875rem; color: #6b7280;">Ürün: </span>
                    <span style="font-weight: 600; color: #1f2937;">{{ review.product.title }}</span>
                  </div>
                  
                  <p v-if="review.comment" style="color: #6b7280; margin: 0; font-size: 0.875rem;">{{ review.comment }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-else style="text-align: center; padding: 64px;">
        <div style="font-size: 2rem; margin-bottom: 16px;">⏳</div>
        <p style="color: #6b7280;">Profil yükleniyor...</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()

const userProfile = ref(null)
const activeTab = ref('products')

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

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('tr-TR')
}

const viewProduct = (productId) => {
  router.push(`/ikinci-el-pazar/urun/${productId}`)
}

onMounted(async () => {
  try {
    const response = await api.get(`/second-hand/users/${route.params.id}`)
    userProfile.value = response.data.data
  } catch (error) {
    console.error('Profil yüklenemedi:', error)
    router.push('/ikinci-el-pazar')
  }
})
</script>