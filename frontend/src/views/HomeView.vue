<template>
  <div class="home-page">
    <!-- Hero Slider -->
    <HeroSlider />

    <!-- Header Advertisement -->
    <Advertisement position="header" type="banner" :maxAds="1" />

    <!-- Features Section -->
    <section class="features-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">✨ Özelliklerimiz</h2>
          <p class="section-subtitle">Hayatınızı kolaylaştıracak araçlar ve içerikler</p>
        </div>
        
        <div class="features-grid">
          <div class="feature-card" v-for="feature in features" :key="feature.id">
            <div class="feature-icon">{{ feature.icon }}</div>
            <h3 class="feature-title">{{ feature.title }}</h3>
            <p class="feature-description">{{ feature.description }}</p>
            <RouterLink :to="feature.link" class="feature-link">
              Keşfet →
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Calculator Tools Section -->
    <section class="tools-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">🧮 Hesaplama Araçları</h2>
          <p class="section-subtitle">Pratik hesaplama araçlarıyla sağlığınızı takip edin</p>
        </div>
        
        <div class="tools-grid">
          <div class="tool-card" v-for="tool in calculatorTools" :key="tool.id">
            <div class="tool-icon">{{ tool.icon }}</div>
            <h3 class="tool-title">{{ tool.title }}</h3>
            <p class="tool-description">{{ tool.description }}</p>
            <RouterLink :to="tool.link" class="tool-button">
              Hesapla
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Content Advertisement -->
    <Advertisement position="content" type="sponsored_content" :maxAds="1" />

    <!-- Premium Section -->
    <section class="premium-section">
      <div class="container">
        <div class="premium-content">
          <div class="premium-text">
            <h2 class="premium-title">
              <span class="premium-icon">👑</span>
              Premium Üyelik
            </h2>
            <p class="premium-description">
              Özel içeriklere erişin, reklamsız deneyim yaşayın ve uzman desteği alın
            </p>
            <ul class="premium-features">
              <li>✅ Reklamsız deneyim</li>
              <li>✅ Premium astroloji analizleri</li>
              <li>✅ Kişiye özel diyet listeleri</li>
              <li>✅ Uzman danışmanlık</li>
              <li>✅ Öncelikli destek</li>
            </ul>
            <RouterLink to="/premium" class="premium-button">
              Premium Ol
            </RouterLink>
          </div>
          <div class="premium-visual">
            <div class="premium-badge">
              <span class="badge-icon">⭐</span>
              <span class="badge-text">Premium</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Partnerships Section -->
    <PartnershipSection />

    <!-- Community Section -->
    <section class="community-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">👥 Topluluk</h2>
          <p class="section-subtitle">Binlerce kadınla deneyimlerinizi paylaşın</p>
        </div>
        
        <div class="community-stats">
          <div class="stat-card">
            <div class="stat-number">50K+</div>
            <div class="stat-label">Aktif Üye</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">100K+</div>
            <div class="stat-label">Forum Mesajı</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">500+</div>
            <div class="stat-label">Uzman İçerik</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Destek</div>
          </div>
        </div>
        
        <div class="community-actions">
          <RouterLink to="/forum" class="community-button">
            <span>💬</span>
            Forum'a Katıl
          </RouterLink>
          <RouterLink to="/courses" class="community-button">
            <span>📚</span>
            Kursları İncele
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-content">
          <h2 class="cta-title">Hayalinizdeki Yaşama Başlayın</h2>
          <p class="cta-description">
            KadınAtlası ile sağlıklı, mutlu ve başarılı bir yaşam için ilk adımı atın
          </p>
          <RouterLink to="/register" class="cta-main-button">
            <span>🌟</span>
            Ücretsiz Üye Ol
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Footer Advertisement -->
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

const features = ref([
  {
    id: 1,
    icon: '🤱',
    title: 'Anne & Bebek',
    description: 'Gebelik takibi, bebek bakımı ve anne sağlığı hakkında uzman içerikleri',
    link: '/gebelik-takibi'
  },
  {
    id: 2,
    icon: '💪',
    title: 'Fitness & Diyet',
    description: 'Kişiye özel egzersiz programları ve sağlıklı beslenme rehberleri',
    link: '/fitness'
  },
  {
    id: 3,
    icon: '🧠',
    title: 'Psikoloji & Yaşam',
    description: 'Ruh sağlığı, motivasyon ve kişisel gelişim içerikleri',
    link: '/psikoloji'
  },
  {
    id: 4,
    icon: '⭐',
    title: 'Astroloji',
    description: 'Günlük, haftalık ve aylık burç yorumları ve kişisel analizler',
    link: '/astroloji'
  },
  {
    id: 5,
    icon: '💄',
    title: 'Güzellik & Bakım',
    description: 'Cilt bakımı, makyaj ipuçları ve güzellik ürün incelemeleri',
    link: '/guzellik'
  },
  {
    id: 6,
    icon: '🛍️',
    title: 'Alışveriş',
    description: 'Kadınlara özel ürünler ve güvenli alışveriş deneyimi',
    link: '/shop'
  }
])

