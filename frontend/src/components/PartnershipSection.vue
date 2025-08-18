<template>
  <section class="partnerships-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">🤝 İşbirlikleri</h2>
        <p class="section-subtitle">Bu markalarla 3 farklı şekilde işbirliği yapıyoruz</p>
        <div class="partnership-benefits">
          <div class="benefit-item">
            <span class="benefit-icon">🛍️</span>
            <span class="benefit-text">Ürün Satışı</span>
          </div>
          <div class="benefit-item">
            <span class="benefit-icon">📝</span>
            <span class="benefit-text">İçerik Sponsorluğu</span>
          </div>
          <div class="benefit-item">
            <span class="benefit-icon">🤝</span>
            <span class="benefit-text">Affiliate Program</span>
          </div>
        </div>
      </div>
      
      <div class="partners-grid" v-if="partnerships.length > 0">
        <div class="partner-card" v-for="partner in partnerships" :key="partner.company_name">
          <div class="partner-logo">
            <span class="partner-icon">{{ getPartnerIcon(partner.partnership_type) }}</span>
          </div>
          <h3 class="partner-name">{{ partner.company_name }}</h3>
          <p class="partner-type">{{ getPartnerTypeLabel(partner.partnership_type) }}</p>
          <p class="partner-description">{{ partner.description }}</p>
          <div class="partner-benefits">
            <span class="benefit-tag">{{ getPartnerBenefit(partner.partnership_type) }}</span>
          </div>
        </div>
      </div>
      
      <div class="partnership-explanation">
        <h3 class="explanation-title">Nasıl Çalışıyor?</h3>
        <div class="explanation-grid">
          <div class="explanation-item">
            <div class="explanation-icon">🛍️</div>
            <h4>Ürün Satışı</h4>
            <p>Partnerlerin ürünlerini sitemizde satarak komisyon alıyoruz</p>
          </div>
          <div class="explanation-item">
            <div class="explanation-icon">📝</div>
            <h4>Sponsorlu İçerik</h4>
            <p>Blog yazılarında ve videolarda marka tanıtımı yapıyoruz</p>
          </div>
          <div class="explanation-item">
            <div class="explanation-icon">🤝</div>
            <h4>Affiliate Program</h4>
            <p>Kullanıcılarımızı yönlendirerek komisyon kazanıyoruz</p>
          </div>
        </div>
      </div>
      
      <div class="partnership-stats" v-if="stats">
        <div class="stat-item">
          <span class="stat-number">{{ stats.total_partners }}+</span>
          <span class="stat-label">Aktif Partner</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">%100</span>
          <span class="stat-label">Güvenilir</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { partnershipService, type Partnership, type PartnershipStats } from '@/services/partnershipService'

const partnerships = ref<Partnership[]>([])
const stats = ref<PartnershipStats | null>(null)

const getPartnerIcon = (type: string): string => {
  const icons: Record<string, string> = {
    'brand': '💄',
    'influencer': '👑',
    'sponsor': '⭐',
    'affiliate': '🤝'
  }
  return icons[type] || '🏢'
}

const getPartnerTypeLabel = (type: string): string => {
  const labels: Record<string, string> = {
    'brand': 'Marka Ortaklığı',
    'influencer': 'Influencer',
    'sponsor': 'Sponsorluk',
    'affiliate': 'Affiliate Program'
  }
  return labels[type] || type
}

const getPartnerBenefit = (type: string): string => {
  const benefits: Record<string, string> = {
    'brand': 'Sitede Ürün Satışı',
    'influencer': 'İçerik Sponsorluğu',
    'sponsor': 'Blog Sponsorluğu',
    'affiliate': 'Affiliate Linki'
  }
  return benefits[type] || 'Ortaklık'
}

const formatRevenue = (revenue: number): string => {
  if (revenue >= 1000) {
    return Math.round(revenue / 1000) + 'K'
  }
  return revenue.toString()
}

onMounted(async () => {
  try {
    const [partnershipsData, statsData] = await Promise.all([
      partnershipService.getPartnerships(),
      partnershipService.getStats()
    ])
    partnerships.value = partnershipsData
    stats.value = statsData
  } catch (error) {
    console.error('Partnership verileri yüklenirken hata:', error)
  }
})
</script>

<style scoped>
.partnerships-section {
  padding: 80px 0;
  background: linear-gradient(135deg, #f8fafc, #e2e8f0);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-header {
  text-align: center;
  margin-bottom: 60px;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 16px;
}

.section-subtitle {
  font-size: 1.2rem;
  color: #6b7280;
  margin-bottom: 32px;
}

.partnership-benefits {
  display: flex;
  justify-content: center;
  gap: 32px;
  flex-wrap: wrap;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 8px;
  background: white;
  padding: 12px 20px;
  border-radius: 25px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.benefit-icon {
  font-size: 1.2rem;
}

.benefit-text {
  font-weight: 600;
  color: #374151;
}

.partners-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 24px;
  margin-bottom: 60px;
}

.partner-card {
  background: white;
  padding: 32px 20px;
  border-radius: 16px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border: 1px solid rgba(236, 72, 153, 0.1);
  display: flex;
  flex-direction: column;
  min-height: 280px;
}

.partner-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
  border-color: rgba(236, 72, 153, 0.3);
}

.partner-logo {
  margin-bottom: 16px;
}

.partner-icon {
  font-size: 2.5rem;
  display: block;
}

.partner-name {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 8px;
}

.partner-type {
  color: #6b7280;
  font-size: 0.9rem;
  font-weight: 500;
  margin-bottom: 12px;
}

.partner-description {
  color: #6b7280;
  font-size: 0.85rem;
  line-height: 1.4;
  margin-bottom: 16px;
  text-align: center;
}

.partner-benefits {
  margin-top: auto;
}

.benefit-tag {
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

.partnership-stats {
  display: flex;
  justify-content: center;
  gap: 60px;
  padding: 40px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-item {
  text-align: center;
}

.stat-number {
  display: block;
  font-size: 2.5rem;
  font-weight: 900;
  color: #ec4899;
  margin-bottom: 8px;
}

.stat-label {
  color: #6b7280;
  font-weight: 600;
  font-size: 1rem;
}

.partnership-explanation {
  margin-bottom: 60px;
}

.explanation-title {
  text-align: center;
  font-size: 1.8rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 32px;
}

.explanation-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
}

.explanation-item {
  background: white;
  padding: 32px 24px;
  border-radius: 16px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(59, 130, 246, 0.1);
}

.explanation-icon {
  font-size: 3rem;
  margin-bottom: 16px;
}

.explanation-item h4 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 12px;
}

.explanation-item p {
  color: #6b7280;
  line-height: 1.5;
}

@media (max-width: 768px) {
  .partnership-stats {
    flex-direction: column;
    gap: 24px;
  }
  
  .partners-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  }
  
  .partnership-benefits {
    flex-direction: column;
    gap: 16px;
  }
  
  .partner-card {
    min-height: 320px;
  }
}
</style>