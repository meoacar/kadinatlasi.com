<template>
  <div class="beauty-page">
    <!-- Header -->
    <div class="page-header">
      <h1 class="page-title">💄 Güzellik & Bakım</h1>
      <p class="page-subtitle">Güzellik ipuçları, ürün incelemeleri ve uzman önerileri</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-number">{{ beautyArticles.length }}</div>
        <div class="stat-label">Rehber</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">💡</div>
        <div class="stat-number">{{ beautyTips.length }}</div>
        <div class="stat-label">İpucu</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🛍️</div>
        <div class="stat-number">{{ beautyProducts.length }}</div>
        <div class="stat-label">Ürün</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📹</div>
        <div class="stat-number">{{ beautyVideos.length }}</div>
        <div class="stat-label">Video</div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tabs-container">
      <button 
        v-for="tab in tabs" 
        :key="tab.key"
        @click="activeTab = tab.key"
        :class="['tab-button', { active: activeTab === tab.key }]"
      >
        {{ tab.icon }} {{ tab.label }}
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
      <p>Güzellik içerikleri yükleniyor...</p>
    </div>

    <!-- Content -->
    <div v-else class="content-container">
      <!-- Articles Tab -->
      <div v-if="activeTab === 'articles'" class="content-grid">
        <div v-for="article in beautyArticles" :key="article.id" class="article-card">
          <div class="article-image">
            <div class="article-icon">✨</div>
            <div class="article-time">{{ article.read_time || 5 }} dk</div>
          </div>
          <div class="article-content">
            <h3 class="article-title">{{ article.title }}</h3>
            <p class="article-excerpt">{{ article.excerpt || article.content?.substring(0, 100) + '...' }}</p>
            <div class="article-footer">
              <div class="article-tags">
                <span v-for="tag in getArticleTags(article)" :key="tag" class="tag">
                  #{{ tag }}
                </span>
              </div>
              <span class="read-more">Devamını Oku →</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tips Tab -->
      <div v-if="activeTab === 'tips'" class="content-grid">
        <div v-for="tip in beautyTips" :key="tip.id" class="tip-card">
          <div class="tip-header">
            <h3 class="tip-title">{{ tip.title }}</h3>
            <div class="tip-badges">
              <span class="difficulty-badge" :class="tip.difficulty_level">
                {{ getDifficultyLabel(tip.difficulty_level) }}
              </span>
              <span class="time-badge">{{ tip.time_required || '5 dk' }}</span>
            </div>
          </div>
          <p class="tip-content">{{ tip.content }}</p>
          <div class="tip-footer">
            <span class="tip-category">{{ getCategoryLabel(tip.category) }}</span>
            <span v-if="tip.featured" class="featured-star">⭐</span>
          </div>
        </div>
      </div>

      <!-- Products Tab -->
      <div v-if="activeTab === 'products'" class="content-grid">
        <div v-for="product in beautyProducts" :key="product.id" class="product-card">
          <div class="product-image">
            <div class="product-icon">🧴</div>
            <div v-if="product.featured" class="featured-badge">⭐ Öne Çıkan</div>
          </div>
          <div class="product-content">
            <div class="product-header">
              <div>
                <h3 class="product-name">{{ product.name }}</h3>
                <p class="product-brand">{{ product.brand }}</p>
              </div>
              <div class="product-rating">
                <span class="rating-star">⭐</span>
                <span class="rating-value">{{ product.rating || 4.5 }}</span>
                <div class="product-price">₺{{ product.price || 99 }}</div>
              </div>
            </div>
            <p class="product-description">{{ product.description }}</p>
            <div class="product-ingredients">
              <h4>İçerik:</h4>
              <p>{{ product.ingredients || 'Doğal içerikler' }}</p>
            </div>
            <div class="pros-cons">
              <div class="pros">
                <h5>Artıları:</h5>
                <ul>
                  <li v-for="pro in getProductPros(product)" :key="pro">✓ {{ pro }}</li>
                </ul>
              </div>
              <div class="cons">
                <h5>Eksileri:</h5>
                <ul>
                  <li v-for="con in getProductCons(product)" :key="con">✗ {{ con }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Videos Tab -->
      <div v-if="activeTab === 'videos'" class="content-grid">
        <div v-for="video in beautyVideos" :key="video.id" class="video-card">
          <div class="video-thumbnail">
            <div class="play-button">▶️</div>
            <div class="video-duration">{{ video.duration || '5:30' }}</div>
          </div>
          <div class="video-content">
            <h3 class="video-title">{{ video.title }}</h3>
            <p class="video-description">{{ video.description }}</p>
            <div class="video-footer">
              <span class="video-views">👁️ {{ formatViews(video.views_count) }} görüntüleme</span>
              <span v-if="video.featured" class="featured-badge">⭐ Öne Çıkan</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="getCurrentTabData().length === 0" class="empty-state">
        <div class="empty-icon">📝</div>
        <h3>Henüz içerik yok</h3>
        <p>Bu kategoride henüz içerik bulunmuyor.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/services/api'

const activeTab = ref('articles')
const loading = ref(true)

const beautyArticles = ref([])
const beautyTips = ref([])
const beautyProducts = ref([])
const beautyVideos = ref([])

const tabs = [
  { key: 'articles', label: 'Rehberler', icon: '📚' },
  { key: 'tips', label: 'İpuçları', icon: '💡' },
  { key: 'products', label: 'Ürünler', icon: '🛍️' },
  { key: 'videos', label: 'Videolar', icon: '📹' }
]

const getCurrentTabData = () => {
  switch (activeTab.value) {
    case 'articles': return beautyArticles.value
    case 'tips': return beautyTips.value
    case 'products': return beautyProducts.value
    case 'videos': return beautyVideos.value
    default: return []
  }
}

const getArticleTags = (article: any) => {
  try {
    return JSON.parse(article.tags || '["güzellik", "bakım"]')
  } catch {
    return ['güzellik', 'bakım']
  }
}

const getProductPros = (product: any) => {
  try {
    return JSON.parse(product.pros || '["Etkili", "Kaliteli"]')
  } catch {
    return ['Etkili', 'Kaliteli']
  }
}

const getProductCons = (product: any) => {
  try {
    return JSON.parse(product.cons || '["Pahalı"]')
  } catch {
    return ['Pahalı']
  }
}

const getDifficultyLabel = (level: string) => {
  const labels = {
    'beginner': 'Kolay',
    'intermediate': 'Orta',
    'advanced': 'Zor'
  }
  return labels[level] || 'Kolay'
}

const getCategoryLabel = (category: string) => {
  const labels = {
    'skincare': 'Cilt Bakımı',
    'makeup': 'Makyaj',
    'haircare': 'Saç Bakımı',
    'nails': 'Tırnak Bakımı',
    'lifestyle': 'Yaşam Tarzı'
  }
  return labels[category] || category
}

const formatViews = (views: number) => {
  if (!views) return '0'
  if (views >= 1000) {
    return Math.floor(views / 1000) + 'K'
  }
  return views.toString()
}

const loadBeautyContent = async () => {
  loading.value = true
  try {
    const [articlesRes, tipsRes, productsRes, videosRes] = await Promise.all([
      api.get('/beauty/articles'),
      api.get('/beauty/tips'),
      api.get('/beauty/products'),
      api.get('/beauty/videos')
    ])

    beautyArticles.value = articlesRes.data.data?.data || articlesRes.data.data || []
    beautyTips.value = tipsRes.data.data?.data || tipsRes.data.data || []
    beautyProducts.value = productsRes.data.data?.data || productsRes.data.data || []
    beautyVideos.value = videosRes.data.data?.data || videosRes.data.data || []

    console.log('Beauty data loaded:', {
      articles: beautyArticles.value.length,
      tips: beautyTips.value.length,
      products: beautyProducts.value.length,
      videos: beautyVideos.value.length
    })
  } catch (error) {
    console.error('Beauty content loading error:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadBeautyContent()
})
</script>

<style scoped>
.beauty-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fdf2f8 0%, #f9fafb 100%);
  padding: 32px 16px;
}

.page-header {
  text-align: center;
  margin-bottom: 48px;
  max-width: 1280px;
  margin-left: auto;
  margin-right: auto;
}

.page-title {
  font-size: 3rem;
  font-weight: bold;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 16px;
}

.page-subtitle {
  font-size: 1.25rem;
  color: #6b7280;
  max-width: 600px;
  margin: 0 auto;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
  max-width: 1280px;
  margin-left: auto;
  margin-right: auto;
}

.stat-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
  transition: transform 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
}

