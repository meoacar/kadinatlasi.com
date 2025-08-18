<template>
  <div class="home-page">
    <!-- Hero Slider - Admin Panelden Yönetiliyor -->
    <HeroSlider />

    <!-- Header Advertisement - Admin Panelden Yönetiliyor -->
    <Advertisement position="header" type="banner" :maxAds="1" />

    <!-- Modern Hero Stats -->
    <section class="hero-stats">
      <div class="container">
        <div class="stats-grid">
          <div class="stat-item" v-for="stat in heroStats" :key="stat.id">
            <div class="stat-icon">{{ stat.icon }}</div>
            <div class="stat-content">
              <div class="stat-number">{{ stat.number }}</div>
              <div class="stat-label">{{ stat.label }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Ultra Modern Features Grid -->
    <section class="features-section">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">✨ Özelliklerimiz</span>
          <h2 class="section-title">Her İhtiyacınız İçin Özel Çözümler</h2>
          <p class="section-subtitle">Kadınlara özel tasarlanmış, hayatınızı kolaylaştıracak dijital araçlar ve uzman içerikleri</p>
        </div>
        
        <div class="features-grid">
          <div 
            class="feature-card" 
            v-for="(feature, index) in features" 
            :key="feature.id"
            :class="`feature-${index + 1}`"
          >
            <div class="feature-icon-wrapper">
              <div class="feature-icon">{{ feature.icon }}</div>
              <div class="feature-glow"></div>
            </div>
            <div class="feature-content">
              <h3 class="feature-title">{{ feature.title }}</h3>
              <p class="feature-description">{{ feature.description }}</p>
              <RouterLink :to="feature.link" class="feature-link">
                <span>Keşfet</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"/>
                </svg>
              </RouterLink>
            </div>
            <div class="feature-bg-pattern"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Calculator Tools -->
    <section class="tools-section">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">🧮 Hesaplama Araçları</span>
          <h2 class="section-title">Akıllı Sağlık Hesaplayıcıları</h2>
          <p class="section-subtitle">Kişiselleştirilmiş analiz ve öneriler için gelişmiş hesaplama araçları</p>
        </div>
        
        <div class="tools-grid">
          <div 
            class="tool-card" 
            v-for="tool in calculatorTools" 
            :key="tool.id"
            @mouseenter="onToolHover"
            @mouseleave="onToolLeave"
          >
            <div class="tool-icon">{{ tool.icon }}</div>
            <div class="tool-content">
              <h3 class="tool-title">{{ tool.title }}</h3>
              <p class="tool-description">{{ tool.description }}</p>
            </div>
            <RouterLink :to="tool.link" class="tool-button">
              <span>Hesapla</span>
              <div class="button-effect"></div>
            </RouterLink>
            <div class="tool-shine"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Content Advertisement - Admin Panelden Yönetiliyor -->
    <Advertisement position="content" type="sponsored_content" :maxAds="1" />

    <!-- Premium Experience Section -->
    <section class="premium-section">
      <div class="container">
        <div class="premium-content">
          <div class="premium-visual">
            <div class="premium-badge">
              <div class="crown-icon">👑</div>
              <div class="premium-sparkles">
                <div class="sparkle sparkle-1">✨</div>
                <div class="sparkle sparkle-2">⭐</div>
                <div class="sparkle sparkle-3">💫</div>
              </div>
            </div>
          </div>
          <div class="premium-text">
            <h2 class="premium-title">
              Premium Deneyimi Yaşayın
            </h2>
            <p class="premium-description">
              Özel tasarlanmış premium içerikler, reklamsız deneyim ve uzman desteği ile farklı bir dünyaya adım atın
            </p>
            <div class="premium-features">
              <div class="feature-row">
                <span class="check-icon">✅</span>
                <span>Reklamsız premium deneyim</span>
              </div>
              <div class="feature-row">
                <span class="check-icon">✅</span>
                <span>Kişiselleştirilmiş astroloji analizleri</span>
              </div>
              <div class="feature-row">
                <span class="check-icon">✅</span>
                <span>Özel diyet ve fitness programları</span>
              </div>
              <div class="feature-row">
                <span class="check-icon">✅</span>
                <span>7/24 uzman danışmanlık hizmeti</span>
              </div>
            </div>
            <RouterLink to="/premium" class="premium-button">
              <span>Premium Ol</span>
              <div class="button-glow"></div>
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Dynamic Community Section -->
    <section class="community-section">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">👥 Topluluk</span>
          <h2 class="section-title">Güçlü Kadın Topluluğu</h2>
          <p class="section-subtitle">Binlerce kadının deneyim paylaştığı, birbirine destek olduğu büyük aile</p>
        </div>
        
        <div class="community-showcase">
          <div class="stats-row">
            <div class="stat-card" v-for="stat in communityStats" :key="stat.id">
              <div class="stat-icon">{{ stat.icon }}</div>
              <div class="stat-number">{{ stat.number }}</div>
              <div class="stat-label">{{ stat.label }}</div>
              <div class="stat-pulse"></div>
            </div>
          </div>
          
          <div class="community-actions">
            <RouterLink to="/forum" class="community-button primary">
              <span class="btn-icon">💬</span>
              <span>Forum'a Katıl</span>
              <div class="ripple-effect"></div>
            </RouterLink>
            <RouterLink to="/courses" class="community-button secondary">
              <span class="btn-icon">📚</span>
              <span>Kursları İncele</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Partnerships Section -->
    <PartnershipSection />

    <!-- Final CTA with Animation -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-content">
          <div class="cta-animation">
            <div class="floating-element element-1">🌟</div>
            <div class="floating-element element-2">💫</div>
            <div class="floating-element element-3">✨</div>
          </div>
          <h2 class="cta-title">Hayalinizdeki Yaşama Başlayın</h2>
          <p class="cta-description">
            KadınAtlası ile sağlıklı, mutlu ve başarılı bir yaşam için ilk adımı bugün atın
          </p>
          <RouterLink to="/register" class="cta-main-button">
            <span class="btn-text">Ücretsiz Üye Ol</span>
            <span class="btn-icon">🚀</span>
            <div class="button-waves">
              <div class="wave wave-1"></div>
              <div class="wave wave-2"></div>
            </div>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Footer Advertisement - Admin Panelden Yönetiliyor -->
    <Advertisement position="footer" type="banner" :maxAds="1" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import PartnershipSection from '@/components/PartnershipSection.vue'
import HeroSlider from '@/components/HeroSlider.vue'
import Advertisement from '@/components/Advertisement.vue'
import { useSEO } from '@/composables/useSEO'

useSEO({
  title: 'KadınAtlası.com - Kadınlar İçin Kapsamlı Yaşam Platformu',
  description: 'Kadın sağlığı, gebelik takibi, güzellik ipuçları, diyet programları, astroloji yorumları ve hesaplama araçları.',
  keywords: 'kadın sağlığı, gebelik takibi, güzellik, diyet, astroloji, VKİ hesaplama, regl takvimi, forum',
  type: 'website'
})

// Hero Stats Data
const heroStats = ref([
  {
    id: 1,
    icon: '👩‍💻',
    number: '50K+',
    label: 'Aktif Kullanıcı'
  },
  {
    id: 2,
    icon: '📊',
    number: '1M+',
    label: 'Hesaplama Yapıldı'
  },
  {
    id: 3,
    icon: '⭐',
    number: '4.9/5',
    label: 'Kullanıcı Puanı'
  },
  {
    id: 4,
    icon: '🏆',
    number: '500+',
    label: 'Uzman İçerik'
  }
])

// Enhanced Features
const features = ref([
  {
    id: 1,
    icon: '🤱',
    title: 'Anne & Bebek',
    description: 'Gebelik takibi, bebek bakımı ve anne sağlığı hakkında uzman içerikleri ile güvenli annelik yolculuğu',
    link: '/gebelik-takibi'
  },
  {
    id: 2,
    icon: '💪',
    title: 'Fitness & Diyet',
    description: 'Kişiye özel egzersiz programları ve bilimsel beslenme rehberleri ile sağlıklı yaşam',
    link: '/fitness'
  },
  {
    id: 3,
    icon: '🧠',
    title: 'Psikoloji & Yaşam',
    description: 'Ruh sağlığı, motivasyon ve kişisel gelişim içerikleri ile zihinsel iyi olma hali',
    link: '/psikoloji'
  },
  {
    id: 4,
    icon: '⭐',
    title: 'Astroloji',
    description: 'Kişiselleştirilmiş burç yorumları ve astrolojik analizler ile gelecek keşfi',
    link: '/astroloji'
  },
  {
    id: 5,
    icon: '💄',
    title: 'Güzellik & Bakım',
    description: 'Profesyonel cilt bakımı ipuçları ve güzellik ürün incelemeleri',
    link: '/guzellik'
  },
  {
    id: 6,
    icon: '🛍️',
    title: 'Güvenli Alışveriş',
    description: 'Kadınlara özel seçilmiş ürünler ve güvenli alışveriş deneyimi',
    link: '/shop'
  }
])

// Enhanced Calculator Tools
const calculatorTools = ref([
  {
    id: 1,
    icon: '⚖️',
    title: 'Akıllı VKİ Hesaplama',
    description: 'Gelişmiş algoritma ile vücut analizi ve sağlık önerileri',
    link: '/hesaplama/vki'
  },
  {
    id: 2,
    icon: '🤰',
    title: 'Gebelik Takip Sistemi',
    description: 'Haftalık gebelik takibi ve doğum tarihi hesaplama',
    link: '/hesaplama/gebelik'
  },
  {
    id: 3,
    icon: '🔥',
    title: 'Kişisel Kalori Rehberi',
    description: 'Yaşam tarzınıza göre kalori ihtiyacı ve beslenme planı',
    link: '/hesaplama/kalori'
  },
  {
    id: 4,
    icon: '💧',
    title: 'Hidrasyon Takipçisi',
    description: 'Günlük su ihtiyacı ve hidrasyon durumu analizi',
    link: '/hesaplama/su-ihtiyaci'
  }
])

// Community Stats
const communityStats = ref([
  {
    id: 1,
    icon: '💬',
    number: '50K+',
    label: 'Aktif Üye'
  },
  {
    id: 2,
    icon: '📝',
    number: '100K+',
    label: 'Forum Mesajı'
  },
  {
    id: 3,
    icon: '👩‍⚕️',
    number: '100+',
    label: 'Uzman'
  },
  {
    id: 4,
    icon: '🕐',
    number: '7/24',
    label: 'Destek'
  }
])

// Animation handlers
const onToolHover = () => {
  // Tool hover animation will be handled by CSS
}

const onToolLeave = () => {
  // Tool leave animation will be handled by CSS
}
</script>

<style scoped>
/* Ultra Modern Homepage Styles */
.home-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
}

