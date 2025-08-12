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
          <router-link to="/cart" style="color: #e57399; text-decoration: none;">Sepet</router-link>
          <span>›</span>
          <span style="color: #111827; font-weight: 500;">Ödeme</span>
        </div>
      </div>
    </nav>

    <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%);">
      <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        
        <!-- Header -->
        <header style="text-align: center; margin-bottom: 48px;">
          <h1 style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 16px;">
            💳 Ödeme
          </h1>
          <p style="font-size: 1.25rem; color: #6b7280; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Siparişinizi tamamlamak için bilgilerinizi girin
          </p>
        </header>

        <!-- Progress Steps -->
        <div style="margin-bottom: 48px;">
          <div style="display: flex; justify-content: center; align-items: center; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <div style="width: 32px; height: 32px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem;">
                ✓
              </div>
              <span style="font-weight: 500; color: #10b981;">Sepet</span>
            </div>
            <div style="width: 40px; height: 2px; background: #10b981;"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem;">
                2
              </div>
              <span style="font-weight: 500; color: #e57399;">Ödeme</span>
            </div>
            <div style="width: 40px; height: 2px; background: #e5e7eb;"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <div style="width: 32px; height: 32px; background: #e5e7eb; color: #9ca3af; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem;">
                3
              </div>
              <span style="font-weight: 500; color: #9ca3af;">Tamamlandı</span>
            </div>
          </div>
        </div>

        <!-- Checkout Content -->
        <div style="display: grid; lg:grid-template-columns: 2fr 1fr; gap: 32px;">
          
          <!-- Checkout Form -->
          <div>
            <form @submit.prevent="processPayment">
              
              <!-- Billing Information -->
              <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 24px; border: 1px solid #f3f4f6;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                  📋 Fatura Bilgileri
                </h2>
                
                <div style="display: grid; md:grid-template-columns: 1fr 1fr; gap: 20px;">
                  <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Ad *</label>
                    <input v-model="billingInfo.firstName" type="text" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                           @focus="$event.target.style.borderColor = '#e57399'"
                           @blur="$event.target.style.borderColor = '#e5e7eb'">
                  </div>
                  <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Soyad *</label>
                    <input v-model="billingInfo.lastName" type="text" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                           @focus="$event.target.style.borderColor = '#e57399'"
                           @blur="$event.target.style.borderColor = '#e5e7eb'">
                  </div>
                </div>
                
                <div style="margin-top: 20px;">
                  <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">E-posta *</label>
                  <input v-model="billingInfo.email" type="email" required
                         style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                         @focus="$event.target.style.borderColor = '#e57399'"
                         @blur="$event.target.style.borderColor = '#e5e7eb'">
                </div>
                
                <div style="margin-top: 20px;">
                  <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Telefon *</label>
                  <input v-model="billingInfo.phone" type="tel" required
                         style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                         @focus="$event.target.style.borderColor = '#e57399'"
                         @blur="$event.target.style.borderColor = '#e5e7eb'">
                </div>
                
                <div style="margin-top: 20px;">
                  <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Adres *</label>
                  <textarea v-model="billingInfo.address" required rows="3"
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s; resize: vertical;"
                            @focus="$event.target.style.borderColor = '#e57399'"
                            @blur="$event.target.style.borderColor = '#e5e7eb'"></textarea>
                </div>
                
                <div style="display: grid; md:grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                  <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Şehir *</label>
                    <input v-model="billingInfo.city" type="text" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                           @focus="$event.target.style.borderColor = '#e57399'"
                           @blur="$event.target.style.borderColor = '#e5e7eb'">
                  </div>
                  <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Posta Kodu *</label>
                    <input v-model="billingInfo.zipCode" type="text" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                           @focus="$event.target.style.borderColor = '#e57399'"
                           @blur="$event.target.style.borderColor = '#e5e7eb'">
                  </div>
                </div>
              </div>

              <!-- Payment Method -->
              <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 24px; border: 1px solid #f3f4f6;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                  💳 Ödeme Yöntemi
                </h2>
                
                <div style="display: grid; gap: 16px;">
                  <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.2s;"
                         :style="paymentMethod === 'card' ? 'border-color: #e57399; background: #fdf2f8;' : ''"
                         @click="paymentMethod = 'card'">
                    <input type="radio" v-model="paymentMethod" value="card" style="margin: 0;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                      <svg style="width: 24px; height: 24px; color: #e57399;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                      </svg>
                      <span style="font-weight: 600;">Kredi/Banka Kartı</span>
                    </div>
                  </label>
                  
                  <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.2s;"
                         :style="paymentMethod === 'transfer' ? 'border-color: #e57399; background: #fdf2f8;' : ''"
                         @click="paymentMethod = 'transfer'">
                    <input type="radio" v-model="paymentMethod" value="transfer" style="margin: 0;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                      <svg style="width: 24px; height: 24px; color: #e57399;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                      </svg>
                      <span style="font-weight: 600;">Havale/EFT</span>
                    </div>
                  </label>
                </div>

                <!-- Card Details -->
                <div v-if="paymentMethod === 'card'" style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                  <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Kart Numarası *</label>
                    <input v-model="cardInfo.number" type="text" placeholder="1234 5678 9012 3456" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                           @focus="$event.target.style.borderColor = '#e57399'"
                           @blur="$event.target.style.borderColor = '#e5e7eb'">
                  </div>
                  
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                      <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Son Kullanma *</label>
                      <input v-model="cardInfo.expiry" type="text" placeholder="MM/YY" required
                             style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                             @focus="$event.target.style.borderColor = '#e57399'"
                             @blur="$event.target.style.borderColor = '#e5e7eb'">
                    </div>
                    <div>
                      <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">CVV *</label>
                      <input v-model="cardInfo.cvv" type="text" placeholder="123" required
                             style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                             @focus="$event.target.style.borderColor = '#e57399'"
                             @blur="$event.target.style.borderColor = '#e5e7eb'">
                    </div>
                  </div>
                  
                  <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Kart Üzerindeki İsim *</label>
                    <input v-model="cardInfo.name" type="text" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: border-color 0.2s;"
                           @focus="$event.target.style.borderColor = '#e57399'"
                           @blur="$event.target.style.borderColor = '#e5e7eb'">
                  </div>
                </div>
              </div>

              <!-- Terms -->
              <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 24px; border: 1px solid #f3f4f6;">
                <label style="display: flex; align-items: start; gap: 12px; cursor: pointer;">
                  <input type="checkbox" v-model="acceptTerms" required style="margin-top: 4px;">
                  <span style="color: #6b7280; line-height: 1.6;">
                    <a href="#" style="color: #e57399; text-decoration: none;">Kullanım Şartları</a> ve 
                    <a href="#" style="color: #e57399; text-decoration: none;">Gizlilik Politikası</a>'nı okudum ve kabul ediyorum.
                  </span>
                </label>
              </div>
            </form>
          </div>

          <!-- Order Summary -->
          <div>
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 24px; border: 1px solid #f3f4f6; position: sticky; top: 24px;">
              
              <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 20px;">
                Sipariş Özeti
              </h3>
              
              <!-- Order Items -->
              <div style="margin-bottom: 20px;">
                <div v-for="item in orderItems" :key="item.id" 
                     style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                  <img :src="item.image" :alt="item.name" 
                       style="width: 60px; height: 60px; object-cover; border-radius: 8px;">
                  <div style="flex: 1;">
                    <h4 style="font-weight: 600; color: #111827; font-size: 0.875rem; margin-bottom: 4px;">
                      {{ item.name }}
                    </h4>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                      <span style="color: #6b7280; font-size: 0.875rem;">{{ item.quantity }}x</span>
                      <span style="font-weight: 600; color: #e57399;">{{ formatPrice(item.price * item.quantity) }} ₺</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Summary Totals -->
              <div style="space-y: 12px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                  <span style="color: #6b7280;">Ara Toplam</span>
                  <span style="font-weight: 500;">{{ formatPrice(subtotal) }} ₺</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                  <span style="color: #6b7280;">Kargo</span>
                  <span style="font-weight: 500; color: #10b981;">
                    {{ subtotal >= 200 ? 'Ücretsiz' : formatPrice(shippingCost) + ' ₺' }}
                  </span>
                </div>
                
                <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 12px;">
                  <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 1.125rem; font-weight: 700; color: #111827;">Toplam</span>
                    <span style="font-size: 1.5rem; font-weight: 800; color: #e57399;">{{ formatPrice(total) }} ₺</span>
                  </div>
                </div>
              </div>
              
              <!-- Payment Button -->
              <button @click="processPayment" :disabled="!acceptTerms || processing"
                      style="width: 100%; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 16px; border-radius: 12px; border: none; cursor: pointer; font-weight: 700; font-size: 1rem; transition: all 0.2s; margin-bottom: 12px;"
                      :style="(!acceptTerms || processing) ? 'opacity: 0.5; cursor: not-allowed;' : ''"
                      @mouseover="!processing && ($event.target.style.transform = 'translateY(-2px)')"
                      @mouseleave="$event.target.style.transform = 'translateY(0)'">
                {{ processing ? 'İşleniyor...' : 'Ödemeyi Tamamla' }}
              </button>
              
              <!-- Security Info -->
              <div style="display: flex; align-items: center; justify-content: center; gap: 8px; color: #6b7280; font-size: 0.875rem;">
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9L10,17Z"/>
                </svg>
                <span>256-bit SSL ile güvenli ödeme</span>
              </div>
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

