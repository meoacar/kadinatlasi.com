<template>
  <div class="course-detail-page">
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Kurs yükleniyor...</p>
    </div>

    <div v-else-if="course" class="course-container">
      <!-- Course Header -->
      <section class="course-header">
        <div class="container">
          <div class="course-info">
            <div class="course-meta">
              <span class="category">{{ course.category }}</span>
              <span class="level">{{ getLevelText(course.level) }}</span>
            </div>
            <h1 class="course-title">{{ course.title }}</h1>
            <p class="course-description">{{ course.description }}</p>
            
            <div class="course-stats">
              <div class="stat">
                <span class="stat-icon">👩🏫</span>
                <span>{{ course.instructor_name }}</span>
              </div>
              <div class="stat">
                <span class="stat-icon">⏱️</span>
                <span>{{ formatDuration(course.duration) }}</span>
              </div>
              <div class="stat">
                <span class="stat-icon">👥</span>
                <span>{{ course.enrollment_count }} kayıt</span>
              </div>
              <div class="stat" v-if="course.rating > 0">
                <span class="stat-icon">⭐</span>
                <span>{{ course.rating }}</span>
              </div>
            </div>

            <div class="enrollment-section">
              <div v-if="isEnrolled" class="enrolled-badge">
                <span class="badge-icon">✅</span>
                <span>Kursa Kayıtlısınız</span>
              </div>
              <button 
                v-else-if="authStore.isAuthenticated"
                @click="enrollCourse"
                class="enroll-btn"
                :disabled="enrolling"
              >
                {{ enrolling ? 'İşleniyor...' : `₺${course.price} - Kayıt Ol` }}
              </button>
              <RouterLink 
                v-else
                to="/login" 
                class="login-btn"
              >
                Giriş Yapın
              </RouterLink>
            </div>
          </div>
          
          <div class="course-image">
            <img :src="course.image_url || 'https://via.placeholder.com/500x300'" :alt="course.title">
          </div>
        </div>
      </section>

      <!-- Course Content -->
      <section class="course-content" v-if="isEnrolled">
        <div class="container">
          <div class="content-header">
            <h2>📚 Kurs İçeriği</h2>
          </div>
          
          <div class="content-body">
            <div v-if="course.video_url" class="video-section">
              <h3>🎥 Video İçerik</h3>
              <div class="video-container">
                <iframe 
                  :src="getEmbedUrl(course.video_url)" 
                  frameborder="0" 
                  allowfullscreen
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  class="course-video"
                ></iframe>
              </div>
            </div>
            
            <div v-if="course.content" class="text-content">
              <h3>📖 Ders Notları</h3>
              <div class="content-text" v-html="course.content"></div>
            </div>
            
            <div v-if="!course.content && !course.video_url" class="no-content">
              <div class="no-content-icon">📝</div>
              <h3>İçerik Hazırlanıyor</h3>
              <p>Bu kursun içeriği henüz hazırlanmaktadır. Yakında eklenecektir.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Access Denied -->
      <section class="access-denied" v-else-if="!isEnrolled">
        <div class="container">
          <div class="denied-card">
            <div class="denied-icon">🔒</div>
            <h3>İçerik Kilitli</h3>
            <p>Bu kursun içeriğini görmek için kursa kayıt olmanız gerekir.</p>
            <button 
              v-if="authStore.isAuthenticated"
              @click="enrollCourse"
              class="unlock-btn"
              :disabled="enrolling"
            >
              {{ enrolling ? 'İşleniyor...' : 'Kursa Kayıt Ol' }}
            </button>
            <RouterLink 
              v-else
              to="/login" 
              class="unlock-btn"
            >
              Giriş Yapın
            </RouterLink>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const course = ref(null)
const loading = ref(true)
const enrolling = ref(false)
const isEnrolled = ref(false)

const loadCourse = async () => {
  try {
    const courseId = route.params.id
    const response = await api.get(`/courses/${courseId}`)
    
    if (response.data.success) {
      course.value = response.data.course
      isEnrolled.value = response.data.is_enrolled
    }
  } catch (error) {
    console.error('Course loading error:', error)
    router.push('/courses')
  } finally {
    loading.value = false
  }
}

const enrollCourse = async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  enrolling.value = true
  try {
    const response = await api.post(`/courses/${course.value.id}/enroll`)
    
    if (response.data.success) {
      alert('Kursa başarıyla kaydoldunuz!')
      await loadCourse() // Reload to show content
    }
  } catch (error) {
    console.error('Enrollment error:', error)
    const errorData = error.response?.data
    
    if (errorData?.requires_premium) {
      const upgrade = confirm(errorData.message + '\n\nPremium üyeliğe yükseltmek ister misiniz?')
      if (upgrade) {
        router.push('/premium')
      }
    } else {
      const message = errorData?.message || 'Kayıt işlemi sırasında bir hata oluştu!'
      alert(message)
    }
  } finally {
    enrolling.value = false
  }
}