/* Core Layout */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

/* Section Headers with Modern Badge */
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

/* Hero Stats Section */
.hero-stats {
  padding: 60px 0;
  background: white;
  margin-top: -30px;
  position: relative;
  z-index: 10;
  border-radius: 32px 32px 0 0;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 40px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 24px;
  background: linear-gradient(135deg, rgba(236, 72, 153, 0.05), rgba(139, 92, 246, 0.05));
  border-radius: 20px;
  border: 1px solid rgba(236, 72, 153, 0.1);
  transition: all 0.3s ease;
}

.stat-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(236, 72, 153, 0.1);
}

.stat-icon {
  font-size: 2.5rem;
  padding: 16px;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-content {
  flex: 1;
}

.stat-number {
  font-size: 2rem;
  font-weight: 900;
  color: #1f2937;
  margin-bottom: 4px;
}

.stat-label {
  color: #6b7280;
  font-weight: 600;
  font-size: 0.9rem;
}

/* Ultra Modern Features Section */
.features-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #fafafa 0%, #ffffff 50%, #f8fafc 100%);
  position: relative;
  overflow: hidden;
}

.features-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ec4899' fill-opacity='0.02'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
  gap: 32px;
  position: relative;
  z-index: 2;
}

.feature-card {
  position: relative;
  background: white;
  padding: 48px 32px;
  border-radius: 24px;
  text-align: center;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid rgba(236, 72, 153, 0.1);
  overflow: hidden;
}