// Form data
const billingInfo = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  zipCode: ''
})

const cardInfo = ref({
  number: '',
  expiry: '',
  cvv: '',
  name: ''
})

const paymentMethod = ref('card')
const acceptTerms = ref(false)
const processing = ref(false)

// Order data
const orderItems = ref([
  {
    id: 1,
    name: 'Organik Cilt Bakım Seti',
    price: 149.90,
    quantity: 1,
    image: 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=100'
  },
  {
    id: 2,
    name: 'Hamile Yoga Matı',
    price: 89.90,
    quantity: 2,
    image: 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=100'
  },
  {
    id: 3,
    name: 'Bebek Bakım Çantası',
    price: 199.90,
    quantity: 1,
    image: 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=100'
  }
])

const shippingCost = 29.90

// Computed values
const subtotal = computed(() => {
  return orderItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

const total = computed(() => {
  const shipping = subtotal.value >= 200 ? 0 : shippingCost
  return subtotal.value + shipping
})

// Methods
const formatPrice = (price: number) => {
  return price.toFixed(2).replace('.', ',')
}

const processPayment = async () => {
  if (!acceptTerms.value) return
  
  processing.value = true
  
  try {
    const orderData = {
      billing_info: {
        first_name: billingInfo.value.firstName,
        last_name: billingInfo.value.lastName,
        email: billingInfo.value.email,
        phone: billingInfo.value.phone,
        address: billingInfo.value.address,
        city: billingInfo.value.city,
        zip_code: billingInfo.value.zipCode
      },
      items: orderItems.value,
      payment_method: paymentMethod.value,
      subtotal: subtotal.value,
      shipping_cost: subtotal.value >= 200 ? 0 : shippingCost,
      total: total.value
    }
    
    const response = await fetch('/api/orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      },
      body: JSON.stringify(orderData)
    })
    
    const result = await response.json()
    
    if (result.success) {
      alert(`Sipariş başarıyla oluşturuldu! Sipariş No: ${result.data.order_number}`)
      router.push('/dashboard')
    } else {
      throw new Error(result.message || 'Sipariş oluşturulamadı')
    }
    
  } catch (error) {
    console.error('Sipariş hatası:', error)
    alert('Sipariş oluşturulurken bir hata oluştu. Lütfen tekrar deneyin.')
  } finally {
    processing.value = false
  }
}
</script>