.stat-icon {
  font-size: 2rem;
  margin-bottom: 8px;
}

.stat-number {
  font-size: 1.5rem;
  font-weight: bold;
  color: #ec4899;
}

.stat-label {
  color: #6b7280;
  font-size: 0.875rem;
}

.tabs-container {
  display: flex;
  justify-content: center;
  margin-bottom: 40px;
  background: white;
  border-radius: 16px;
  padding: 8px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  gap: 8px;
}

.tab-button {
  background: transparent;
  color: #6b7280;
  padding: 12px 24px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
  flex: 1;
}

.tab-button.active {
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
}

.tab-button:hover:not(.active) {
  background: #f3f4f6;
}

.loading-container {
  text-align: center;
  padding: 64px;
  max-width: 1280px;
  margin: 0 auto;
}

.spinner {
  width: 64px;
  height: 64px;
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

.content-container {
  max-width: 1280px;
  margin: 0 auto;
}

.content-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 24px;
}

.article-card, .tip-card, .product-card, .video-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  border: 1px solid rgba(236, 72, 153, 0.1);
}

.article-card:hover, .tip-card:hover, .product-card:hover, .video-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(236, 72, 153, 0.15);
}

.article-image, .video-thumbnail {
  height: 200px;
  background: linear-gradient(135deg, #fce7f3, #f3e8ff);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.article-icon, .product-icon {
  font-size: 4rem;
}

.article-time, .video-duration {
  position: absolute;
  top: 16px;
  right: 16px;
  background: rgba(255,255,255,0.9);
  padding: 8px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  color: #ec4899;
  font-weight: 600;
}

.article-content, .tip-card, .product-content, .video-content {
  padding: 24px;
}

.article-title, .tip-title, .product-name, .video-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 12px;
  line-height: 1.4;
}

