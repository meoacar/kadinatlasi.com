<template>
  <div v-if="ads.length > 0" class="advertisement-container">
    <div 
      v-for="ad in ads" 
      :key="ad.id" 
      :class="getAdClass(ad)"
      @click="handleAdClick(ad)"
    >
      <!-- Banner Reklamı -->
      <div v-if="ad.type === 'banner'" class="banner-ad">
        <div class="ad-label">Reklam</div>
        <img 
          v-if="ad.image_url" 
          :src="ad.image_url" 
          :alt="ad.title"
          class="ad-image"
        />
        <div v-else class="ad-content">
          <h3 class="ad-title">{{ ad.title }}</h3>
          <p class="ad-text">{{ ad.content }}</p>
        </div>
      </div>

      <!-- Sidebar Reklamı -->
      <div v-else-if="ad.type === 'sidebar'" class="sidebar-ad">
        <div class="ad-label">Reklam</div>
        <div class="ad-content">
          <img 
            v-if="ad.image_url" 
            :src="ad.image_url" 
            :alt="ad.title"
            class="ad-image"
          />
          <h4 class="ad-title">{{ ad.title }}</h4>
          <p class="ad-text">{{ ad.content }}</p>
        </div>
      </div>

      <!-- Sponsorlu İçerik -->
      <div v-else-if="ad.type === 'sponsored_content'" class="sponsored-content">
        <div class="sponsored-label">Sponsorlu İçerik</div>
        <div class="sponsored-body">
          <img 
            v-if="ad.image_url" 
            :src="ad.image_url" 
            :alt="ad.title"
            class="sponsored-image"
          />
          <div class="sponsored-text">
            <h3 class="sponsored-title">{{ ad.title }}</h3>
            <p class="sponsored-description">{{ ad.content }}</p>
          </div>
        </div>
      </div>

      <!-- Pop-up Reklamı -->
      <div v-else-if="ad.type === 'popup' && showPopup" class="popup-ad">
        <div class="popup-overlay" @click="closePopup">
          <div class="popup-content" @click.stop>
            <button class="popup-close" @click="closePopup">&times;</button>
            <img 
              v-if="ad.image_url" 
              :src="ad.image_url" 
              :alt="ad.title"
              class="popup-image"
            />
            <div class="popup-text">
              <h3 class="popup-title">{{ ad.title }}</h3>
              <p class="popup-description">{{ ad.content }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/services/api'

interface Advertisement {
  id: number
  title: string
  type: string
  position: string
  content?: string
  image_url?: string
  link_url?: string
  client_name: string
}

const props = defineProps<{
  position?: string
  type?: string
  maxAds?: number
}>()

const ads = ref<Advertisement[]>([])
const showPopup = ref(false)
const popupShown = ref(false)

const getAdClass = (ad: Advertisement) => {
  return {
    'advertisement': true,
    [`ad-${ad.type}`]: true,
    [`ad-${ad.position}`]: true,
    'clickable': !!ad.link_url
  }
}

const handleAdClick = async (ad: Advertisement) => {
  if (ad.link_url) {
    // Track click
    try {
      await api.post(`/advertisements/${ad.id}/click`)
    } catch (error) {
      console.error('Error tracking ad click:', error)
    }
    
    // Open link
    if (ad.type === 'popup') {
      closePopup()
    }
    window.open(ad.link_url, '_blank')
  }
}

const closePopup = () => {
  showPopup.value = false
  localStorage.setItem('popup_closed_today', new Date().toDateString())
}

const loadAds = async () => {
  try {
    const params = new URLSearchParams()
    if (props.position) params.append('position', props.position)
    if (props.type) params.append('type', props.type)
    
    const response = await api.get(`/advertisements?${params.toString()}`)
    if (response.data.success) {
      let loadedAds = response.data.data
      
      // Limit number of ads if specified
      if (props.maxAds) {
        loadedAds = loadedAds.slice(0, props.maxAds)
      }
      
      ads.value = loadedAds
      
      // Show popup if there are popup ads and not shown today
      const hasPopup = loadedAds.some((ad: Advertisement) => ad.type === 'popup')
      const popupClosedToday = localStorage.getItem('popup_closed_today')
      const today = new Date().toDateString()
      
      if (hasPopup && popupClosedToday !== today && !popupShown.value) {
        setTimeout(() => {
          showPopup.value = true
          popupShown.value = true
        }, 3000) // Show popup after 3 seconds
      }
    }
  } catch (error) {
    console.error('Error loading advertisements:', error)
  }
}

onMounted(() => {
  loadAds()
})
</script>

<style scoped>
.advertisement-container {
  margin: 1rem 0;
}

.advertisement {
  margin-bottom: 1rem;
  border-radius: 8px;
  overflow: hidden;
  position: relative;
}

.advertisement.clickable {
  cursor: pointer;
  transition: transform 0.2s ease;
}

.advertisement.clickable:hover {
  transform: translateY(-2px);
}

/* Banner Ads */
.banner-ad {
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  border: 2px solid #f59e0b;
  padding: 1rem;
  text-align: center;
  position: relative;
}

.banner-ad .ad-image {
  width: 100%;
  max-height: 200px;
  object-fit: cover;
  border-radius: 4px;
}

/* Sidebar Ads */
.sidebar-ad {
  background: white;
  border: 1px solid #e5e7eb;
  padding: 1rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  max-width: 300px;
}

.sidebar-ad .ad-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 4px;
  margin-bottom: 0.5rem;
}

/* Sponsored Content */
.sponsored-content {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  padding: 1.5rem;
  margin: 2rem 0;
}

.sponsored-body {
  display: flex;
  gap: 1rem;
  align-items: flex-start;
}

.sponsored-image {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 8px;
  flex-shrink: 0;
}

.sponsored-text {
  flex: 1;
}

/* Popup Ads */
.popup-ad {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
}

.popup-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.popup-content {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  max-width: 500px;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.popup-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: #ef4444;
  color: white;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  font-size: 1.5rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.popup-image {
  width: 100%;
  max-height: 300px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 1rem;
}

/* Labels */
.ad-label, .sponsored-label {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
}

.sponsored-label {
  position: static;
  display: inline-block;
  margin-bottom: 1rem;
  background: #3b82f6;
}

/* Typography */
.ad-title, .sponsored-title, .popup-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.ad-text, .sponsored-description, .popup-description {
  color: #6b7280;
  line-height: 1.5;
}

.sidebar-ad .ad-title {
  font-size: 1rem;
}

.sidebar-ad .ad-text {
  font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
  .sponsored-body {
    flex-direction: column;
  }
  
  .sponsored-image {
    width: 100%;
    height: 200px;
  }
  
  .popup-content {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .sidebar-ad {
    max-width: 100%;
  }
}
</style>