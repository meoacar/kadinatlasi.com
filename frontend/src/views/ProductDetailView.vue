<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); padding: 32px 0;">
    <div style="max-width: 1152px; margin: 0 auto; padding: 0 16px;">
      
      <!-- Back Button -->
      <button @click="$router.go(-1)" 
              style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; color: #ec4899; font-weight: 600; background: none; border: none; cursor: pointer;">
        ← Geri Dön
      </button>

      <div v-if="loading" style="display: flex; justify-content: center; align-items: center; height: 400px;">
        <div style="width: 48px; height: 48px; border: 4px solid #f3f4f6; border-top: 4px solid #ec4899; border-radius: 50%; animation: spin 1s linear infinite;"></div>
      </div>

      <div v-else-if="product" style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
        
        <!-- Product Images -->
        <div>
          <div style="background: white; border-radius: 16px; overflow: hidden; margin-bottom: 16px;">
            <div style="height: 400px; position: relative;">
              <img :src="productImage" :alt="product.name"
                   style="width: 100%; height: 100%; object-fit: cover;">
              
              <!-- Discount Badge -->
              <div v-if="product.sale_price" 
                   style="position: absolute; top: 16px; left: 16px; background: #dc2626; color: white; padding: 8px 12px; border-radius: 8px; font-size: 0.875rem; font-weight: bold;">
                %{{ Math.round((1 - product.sale_price / product.price) * 100) }} İndirim
              </div>
            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div style="background: white; border-radius: 16px; padding: 24px;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h1 style="font-size: 1.5rem; font-weight: bold; color: #1f2937; margin: 0;">{{ product.name }}</h1>
            <button style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #9ca3af;">
              🤍
            </button>
          </div>

          <!-- Price -->
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <span style="font-size: 2rem; font-weight: bold; color: #ec4899;">
              {{ formatPrice(product.final_price || product.price) }}
            </span>
            <span v-if="product.sale_price" 
                  style="font-size: 1rem; color: #6b7280; text-decoration: line-through;">
              {{ formatPrice(product.price) }}
            </span>
          </div>

          <!-- Rating -->
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <div style="display: flex; color: #fbbf24;">
              <span v-for="i in 5" :key="i">⭐</span>
            </div>
            <span style="font-size: 0.875rem; color: #6b7280;">(4.8) • 127 değerlendirme</span>
          </div>

          <!-- Brand & SKU -->
          <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
            <span v-if="product.brand" style="background: #f3f4f6; color: #6b7280; padding: 4px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 500;">
              {{ product.brand }}
            </span>
            <span v-if="product.sku" style="color: #6b7280; font-size: 0.875rem;">
              SKU: {{ product.sku }}
            </span>
          </div>

          <!-- Description -->
          <div v-if="product.description || product.short_description" style="margin-bottom: 24px;">
            <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 8px;">Açıklama</h3>
            <p v-if="product.short_description" style="color: #6b7280; line-height: 1.6; margin-bottom: 12px; font-weight: 500;">{{ product.short_description }}</p>
            <div v-if="product.description" style="color: #6b7280; line-height: 1.6;" v-html="product.description"></div>
          </div>

          <!-- Size Selection -->
          <div v-if="hasVariants" style="margin-bottom: 24px;">
            <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 12px;">Beden Seçin</h3>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
              <button v-for="variant in product.active_variants" :key="variant.id"
                      @click="selectedVariant = variant"
                      :style="{
                        padding: '12px',
                        border: selectedVariant?.id === variant.id ? '2px solid #ec4899' : '2px solid #e5e7eb',
                        borderRadius: '8px',
                        background: selectedVariant?.id === variant.id ? '#fdf2f8' : 'white',
                        color: selectedVariant?.id === variant.id ? '#ec4899' : '#374151',
                        fontWeight: '600',
                        cursor: 'pointer'
                      }">
                {{ variant.size }}
              </button>
            </div>
          </div>

          <!-- Quantity & Stock -->
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding: 16px; background: #f9fafb; border-radius: 8px;">
            <div>
              <span style="font-size: 0.875rem; color: #6b7280;">Miktar:</span>
              <div style="display: flex; align-items: center; gap: 12px; margin-top: 8px;">
                <button @click="quantity > 1 && quantity--"
                        style="width: 36px; height: 36px; border: 2px solid #e5e7eb; border-radius: 6px; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                  -
                </button>
                <span style="font-weight: 600; min-width: 40px; text-align: center;">{{ quantity }}</span>
                <button @click="quantity < 10 && quantity++"
                        style="width: 36px; height: 36px; border: 2px solid #e5e7eb; border-radius: 6px; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                  +
                </button>
              </div>
            </div>
            <div style="text-align: right;">
              <span style="font-size: 0.875rem; color: #6b7280;">Stok:</span>
              <div style="font-weight: 600; color: #10b981; margin-top: 4px;">{{ stockInfo }}</div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
            <button @click="addToCart"
                    style="width: 100%; background: #ec4899; color: white; padding: 16px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; font-size: 1rem;">
              Sepete Ekle - {{ formatPrice((product.final_price || product.price) * quantity) }}
            </button>
            <button @click="buyNow"
                    style="width: 100%; background: #1f2937; color: white; padding: 16px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; font-size: 1rem;">
              Hemen Satın Al
            </button>
          </div>

          <!-- Features -->
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 16px; background: #f9fafb; border-radius: 8px;">
            <div style="text-align: center;">
              <div style="font-size: 1.5rem; margin-bottom: 4px;">🚚</div>
              <div style="font-size: 0.75rem; font-weight: 600; color: #1f2937;">Ücretsiz Kargo</div>
              <div style="font-size: 0.75rem; color: #6b7280;">500₺ üzeri</div>
            </div>
            <div style="text-align: center;">
              <div style="font-size: 1.5rem; margin-bottom: 4px;">🔒</div>
              <div style="font-size: 0.75rem; font-weight: 600; color: #1f2937;">Güvenli Ödeme</div>
              <div style="font-size: 0.75rem; color: #6b7280;">SSL korumalı</div>
            </div>
            <div style="text-align: center;">
              <div style="font-size: 1.5rem; margin-bottom: 4px;">↩️</div>
              <div style="font-size: 0.75rem; font-weight: 600; color: #1f2937;">30 Gün İade</div>
              <div style="font-size: 0.75rem; color: #6b7280;">Koşulsuz</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Product Details -->
      <div v-if="product" style="background: white; border-radius: 16px; padding: 24px;">
        <h2 style="font-size: 1.25rem; font-weight: bold; color: #1f2937; margin-bottom: 16px;">
          Ürün Detayları
        </h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
          <div>
            <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 12px;">Ürün Özellikleri</h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <div v-if="product.brand" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Marka:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ product.brand }}</span>
              </div>
              <div v-if="product.sku" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Ürün Kodu:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ product.sku }}</span>
              </div>
              <div v-if="product.material" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Malzeme:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ product.material }}</span>
              </div>
              <div v-if="product.color" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Ana Renk:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ product.color }}</span>
              </div>
              <div v-if="product.pattern" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Desen:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ getPatternLabel(product.pattern) }}</span>
              </div>
              <div v-if="product.fit_type" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Kesim:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ getFitTypeLabel(product.fit_type) }}</span>
              </div>
              <div v-if="product.gender" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Cinsiyet:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ getGenderLabel(product.gender) }}</span>
              </div>
              <div v-if="product.age_group" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Yaş Grubu:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ getAgeGroupLabel(product.age_group) }}</span>
              </div>
              <div v-if="product.season" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Mevsim:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ getSeasonLabel(product.season) }}</span>
              </div>
              <div v-if="product.occasion" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="color: #6b7280;">Kullanım:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ getOccasionLabel(product.occasion) }}</span>
              </div>
              <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                <span style="color: #6b7280;">Kategori:</span>
                <span style="font-weight: 600; color: #1f2937;">{{ product.category?.name || 'Genel' }}</span>
              </div>
            </div>
          </div>
          
          <div>
            <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 16px;">Bakım & Malzeme</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div v-if="product.care_instructions_text">
                <h4 style="font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Bakım Talimatları:</h4>
                <p style="font-size: 0.875rem; color: #6b7280; line-height: 1.5;">{{ product.care_instructions_text }}</p>
              </div>
              
              <div v-if="product.ingredients_text">
                <h4 style="font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 8px;">İçerik:</h4>
                <p style="font-size: 0.875rem; color: #6b7280; line-height: 1.5;">{{ product.ingredients_text }}</p>
              </div>
              
              <div v-if="product.tags_text">
                <h4 style="font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Etiketler:</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                  <span v-for="tag in product.tags_text.split(',')"
                        :key="tag.trim()"
                        style="background: #f3f4f6; color: #6b7280; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem;">
                    {{ tag.trim() }}
                  </span>
                </div>
              </div>
              
              <div style="margin-top: 16px;">
                <h4 style="font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Kargo & İade:</h4>
                <div style="display: flex; flex-direction: column; gap: 4px; font-size: 0.875rem; color: #6b7280;">
                  <p>• 500₺ ve üzeri alışverişlerde ücretsiz kargo</p>
                  <p>• 1-3 iş günü içinde kargoya verilir</p>
                  <p>• 30 gün içinde koşulsuz iade hakkı</p>
                  <p>• Değişim için kargo ücretsiz</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="!loading" style="text-align: center; padding: 64px 0;">
        <div style="font-size: 4rem; margin-bottom: 16px;">😕</div>
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #1f2937; margin-bottom: 8px;">Ürün Bulunamadı</h2>
        <p style="color: #6b7280; margin-bottom: 24px;">Aradığınız ürün mevcut değil veya kaldırılmış olabilir.</p>
        <button @click="$router.push('/shop')"
                style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
          Mağazaya Dön
        </button>
      </div>

      <!-- Toast -->
      <div v-if="showToast" 
           style="position: fixed; top: 24px; right: 24px; background: #10b981; color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 50; display: flex; align-items: center; gap: 8px;">
        <span>✅</span>
        {{ toastMessage }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const product = ref(null)
const selectedVariant = ref(null)
const quantity = ref(1)
const loading = ref(true)
const showToast = ref(false)
const toastMessage = ref('')

const productImage = computed(() => {
  return product.value?.image_urls?.[0] || 'https://via.placeholder.com/400x400/f3f4f6/9ca3af?text=Resim+Yok'
})

const hasVariants = computed(() => product.value?.active_variants?.length > 0)

const stockInfo = computed(() => {
  if (selectedVariant.value) {
    return selectedVariant.value.stock_quantity > 0 ? `${selectedVariant.value.stock_quantity} adet` : 'Tükendi'
  }
  if (hasVariants.value) {
    const total = product.value.active_variants.reduce((sum, v) => sum + v.stock_quantity, 0)
    return total > 0 ? `${total} adet` : 'Tükendi'
  }
  return product.value?.stock_quantity > 0 ? `${product.value.stock_quantity} adet` : 'Stokta var'
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(price)
}

const fetchProduct = async () => {
  try {
    const response = await api.get(`/products/${route.params.id}`)
    product.value = response.data
    if (hasVariants.value) {
      selectedVariant.value = product.value.active_variants[0]
    }
  } catch (error) {
    product.value = null
  } finally {
    loading.value = false
  }
}

const addToCart = async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    const payload = { product_id: product.value.id, quantity: quantity.value }
    if (selectedVariant.value) payload.variant_id = selectedVariant.value.id

    await api.post('/cart/items', payload)
    showToast.value = true
    toastMessage.value = 'Ürün sepete eklendi!'
    setTimeout(() => showToast.value = false, 3000)
  } catch (error) {
    console.error(error)
  }
}

