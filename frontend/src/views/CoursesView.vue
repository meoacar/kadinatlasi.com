<template>
  <div class="courses-page">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-content">
        <h1 class="hero-title">📚 Eğitim & Atölyeler</h1>
        <p class="hero-subtitle">Uzmanlardan öğrenin, kendinizi geliştirin</p>
      </div>
    </section>



    <!-- Courses Section -->
    <section class="courses-section">
      <div class="container">
        <div v-if="loading" class="loading-state">
          <div class="spinner"></div>
          <p>Kurslar yükleniyor...</p>
        </div>

        <div v-else class="courses-grid">
          <div 
            v-for="course in courses" 
            :key="course.id"
            class="course-card"
          >
            <div class="course-image">
              <img :src="course.image_url || 'https://via.placeholder.com/400x250'" :alt="course.title">
              <div class="course-level">{{ getLevelText(course.level) }}</div>
            </div>
            
            <div class="course-content">
              <div class="course-category">{{ course.category }}</div>
              <h3 class="course-title">{{ course.title }}</h3>
              <p class="course-description">{{ course.description }}</p>
              
              <div class="course-meta">
                <div class="instructor">
                  <span class="instructor-icon">👩‍🏫</span>
                  {{ course.instructor_name }}
                </div>
                <div class="duration">
                  <span class="duration-icon">⏱️</span>
                  {{ formatDuration(course.duration) }}
                </div>
              </div>
              
              <div class="course-stats">
                <div class="enrollment-count">
                  <span class="stats-icon">👥</span>
                  {{ course.enrollment_count }} kayıt
                </div>
                <div class="rating" v-if="course.rating > 0">
                  <span class="rating-icon">⭐</span>
                  {{ course.rating }}
                </div>
              </div>
              
              <div class="course-footer">
                <div class="price">₺{{ course.price }}</div>
                <div class="course-actions">
                  <RouterLink 
                    :to="`/courses/${course.id}`"
                    class="detail-btn"
                  >
                    Detay
                  </RouterLink>
                  <RouterLink 
                    v-if="course.is_enrolled"
                    :to="`/courses/${course.id}`"
                    class="enter-btn"
                  >
                    Kursa Gir
                  </RouterLink>
                  <button 
                    v-else
                    @click="handleEnrollClick(course.id)"
                    class="enroll-btn"
                    :disabled="enrolling"
                  >
                    {{ enrolling ? 'İşleniyor...' : 'Kayıt Ol' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!loading && courses.length === 0" class="empty-state">
          <div class="empty-icon">📚</div>
          <h3>Henüz kurs bulunmuyor</h3>
          <p>Yakında yeni kurslar eklenecek!</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const authStore = useAuthStore()
const router = useRouter()

const courses = ref([])
const loading = ref(true)
const enrolling = ref(false)
const userPremiumStatus = ref(null)
const isAdmin = ref(false)

const loadCourses = async () => {
  try {
    const response = await api.get('/courses')
    if (response.data.success) {
      courses.value = response.data.courses
    }
  } catch (error) {
    console.error('Courses loading error:', error)
  } finally {
    loading.value = false
  }
}

const checkPremiumStatus = async () => {
  if (!authStore.isAuthenticated) return
  
  try {
    // Check if admin
    isAdmin.value = authStore.user?.email === 'admin@kadinatlasi.com'
    
    if (!isAdmin.value) {
      const response = await api.get('/premium/subscription')
      if (response.data.success) {
        userPremiumStatus.value = response.data.subscription
      }
    }
  } catch (error) {
    console.error('Premium status check error:', error)
  }
}

const handleEnrollClick = async (courseId: number) => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  await enrollCourse(courseId)
}

const enrollCourse = async (courseId: number) => {
  enrolling.value = true
  try {
    const response = await api.post(`/courses/${courseId}/enroll`)
    
    if (response.data.success) {
      alert('Kursa başarıyla kaydoldunuz!')
      // Refresh courses to update enrollment count
      await loadCourses()
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

onMounted(() => {
  loadCourses()
  checkPremiumStatus()
})
</script>

<style scoped>
.courses-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%);
}

.premium-notice {
  padding: 40px 20px;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
}

.notice-card {
  background: white;
  border-radius: 16px;
  padding: 32px;
  display: flex;
  align-items: center;
  gap: 24px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  border: 2px solid #f59e0b;
}

.notice-icon {
  font-size: 3rem;
}

.notice-content h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 8px;
}

.notice-content p {
  color: #6b7280;
  margin-bottom: 16px;
}

.upgrade-btn {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  padding: 12px 24px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-block;
}

.upgrade-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
}

.hero-section {
  background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
  padding: 80px 20px;
  text-align: center;
  color: white;
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 16px;
}

.hero-subtitle {
  font-size: 1.25rem;
  opacity: 0.9;
}

.courses-section {
  padding: 80px 20px;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 32px;
}

.course-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.course-card:hover {
  transform: translateY(-8px);
}

.course-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.course-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.course-level {
  position: absolute;
  top: 16px;
  right: 16px;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.875rem;
  font-weight: 600;
}

.course-content {
  padding: 24px;
}

.course-category {
  color: #ec4899;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 8px;
}

.course-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 12px;
  line-height: 1.4;
}

.course-description {
  color: #6b7280;
  line-height: 1.6;
  margin-bottom: 16px;
}

.course-meta {
  display: flex;
  gap: 16px;
  margin-bottom: 16px;
  font-size: 0.875rem;
  color: #6b7280;
}

.instructor, .duration {
  display: flex;
  align-items: center;
  gap: 6px;
}

.course-stats {
  display: flex;
  gap: 16px;
  margin-bottom: 20px;
  font-size: 0.875rem;
  color: #6b7280;
}

.enrollment-count, .rating {
  display: flex;
  align-items: center;
  gap: 6px;
}

.course-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.course-actions {
  display: flex;
  gap: 8px;
}

.detail-btn {
  background: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.detail-btn:hover {
  background: #e5e7eb;
  transform: translateY(-1px);
}

.price {
  font-size: 1.5rem;
  font-weight: 800;
  color: #ec4899;
}

.enroll-btn, .enter-btn {
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  text-align: center;
  transition: all 0.3s ease;
}

.enter-btn {
  background: linear-gradient(135deg, #10b981, #059669);
}

.enroll-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.enter-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

.enroll-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.loading-state, .empty-state {
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

.empty-icon {
  font-size: 4rem;
  margin-bottom: 16px;
}

.empty-state h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 8px;
}

.empty-state p {
  color: #6b7280;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .courses-grid {
    grid-template-columns: 1fr;
  }
  
  .course-meta {
    flex-direction: column;
    gap: 8px;
  }
}
</style>