const getLevelText = (level: string) => {
  const levels = {
    'beginner': 'Başlangıç',
    'intermediate': 'Orta',
    'advanced': 'İleri'
  }
  return levels[level] || level
}

const formatDuration = (minutes: number) => {
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  
  if (hours > 0) {
    return `${hours}s ${mins}dk`
  }
  return `${mins} dakika`
}

const getEmbedUrl = (url: string) => {
  if (!url) return ''
  
  // YouTube URL dönüştürme
  if (url.includes('youtube.com/watch?v=')) {
    const videoId = url.split('v=')[1]?.split('&')[0]
    return `https://www.youtube.com/embed/${videoId}`
  }
  
  if (url.includes('youtu.be/')) {
    const videoId = url.split('youtu.be/')[1]?.split('?')[0]
    return `https://www.youtube.com/embed/${videoId}`
  }
  
  // Vimeo URL dönüştürme
  if (url.includes('vimeo.com/')) {
    const videoId = url.split('vimeo.com/')[1]?.split('?')[0]
    return `https://player.vimeo.com/video/${videoId}`
  }
  
  // Zaten embed URL ise olduğu gibi döndür
  if (url.includes('/embed/') || url.includes('player.vimeo.com')) {
    return url
  }
  
  // Diğer durumlar için olduğu gibi döndür
  return url
}

onMounted(() => {
  loadCourse()
})
</script>

<style scoped>
.course-detail-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 50vh;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f4f6;
  border-top: 4px solid #ec4899;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.course-header {
  padding: 60px 0;
  background: white;
}

.course-header .container {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 60px;
  align-items: center;
}

.course-meta {
  display: flex;
  gap: 12px;
  margin-bottom: 16px;
}

.category, .level {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 600;
}

.category {
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
}

.level {
  background: #f3f4f6;
  color: #374151;
}

.course-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 16px;
  line-height: 1.2;
}

.course-description {
  font-size: 1.2rem;
  color: #6b7280;
  line-height: 1.6;
  margin-bottom: 32px;
}

.course-stats {
  display: flex;
  gap: 24px;
  margin-bottom: 32px;
  flex-wrap: wrap;
}

.stat {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #374151;
  font-weight: 500;
}

.stat-icon {
  font-size: 1.1rem;
}

.enrollment-section {
  margin-top: 32px;
}

.enrolled-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 600;
  display: inline-flex;
}

.enroll-btn, .login-btn, .unlock-btn {
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  border: none;
  padding: 16px 32px;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}

.enroll-btn:hover:not(:disabled),
.login-btn:hover,
.unlock-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.enroll-btn:disabled,
.unlock-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.course-image img {
  width: 100%;
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.course-content {
  padding: 80px 0;
  background: white;
}

.content-header {
  text-align: center;
  margin-bottom: 60px;
}

.content-header h2 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1f2937;
}

.content-body {
  max-width: 800px;
  margin: 0 auto;
}

.video-section, .text-content {
  margin-bottom: 60px;
}

.video-section h3, .text-content h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 24px;
}

.video-container {
  position: relative;
  width: 100%;
  height: 0;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.course-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.content-text {
  background: #f9fafb;
  padding: 32px;
  border-radius: 16px;
  line-height: 1.8;
  color: #374151;
}

.no-content {
  text-align: center;
  padding: 60px 32px;
  background: #f9fafb;
  border-radius: 16px;
}

.no-content-icon {
  font-size: 4rem;
  margin-bottom: 16px;
}

.no-content h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 12px;
}

.no-content p {
  color: #6b7280;
}

.access-denied {
  padding: 80px 0;
  background: white;
}

.denied-card {
  text-align: center;
  max-width: 500px;
  margin: 0 auto;
  padding: 60px 40px;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  border-radius: 20px;
  border: 2px solid #f59e0b;
}

.denied-icon {
  font-size: 4rem;
  margin-bottom: 24px;
}

.denied-card h3 {
  font-size: 1.8rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 16px;
}

.denied-card p {
  color: #6b7280;
  margin-bottom: 32px;
  font-size: 1.1rem;
}

@media (max-width: 768px) {
  .course-header .container {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  
  .course-title {
    font-size: 2rem;
  }
  
  .course-stats {
    flex-direction: column;
    gap: 12px;
  }
}
</style>