.feature-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 25px 50px rgba(236, 72, 153, 0.15);
}

.feature-icon-wrapper {
  position: relative;
  display: inline-flex;
  margin-bottom: 24px;
}

.feature-icon {
  font-size: 4rem;
  padding: 24px;
  background: linear-gradient(135deg, #fdf2f8, #f3e8ff);
  border-radius: 20px;
  position: relative;
  z-index: 2;
}

.feature-glow {
  position: absolute;
  inset: -4px;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  border-radius: 24px;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 1;
}

.feature-card:hover .feature-glow {
  opacity: 0.2;
}

.feature-content {
  position: relative;
  z-index: 2;
}

.feature-title {
  font-size: 1.75rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 16px;
}

.feature-description {
  color: #6b7280;
  line-height: 1.7;
  margin-bottom: 32px;
  font-size: 1.1rem;
}

.feature-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #ec4899;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  padding: 12px 24px;
  border-radius: 12px;
  background: rgba(236, 72, 153, 0.05);
}

.feature-link:hover {
  background: rgba(236, 72, 153, 0.1);
  color: #be185d;
  transform: translateX(4px);
}

.feature-bg-pattern {
  position: absolute;
  top: 0;
  right: 0;
  width: 100px;
  height: 100px;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  opacity: 0.05;
  border-radius: 0 24px 0 100px;
}

