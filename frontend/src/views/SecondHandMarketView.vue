<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); padding: 32px 0;">
    <div style="max-width: 1152px; margin: 0 auto; padding: 0 16px;">
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #1f2937; margin-bottom: 8px;">🛍️ İkinci El Ürün Pazarı</h1>
        <p style="color: #6b7280;">Kaliteli ikinci el ürünleri keşfedin ve satın</p>
      </div>

      <!-- Filters and Add Button -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
          <select v-model="selectedCategory" @change="filterProducts" 
                  style="padding: 8px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background: white;">
            <option value="">Tüm Kategoriler</option>
            <option v-for="category in categories" :key="category.value" :value="category.value">
              {{ category.label }}
            </option>
          </select>
          <select v-model="selectedCondition" @change="filterProducts"
                  style="padding: 8px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background: white;">
            <option value="">Tüm Durumlar</option>
            <option value="excellent">Mükemmel</option>
            <option value="good">İyi</option>
            <option value="fair">Orta</option>
            <option value="poor">Kötü</option>
          </select>
        </div>
        <div style="display: flex; gap: 12px;">
          <router-link v-if="authStore.isAuthenticated" to="/ikinci-el-pazar/favorilerim" 
                       style="background: #dc2626; color: white; padding: 12px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            ❤️ Favorilerim
          </router-link>
          <button @click="checkLimitsAndShowForm" 
                  :disabled="listingLimits && !listingLimits.can_create"
                  :style="{
                    background: (listingLimits && !listingLimits.can_create) ? '#9ca3af' : '#ea580c',
                    color: 'white',
                    padding: '12px 24px',
                    borderRadius: '8px',
                    fontWeight: '600',
                    border: 'none',
                    cursor: (listingLimits && !listingLimits.can_create) ? 'not-allowed' : 'pointer',
                    transition: 'background 0.2s',
                    opacity: (listingLimits && !listingLimits.can_create) ? 0.6 : 1
                  }">
            {{ (listingLimits && !listingLimits.can_create) ? 'Limit Doldu' : '+ Ürün Ekle' }}
          </button>
        </div>
      </div>

      <!-- Listing Limits Info -->
      <div v-if="listingLimits" style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #0ea5e9; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
          <h3 style="color: #0369a1; font-weight: 600; margin: 0;">
            📊 {{ listingLimits.membership_type }} Üyeliğiniz - İlan Limitleri
          </h3>
          <span style="background: #0ea5e9; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.875rem; font-weight: 500;">
            {{ listingLimits.month }}
          </span>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="flex: 1; background: #e0f2fe; border-radius: 8px; height: 8px; overflow: hidden;">
            <div 
              style="background: #0ea5e9; height: 100%; border-radius: 8px; transition: width 0.3s; max-width: 100%;"
              :style="{ width: listingLimits.limit > 0 ? Math.min(100, (listingLimits.used / listingLimits.limit * 100)) + '%' : '0%' }"
            ></div>
          </div>
          <span style="color: #0369a1; font-weight: 600; font-size: 0.875rem; white-space: nowrap;">
            {{ listingLimits.used }} / {{ listingLimits.limit }} ilan
          </span>
        </div>
        <p style="color: #0369a1; margin: 8px 0 0 0; font-size: 0.875rem;">
          Bu ay {{ listingLimits.remaining }} ilan hakkınız kaldı.
          <router-link v-if="listingLimits.remaining === 0" to="/premium" style="color: #f59e0b; font-weight: 600; text-decoration: none;">
            ⭐ Premium'a geçin
          </router-link>
        </p>
      </div>

      <!-- Stats -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
        <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
          <div style="font-size: 1.5rem; font-weight: bold; color: #ea580c;">{{ filteredProducts.length }}</div>
          <div style="font-size: 0.875rem; color: #6b7280;">Aktif Ürün</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
          <div style="font-size: 1.5rem; font-weight: bold; color: #10b981;">{{ categories.length }}</div>
          <div style="font-size: 0.875rem; color: #6b7280;">Kategori</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
          <div style="font-size: 1.5rem; font-weight: bold; color: #3b82f6;">%{{ averageDiscount }}</div>
          <div style="font-size: 0.875rem; color: #6b7280;">Ortalama İndirim</div>
        </div>
      </div>

      <!-- Products Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
        <div v-for="product in filteredProducts" :key="product.id" 
             style="background: white; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)'">
          
          <!-- Product Image -->
          <div style="height: 200px; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <img v-if="product.images && product.images.length > 0" 
                 :src="getImageUrl(product.images[0])" 
                 style="width: 100%; height: 100%; object-fit: cover;"
                 @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'">
            <div v-else style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
              <span style="font-size: 3rem; color: #9ca3af;">📷</span>
            </div>
            
            <!-- Status Badge -->
            <div v-if="product.status === 'pending'" 
                 style="position: absolute; top: 12px; left: 12px; background: #f59e0b; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">
              ⏳ Onay Bekliyor
            </div>
            
            <div style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.7); color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">
              {{ getCategoryLabel(product.category) }}
            </div>
            <div v-if="getDiscountPercent(product) > 0" 
                 style="position: absolute; top: 12px; left: 12px; background: #dc2626; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">
              -%{{ getDiscountPercent(product) }}
            </div>
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
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 16px; line-height: 1.4;">{{ product.description }}</p>
            
            <!-- Status Info -->
            <div v-if="product.status === 'pending'" style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 8px; margin-bottom: 12px; text-align: center;">
              <span style="color: #92400e; font-size: 0.875rem; font-weight: 600;">⏳ Admin onayı bekleniyor</span>
            </div>
            
            <!-- Seller and Actions -->
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  👤
                </div>
                <span style="font-size: 0.875rem; color: #6b7280;">{{ product.seller_name }}</span>
              </div>
              <div style="display: flex; gap: 8px;">
                <button v-if="product.status === 'active'" @click="toggleFavorite(product)" 
                        :style="{
                          background: 'none',
                          border: 'none',
                          fontSize: '1.25rem',
                          cursor: 'pointer',
                          color: product.is_favorited ? '#dc2626' : '#9ca3af'
                        }">
                  {{ product.is_favorited ? '❤️' : '🤍' }}
                </button>
                <button @click="viewProduct(product)" 
                        :style="{
                          background: product.status === 'pending' ? '#9ca3af' : '#f97316',
                          color: 'white',
                          padding: '8px 16px',
                          borderRadius: '6px',
                          fontSize: '0.875rem',
                          fontWeight: '500',
                          border: 'none',
                          cursor: 'pointer'
                        }">
                  {{ product.status === 'pending' ? 'Beklemede' : 'Detay' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredProducts.length === 0" style="text-align: center; padding: 64px 0;">
        <div style="font-size: 4rem; margin-bottom: 16px;">🛍️</div>
        <h3 style="font-size: 1.25rem; font-weight: bold; color: #1f2937; margin-bottom: 8px;">Henüz Ürün Yok</h3>
        <p style="color: #6b7280; margin-bottom: 24px;">Bu kategoride henüz ürün bulunmuyor. İlk ürünü siz ekleyin!</p>
        <button @click="showAddForm = true" 
                style="background: #ea580c; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
          İlk Ürünü Ekle
        </button>
      </div>

      <!-- Add Product Modal -->
      <div v-if="showAddForm" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50;">
        <div style="background: white; border-radius: 16px; padding: 24px; max-width: 500px; width: 100%; margin: 16px; max-height: 90vh; overflow-y: auto;">
          <h2 style="font-size: 1.25rem; font-weight: bold; color: #1f2937; margin-bottom: 16px;">Ürün Ekle</h2>
          
          <form @submit.prevent="addProduct" style="display: flex; flex-direction: column; gap: 16px;">
            <input v-model="newProduct.title" placeholder="Ürün Başlığı" required 
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px;">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <input v-model.number="newProduct.price" type="number" placeholder="Satış Fiyatı" required 
                     style="padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px;">
              <input v-model.number="newProduct.original_price" type="number" placeholder="Orijinal Fiyat" required 
                     style="padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px;">
            </div>
            
            <select v-model="newProduct.condition" required 
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background: white;">
              <option value="">Durum Seçin</option>
              <option value="excellent">Mükemmel</option>
              <option value="good">İyi</option>
              <option value="fair">Orta</option>
              <option value="poor">Kötü</option>
            </select>
            
            <select v-model="newProduct.category" required 
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background: white;">
              <option value="">Kategori Seçin</option>
              <option v-for="category in categories" :key="category.value" :value="category.value">
                {{ category.label }}
              </option>
            </select>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <input v-model="newProduct.location" placeholder="Şehir" required 
                     style="padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px;">
              <input v-model="newProduct.seller_phone" placeholder="Telefon (İsteğe bağlı)" 
                     style="padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px;">
            </div>
            
            <textarea v-model="newProduct.description" placeholder="Ürün Açıklaması" required 
                      style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; height: 80px; resize: vertical;"></textarea>
            
            <!-- Resim Yükleme -->
            <div>
              <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Ürün Resimleri (En fazla 4 adet)</label>
              <input type="file" @change="handleImageUpload" multiple accept="image/*" 
                     style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background: white;">
              <div v-if="imagePreview.length > 0" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 8px; margin-top: 12px;">
                <div v-for="(image, index) in imagePreview" :key="index" 
                     style="position: relative; border-radius: 8px; overflow: hidden; aspect-ratio: 1;">
                  <img :src="image" style="width: 100%; height: 100%; object-fit: cover;">
                  <div v-if="index === 0" style="position: absolute; top: 4px; left: 4px; background: #ea580c; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">Ana</div>
                  <button @click="removeImage(index)" type="button"
                          style="position: absolute; top: 4px; right: 4px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 0.75rem;">×</button>
                </div>
              </div>
            </div>
            
            <div style="display: flex; gap: 12px;">
              <button type="submit" 
                      style="flex: 1; background: #ea580c; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
                Ürünü Ekle
              </button>
              <button type="button" @click="showAddForm = false" 
                      style="flex: 1; background: #6b7280; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
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
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()

const products = ref([])
const categories = ref([])
const selectedCategory = ref('')
const selectedCondition = ref('')
const showAddForm = ref(false)
const listingLimits = ref(null)

const newProduct = reactive({
  title: '',
  price: null,
  original_price: null,
  condition: '',
  category: '',
  location: '',
  description: '',
  seller_phone: ''
})

const imageFiles = ref([])
const imagePreview = ref([])

const filteredProducts = computed(() => {
  let filtered = products.value
  
  if (selectedCategory.value) {
    filtered = filtered.filter(p => p.category === selectedCategory.value)
  }
  
  if (selectedCondition.value) {
    filtered = filtered.filter(p => p.condition === selectedCondition.value)
  }
  
  return filtered
})

const averageDiscount = computed(() => {
  if (products.value.length === 0) return 0
  const totalDiscount = products.value.reduce((sum, product) => {
    return sum + getDiscountPercent(product)
  }, 0)
  return Math.round(totalDiscount / products.value.length)
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

const filterProducts = () => {
  // Trigger reactivity
}

const toggleFavorite = async (product) => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    const response = await api.post(`/second-hand/${product.id}/favorite`)
    product.is_favorited = response.data.is_favorited
  } catch (error) {
    console.error('Favori işlemi başarısız:', error)
  }
}

const viewProduct = (product) => {
  router.push(`/ikinci-el-pazar/urun/${product.id}`)
}

const getImageUrl = (imagePath) => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  return `http://localhost:8000/storage/${imagePath}`
}

const handleImageUpload = (event) => {
  const files = Array.from(event.target.files)
  if (files.length > 4) {
    alert('En fazla 4 resim yükleyebilirsiniz')
    return
  }
  
  imageFiles.value = files
  imagePreview.value = []
  
  files.forEach(file => {
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value.push(e.target.result)
    }
    reader.readAsDataURL(file)
  })
}