.article-excerpt, .tip-content, .product-description, .video-description {
  color: #6b7280;
  font-size: 0.95rem;
  margin-bottom: 16px;
  line-height: 1.6;
}

.article-footer, .tip-footer, .video-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.article-tags {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.tag {
  background: #fce7f3;
  color: #be185d;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 500;
}

.read-more {
  color: #ec4899;
  font-weight: 600;
  font-size: 0.875rem;
}

.tip-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 16px;
}

.tip-badges {
  display: flex;
  gap: 8px;
  margin-left: 16px;
}

.difficulty-badge {
  padding: 4px 8px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 500;
}

.difficulty-badge.beginner {
  background: #dcfce7;
  color: #166534;
}

.difficulty-badge.intermediate {
  background: #fef3c7;
  color: #92400e;
}

.difficulty-badge.advanced {
  background: #fee2e2;
  color: #dc2626;
}

.time-badge {
  background: #e0f2fe;
  color: #0369a1;
  padding: 4px 8px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 500;
}

.tip-category {
  background: #fce7f3;
  color: #be185d;
  padding: 6px 12px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: capitalize;
}

.featured-star {
  color: #fbbf24;
  font-size: 1.25rem;
}

.product-image {
  height: 180px;
  background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.featured-badge {
  position: absolute;
  top: 16px;
  left: 16px;
  background: #fbbf24;
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

.product-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 12px;
}

.product-brand {
  color: #6b7280;
  font-size: 0.875rem;
  font-weight: 500;
}

.product-rating {
  text-align: right;
}

.rating-star {
  color: #fbbf24;
}

.rating-value {
  font-weight: 600;
  color: #111827;
  margin-left: 4px;
}

.product-price {
  color: #ec4899;
  font-size: 1.25rem;
  font-weight: 700;
  margin-top: 4px;
}

.product-ingredients {
  margin-bottom: 16px;
}

.product-ingredients h4 {
  font-size: 0.875rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 8px;
}

.product-ingredients p {
  color: #6b7280;
  font-size: 0.875rem;
}

.pros-cons {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.pros h5, .cons h5 {
  font-size: 0.75rem;
  font-weight: 600;
  margin-bottom: 4px;
}

.pros h5 {
  color: #16a34a;
}

.cons h5 {
  color: #dc2626;
}

.pros ul, .cons ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.pros li {
  color: #16a34a;
  font-size: 0.75rem;
  margin-bottom: 2px;
}

.cons li {
  color: #dc2626;
  font-size: 0.75rem;
  margin-bottom: 2px;
}

.play-button {
  background: rgba(0,0,0,0.7);
  border-radius: 50%;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  color: white;
  font-size: 2rem;
  margin-left: 4px;
}

.play-button:hover {
  transform: scale(1.1);
}

.video-views {
  color: #6b7280;
  font-size: 0.875rem;
}

.empty-state {
  text-align: center;
  padding: 64px 32px;
  color: #6b7280;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 16px;
}

.empty-state h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 8px;
  color: #374151;
}

@media (max-width: 768px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
  
  .tabs-container {
    flex-direction: column;
    gap: 4px;
  }
  
  .tab-button {
    padding: 16px;
  }
  
  .pros-cons {
    grid-template-columns: 1fr;
  }
  
  .product-header {
    flex-direction: column;
    gap: 12px;
  }
  
  .tip-header {
    flex-direction: column;
    gap: 12px;
  }
}
</style>