/* Interactive Calculator Tools */
.tools-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
  position: relative;
  overflow: hidden;
}

.tools-section .section-title {
  background: linear-gradient(135deg, #ffffff, #f3f4f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.tools-section .section-subtitle {
  color: rgba(255, 255, 255, 0.8);
}

.tools-section .section-badge {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.tools-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 32px;
}

.tool-card {
  position: relative;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(20px);
  padding: 40px 32px;
  border-radius: 24px;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  cursor: pointer;
}

.tool-card:hover {
  transform: translateY(-8px);
  background: rgba(255, 255, 255, 0.1);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
}

.tool-icon {
  font-size: 3.5rem;
  margin-bottom: 24px;
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  padding: 20px;
  border-radius: 20px;
  display: inline-block;
}

.tool-content {
  margin-bottom: 32px;
}

.tool-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: white;
  margin-bottom: 16px;
}

.tool-description {
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.6;
  font-size: 1.1rem;
}

.tool-button {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
  padding: 16px 32px;
  border-radius: 16px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  overflow: hidden;
}

.tool-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4);
}

.button-effect {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transform: translateX(-100%);
  transition: transform 0.6s ease;
}

.tool-button:hover .button-effect {
  transform: translateX(100%);
}

.tool-shine {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transform: translateX(-100%) translateY(-100%) rotate(45deg);
  transition: transform 0.8s ease;
}

.tool-card:hover .tool-shine {
  transform: translateX(100%) translateY(100%) rotate(45deg);
}

/* Premium Experience Section */
.premium-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  color: white;
  position: relative;
  overflow: hidden;
}

.premium-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f59e0b' fill-opacity='0.03'%3E%3Cpath d='M30 30l15-15v30l-15-15z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}

.premium-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
  position: relative;
  z-index: 2;
}

