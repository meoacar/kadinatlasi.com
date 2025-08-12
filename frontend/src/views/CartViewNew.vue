<template>
  <div>
    <!-- Breadcrumb -->
    <nav style="background: white; border-bottom: 1px solid #e5e7eb; padding: 12px 0;">
      <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; color: #6b7280;">
          <router-link to="/" style="color: #e57399; text-decoration: none;">Ana Sayfa</router-link>
          <span>›</span>
          <router-link to="/shop" style="color: #e57399; text-decoration: none;">Mağaza</router-link>
          <span>›</span>
          <span style="color: #111827; font-weight: 500;">Sepetim</span>
        </div>
      </div>
    </nav>

    <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%);">
      <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        
        <!-- Header -->
        <header style="text-align: center; margin-bottom: 48px;">
          <h1 style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 16px;">
            🛒 Sepetim
          </h1>
          <p style="font-size: 1.25rem; color: #6b7280; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Seçtiğin ürünleri gözden geçir ve siparişini tamamla
          </p>
        </header>

        <!-- Cart Content -->
        <div style="display: grid; lg:grid-template-columns: 2fr 1fr; gap: 32px;">
          
          <!-- Cart Items -->
          <div>
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #f3f4f6;">
              
              <!-- Cart Header -->
              <div style="padding: 24px; border-bottom: 1px solid #f3f4f6; background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 100%);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827;">
                    Sepetindeki Ürünler ({{ cartItems.length }})
                  </h2>
                  <button v-if="cartItems.length > 0" @click="clearCart"
                          style="color: #dc2626; font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer;">
                    Sepeti Temizle
                  </button>
                </div>
              </div>

              <!-- Empty Cart -->
              <div v-if="cartItems.length === 0" style="padding: 60px; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 16px;">🛒</div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Sepetiniz boş</h3>
                <p style="color: #6b7280; margin-bottom: 24px;">Alışverişe başlamak için mağazamızı keşfedin</p>
                <router-link to="/shop"
                            style="display: inline-block; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 12px 24px; border-radius: 20px; text-decoration: none; font-weight: 600;">
                  Alışverişe Başla
                </router-link>
              </div>

              <!-- Cart Items List -->
              <div v-else>
                <div v-for="item in cartItems" :key="item.id" 
                     style="padding: 24px; border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                     @mouseover="$event.currentTarget.style.backgroundColor = '#fafafa'"
                     @mouseleave="$event.currentTarget.style.backgroundColor = 'white'">
                  
                  <div style="display: flex; gap: 20px;">
                    <!-- Product Image -->
                    <div style="flex-shrink: 0;">
                      <img :src="item.image" :alt="item.name" 
                           style="width: 100px; height: 100px; object-cover; border-radius: 12px; border: 1px solid #e5e7eb;">
                    </div>
                    
                    <!-- Product Info -->
                    <div style="flex: 1;">
                      <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">
                        {{ item.name }}
                      </h3>
                      
                      <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 12px;">
                        {{ item.description }}
                      </p>
                      
                      <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                        <span style="color: #9ca3af; font-size: 0.875rem;">Kategori:</span>
                        <span style="background: #f3f4f6; color: #6b7280; padding: 4px 8px; border-radius: 8px; font-size: 0.75rem;">
                          {{ item.category }}
                        </span>
                      </div>
                      
                      <!-- Quantity & Price -->
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                          <span style="color: #6b7280; font-size: 0.875rem;">Adet:</span>
                          <div style="display: flex; align-items: center; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <button @click="decreaseQuantity(item.id)"
                                    style="padding: 8px 12px; background: none; border: none; cursor: pointer; color: #6b7280;">
                              -
                            </button>
                            <span style="padding: 8px 16px; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; font-weight: 500;">
                              {{ item.quantity }}
                            </span>
                            <button @click="increaseQuantity(item.id)"
                                    style="padding: 8px 12px; background: none; border: none; cursor: pointer; color: #6b7280;">
                              +
                            </button>
                          </div>
                        </div>
                        
                        <div style="text-align: right;">
                          <div style="font-size: 1.25rem; font-weight: 700; color: #e57399;">
                            {{ formatPrice(item.price * item.quantity) }} ₺
                          </div>
                          <div v-if="item.originalPrice && item.originalPrice > item.price" 
                               style="font-size: 0.875rem; color: #9ca3af; text-decoration: line-through;">
                            {{ formatPrice(item.originalPrice * item.quantity) }} ₺
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Remove Button -->
                    <div style="flex-shrink: 0;">
                      <button @click="removeFromCart(item.id)"
                              style="padding: 8px; background: #fee2e2; color: #dc2626; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.2s;"
                              @mouseover="$event.target.style.backgroundColor = '#fecaca'"
                              @mouseleave="$event.target.style.backgroundColor = '#fee2e2'">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div v-if="cartItems.length > 0">
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 24px; border: 1px solid #f3f4f6; position: sticky; top: 24px;">
              
              <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 20px;">
                Sipariş Özeti
              </h3>
              
              <!-- Summary Items -->
              <div style="space-y: 12px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                  <span style="color: #6b7280;">Ara Toplam ({{ totalItems }} ürün)</span>
                  <span style="font-weight: 500;">{{ formatPrice(subtotal) }} ₺</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                  <span style="color: #6b7280;">Kargo</span>
                  <span style="font-weight: 500; color: #10b981;">
                    {{ subtotal >= 200 ? 'Ücretsiz' : formatPrice(shippingCost) + ' ₺' }}
                  </span>
                </div>
                
                <div v-if="discount > 0" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                  <span style="color: #6b7280;">İndirim</span>
                  <span style="font-weight: 500; color: #10b981;">-{{ formatPrice(discount) }} ₺</span>
                </div>
                
                <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 12px;">
                  <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 1.125rem; font-weight: 700; color: #111827;">Toplam</span>
                    <span style="font-size: 1.5rem; font-weight: 800; color: #e57399;">{{ formatPrice(total) }} ₺</span>
                  </div>
                </div>
              </div>
              
              <!-- Free Shipping Info -->
              <div v-if="subtotal < 200" style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 12px; padding: 12px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <svg style="width: 16px; height: 16px; color: #f59e0b;" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span style="font-size: 0.875rem; color: #92400e;">
                    {{ formatPrice(200 - subtotal) }} ₺ daha alışveriş yapın, kargo ücretsiz olsun!
                  </span>
                </div>
              </div>
              
              <!-- Checkout Button -->
              <button @click="goToCheckout"
                      style="width: 100%; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 16px; border-radius: 12px; border: none; cursor: pointer; font-weight: 700; font-size: 1rem; transition: transform 0.2s; margin-bottom: 12px;"
                      @mouseover="$event.target.style.transform = 'translateY(-2px)'"
                      @mouseleave="$event.target.style.transform = 'translateY(0)'">
                Siparişi Tamamla
              </button>
              
              <button @click="$router.push('/shop')"
                      style="width: 100%; background: white; color: #e57399; padding: 12px; border: 2px solid #e57399; border-radius: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s;"
                      @mouseover="$event.target.style.background = '#e57399'; $event.target.style.color = 'white'"
                      @mouseleave="$event.target.style.background = 'white'; $event.target.style.color = '#e57399'">
                Alışverişe Devam Et
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Sample cart data
const cartItems = ref([
  {
    id: 1,
    name: 'Organik Cilt Bakım Seti',
    description: 'Doğal malzemelerle hazırlanmış 3\'lü cilt bakım seti',
    price: 149.90,
    originalPrice: 199.90,
    quantity: 1,
    image: 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=200',
    category: 'Güzellik & Bakım'
  },
  {
    id: 2,
    name: 'Hamile Yoga Matı',
    description: 'Hamilelik dönemine özel tasarlanmış yoga matı',
    price: 89.90,
    originalPrice: null,
    quantity: 2,
    image: 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=200',
    category: 'Spor & Fitness'
  },
  {
    id: 3,
    name: 'Bebek Bakım Çantası',
    description: 'Çok bölmeli, pratik bebek bakım çantası',
    price: 199.90,
    originalPrice: 249.90,
    quantity: 1,
    image: 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=200',
    category: 'Anne & Bebek'
  }
])

const shippingCost = 29.90
const discount = 0

// Computed values
const subtotal = computed(() => {
  return cartItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

const totalItems = computed(() => {
  return cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
})

const total = computed(() => {
  const shipping = subtotal.value >= 200 ? 0 : shippingCost
  return subtotal.value + shipping - discount
})

// Methods
const formatPrice = (price: number) => {
  return price.toFixed(2).replace('.', ',')
}

const increaseQuantity = (itemId: number) => {
  const item = cartItems.value.find(item => item.id === itemId)
  if (item) {
    item.quantity++
  }
}

const decreaseQuantity = (itemId: number) => {
  const item = cartItems.value.find(item => item.id === itemId)
  if (item && item.quantity > 1) {
    item.quantity--
  }
}

const removeFromCart = (itemId: number) => {
  const index = cartItems.value.findIndex(item => item.id === itemId)
  if (index > -1) {
    cartItems.value.splice(index, 1)
  }
}

const clearCart = () => {
  if (confirm('Sepetinizdeki tüm ürünleri silmek istediğinizden emin misiniz?')) {
    cartItems.value = []
  }
}

const goToCheckout = () => {
  router.push('/checkout')
}
</script>