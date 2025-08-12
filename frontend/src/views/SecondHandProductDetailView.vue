<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); padding: 32px 0;">
    <div style="max-width: 1152px; margin: 0 auto; padding: 0 16px;">
      
      <!-- Back Button -->
      <button @click="$router.go(-1)" 
              style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; color: #ea580c; font-weight: 600; background: none; border: none; cursor: pointer;">
        ← Geri Dön
      </button>

      <div v-if="product" style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
        
        <!-- Product Images -->
        <div>
          <div style="background: white; border-radius: 16px; overflow: hidden; margin-bottom: 16px;">
            <div style="height: 400px; position: relative;">
              <img v-if="product.images && product.images.length > 0" 
                   :src="getImageUrl(product.images[currentImageIndex])" 
                   style="width: 100%; height: 100%; object-fit: cover;">
              <div v-else style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background: #f3f4f6;">
                <span style="font-size: 4rem; color: #9ca3af;">📷</span>
              </div>
              
              <!-- Image Navigation -->
              <div v-if="product.images && product.images.length > 1" 
                   style="position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px;">
                <button v-for="(image, index) in product.images" :key="index"
                        @click="currentImageIndex = index"
                        :style="{
                          width: '12px',
                          height: '12px',
                          borderRadius: '50%',
                          border: 'none',
                          background: index === currentImageIndex ? '#ea580c' : 'rgba(255,255,255,0.5)',
                          cursor: 'pointer'
                        }">
                </button>
              </div>
            </div>
          </div>
          
          <!-- Thumbnail Images -->
          <div v-if="product.images && product.images.length > 1" 
               style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
            <div v-for="(image, index) in product.images" :key="index"
                 @click="currentImageIndex = index"
                 :style="{
                   height: '80px',
                   borderRadius: '8px',
                   overflow: 'hidden',
                   cursor: 'pointer',
                   border: index === currentImageIndex ? '2px solid #ea580c' : '2px solid transparent'
                 }">
              <img :src="getImageUrl(image)" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div style="background: white; border-radius: 16px; padding: 24px;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h1 style="font-size: 1.5rem; font-weight: bold; color: #1f2937; margin: 0;">{{ product.title }}</h1>
            <button @click="toggleFavorite" 
                    :style="{
                      background: 'none',
                      border: 'none',
                      fontSize: '1.5rem',
                      cursor: 'pointer',
                      color: product.is_favorited ? '#dc2626' : '#9ca3af'
                    }">
              {{ product.is_favorited ? '❤️' : '🤍' }}
            </button>
          </div>

          <!-- Price -->
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <span style="font-size: 2rem; font-weight: bold; color: #ea580c;">{{ product.price }} ₺</span>
            <span v-if="product.original_price > product.price" 
                  style="font-size: 1rem; color: #6b7280; text-decoration: line-through;">{{ product.original_price }} ₺</span>
            <span v-if="getDiscountPercent(product) > 0" 
                  style="background: #dc2626; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">
              -%{{ getDiscountPercent(product) }}
            </span>
          </div>

          <!-- Product Details -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; padding: 16px; background: #f9fafb; border-radius: 8px;">
            <div>
              <span style="font-size: 0.875rem; color: #6b7280;">Durum:</span>
              <div style="font-weight: 600; color: #1f2937;">{{ getConditionLabel(product.condition) }}</div>
            </div>
            <div>
              <span style="font-size: 0.875rem; color: #6b7280;">Kategori:</span>
              <div style="font-weight: 600; color: #1f2937;">{{ getCategoryLabel(product.category) }}</div>
            </div>
            <div>
              <span style="font-size: 0.875rem; color: #6b7280;">Konum:</span>
              <div style="font-weight: 600; color: #1f2937;">📍 {{ product.location }}</div>
            </div>
            <div>
              <span style="font-size: 0.875rem; color: #6b7280;">İlan Tarihi:</span>
              <div style="font-weight: 600; color: #1f2937;">{{ formatDate(product.created_at) }}</div>
            </div>
          </div>

          <!-- Description -->
          <div style="margin-bottom: 24px;">
            <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 8px;">Açıklama</h3>
            <p style="color: #6b7280; line-height: 1.6;">{{ product.description }}</p>
          </div>

          <!-- Seller Info -->
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #f9fafb; border-radius: 8px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <div style="width: 48px; height: 48px; background: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                {{ product.seller_name.charAt(0).toUpperCase() }}
              </div>
              <div>
                <div style="font-weight: 600; color: #1f2937;">{{ product.seller_name }}</div>
                <div v-if="product.average_rating > 0" style="display: flex; align-items: center; gap: 4px; font-size: 0.875rem; color: #6b7280;">
                  ⭐ {{ product.average_rating.toFixed(1) }} ({{ product.review_count }} değerlendirme)
                </div>
              </div>
            </div>
            <button @click="viewSellerProfile" 
                    style="background: #f3f4f6; color: #374151; padding: 8px 16px; border-radius: 6px; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
              Profili Gör
            </button>
          </div>

          <!-- Action Buttons -->
          <div style="display: flex; gap: 12px;">
            <button @click="showMessageModal = true" 
                    style="flex: 1; background: #10b981; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
              💬 Mesaj Gönder
            </button>
            <button @click="shareProduct" 
                    style="background: #6b7280; color: white; padding: 12px 16px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
              📤
            </button>
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
          <h2 style="font-size: 1.25rem; font-weight: bold; color: #1f2937; margin: 0;">
            Değerlendirmeler ({{ reviews.length }})
          </h2>
          <button v-if="canReview" @click="showReviewModal = true" 
                  style="background: #ea580c; color: white; padding: 8px 16px; border-radius: 6px; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
            Değerlendir
          </button>
        </div>

        <div v-if="reviews.length === 0" style="text-align: center; padding: 32px; color: #6b7280;">
          Henüz değerlendirme yok. İlk değerlendirmeyi siz yapın!
        </div>

        <div v-else style="display: flex; flex-direction: column; gap: 16px;">
          <div v-for="review in reviews" :key="review.id" 
               style="padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  {{ review.reviewer.name.charAt(0).toUpperCase() }}
                </div>
                <span style="font-weight: 600; color: #1f2937;">{{ review.reviewer.name }}</span>
              </div>
              <div style="display: flex; align-items: center; gap: 4px;">
                <span v-for="i in 5" :key="i" :style="{ color: i <= review.rating ? '#fbbf24' : '#e5e7eb' }">⭐</span>
              </div>
            </div>
            <p v-if="review.comment" style="color: #6b7280; margin: 0;">{{ review.comment }}</p>
          </div>
        </div>
      </div>

      <!-- Message Modal -->
      <div v-if="showMessageModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50;">
        <div style="background: white; border-radius: 16px; padding: 24px; max-width: 500px; width: 100%; margin: 16px; max-height: 80vh; overflow-y: auto;">
          <h2 style="font-size: 1.25rem; font-weight: bold; color: #1f2937; margin-bottom: 16px;">Satıcıya Mesaj Gönder</h2>
          
          <!-- Messages -->
          <div style="max-height: 300px; overflow-y: auto; margin-bottom: 16px; padding: 16px; background: #f9fafb; border-radius: 8px;">
            <div v-if="messages.length === 0" style="text-align: center; color: #6b7280; padding: 32px;">
              Henüz mesaj yok. İlk mesajı siz gönderin!
            </div>
            <div v-else style="display: flex; flex-direction: column; gap: 12px;">
              <div v-for="message in messages" :key="message.id" 
                   :style="{
                     alignSelf: message.sender_id === $store.auth.user?.id ? 'flex-end' : 'flex-start',
                     maxWidth: '70%'
                   }">
                <div :style="{
                       background: message.sender_id === $store.auth.user?.id ? '#ea580c' : '#e5e7eb',
                       color: message.sender_id === $store.auth.user?.id ? 'white' : '#1f2937',
                       padding: '8px 12px',
                       borderRadius: '12px',
                       fontSize: '0.875rem'
                     }">
                  {{ message.message }}
                </div>
                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 4px;">
                  {{ message.sender.name }} - {{ formatDate(message.created_at) }}
                </div>
              </div>
            </div>
          </div>
          
          <!-- Send Message -->
          <form @submit.prevent="sendMessage" style="display: flex; flex-direction: column; gap: 12px;">
            <textarea v-model="newMessage" placeholder="Mesajınızı yazın..." required 
                      style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; height: 80px; resize: vertical;"></textarea>
            <div style="display: flex; gap: 12px;">
              <button type="submit" 
                      style="flex: 1; background: #ea580c; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
                Gönder
              </button>
              <button type="button" @click="showMessageModal = false" 
                      style="background: #6b7280; color: white; padding: 12px 16px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
                İptal
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Review Modal -->
      <div v-if="showReviewModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50;">
        <div style="background: white; border-radius: 16px; padding: 24px; max-width: 400px; width: 100%; margin: 16px;">
          <h2 style="font-size: 1.25rem; font-weight: bold; color: #1f2937; margin-bottom: 16px;">Ürünü Değerlendir</h2>
          
          <form @submit.prevent="submitReview" style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Rating -->
            <div>
              <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Puan</label>
              <div style="display: flex; gap: 4px;">
                <button v-for="i in 5" :key="i" type="button"
                        @click="newReview.rating = i"
                        :style="{
                          background: 'none',
                          border: 'none',
                          fontSize: '1.5rem',
                          cursor: 'pointer',
                          color: i <= newReview.rating ? '#fbbf24' : '#e5e7eb'
                        }">
                  ⭐
                </button>
              </div>
            </div>
            
            <!-- Comment -->
            <div>
              <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Yorum (İsteğe bağlı)</label>
              <textarea v-model="newReview.comment" placeholder="Deneyiminizi paylaşın..." 
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; height: 80px; resize: vertical;"></textarea>
            </div>
            
            <div style="display: flex; gap: 12px;">
              <button type="submit" 
                      style="flex: 1; background: #ea580c; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
                Değerlendir
              </button>
              <button type="button" @click="showReviewModal = false" 
                      style="background: #6b7280; color: white; padding: 12px 16px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
                İptal
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const product = ref(null)
const reviews = ref([])
const messages = ref([])
const categories = ref([])
const currentImageIndex = ref(0)
const showMessageModal = ref(false)
const showReviewModal = ref(false)
const newMessage = ref('')