.premium-visual {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

.premium-badge {
  position: relative;
  width: 300px;
  height: 300px;
  background: linear-gradient(135deg, #f59e0b, #d97706);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 30px 60px rgba(245, 158, 11, 0.3);
  animation: premiumFloat 6s ease-in-out infinite;
}

.crown-icon {
  font-size: 5rem;
  z-index: 2;
}

.premium-sparkles {
  position: absolute;
  inset: 0;
}

.sparkle {
  position: absolute;
  font-size: 2rem;
  animation: sparkleFloat 4s ease-in-out infinite;
}

.sparkle-1 {
  top: 10%;
  left: 20%;
  animation-delay: 0s;
}

.sparkle-2 {
  top: 20%;
  right: 15%;
  animation-delay: 1.3s;
}

.sparkle-3 {
  bottom: 15%;
  left: 15%;
  animation-delay: 2.6s;
}

.premium-title {
  font-size: 3.5rem;
  font-weight: 900;
  margin-bottom: 32px;
  background: linear-gradient(135deg, #ffffff, #f59e0b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.premium-description {
  font-size: 1.3rem;
  margin-bottom: 40px;
  opacity: 0.9;
  line-height: 1.7;
}

.premium-features {
  margin-bottom: 48px;
}

.feature-row {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 16px;
  font-size: 1.2rem;
}

.check-icon {
  font-size: 1.5rem;
}

.premium-button {
  position: relative;
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  padding: 20px 40px;
  border-radius: 20px;
  text-decoration: none;
  font-weight: 800;
  font-size: 1.2rem;
  display: inline-block;
  transition: all 0.4s ease;
  overflow: hidden;
}

.premium-button:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(245, 158, 11, 0.4);
}

.button-glow {
  position: absolute;
  inset: -2px;
  background: linear-gradient(135deg, #f59e0b, #d97706);
  border-radius: 22px;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
}

.premium-button:hover .button-glow {
  opacity: 0.5;
}

/* Dynamic Community Section */
.community-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.community-showcase {
  max-width: 1000px;
  margin: 0 auto;
}

.stats-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 32px;
  margin-bottom: 80px;
}

.stat-card {
  position: relative;
  text-align: center;
  padding: 40px 24px;
  background: linear-gradient(135deg, #fdf2f8, #f3e8ff);
  border-radius: 24px;
  transition: all 0.4s ease;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 50px rgba(236, 72, 153, 0.15);
}

.stat-icon {
  font-size: 3rem;
  margin-bottom: 16px;
}

.stat-number {
  font-size: 3.5rem;
  font-weight: 900;
  color: #ec4899;
  margin-bottom: 8px;
}

.stat-label {
  color: #6b7280;
  font-weight: 600;
  font-size: 1.1rem;
}

.stat-pulse {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  opacity: 0;
  border-radius: 24px;
  animation: pulse 3s ease-in-out infinite;
}

.community-actions {
  display: flex;
  justify-content: center;
  gap: 24px;
}

.community-button {
  position: relative;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 40px;
  border-radius: 20px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  overflow: hidden;
}

.community-button.primary {
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
}

.community-button.secondary {
  background: white;
  color: #ec4899;
  border: 2px solid #ec4899;
}

.community-button:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 35px rgba(236, 72, 153, 0.3);
}

.btn-icon {
  font-size: 1.3rem;
}

.ripple-effect {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
  transform: scale(0);
  transition: transform 0.6s ease;
}

.community-button:hover .ripple-effect {
  transform: scale(1);
}

/* Final CTA with Animation */
.cta-section {
  padding: 120px 0;
  background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 50%, #3b82f6 100%);
  color: white;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.cta-content {
  position: relative;
  z-index: 2;
}

.cta-animation {
  position: absolute;
  inset: 0;
  pointer-events: none;
}

.floating-element {
  position: absolute;
  font-size: 3rem;
  animation: floatAround 8s ease-in-out infinite;
}

.element-1 {
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.element-2 {
  top: 60%;
  right: 15%;
  animation-delay: 2.6s;
}

.element-3 {
  bottom: 30%;
  left: 20%;
  animation-delay: 5.2s;
}

.cta-title {
  font-size: 4rem;
  font-weight: 900;
  margin-bottom: 32px;
  text-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.cta-description {
  font-size: 1.4rem;
  margin-bottom: 48px;
  opacity: 0.95;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}

.cta-main-button {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 16px;
  padding: 24px 48px;
  background: white;
  color: #ec4899;
  text-decoration: none;
  border-radius: 24px;
  font-weight: 900;
  font-size: 1.3rem;
  transition: all 0.4s ease;
  box-shadow: 0 15px 35px rgba(255, 255, 255, 0.3);
  overflow: hidden;
}

.cta-main-button:hover {
  transform: translateY(-6px) scale(1.05);
  box-shadow: 0 25px 50px rgba(255, 255, 255, 0.4);
}

.btn-text {
  position: relative;
  z-index: 2;
}

.btn-icon {
  font-size: 1.5rem;
  position: relative;
  z-index: 2;
}

.button-waves {
  position: absolute;
  inset: 0;
}

.wave {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent, rgba(236, 72, 153, 0.1), transparent);
  border-radius: 24px;
  animation: waveAnimation 2s ease-in-out infinite;
}

.wave-1 {
  animation-delay: 0s;
}

.wave-2 {
  animation-delay: 1s;
}

/* Animations */
@keyframes premiumFloat {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(5deg); }
}

@keyframes sparkleFloat {
  0%, 100% { transform: translateY(0px) scale(1); opacity: 1; }
  50% { transform: translateY(-15px) scale(1.2); opacity: 0.8; }
}

@keyframes floatAround {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  25% { transform: translateY(-20px) rotate(90deg); }
  50% { transform: translateY(-10px) rotate(180deg); }
  75% { transform: translateY(-30px) rotate(270deg); }
}

@keyframes waveAnimation {
  0% { transform: scale(0.8); opacity: 1; }
  100% { transform: scale(1.2); opacity: 0; }
}

@keyframes pulse {
  0%, 100% { opacity: 0; transform: scale(1); }
  50% { opacity: 0.1; transform: scale(1.05); }
}

/* Responsive Design */
@media (max-width: 1024px) {
  .section-title {
    font-size: 2.5rem;
  }
  
  .premium-content {
    grid-template-columns: 1fr;
    gap: 60px;
    text-align: center;
  }
  
  .features-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  }
}

@media (max-width: 768px) {
  .section-title {
    font-size: 2rem;
  }
  
  .cta-title {
    font-size: 2.5rem;
  }
  
  .premium-title {
    font-size: 2.5rem;
  }
  
  .community-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .features-grid,
  .tools-grid {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
  }
  
  .stat-item {
    flex-direction: column;
    text-align: center;
    gap: 12px;
  }
  
  .premium-badge {
    width: 200px;
    height: 200px;
  }
  
  .crown-icon {
    font-size: 3rem;
  }
}

@media (max-width: 480px) {
  .container {
    padding: 0 16px;
  }
  
  .section-title {
    font-size: 1.75rem;
  }
  
  .cta-title {
    font-size: 2rem;
  }
  
  .feature-card,
  .tool-card {
    padding: 32px 24px;
  }
}
</style>