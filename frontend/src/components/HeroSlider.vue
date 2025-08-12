<template>
  <section class="hero-slider">
    <div class="slider-container">
      <div class="slider-wrapper" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
        <div 
          v-for="(slide, index) in slides" 
          :key="slide.id"
          class="slide"
          :style="{ backgroundColor: slide.background_color }"
        >
          <div class="slide-content">
            <div class="slide-text">
              <h1 class="slide-title">{{ slide.title }}</h1>
              <p class="slide-description">{{ slide.description }}</p>
              <RouterLink 
                v-if="slide.button_text && slide.button_link"
                :to="slide.button_link" 
                class="slide-button"
              >
                {{ slide.button_text }}
              </RouterLink>
            </div>
            <div class="slide-visual">
              <img 
                v-if="slide.image_url" 
                :src="slide.image_url" 
                :alt="slide.title"
                class="slide-image"
              >
              <div v-else class="slide-placeholder">
                <div class="floating-elements">
                  <div class="element element-1">💄</div>
                  <div class="element element-2">🤱</div>
                  <div class="element element-3">💪</div>
                  <div class="element element-4">⭐</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Navigation Dots -->
      <div class="slider-dots">
        <button 
          v-for="(slide, index) in slides" 
          :key="index"
          @click="goToSlide(index)"
          class="dot"
          :class="{ active: currentSlide === index }"
        ></button>
      </div>
      
      <!-- Navigation Arrows -->
      <button @click="prevSlide" class="nav-arrow prev-arrow">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <button @click="nextSlide" class="nav-arrow next-arrow">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import api from '@/services/api'

interface HeroSlide {
  id: number
  title: string
  description: string
  button_text?: string
  button_link?: string
  image_url?: string
  background_color: string
}

const slides = ref<HeroSlide[]>([])
const currentSlide = ref(0)
let autoSlideInterval: NodeJS.Timeout | null = null

const fetchSlides = async () => {
  try {
    const response = await api.get('/hero-sliders')
    if (response.data.success) {
      slides.value = response.data.data
    }
  } catch (error) {
    console.error('Hero slider verileri yüklenirken hata:', error)
  }
}

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.value.length
}

const prevSlide = () => {
  currentSlide.value = currentSlide.value === 0 ? slides.value.length - 1 : currentSlide.value - 1
}

const goToSlide = (index: number) => {
  currentSlide.value = index
}

const startAutoSlide = () => {
  autoSlideInterval = setInterval(() => {
    nextSlide()
  }, 5000)
}

const stopAutoSlide = () => {
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval)
    autoSlideInterval = null
  }
}

onMounted(async () => {
  await fetchSlides()
  if (slides.value.length > 1) {
    startAutoSlide()
  }
})

onUnmounted(() => {
  stopAutoSlide()
})
</script>

<style scoped>
.hero-slider {
  position: relative;
  height: 500px;
  overflow: hidden;
  border-radius: 0 0 24px 24px;
}

.slider-container {
  position: relative;
  width: 100%;
  height: 100%;
}

.slider-wrapper {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 0.5s ease-in-out;
}

.slide {
  min-width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  position: relative;
  background: linear-gradient(135deg, var(--bg-color, #ec4899), rgba(255,255,255,0.1));
}

.slide-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
  width: 100%;
}

.slide-text {
  color: white;
}

.slide-title {
  font-size: 3rem;
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 24px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.slide-description {
  font-size: 1.2rem;
  line-height: 1.6;
  margin-bottom: 32px;
  opacity: 0.95;
}

.slide-button {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 16px 32px;
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  color: white;
  text-decoration: none;
  border-radius: 16px;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  border: 2px solid rgba(255,255,255,0.3);
}

.slide-button:hover {
  background: rgba(255,255,255,0.3);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.slide-visual {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 400px;
}

.slide-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
  border-radius: 16px;
  box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.slide-placeholder {
  position: relative;
  width: 300px;
  height: 300px;
}

.floating-elements {
  position: relative;
  width: 100%;
  height: 100%;
}

.element {
  position: absolute;
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  padding: 20px;
  border-radius: 20px;
  font-size: 2rem;
  animation: float 6s ease-in-out infinite;
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.element-1 {
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.element-2 {
  top: 10%;
  right: 20%;
  animation-delay: 1.5s;
}

.element-3 {
  bottom: 30%;
  left: 20%;
  animation-delay: 3s;
}

.element-4 {
  bottom: 20%;
  right: 10%;
  animation-delay: 4.5s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

.slider-dots {
  position: absolute;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 12px;
}

.dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255,255,255,0.4);
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.dot.active {
  background: white;
  transform: scale(1.2);
}

.nav-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255,255,255,0.3);
  color: white;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-arrow:hover {
  background: rgba(255,255,255,0.3);
  transform: translateY(-50%) scale(1.1);
}

.prev-arrow {
  left: 24px;
}

.next-arrow {
  right: 24px;
}

.nav-arrow svg {
  width: 24px;
  height: 24px;
}

@media (max-width: 768px) {
  .hero-slider {
    height: 400px;
  }
  
  .slide-content {
    grid-template-columns: 1fr;
    gap: 32px;
    text-align: center;
  }
  
  .slide-title {
    font-size: 2rem;
  }
  
  .slide-description {
    font-size: 1rem;
  }
  
  .slide-visual {
    height: 200px;
  }
  
  .slide-placeholder {
    width: 200px;
    height: 200px;
  }
  
  .nav-arrow {
    width: 40px;
    height: 40px;
  }
  
  .nav-arrow svg {
    width: 20px;
    height: 20px;
  }
}
</style>