const calculatorTools = ref([
  {
    id: 1,
    icon: '⚖️',
    title: 'VKİ Hesaplama',
    description: 'Vücut kitle indeksinizi hesaplayın',
    link: '/hesaplama/vki'
  },
  {
    id: 2,
    icon: '🤰',
    title: 'Gebelik Hesaplama',
    description: 'Gebelik haftanızı ve doğum tarihinizi öğrenin',
    link: '/hesaplama/gebelik'
  },
  {
    id: 3,
    icon: '🔥',
    title: 'Kalori Hesaplama',
    description: 'Günlük kalori ihtiyacınızı belirleyin',
    link: '/hesaplama/kalori'
  },
  {
    id: 4,
    icon: '💧',
    title: 'Su İhtiyacı',
    description: 'Günlük su ihtiyacınızı hesaplayın',
    link: '/hesaplama/su-ihtiyaci'
  }
])
</script>

<style scoped>
.home-page {
  min-height: 100vh;
}

/* Sections */
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
}

/* Features Section */
.features-section {
  padding: 100px 0;
  background: white;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 32px;
}

.feature-card {
  background: linear-gradient(135deg, #fdf2f8, #f3e8ff);
  padding: 40px;
  border-radius: 24px;
  text-align: center;
  transition: transform 0.3s ease;
  border: 1px solid rgba(236, 72, 153, 0.1);
}

.feature-card:hover {
  transform: translateY(-8px);
}

.feature-icon {
  font-size: 3rem;
  margin-bottom: 20px;
}

.feature-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 16px;
}

.feature-description {
  color: #6b7280;
  line-height: 1.6;
  margin-bottom: 24px;
}

.feature-link {
  color: #ec4899;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.feature-link:hover {
  color: #be185d;
}

/* Tools Section */
.tools-section {
  padding: 100px 0;
  background: linear-gradient(135deg, #f8fafc, #e2e8f0);
}

.tools-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}

.tool-card {
  background: white;
  padding: 32px;
  border-radius: 20px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.tool-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.tool-icon {
  font-size: 2.5rem;
  margin-bottom: 16px;
}

.tool-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 12px;
}

.tool-description {
  color: #6b7280;
  margin-bottom: 20px;
}

.tool-button {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
  padding: 10px 24px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.tool-button:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Premium Section */
.premium-section {
  padding: 100px 0;
  background: linear-gradient(135deg, #1f2937, #374151);
  color: white;
}

.premium-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.premium-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.premium-icon {
  font-size: 3rem;
}

.premium-description {
  font-size: 1.2rem;
  margin-bottom: 32px;
  opacity: 0.9;
}

.premium-features {
  list-style: none;
  padding: 0;
  margin-bottom: 40px;
}

.premium-features li {
  padding: 8px 0;
  font-size: 1.1rem;
}

.premium-button {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  padding: 16px 32px;
  border-radius: 16px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  display: inline-block;
  transition: all 0.3s ease;
}

.premium-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
}

.premium-visual {
  display: flex;
  justify-content: center;
  align-items: center;
}

.premium-badge {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  padding: 40px;
  border-radius: 50%;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  box-shadow: 0 20px 40px rgba(245, 158, 11, 0.3);
  animation: pulse 2s ease-in-out infinite;
}

.badge-icon {
  font-size: 3rem;
}

.badge-text {
  font-weight: 700;
  font-size: 1.2rem;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

/* Community Section */
.community-section {
  padding: 100px 0;
  background: white;
}

.community-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 32px;
  margin-bottom: 60px;
}

.stat-card {
  text-align: center;
  padding: 32px;
  background: linear-gradient(135deg, #fdf2f8, #f3e8ff);
  border-radius: 20px;
}

.stat-number {
  font-size: 3rem;
  font-weight: 900;
  color: #ec4899;
  margin-bottom: 8px;
}

.stat-label {
  color: #6b7280;
  font-weight: 600;
}

.community-actions {
  display: flex;
  justify-content: center;
  gap: 24px;
}

.community-button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 16px 32px;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  text-decoration: none;
  border-radius: 16px;
  font-weight: 700;
  transition: all 0.3s ease;
}

.community-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

/* CTA Section */
.cta-section {
  padding: 100px 0;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  text-align: center;
}

.cta-title {
  font-size: 3rem;
  font-weight: 900;
  margin-bottom: 24px;
}

.cta-description {
  font-size: 1.3rem;
  margin-bottom: 40px;
  opacity: 0.9;
}

.cta-main-button {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  padding: 20px 40px;
  background: white;
  color: #ec4899;
  text-decoration: none;
  border-radius: 20px;
  font-weight: 800;
  font-size: 1.2rem;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
}

.cta-main-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(255, 255, 255, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
  .premium-content {
    grid-template-columns: 1fr;
    gap: 40px;
    text-align: center;
  }
  
  .community-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .features-grid {
    grid-template-columns: 1fr;
  }
  
  .tools-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  }
}
</style>