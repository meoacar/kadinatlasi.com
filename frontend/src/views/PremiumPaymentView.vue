<template>
  <div class="payment-page">
    <div class="container">
      <div class="payment-card">
        <div class="payment-header">
          <h1>💳 Ödeme Sayfası</h1>
          <p>Premium üyeliğinizi tamamlamak için ödeme bilgilerinizi girin</p>
        </div>

        <div v-if="loading" class="loading-state">
          <div class="spinner"></div>
          <p>Yükleniyor...</p>
        </div>

        <div v-else class="payment-content">
          <!-- Order Summary -->
          <div class="order-summary">
            <h3>Sipariş Özeti</h3>
            <div class="plan-info">
              <div class="plan-name">{{ planName }} Plan</div>
              <div class="plan-price">₺{{ planPrice }}</div>
            </div>
            <div class="total">
              <span>Toplam:</span>
              <span class="total-price">₺{{ planPrice }}</span>
            </div>
          </div>

          <!-- Payment Form -->
          <form @submit.prevent="processPayment" class="payment-form">
            <h3>Ödeme Bilgileri</h3>
            
            <div class="form-group">
              <label>Kart Numarası</label>
              <input 
                v-model="paymentForm.cardNumber" 
                type="text" 
                placeholder="1234 5678 9012 3456"
                maxlength="19"
                @input="formatCardNumber"
                required
              >
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Son Kullanma</label>
                <input 
                  v-model="paymentForm.expiryDate" 
                  type="text" 
                  placeholder="MM/YY"
                  maxlength="5"
                  @input="formatExpiryDate"
                  required
                >
              </div>
              <div class="form-group">
                <label>CVV</label>
                <input 
                  v-model="paymentForm.cvv" 
                  type="text" 
                  placeholder="123"
                  maxlength="3"
                  required
                >
              </div>
            </div>
            
            <div class="form-group">
              <label>Kart Sahibi Adı</label>
              <input 
                v-model="paymentForm.cardHolder" 
                type="text" 
                placeholder="Ad Soyad"
                required
              >
            </div>
            
            <button 
              type="submit" 
              class="pay-button"
              :disabled="processing"
            >
              {{ processing ? 'İşleniyor...' : `₺${planPrice} Öde` }}
            </button>
          </form>
        </div>
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

const loading = ref(true)
const processing = ref(false)
const planName = ref('')
const planPrice = ref(0)

const paymentForm = ref({
  cardNumber: '',
  expiryDate: '',
  cvv: '',
  cardHolder: ''
})

const formatCardNumber = (event: Event) => {
  const input = event.target as HTMLInputElement
  let value = input.value.replace(/\s/g, '').replace(/[^0-9]/gi, '')
  const formattedValue = value.match(/.{1,4}/g)?.join(' ') || value
  paymentForm.value.cardNumber = formattedValue
}

const formatExpiryDate = (event: Event) => {
  const input = event.target as HTMLInputElement
  let value = input.value.replace(/\D/g, '')
  if (value.length >= 2) {
    value = value.substring(0, 2) + '/' + value.substring(2, 4)
  }
  paymentForm.value.expiryDate = value
}

const loadSubscriptionDetails = async () => {
  try {
    const subscriptionId = route.params.id
    // For demo, we'll use plan mapping
    const planMapping = {
      '1': { name: 'Temel', price: 29 },
      '2': { name: 'Premium', price: 59 },
      '3': { name: 'VIP', price: 99 }
    }
    
    // Simulate loading subscription details
    const plan = planMapping[subscriptionId as string] || { name: 'Premium', price: 59 }
    planName.value = plan.name
    planPrice.value = plan.price
  } catch (error) {
    console.error('Error loading subscription:', error)
  } finally {
    loading.value = false
  }
}

const processPayment = async () => {
  processing.value = true
  
  try {
    // Simulate payment processing
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    const subscriptionId = route.params.id
    const response = await api.post(`/premium/complete-payment/${subscriptionId}`)
    
    if (response.data.success) {
      alert('Ödeme başarılı! Premium üyeliğiniz aktif edildi.')
      router.push('/profile')
    }
  } catch (error) {
    console.error('Payment error:', error)
    alert('Ödeme işlemi sırasında bir hata oluştu!')
  } finally {
    processing.value = false
  }
}

onMounted(() => {
  loadSubscriptionDetails()
})
</script>

<style scoped>
.payment-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%);
  padding: 40px 20px;
}

.container {
  max-width: 600px;
  margin: 0 auto;
}

.payment-card {
  background: white;
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.payment-header {
  text-align: center;
  margin-bottom: 40px;
}

.payment-header h1 {
  font-size: 2rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 8px;
}

.payment-header p {
  color: #6b7280;
}

.order-summary {
  background: #f9fafb;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 32px;
}

.order-summary h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 16px;
}

.plan-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.plan-name {
  font-weight: 600;
  color: #374151;
}

.plan-price {
  font-weight: 700;
  color: #ec4899;
}

.total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 16px;
  border-top: 1px solid #e5e7eb;
  font-weight: 600;
}

.total-price {
  font-size: 1.25rem;
  color: #ec4899;
}

.payment-form h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #374151;
}

.form-group input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.form-group input:focus {
  outline: none;
  border-color: #ec4899;
}

.pay-button {
  width: 100%;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  border: none;
  padding: 16px 32px;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 24px;
}

.pay-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.pay-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.loading-state {
  text-align: center;
  padding: 48px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f4f6;
  border-top: 4px solid #ec4899;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 16px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>