const removeImage = (index) => {
  imageFiles.value.splice(index, 1)
  imagePreview.value.splice(index, 1)
}

const checkLimitsAndShowForm = async () => {
  await loadListingLimits()
  if (listingLimits.value && !listingLimits.value.can_create) {
    alert(`Bu ay ilan verme limitinizi doldurdunuz. ${listingLimits.value.membership_type} üyeliğinizle ayda maksimum ${listingLimits.value.limit} ilan verebilirsiniz.`)
    return
  }
  showAddForm.value = true
}

const loadListingLimits = async () => {
  try {
    const response = await api.get('/second-hand/limits/user')
    listingLimits.value = response.data.data
  } catch (error) {
    console.error('Limit bilgileri yüklenemedi:', error)
  }
}

const addProduct = async () => {
  try {
    const formData = new FormData()
    
    // Ürün bilgilerini ekle
    Object.keys(newProduct).forEach(key => {
      if (newProduct[key] !== null && newProduct[key] !== '') {
        formData.append(key, newProduct[key])
      }
    })
    
    // Resimleri ekle
    imageFiles.value.forEach((file, index) => {
      formData.append(`images[${index}]`, file)
    })
    
    const response = await api.post('/second-hand', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    products.value.unshift(response.data.data)
    showAddForm.value = false
    
    // Formu temizle
    Object.keys(newProduct).forEach(key => {
      if (typeof newProduct[key] === 'string') {
        newProduct[key] = ''
      } else {
        newProduct[key] = null
      }
    })
    imageFiles.value = []
    imagePreview.value = []
    
    // Limitleri yeniden yükle
    await loadListingLimits()
    
    // Ürünleri yeniden yükle
    const productsRes = await api.get('/second-hand')
    products.value = productsRes.data.data
    
    alert('Ürün başarıyla eklendi!')
  } catch (error) {
    console.error('Ürün eklenemedi:', error)
    const errorMessage = error.response?.data?.message || 'Ürün eklenirken bir hata oluştu.'
    alert(errorMessage)
  }
}

onMounted(async () => {
  try {
    // Auth durumunu kontrol et ve gerekirse kullanıcı bilgilerini çek
    if (authStore.isAuthenticated && !authStore.user) {
      await authStore.fetchUser()
    }
    
    const [productsRes, categoriesRes] = await Promise.all([
      api.get('/second-hand'),
      api.get('/second-hand/categories')
    ])
    
    products.value = productsRes.data.data
    categories.value = categoriesRes.data.data
    
    // Load listing limits if user is authenticated
    if (authStore.isAuthenticated) {
      await loadListingLimits()
    }
  } catch (error) {
    console.error('Veriler yüklenemedi:', error)
  }
})
</script>