const buyNow = async () => {
  await addToCart()
  router.push('/cart')
}

// Label helper functions
const getPatternLabel = (pattern) => {
  const patterns = {
    'düz': 'Düz',
    'çizgili': 'Çizgili',
    'kareli': 'Kareli',
    'çiçekli': 'Çiçekli',
    'desenli': 'Desenli',
    'noktalı': 'Noktalı'
  }
  return patterns[pattern] || pattern
}

const getFitTypeLabel = (fitType) => {
  const fitTypes = {
    'slim': 'Slim Fit',
    'regular': 'Regular Fit',
    'oversized': 'Oversized',
    'loose': 'Bol Kesim'
  }
  return fitTypes[fitType] || fitType
}

const getGenderLabel = (gender) => {
  const genders = {
    'kadın': 'Kadın',
    'erkek': 'Erkek',
    'unisex': 'Unisex',
    'çocuk-kız': 'Çocuk (Kız)',
    'çocuk-erkek': 'Çocuk (Erkek)',
    'bebek-kız': 'Bebek (Kız)',
    'bebek-erkek': 'Bebek (Erkek)',
    'bebek-unisex': 'Bebek (Unisex)'
  }
  return genders[gender] || gender
}

const getAgeGroupLabel = (ageGroup) => {
  const ageGroups = {
    'bebek': 'Bebek (0-2 yaş)',
    'çocuk': 'Çocuk (3-12 yaş)',
    'genç': 'Genç (13-17 yaş)',
    'yetişkin': 'Yetişkin (18+ yaş)'
  }
  return ageGroups[ageGroup] || ageGroup
}

const getSeasonLabel = (season) => {
  const seasons = {
    'yaz': 'Yaz',
    'kış': 'Kış',
    'sonbahar': 'Sonbahar',
    'ilkbahar': 'İlkbahar',
    'dört-mevsim': 'Dört Mevsim'
  }
  return seasons[season] || season
}

const getOccasionLabel = (occasion) => {
  const occasions = {
    'günlük': 'Günlük',
    'özel': 'Özel Günler',
    'spor': 'Spor',
    'iş': 'İş/Ofis',
    'gece': 'Gece'
  }
  return occasions[occasion] || occasion
}

onMounted(fetchProduct)
</script>

<style>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>