const newReview = reactive({
  rating: 5,
  comment: ''
})

const canReview = computed(() => {
  return authStore.isAuthenticated && 
         product.value && 
         authStore.user?.id !== product.value.user_id
})

const getConditionLabel = (condition: string) => {
  const labels = {
    excellent: 'Mükemmel',
    good: 'İyi', 
    fair: 'Orta',
    poor: 'Kötü'
  }
  return labels[condition] || condition
}

const getCategoryLabel = (category: string) => {
  const found = categories.value.find(c => c.value === category)
  return found ? found.label : category
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

const toggleFavorite = async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    const response = await api.post(`/second-hand/${product.value.id}/favorite`)
    product.value.is_favorited = response.data.is_favorited
  } catch (error) {
    console.error('Favori işlemi başarısız:', error)
  }
}

const sendMessage = async () => {
  if (!newMessage.value.trim()) return

  try {
    const response = await api.post(`/second-hand/${product.value.id}/messages`, {
      message: newMessage.value
    })
    
    messages.value.push(response.data.data)
    newMessage.value = ''
  } catch (error) {
    console.error('Mesaj gönderilemedi:', error)
    alert('Mesaj gönderilemedi')
  }
}

const submitReview = async () => {
  try {
    const response = await api.post(`/second-hand/${product.value.id}/review`, newReview)
    reviews.value.unshift(response.data.data)
    showReviewModal.value = false
    
    // Reset form
    newReview.rating = 5
    newReview.comment = ''
    
    alert('Değerlendirmeniz kaydedildi!')
  } catch (error) {
    console.error('Değerlendirme kaydedilemedi:', error)
    alert(error.response?.data?.message || 'Değerlendirme kaydedilemedi')
  }
}

const viewSellerProfile = () => {
  router.push(`/ikinci-el-pazar/kullanici/${product.value.user_id}`)
}

const shareProduct = () => {
  if (navigator.share) {
    navigator.share({
      title: product.value.title,
      text: product.value.description,
      url: window.location.href
    })
  } else {
    navigator.clipboard.writeText(window.location.href)
    alert('Link kopyalandı!')
  }
}

const loadMessages = async () => {
  if (!authStore.isAuthenticated) return
  
  try {
    const response = await api.get(`/second-hand/${route.params.id}/messages`)
    messages.value = response.data.data
  } catch (error) {
    console.error('Mesajlar yüklenemedi:', error)
  }
}

onMounted(async () => {
  try {
    const [productRes, reviewsRes, categoriesRes] = await Promise.all([
      api.get(`/second-hand/${route.params.id}`),
      api.get(`/second-hand/${route.params.id}/reviews`),
      api.get('/second-hand/categories')
    ])
    
    product.value = productRes.data.data
    reviews.value = reviewsRes.data.data
    categories.value = categoriesRes.data.data
    
    await loadMessages()
  } catch (error) {
    console.error('Veriler yüklenemedi:', error)
    router.push('/ikinci-el-pazar')
  }
})
</script>