<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);">
    <!-- Header -->
    <div style="background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px 0;">
      <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; text-align: center; color: #be185d; margin: 0;">
          ✨ Premium Üyelik Planları
        </h1>
        <p style="text-align: center; color: #6b7280; margin-top: 10px; font-size: 1.1rem;">
          KadınAtlası'nın tüm özelliklerinden sınırsız yararlanın
        </p>
      </div>
    </div>

    <!-- Current Membership Status -->
    <div v-if="currentSubscription" style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">
      <div style="background: #dcfce7; border: 2px solid #16a34a; border-radius: 12px; padding: 20px; text-align: center;">
        <h3 style="color: #16a34a; margin: 0 0 10px 0; font-size: 1.3rem;">
          🎉 Aktif Üyeliğiniz: {{ currentSubscription.membershipPlan?.name }}
        </h3>
        <p style="color: #15803d; margin: 0;">
          Kalan süre: {{ remainingDays }} gün
        </p>
        <button 
          @click="cancelSubscription"
          style="background: #dc2626; color: white; border: none; padding: 8px 16px; border-radius: 6px; margin-top: 10px; cursor: pointer;"
        >
          Üyeliği İptal Et
        </button>
      </div>
    </div>

    <!-- Plans Grid -->
    <div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
        <div 
          v-for="plan in plans" 
          :key="plan.id"
          :style="{
            background: 'white',
            borderRadius: '16px',
            padding: '30px',
            boxShadow: plan.slug.includes('premium') ? '0 20px 40px rgba(190, 24, 93, 0.15)' : '0 10px 30px rgba(0,0,0,0.1)',
            border: plan.slug.includes('premium') ? '3px solid #be185d' : '2px solid #e5e7eb',
            position: 'relative',
            transform: plan.slug.includes('premium') ? 'scale(1.05)' : 'scale(1)',
            transition: 'all 0.3s ease'
          }"
        >
          <!-- Popular Badge -->
          <div 
            v-if="plan.slug.includes('premium')"
            style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: #be185d; color: white; padding: 8px 20px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;"
          >
            🔥 En Popüler
          </div>

          <!-- Plan Header -->
          <div style="text-align: center; margin-bottom: 25px;">
            <h3 style="font-size: 1.8rem; font-weight: bold; color: #1f2937; margin: 0 0 10px 0;">
              {{ plan.name }}
            </h3>
            <p style="color: #6b7280; margin: 0 0 20px 0; line-height: 1.5;">
              {{ plan.description }}
            </p>
            <div style="font-size: 3rem; font-weight: bold; color: #be185d; margin-bottom: 5px;">
              ₺{{ plan.price }}
            </div>
            <div style="color: #6b7280; font-size: 0.9rem;">
              {{ plan.duration_days === 30 ? 'Aylık' : plan.duration_days === 90 ? '3 Aylık' : 'Yıllık' }}
            </div>
          </div>

          <!-- Features List -->
          <div style="margin-bottom: 30px;">
            <div 
              v-for="feature in plan.features" 
              :key="feature"
              style="display: flex; align-items: center; margin-bottom: 12px; padding: 8px 0;"
            >
              <span style="color: #16a34a; margin-right: 12px; font-size: 1.2rem;">✓</span>
              <span style="color: #374151; font-size: 0.95rem;">{{ feature }}</span>
            </div>
          </div>

          <!-- Subscribe Button -->
          <button 
            @click="subscribe(plan)"
            :disabled="loading || (currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug)"
            :style="{
              width: '100%',
              padding: '15px',
              borderRadius: '10px',
              border: 'none',
              fontSize: '1.1rem',
              fontWeight: 'bold',
              cursor: loading || (currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug) ? 'not-allowed' : 'pointer',
              background: currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug 
                ? '#9ca3af' 
                : plan.slug.includes('premium') 
                  ? '#be185d' 
                  : '#3b82f6',
              color: 'white',
              opacity: loading || (currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug) ? '0.6' : '1',
              transition: 'all 0.3s ease'
            }"
          >
            <span v-if="loading">Yükleniyor...</span>
            <span v-else-if="currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug">
              Mevcut Planınız
            </span>
            <span v-else>Hemen Başla</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Payment History -->
    <div v-if="paymentHistory.length > 0" style="max-width: 1200px; margin: 50px auto; padding: 0 20px;">
      <h2 style="font-size: 1.8rem; font-weight: bold; color: #1f2937; margin-bottom: 20px; text-align: center;">
        💳 Ödeme Geçmişi
      </h2>
      <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f9fafb;">
              <tr>
                <th style="padding: 15px; text-align: left; font-weight: 600; color: #374151;">Tarih</th>
                <th style="padding: 15px; text-align: left; font-weight: 600; color: #374151;">Plan</th>
                <th style="padding: 15px; text-align: left; font-weight: 600; color: #374151;">Tutar</th>
                <th style="padding: 15px; text-align: left; font-weight: 600; color: #374151;">Durum</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="payment in paymentHistory" :key="payment.id" style="border-top: 1px solid #e5e7eb;">
                <td style="padding: 15px; color: #6b7280;">
                  {{ formatDate(payment.created_at) }}
                </td>
                <td style="padding: 15px; color: #374151; font-weight: 500;">
                  {{ payment.subscription?.membership_plan?.name || 'N/A' }}
                </td>
                <td style="padding: 15px; color: #374151; font-weight: 600;">
                  ₺{{ payment.amount }}
                </td>
                <td style="padding: 15px;">
                  <span :style="{
                    padding: '4px 12px',
                    borderRadius: '20px',
                    fontSize: '0.85rem',
                    fontWeight: '500',
                    background: getStatusColor(payment.status).bg,
                    color: getStatusColor(payment.status).text
                  }">
                    {{ getStatusText(payment.status) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- FAQ Section -->
    <div style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
      <h2 style="font-size: 1.8rem; font-weight: bold; color: #1f2937; margin-bottom: 30px; text-align: center;">
        ❓ Sıkça Sorulan Sorular
      </h2>
      <div style="space-y: 20px;">
        <div v-for="faq in faqs" :key="faq.question" style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 15px;">
          <h3 style="color: #be185d; font-weight: 600; margin: 0 0 10px 0;">{{ faq.question }}</h3>
          <p style="color: #6b7280; margin: 0; line-height: 1.6;">{{ faq.answer }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const authStore = useAuthStore()
const plans = ref([])
const currentSubscription = ref(null)
const paymentHistory = ref([])
const loading = ref(false)
const remainingDays = ref(0)

const faqs = [
  {
    question: "Premium üyelik nasıl çalışır?",
    answer: "Premium üyelik satın aldığınızda, seçtiğiniz planın süresi boyunca tüm premium özelliklerden yararlanabilirsiniz. Otomatik yenileme yoktur, süre dolduğunda manuel olarak yenilemeniz gerekir."
  },
  {
    question: "Üyeliğimi iptal edebilir miyim?",
    answer: "Evet, istediğiniz zaman üyeliğinizi iptal edebilirsiniz. İptal ettiğinizde kalan süreniz boyunca premium özelliklerden yararlanmaya devam edersiniz."
  },
  {
    question: "Ödeme güvenli mi?",
    answer: "Evet, tüm ödemeler İyzico güvenli ödeme sistemi üzerinden gerçekleştirilir. Kredi kartı bilgileriniz şifrelenerek korunur."
  },
  {
    question: "Plan değiştirebilir miyim?",
    answer: "Mevcut planınızın süresi dolduğunda farklı bir plan seçebilirsiniz. Aktif üyelik sırasında plan değişikliği şu anda desteklenmemektedir."
  }
]

const loadPlans = async () => {
  try {
    const response = await api.get('/subscription/plans')
    plans.value = response.data.data
  } catch (error) {
    console.error('Plans yüklenirken hata:', error)
  }
}

const loadCurrentSubscription = async () => {
  if (!authStore.isAuthenticated) return
  
  try {
    const response = await api.get('/subscription/my')
    currentSubscription.value = response.data.data
    remainingDays.value = response.data.remaining_days || 0
  } catch (error) {
    console.error('Mevcut abonelik yüklenirken hata:', error)
  }
}

const loadPaymentHistory = async () => {
  if (!authStore.isAuthenticated) return
  
  try {
    const response = await api.get('/subscription/payment-history')
    paymentHistory.value = response.data.data.data || []
  } catch (error) {
    console.error('Ödeme geçmişi yüklenirken hata:', error)
  }
}

const subscribe = async (plan: any) => {
  if (!authStore.isAuthenticated) {
    authStore.showLoginModal = true
    return
  }

  loading.value = true
  try {
    const response = await api.post('/subscription/subscribe', {
      plan_id: plan.id
    })

    if (response.data.success) {
      // İyzico ödeme sayfasına yönlendir
      window.location.href = response.data.data.payment_page_url
    }
  } catch (error: any) {
    alert(error.response?.data?.message || 'Bir hata oluştu')
  } finally {
    loading.value = false
  }
}

const cancelSubscription = async () => {
  if (!confirm('Üyeliğinizi iptal etmek istediğinizden emin misiniz?')) return

  try {
    const response = await api.post('/subscription/cancel')
    if (response.data.success) {
      alert('Üyeliğiniz başarıyla iptal edildi')
      await loadCurrentSubscription()
    }
  } catch (error: any) {
    alert(error.response?.data?.message || 'Bir hata oluştu')
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('tr-TR')
}

const getStatusColor = (status: string) => {
  const colors = {
    success: { bg: '#dcfce7', text: '#16a34a' },
    failed: { bg: '#fee2e2', text: '#dc2626' },
    pending: { bg: '#fef3c7', text: '#d97706' },
    cancelled: { bg: '#f3f4f6', text: '#6b7280' },
    refunded: { bg: '#dbeafe', text: '#2563eb' }
  }
  return colors[status] || colors.pending
}

const getStatusText = (status: string) => {
  const texts = {
    success: 'Başarılı',
    failed: 'Başarısız',
    pending: 'Beklemede',
    cancelled: 'İptal Edildi',
    refunded: 'İade Edildi'
  }
  return texts[status] || status
}

onMounted(() => {
  loadPlans()
  loadCurrentSubscription()
  loadPaymentHistory()
})
</script>