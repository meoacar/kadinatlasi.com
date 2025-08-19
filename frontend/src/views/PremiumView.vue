<template>
  <div class="premium-page">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-background">
        <div class="floating-elements">
          <div class="floating-element element-1">👑</div>
          <div class="floating-element element-2">✨</div>
          <div class="floating-element element-3">💎</div>
          <div class="floating-element element-4">🌟</div>
          <div class="floating-element element-5">💫</div>
        </div>
      </div>
      
      <div class="hero-content">
        <div class="hero-badge">
          <span class="badge-icon">🔥</span>
          <span>Premium Deneyimi</span>
        </div>
        
        <h1 class="hero-title">
          Hayalinizdeki Yaşama 
          <span class="gradient-text">Premium</span> ile Başlayın
        </h1>
        
        <p class="hero-description">
          KadınAtlası'nın tüm premium özelliklerinden yararlanın, reklamsız deneyim yaşayın ve uzman desteği alın
        </p>
        
        <div class="hero-stats">
          <div class="stat-item">
            <div class="stat-number">10K+</div>
            <div class="stat-label">Mutlu Premium Üye</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">99%</div>
            <div class="stat-label">Memnuniyet Oranı</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">7/24</div>
            <div class="stat-label">Uzman Desteği</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Current Membership Status -->
    <section v-if="currentSubscription" class="current-membership">
      <div class="container">
        <div class="membership-card">
          <div class="membership-badge">
            <span class="crown">👑</span>
            <span class="premium-label">Premium Aktif</span>
          </div>
          
          <div class="membership-info">
            <h3 class="plan-name">{{ currentSubscription.membershipPlan?.name }}</h3>
            <div class="remaining-time">
              <span class="time-icon">⏰</span>
              <span>{{ remainingDays }} gün kaldı</span>
            </div>
          </div>
          
          <div class="membership-actions">
            <button class="extend-btn">
              <span>🚀</span>
              Uzat
            </button>
            <button @click="cancelSubscription" class="cancel-btn">
              <span>❌</span>
              İptal Et
            </button>
          </div>
          
          <div class="membership-glow"></div>
        </div>
      </div>
    </section>

    <!-- Plans Section -->
    <section class="plans-section">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">💎 Premium Planları</span>
          <h2 class="section-title">Size En Uygun Planı Seçin</h2>
          <p class="section-subtitle">
            Her bütçeye uygun esnek planlarımızla premium deneyimine başlayın
          </p>
        </div>
        
        <div class="plans-grid">
          <div 
            v-for="(plan, index) in plans" 
            :key="plan.id"
            class="plan-card"
            :class="{
              'featured': plan.slug.includes('premium'),
              'plan-1': index === 0,
              'plan-2': index === 1,
              'plan-3': index === 2
            }"
          >
            <!-- Popular Badge -->
            <div v-if="plan.slug.includes('premium')" class="popular-badge">
              <span class="fire-icon">🔥</span>
              <span>En Popüler</span>
            </div>

            <!-- Plan Icon -->
            <div class="plan-icon">
              <span v-if="plan.duration_days === 30">📅</span>
              <span v-else-if="plan.duration_days === 90">🗓️</span>
              <span v-else>📆</span>
            </div>

            <!-- Plan Header -->
            <div class="plan-header">
              <h3 class="plan-name">{{ plan.name }}</h3>
              <p class="plan-description">{{ plan.description }}</p>
              
              <div class="price-container">
                <div class="price">₺{{ plan.price }}</div>
                <div class="duration">
                  {{ plan.duration_days === 30 ? 'Aylık' : plan.duration_days === 90 ? '3 Aylık' : 'Yıllık' }}
                </div>
              </div>
            </div>

            <!-- Features List -->
            <div class="features-list">
              <div 
                v-for="feature in plan.features" 
                :key="feature"
                class="feature-item"
              >
                <span class="check-icon">✅</span>
                <span class="feature-text">{{ feature }}</span>
              </div>
            </div>

            <!-- Subscribe Button -->
            <button 
              @click="subscribe(plan)"
              :disabled="loading || (currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug)"
              class="subscribe-btn"
              :class="{
                'current-plan': currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug,
                'premium-btn': plan.slug.includes('premium'),
                'loading': loading
              }"
            >
              <span v-if="loading" class="loading-spinner">⏳</span>
              <span v-else-if="currentSubscription && currentSubscription.membershipPlan?.slug === plan.slug">
                <span class="crown-small">👑</span>
                Mevcut Planınız
              </span>
              <span v-else>
                <span class="rocket">🚀</span>
                Hemen Başla
              </span>
              
              <div class="button-glow"></div>
            </button>
            
            <!-- Card Background Effect -->
            <div class="card-bg-effect"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Payment History -->
    <section v-if="paymentHistory.length > 0" class="payment-history">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">💳 Ödeme Geçmişi</span>
          <h2 class="section-title">İşlem Geçmişiniz</h2>
          <p class="section-subtitle">Tüm premium ödemelerinizi burada görüntüleyebilirsiniz</p>
        </div>
        
        <div class="payment-table-container">
          <div class="payment-table">
            <div class="table-header">
              <div class="header-cell">📅 Tarih</div>
              <div class="header-cell">📋 Plan</div>
              <div class="header-cell">💰 Tutar</div>
              <div class="header-cell">📊 Durum</div>
            </div>
            
            <div class="table-body">
              <div v-for="payment in paymentHistory" :key="payment.id" class="table-row">
                <div class="table-cell">
                  <span class="date">{{ formatDate(payment.created_at) }}</span>
                </div>
                <div class="table-cell">
                  <span class="plan-name">{{ payment.subscription?.membership_plan?.name || 'N/A' }}</span>
                </div>
                <div class="table-cell">
                  <span class="amount">₺{{ payment.amount }}</span>
                </div>
                <div class="table-cell">
                  <span 
                    class="status-badge"
                    :class="payment.status"
                  >
                    <span class="status-icon">{{ getStatusIcon(payment.status) }}</span>
                    {{ getStatusText(payment.status) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">❓ Sıkça Sorulan Sorular</span>
          <h2 class="section-title">Merak Ettikleriniz</h2>
          <p class="section-subtitle">Premium üyelik hakkında en çok sorulan sorular ve cevapları</p>
        </div>
        
        <div class="faq-grid">
          <div 
            v-for="(faq, index) in faqs" 
            :key="faq.question" 
            class="faq-card"
            :class="`faq-${index + 1}`"
            @click="toggleFaq(index)"
          >
            <div class="faq-header">
              <span class="faq-icon">🤔</span>
              <h3 class="faq-question">{{ faq.question }}</h3>
              <span class="toggle-icon" :class="{ active: openFaq === index }">
                {{ openFaq === index ? '−' : '+' }}
              </span>
            </div>
            
            <div class="faq-content" :class="{ open: openFaq === index }">
              <p class="faq-answer">{{ faq.answer }}</p>
            </div>
            
            <div class="faq-glow"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-content">
          <div class="cta-badge">
            <span class="badge-icon">✨</span>
            <span>Hemen Başlayın</span>
          </div>
          
          <h2 class="cta-title">
            Premium Dünyasına Adım Atın
          </h2>
          
          <p class="cta-description">
            Binlerce kadının güvendiği platform ile hayalinizdeki yaşama bugün başlayın
          </p>
          
          <div class="cta-buttons">
            <button @click="scrollToPlans" class="cta-primary-btn">
              <span class="btn-icon">🚀</span>
              <span class="btn-text">Planları Görüntüle</span>
              <div class="btn-shine"></div>
            </button>
            
            <button class="cta-secondary-btn">
              <span class="btn-icon">💬</span>
              <span class="btn-text">Destek Al</span>
            </button>
          </div>
        </div>
        
        <div class="cta-decoration">
          <div class="decoration-element element-1">💎</div>
          <div class="decoration-element element-2">🌟</div>
          <div class="decoration-element element-3">✨</div>
        </div>
      </div>
    </section>
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
const openFaq = ref(-1)

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

const getStatusIcon = (status: string) => {
  const icons = {
    success: '✅',
    failed: '❌',
    pending: '⏳',
    cancelled: '🚫',
    refunded: '↩️'
  }
  return icons[status] || '❓'
}

const toggleFaq = (index: number) => {
  openFaq.value = openFaq.value === index ? -1 : index
}

const scrollToPlans = () => {
  const plansSection = document.querySelector('.plans-section')
  if (plansSection) {
    plansSection.scrollIntoView({ behavior: 'smooth' })
  }
}

onMounted(() => {
  loadPlans()
  loadCurrentSubscription()
  loadPaymentHistory()
})
</script>

<style scoped>
/* Ultra Modern Premium Page Styles */
.premium-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
}

/* Container */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

/* Hero Section */
.hero-section {
  position: relative;
  min-height: 80vh;
  display: flex;
  align-items: center;
  background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 50%, #3b82f6 100%);
  overflow: hidden;
}

.hero-background {
  position: absolute;
  inset: 0;
  pointer-events: none;
}

.floating-elements {
  position: relative;
  width: 100%;
  height: 100%;
}

.floating-element {
  position: absolute;
  font-size: 3rem;
  animation: heroFloat 8s ease-in-out infinite;
  opacity: 0.7;
}

.element-1 {
  top: 10%;
  left: 10%;
  animation-delay: 0s;
}

.element-2 {
  top: 20%;
  right: 15%;
  animation-delay: 1.5s;
}

.element-3 {
  bottom: 30%;
  left: 20%;
  animation-delay: 3s;
}

.element-4 {
  bottom: 20%;
  right: 25%;
  animation-delay: 4.5s;
}

.element-5 {
  top: 50%;
  left: 50%;
  animation-delay: 6s;
}

.hero-content {
  position: relative;
  z-index: 2;
  text-align: center;
  color: white;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(20px);
  padding: 12px 24px;
  border-radius: 50px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  margin-bottom: 32px;
  font-weight: 600;
}

.badge-icon {
  font-size: 1.2rem;
}

.hero-title {
  font-size: 4rem;
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 24px;
  text-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.gradient-text {
  background: linear-gradient(135deg, #fbbf24, #f59e0b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-description {
  font-size: 1.3rem;
  margin-bottom: 48px;
  opacity: 0.95;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}

.hero-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 32px;
  max-width: 600px;
  margin: 0 auto;
}

.stat-item {
  text-align: center;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 900;
  margin-bottom: 8px;
  background: linear-gradient(135deg, #fbbf24, #f59e0b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stat-label {
  font-size: 0.9rem;
  opacity: 0.9;
  font-weight: 500;
}

/* Current Membership */
.current-membership {
  padding: 60px 0;
  background: white;
}

.membership-card {
  position: relative;
  background: linear-gradient(135deg, #fdf2f8, #f3e8ff);
  border: 2px solid #ec4899;
  border-radius: 24px;
  padding: 40px;
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 32px;
  align-items: center;
  overflow: hidden;
}

.membership-badge {
  display: flex;
  align-items: center;
  gap: 12px;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  padding: 16px 24px;
  border-radius: 16px;
  font-weight: 700;
}

.crown {
  font-size: 1.5rem;
}

.membership-info {
  text-align: center;
}

.plan-name {
  font-size: 1.8rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 12px;
}

.remaining-time {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: #6b7280;
  font-weight: 600;
}

.membership-actions {
  display: flex;
  gap: 12px;
}

.extend-btn, .cancel-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.extend-btn {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
}

.extend-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

.cancel-btn {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
}

.cancel-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
}

.membership-glow {
  position: absolute;
  inset: -2px;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  border-radius: 26px;
  opacity: 0.5;
  z-index: -1;
  animation: glow 3s ease-in-out infinite;
}

/* Section Headers */
.section-header {
  text-align: center;
  margin-bottom: 80px;
}

.section-badge {
  display: inline-block;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  padding: 8px 24px;
  border-radius: 50px;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 24px;
  letter-spacing: 0.5px;
}

.section-title {
  font-size: 3.5rem;
  font-weight: 900;
  background: linear-gradient(135deg, #1f2937, #374151);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 20px;
  line-height: 1.1;
}

.section-subtitle {
  font-size: 1.25rem;
  color: #6b7280;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}

/* Plans Section */
.plans-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #fafafa 0%, #ffffff 50%, #f8fafc 100%);
  position: relative;
  overflow: hidden;
}

.plans-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ec4899' fill-opacity='0.02'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.plans-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
  gap: 32px;
  position: relative;
  z-index: 2;
}

.plan-card {
  position: relative;
  background: white;
  border-radius: 24px;
  padding: 40px 32px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid #e5e7eb;
  overflow: hidden;
}

.plan-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 25px 50px rgba(236, 72, 153, 0.15);
}

.plan-card.featured {
  border-color: #ec4899;
  transform: scale(1.05);
  box-shadow: 0 20px 40px rgba(236, 72, 153, 0.15);
}

.plan-card.featured:hover {
  transform: translateY(-12px) scale(1.07);
}

.popular-badge {
  position: absolute;
  top: -15px;
  left: 50%;
  transform: translateX(-50%);
  background: linear-gradient(135deg, #ec4899, #be185d);
  color: white;
  padding: 8px 20px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
}

.fire-icon {
  animation: fire 2s ease-in-out infinite;
}

.plan-icon {
  text-align: center;
  font-size: 3rem;
  margin-bottom: 24px;
}

.plan-header {
  text-align: center;
  margin-bottom: 32px;
}

.plan-name {
  font-size: 1.75rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 12px;
}

.plan-description {
  color: #6b7280;
  line-height: 1.6;
  margin-bottom: 24px;
  font-size: 1.1rem;
}

.price-container {
  margin-bottom: 8px;
}

.price {
  font-size: 3.5rem;
  font-weight: 900;
  color: #ec4899;
  margin-bottom: 4px;
}

.duration {
  color: #6b7280;
  font-size: 0.95rem;
  font-weight: 600;
}

.features-list {
  margin-bottom: 40px;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
  padding: 8px 0;
}

.check-icon {
  font-size: 1.2rem;
}

.feature-text {
  color: #374151;
  font-size: 1rem;
  line-height: 1.5;
}

.subscribe-btn {
  position: relative;
  width: 100%;
  padding: 16px;
  border: none;
  border-radius: 16px;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  overflow: hidden;
}

.subscribe-btn:not(.current-plan):not(.loading) {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.subscribe-btn.premium-btn:not(.current-plan):not(.loading) {
  background: linear-gradient(135deg, #ec4899, #be185d);
}

.subscribe-btn.current-plan {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  cursor: not-allowed;
}

.subscribe-btn.loading {
  background: #9ca3af;
  color: white;
  cursor: not-allowed;
}

.subscribe-btn:not(.current-plan):not(.loading):hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4);
}

.subscribe-btn.premium-btn:not(.current-plan):not(.loading):hover {
  box-shadow: 0 12px 30px rgba(236, 72, 153, 0.4);
}

.button-glow {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transform: translateX(-100%);
  transition: transform 0.6s ease;
}

.subscribe-btn:hover .button-glow {
  transform: translateX(100%);
}

.loading-spinner {
  animation: spin 1s linear infinite;
}

.card-bg-effect {
  position: absolute;
  top: 0;
  right: 0;
  width: 100px;
  height: 100px;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  opacity: 0.05;
  border-radius: 0 24px 0 100px;
}

/* Payment History */
.payment-history {
  padding: 120px 0;
  background: white;
}

.payment-table-container {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb;
}

.payment-table {
  width: 100%;
}

.table-header {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  background: linear-gradient(135deg, #f8fafc, #e2e8f0);
  padding: 0;
}

.header-cell {
  padding: 20px;
  font-weight: 700;
  color: #374151;
  font-size: 1rem;
}

.table-body {
  display: grid;
  gap: 0;
}

.table-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  border-top: 1px solid #e5e7eb;
  transition: background-color 0.2s ease;
}

.table-row:hover {
  background: #f9fafb;
}

.table-cell {
  padding: 20px;
  display: flex;
  align-items: center;
}

.date {
  color: #6b7280;
  font-weight: 500;
}

.plan-name {
  color: #374151;
  font-weight: 600;
}

.amount {
  color: #374151;
  font-weight: 700;
  font-size: 1.1rem;
}

.status-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-badge.success {
  background: #dcfce7;
  color: #16a34a;
}

.status-badge.failed {
  background: #fee2e2;
  color: #dc2626;
}

.status-badge.pending {
  background: #fef3c7;
  color: #d97706;
}

.status-badge.cancelled {
  background: #f3f4f6;
  color: #6b7280;
}

.status-badge.refunded {
  background: #dbeafe;
  color: #2563eb;
}

/* FAQ Section */
.faq-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.faq-grid {
  display: grid;
  gap: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.faq-card {
  position: relative;
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  cursor: pointer;
  border: 1px solid #e5e7eb;
}

.faq-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.1);
}

.faq-header {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 24px;
}

.faq-icon {
  font-size: 1.5rem;
}

.faq-question {
  flex: 1;
  color: #1f2937;
  font-weight: 700;
  font-size: 1.1rem;
  margin: 0;
}

.toggle-icon {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.2rem;
  transition: all 0.3s ease;
}

.toggle-icon.active {
  transform: rotate(180deg);
}

.faq-content {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.faq-content.open {
  max-height: 200px;
}

.faq-answer {
  padding: 0 24px 24px 24px;
  color: #6b7280;
  line-height: 1.7;
  margin: 0;
}

.faq-glow {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
}

.faq-card:hover .faq-glow {
  opacity: 0.05;
}

/* CTA Section */
.cta-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  color: white;
  position: relative;
  overflow: hidden;
}

.cta-content {
  text-align: center;
  position: relative;
  z-index: 2;
}

.cta-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  padding: 12px 24px;
  border-radius: 50px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  margin-bottom: 32px;
  font-weight: 600;
}

.cta-title {
  font-size: 3.5rem;
  font-weight: 900;
  margin-bottom: 24px;
  background: linear-gradient(135deg, #ffffff, #f59e0b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.cta-description {
  font-size: 1.3rem;
  margin-bottom: 48px;
  opacity: 0.9;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}

.cta-buttons {
  display: flex;
  justify-content: center;
  gap: 24px;
}

.cta-primary-btn, .cta-secondary-btn {
  position: relative;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 32px;
  border: none;
  border-radius: 16px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  overflow: hidden;
}

.cta-primary-btn {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
}

.cta-primary-btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(245, 158, 11, 0.4);
}

.cta-secondary-btn {
  background: transparent;
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.cta-secondary-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: translateY(-2px);
}

.btn-shine {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transform: translateX(-100%);
  transition: transform 0.6s ease;
}

.cta-primary-btn:hover .btn-shine {
  transform: translateX(100%);
}

.cta-decoration {
  position: absolute;
  inset: 0;
  pointer-events: none;
}

.decoration-element {
  position: absolute;
  font-size: 2rem;
  animation: decorationFloat 6s ease-in-out infinite;
  opacity: 0.3;
}

.decoration-element.element-1 {
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.decoration-element.element-2 {
  top: 60%;
  right: 15%;
  animation-delay: 2s;
}

.decoration-element.element-3 {
  bottom: 30%;
  left: 20%;
  animation-delay: 4s;
}

/* Animations */
@keyframes heroFloat {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  25% { transform: translateY(-20px) rotate(90deg); }
  50% { transform: translateY(-10px) rotate(180deg); }
  75% { transform: translateY(-30px) rotate(270deg); }
}

@keyframes fire {
  0%, 100% { transform: scale(1) rotate(0deg); }
  50% { transform: scale(1.1) rotate(5deg); }
}

@keyframes glow {
  0%, 100% { opacity: 0.3; transform: scale(1); }
  50% { opacity: 0.6; transform: scale(1.02); }
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes decorationFloat {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

/* Responsive Design */
@media (max-width: 1024px) {
  .hero-title {
    font-size: 3rem;
  }
  
  .section-title {
    font-size: 2.5rem;
  }
  
  .cta-title {
    font-size: 2.5rem;
  }
  
  .membership-card {
    grid-template-columns: 1fr;
    text-align: center;
    gap: 24px;
  }
  
  .plans-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .cta-title {
    font-size: 2rem;
  }
  
  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }
  
  .plans-grid {
    grid-template-columns: 1fr;
  }
  
  .hero-stats {
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 20px;
  }
  
  .table-header,
  .table-row {
    grid-template-columns: 1fr 1fr;
    gap: 0;
  }
  
  .table-cell:nth-child(3),
  .table-cell:nth-child(4),
  .header-cell:nth-child(3),
  .header-cell:nth-child(4) {
    display: none;
  }
}

@media (max-width: 480px) {
  .container {
    padding: 0 16px;
  }
  
  .hero-title {
    font-size: 2rem;
  }
  
  .section-title {
    font-size: 1.75rem;
  }
  
  .plan-card {
    padding: 32px 24px;
  }
}
</style>