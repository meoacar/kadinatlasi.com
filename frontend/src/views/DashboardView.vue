<template>
  <div class="dashboard-page">
    <!-- Welcome Header -->
    <div class="welcome-header">
      <div class="welcome-content">
        <div class="user-greeting">
          <h1 class="greeting-title">
            Merhaba {{ user?.name || 'Kullanıcı' }}! 👋
          </h1>
          <p class="greeting-subtitle">
            {{ getGreetingMessage() }}
          </p>
        </div>
        <div class="user-stats">
          <!-- Notification Bell -->
          <div class="notification-bell" @click="toggleNotifications">
            <div class="relative cursor-pointer p-2 hover:bg-white/20 rounded-lg transition-colors">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h0z"/>
              </svg>
              <div v-if="unreadCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ unreadCount > 9 ? '9+' : unreadCount }}
              </div>
            </div>
          </div>
          
          <div class="stat-item">
            <div class="stat-number">{{ gamificationStats?.points || 0 }}</div>
            <div class="stat-label">Puan</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">{{ gamificationStats?.level || 1 }}</div>
            <div class="stat-label">Seviye</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">{{ gamificationStats?.daily_streak || 0 }}</div>
            <div class="stat-label">Gün Serisi</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
      
      <!-- Daily Check-in -->
      <DailyCheckin />



      <!-- Quick Actions -->
      <div class="dashboard-card">
        <h2 class="card-title">⚡ Hızlı İşlemler</h2>
        <div class="quick-actions">
          <button @click="trackAndNavigate('/hesaplama/vki', 'calculator_bmi_use')" class="action-btn">
            <span class="action-icon">⚖️</span>
            <span class="action-text">VKİ Hesapla</span>
          </button>
          <button @click="trackAndNavigate('/hesaplama/regl-takvimi', 'calculator_menstrual_use')" class="action-btn">
            <span class="action-icon">📅</span>
            <span class="action-text">Regl Takvimi</span>
          </button>
          <button @click="trackAndNavigate('/hesaplama/gebelik', 'calculator_pregnancy_use')" class="action-btn">
            <span class="action-icon">🤱</span>
            <span class="action-text">Gebelik Hesapla</span>
          </button>
          <button @click="trackAndNavigate('/hesaplama/kalori', 'calculator_calorie_use')" class="action-btn">
            <span class="action-icon">🍎</span>
            <span class="action-text">Kalori Hesapla</span>
          </button>
          <button @click="$router.push('/forum')" class="action-btn">
            <span class="action-icon">💬</span>
            <span class="action-text">Forum</span>
          </button>
          <button @click="$router.push('/profile')" class="action-btn">
            <span class="action-icon">👤</span>
            <span class="action-text">Profil</span>
          </button>
        </div>
      </div>

      <!-- Health Tracker -->
      <div class="dashboard-card">
        <h2 class="card-title">🏥 Sağlık Takibi</h2>
        <div class="health-tracker">
          <div class="health-item">
            <div class="health-icon">💧</div>
            <div class="health-info">
              <div class="health-title">Su İçme</div>
              <div class="health-progress">
                <div class="progress-bar">
                  <div class="progress-fill" :style="{ width: waterProgress + '%' }"></div>
                </div>
                <span class="progress-text">{{ waterGlasses }}/8 bardak</span>
              </div>
            </div>
            <button @click.prevent="addWaterGlass" class="health-btn" type="button">+</button>
          </div>
          
          <div class="health-item">
            <div class="health-icon">🚶♀️</div>
            <div class="health-info">
              <div class="health-title">Günlük Adım</div>
              <div class="health-progress">
                <div class="progress-bar">
                  <div class="progress-fill" :style="{ width: stepProgress + '%' }"></div>
                </div>
                <span class="progress-text">{{ steps }}/10000 adım</span>
              </div>
            </div>
            <button @click.prevent="addSteps" class="health-btn" type="button">+</button>
          </div>
          
          <div class="health-item">
            <div class="health-icon">💪</div>
            <div class="health-info">
              <div class="health-title">Fitness</div>
              <div class="health-text">{{ fitnessStats?.today_minutes || 0 }} dk antrenman</div>
              <div class="health-subtext">{{ fitnessStats?.today_calories || 0 }} kalori yakıldı</div>
            </div>
            <button @click.prevent="$router.push('/fitness')" class="health-btn" type="button">→</button>
          </div>
          
          <div class="health-item">
            <div class="health-icon">😊</div>
            <div class="health-info">
              <div class="health-title">Ruh Hali</div>
              <div class="health-text">{{ currentMood || 'Belirtilmemiş' }}</div>
              <div class="health-subtext">{{ moodStats?.streak || 0 }} günlük takip</div>
            </div>
            <button @click.prevent="$router.push('/psikoloji')" class="health-btn" type="button">→</button>
          </div>

          <div class="health-item period-tracker" :class="getPeriodStatus()">
            <div class="health-icon">📅</div>
            <div class="health-info">
              <div class="health-title">Regl Döngüsü</div>
              <div class="health-text">{{ getNextPeriodText() }}</div>
              <div class="health-subtext">Son: {{ getLastPeriodInfo() }}</div>
            </div>
            <button @click.prevent="$router.push('/profile')" class="health-btn period-btn" type="button">
              <svg class="period-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Daily Quote -->
      <div class="dashboard-card">
        <h2 class="card-title">💫 Günün Sözü</h2>
        <div class="daily-quote" v-if="dailyQuote">
          <div class="quote-icon">✨</div>
          <p class="quote-text">{{ dailyQuote.quote }}</p>
          <p class="quote-category">{{ dailyQuote.category }}</p>
        </div>
        <div v-else class="quote-loading">Yükleniyor...</div>
      </div>

      <!-- Horoscope -->
      <div class="dashboard-card" v-if="user?.zodiac_sign">
        <h2 class="card-title">⭐ Günlük Burç Yorumu</h2>
        <div class="horoscope-content" v-if="horoscope">
          <div class="horoscope-header">
            <span class="zodiac-icon">{{ getZodiacIcon(user.zodiac_sign) }}</span>
            <span class="zodiac-name">{{ user.zodiac_sign }}</span>
          </div>
          <p class="horoscope-text">{{ horoscope.general }}</p>
          <div class="horoscope-scores">
            <div class="score-item">
              <span class="score-label">Aşk</span>
              <div class="score-stars">{{ '⭐'.repeat(horoscope.love_score || 3) }}</div>
            </div>
            <div class="score-item">
              <span class="score-label">Kariyer</span>
              <div class="score-stars">{{ '⭐'.repeat(horoscope.career_score || 3) }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activities -->
      <div class="dashboard-card full-width">
        <h2 class="card-title">📊 Son Aktiviteler</h2>
        <div class="activities-grid">
          <div class="activity-section">
            <h3 class="section-title">📝 Son Blog Yazıları</h3>
            <div class="activity-list" v-if="recentBlogs.length">
              <div v-for="blog in recentBlogs" :key="blog.id" 
                   @click="$router.push(`/blog/${blog.id}`)"
                   class="activity-item">
                <h4 class="activity-title">{{ blog.title }}</h4>
                <p class="activity-excerpt">{{ blog.excerpt }}</p>
                <span class="activity-date">{{ formatDate(blog.created_at) }}</span>
              </div>
            </div>
            <div v-else class="no-data">Henüz blog yazısı yok</div>
          </div>

          <div class="activity-section">
            <h3 class="section-title">💬 Forum Aktiviteleri</h3>
            <div class="activity-list" v-if="recentForumTopics.length">
              <div v-for="topic in recentForumTopics" :key="topic.id"
                   @click="$router.push(`/forum/topic/${topic.id}`)"
                   class="activity-item">
                <h4 class="activity-title">{{ topic.title }}</h4>
                <p class="activity-excerpt">{{ topic.content?.substring(0, 100) }}...</p>
                <span class="activity-date">{{ formatDate(topic.created_at) }}</span>
              </div>
            </div>
            <div v-else class="no-data">Henüz forum aktivitesi yok</div>
          </div>
        </div>
      </div>



    </div>
    
    <!-- Notification Dropdown -->
    <div v-if="showNotifications" class="fixed inset-0 z-50" @click="showNotifications = false">
      <div class="absolute top-20 right-4 w-80 bg-white rounded-lg shadow-xl border border-gray-200" @click.stop>
        <div class="p-4 border-b border-gray-100">
          <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Bildirimler</h3>
            <div class="flex items-center gap-2">
              <PushNotificationButton />
              <router-link to="/notifications" class="text-sm text-pink-500 hover:text-pink-600">
                Tümü
              </router-link>
            </div>
          </div>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
          <div v-if="notifications.length" class="divide-y divide-gray-100">
            <div v-for="notification in notifications.slice(0, 5)" :key="notification.id" 
                 class="p-4 hover:bg-gray-50 transition-colors">
              <div class="flex items-start gap-3">
                <div class="text-lg">{{ getNotificationIcon(notification.type) }}</div>
                <div class="flex-1 min-w-0">
                  <div class="font-medium text-sm text-gray-900">{{ notification.title }}</div>
                  <div class="text-xs text-gray-600 mt-1">{{ notification.message }}</div>
                  <div class="text-xs text-gray-400 mt-1">{{ formatDate(notification.created_at) }}</div>
                </div>
                <div v-if="!notification.read_at" class="w-2 h-2 bg-pink-500 rounded-full mt-1"></div>
              </div>
            </div>
          </div>
          
          <div v-else class="p-8 text-center text-gray-500">
            <div class="text-4xl mb-2">🔔</div>
            <div class="text-sm">Henüz bildirim yok</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, nextTick } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import DailyCheckin from '@/components/DailyCheckin.vue'
import PushNotificationButton from '@/components/PushNotificationButton.vue'

const authStore = useAuthStore()
const router = useRouter()
const user = computed(() => authStore.user)
const profile = ref(null)
const dailyQuote = ref(null)
const horoscope = ref(null)
const recentBlogs = ref([])
const recentForumTopics = ref([])
const notifications = ref([])
const waterGlasses = ref(0)
const steps = ref(0)
const streakDays = ref(0)
const gamificationStats = ref({
  level: 1,
  points: 0,
  daily_streak: 0,
  achievements_count: 0
})
const fitnessStats = ref({})
const currentMood = ref('')
const moodStats = ref({})
const unreadCount = ref(0)
const showNotifications = ref(false)

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value
}

const getNotificationIcon = (type: string) => {
  const icons: Record<string, string> = {
    forum_reply: '💬',
    blog_comment: '📝',
    menstrual_reminder: '🌸',
    water_reminder: '💧',
    exercise_reminder: '💪',
    achievement: '🏆',
    default: '🔔'
  }
  return icons[type] || icons.default
}

const waterProgress = computed(() => {
  console.log('Water progress computed:', waterGlasses.value, (waterGlasses.value / 8) * 100)
  return (waterGlasses.value / 8) * 100
})
const stepProgress = computed(() => {
  console.log('Step progress computed:', steps.value, (steps.value / 10000) * 100)
  return (steps.value / 10000) * 100
})

const getGreetingMessage = () => {
  const hour = new Date().getHours()
  if (hour < 12) return "Günaydın! Harika bir gün sizi bekliyor ✨"
  if (hour < 18) return "İyi günler! Gününüz nasıl geçiyor? 🌞"
  return "İyi akşamlar! Dinlenmek için güzel bir zaman 🌙"
}

const getZodiacIcon = (sign: string) => {
  const icons = {
    'Koç': '♈', 'Boğa': '♉', 'İkizler': '♊', 'Yengeç': '♋',
    'Aslan': '♌', 'Başak': '♍', 'Terazi': '♎', 'Akrep': '♏',
    'Yay': '♐', 'Oğlak': '♑', 'Kova': '♒', 'Balık': '♓'
  }
  return icons[sign] || '⭐'
}

const getDailyQuote = () => {
  const quotes = [
    { quote: "Her yeni gün, kendini daha iyi hissetmek için yeni bir fırsattır.", category: "Motivasyon" },
    { quote: "Güçlü kadınlar birbirini destekler, yıkmaz.", category: "Güçlendirme" },
    { quote: "Kendine olan sevgin, başkalarından alabileceğin en büyük hediyedir.", category: "Öz Sevgi" },
    { quote: "Hayallerinin peşinden koşmaktan asla vazgeçme.", category: "Hedefler" },
    { quote: "Sen kendi hikayenin yazarısın, sayfaları güzel doldur.", category: "Kişisel Gelişim" }
  ]
  
  const today = new Date()
  const dayOfMonth = today.getDate()
  const quoteIndex = (dayOfMonth - 1) % quotes.length
  return quotes[quoteIndex]
}

const getDailyHoroscope = (zodiacSign: string) => {
  const horoscopes = {
    'Koç': { general: "Bugün enerjiniz yüksek! Yeni projelere başlamak için ideal bir gün.", love_score: 4, career_score: 5 },
    'Boğa': { general: "Sabır ve kararlılığınız bugün size avantaj sağlayacak.", love_score: 3, career_score: 4 },
    'İkizler': { general: "İletişim yetenekleriniz bugün ön planda.", love_score: 5, career_score: 3 }
  }
  
  return horoscopes[zodiacSign] || { general: "Bugün kendinize odaklanın.", love_score: 3, career_score: 3 }
}

const getNextPeriodText = () => {
  if (!profile.value?.last_period_date) return 'Bilgi girilmemiş'
  const lastDate = new Date(profile.value.last_period_date)
  const cycleLength = profile.value.cycle_length || 28
  const nextDate = new Date(lastDate.getTime() + cycleLength * 24 * 60 * 60 * 1000)
  const today = new Date()
  const diffDays = Math.ceil((nextDate.getTime() - today.getTime()) / (24 * 60 * 60 * 1000))
  
  if (diffDays < 0) return `${Math.abs(diffDays)} gün geç kaldı`
  if (diffDays === 0) return 'Bugün bekleniyor'
  if (diffDays === 1) return 'Yarın bekleniyor'
  return `${diffDays} gün sonra`
}

const getLastPeriodInfo = () => {
  if (!profile.value?.last_period_date) return 'Bilgi girilmemiş'
  const lastDate = new Date(profile.value.last_period_date)
  const today = new Date()
  const diffDays = Math.floor((today.getTime() - lastDate.getTime()) / (24 * 60 * 60 * 1000))
  
  if (diffDays === 0) return 'Bugün başladı'
  if (diffDays === 1) return 'Dün başladı'
  return `${diffDays} gün önce`
}

const getPeriodStatus = () => {
  if (!profile.value?.last_period_date) return 'normal'
  const lastDate = new Date(profile.value.last_period_date)
  const cycleLength = profile.value.cycle_length || 28
  const nextDate = new Date(lastDate.getTime() + cycleLength * 24 * 60 * 60 * 1000)
  const today = new Date()
  const diffDays = Math.ceil((nextDate.getTime() - today.getTime()) / (24 * 60 * 60 * 1000))
  
  if (diffDays < -3) return 'late'
  if (diffDays <= 3 && diffDays >= -1) return 'soon'
  return 'normal'
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('tr-TR', { 
    day: 'numeric', 
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const addWaterGlass = async () => {
  console.log('Adding water glass, current:', waterGlasses.value)
  if (waterGlasses.value < 8) {
    waterGlasses.value++
    console.log('New water glasses:', waterGlasses.value)
    await nextTick()
    saveHealthData()
  }
}

const addSteps = async () => {
  console.log('Adding steps, current:', steps.value)
  steps.value += 1000
  if (steps.value > 10000) steps.value = 10000
  console.log('New steps:', steps.value)
  await nextTick()
  saveHealthData()
}

const saveHealthData = async () => {
  try {
    const today = new Date().toISOString().split('T')[0]
    localStorage.setItem(`water_${today}`, waterGlasses.value.toString())
    localStorage.setItem(`steps_${today}`, steps.value.toString())
    
    await api.put('/profile', {
      health_data: {
        water_glasses: waterGlasses.value,
        steps: steps.value,
        date: today
      }
    })
    
    // Track water intake action
    if (waterGlasses.value > 0) {
      await api.post('/gamification/track', {
        action_type: 'calculator_water_use',
        action_target: 'dashboard',
        metadata: { glasses: waterGlasses.value }
      })
    }
  } catch (error) {
    console.error('Health data save error:', error)
  }
}

const trackLogin = async () => {
  try {
    await api.post('/gamification/track', {
      action_type: 'login',
      action_target: 'dashboard',
      metadata: { timestamp: new Date().toISOString() }
    })
  } catch (error) {
    console.error('Error tracking login:', error)
  }
}

const trackAndNavigate = async (route: string, actionType: string) => {
  try {
    await api.post('/gamification/track', {
      action_type: actionType,
      action_target: 'dashboard_quick_action',
      metadata: { route }
    })
  } catch (error) {
    console.error('Error tracking action:', error)
  }
  router.push(route)
}

const loadGamificationStats = async () => {
  try {
    const response = await api.get('/gamification/stats')
    if (response.data.success) {
      gamificationStats.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading gamification stats:', error)
  }
}

const loadDashboardData = async () => {
  try {
    const profileResponse = await api.get('/profile')
    if (profileResponse.data.success) {
      profile.value = profileResponse.data.profile
    }

    dailyQuote.value = getDailyQuote()

    if (user.value?.zodiac_sign) {
      horoscope.value = getDailyHoroscope(user.value.zodiac_sign)
    }

    const blogsResponse = await api.get('/blog-posts?limit=3')
    if (blogsResponse.data.success) {
      recentBlogs.value = blogsResponse.data.data
    }

    const forumResponse = await api.get('/forum/topics?limit=3')
    if (forumResponse.data.success) {
      recentForumTopics.value = forumResponse.data.data
    }

    const notificationsResponse = await api.get('/notifications?limit=5')
    if (notificationsResponse.data.success) {
      notifications.value = notificationsResponse.data.data
      unreadCount.value = notifications.value.filter(n => !n.read_at).length
    }

    const fitnessResponse = await api.get('/fitness/stats')
    if (fitnessResponse.data.success) {
      fitnessStats.value = fitnessResponse.data.stats
    }

    const moodResponse = await api.get('/mood-tracker/stats')
    if (moodResponse.data.success) {
      moodStats.value = moodResponse.data.stats
      currentMood.value = moodResponse.data.current_mood || 'Belirtilmemiş'
    }

  } catch (error) {
    console.error('Dashboard data load error:', error)
  }
}

onMounted(async () => {
  await trackLogin()
  await loadGamificationStats()
  loadDashboardData()
  
  const today = new Date().toISOString().split('T')[0]
  const savedWater = localStorage.getItem(`water_${today}`)
  const savedSteps = localStorage.getItem(`steps_${today}`)
  
  if (savedWater) waterGlasses.value = parseInt(savedWater)
  if (savedSteps) steps.value = parseInt(savedSteps)
})
</script>

<style scoped>
.dashboard-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%);
  padding: 20px;
}

.welcome-header {
  background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
  border-radius: 20px;
  padding: 40px;
  margin-bottom: 32px;
  color: white;
  box-shadow: 0 10px 25px rgba(236, 72, 153, 0.3);
}

.welcome-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}

.greeting-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 8px;
}

.greeting-subtitle {
  font-size: 1.2rem;
  opacity: 0.9;
}

.user-stats {
  display: flex;
  gap: 32px;
}

.stat-item {
  text-align: center;
}

.stat-number {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 0.9rem;
  opacity: 0.8;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 24px;
  max-width: 1200px;
  margin: 0 auto;
}

.dashboard-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.dashboard-card:hover {
  transform: translateY(-2px);
}

.dashboard-card.full-width {
  grid-column: 1 / -1;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 20px;
}

.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 12px;
}

.action-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 16px;
  background: linear-gradient(135deg, #f9fafb, #f3f4f6);
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.action-btn:hover {
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  transform: translateY(-2px);
}

.action-icon {
  font-size: 1.5rem;
}

.action-text {
  font-size: 0.875rem;
  font-weight: 600;
}

.health-tracker {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.health-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: #f9fafb;
  border-radius: 12px;
}

.health-icon {
  font-size: 1.5rem;
}

.health-info {
  flex: 1;
}

.health-title {
  font-weight: 600;
  color: #374151;
  margin-bottom: 4px;
}

.health-progress {
  display: flex;
  align-items: center;
  gap: 8px;
}

.progress-bar {
  flex: 1;
  height: 8px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  transition: width 0.3s ease;
}

.progress-text {
  font-size: 0.75rem;
  color: #6b7280;
  white-space: nowrap;
}

.health-text {
  font-size: 0.875rem;
  color: #6b7280;
}

.health-subtext {
  font-size: 0.75rem;
  color: #9ca3af;
  margin-top: 2px;
}

.health-btn {
  width: 32px;
  height: 32px;
  background: #ec4899;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.2rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.health-btn:hover {
  background: #db2777;
  transform: scale(1.1);
}

.health-btn:active {
  transform: scale(0.95);
}

.period-tracker.soon {
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  border-left: 4px solid #f59e0b;
}

.period-tracker.late {
  background: linear-gradient(135deg, #fee2e2, #fecaca);
  border-left: 4px solid #ef4444;
}

.daily-quote {
  text-align: center;
  padding: 20px;
  background: linear-gradient(135deg, #fce7f3, #f3e8ff);
  border-radius: 12px;
}

.quote-icon {
  font-size: 2rem;
  margin-bottom: 12px;
}

.quote-text {
  font-style: italic;
  color: #7c3aed;
  font-size: 1rem;
  line-height: 1.6;
  margin-bottom: 8px;
}

.quote-category {
  font-size: 0.875rem;
  color: #8b5cf6;
  font-weight: 600;
}

.horoscope-content {
  text-align: center;
}

.horoscope-header {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 16px;
}

.zodiac-icon {
  font-size: 1.5rem;
}

.zodiac-name {
  font-weight: 600;
  color: #ec4899;
}

.horoscope-text {
  color: #374151;
  line-height: 1.6;
  margin-bottom: 16px;
}

.horoscope-scores {
  display: flex;
  justify-content: center;
  gap: 24px;
}

.score-item {
  text-align: center;
}

.score-label {
  display: block;
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 4px;
}

.activities-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
}

.activity-section {
  background: #f9fafb;
  padding: 20px;
  border-radius: 12px;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.activity-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.activity-item {
  padding: 12px;
  background: white;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.activity-item:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.activity-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 4px;
}

.activity-excerpt {
  font-size: 0.75rem;
  color: #6b7280;
  margin-bottom: 4px;
}

.activity-date {
  font-size: 0.75rem;
  color: #9ca3af;
}

.notifications-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 16px;
}

.notification-item {
  padding: 12px;
  background: #f9fafb;
  border-radius: 8px;
  border-left: 4px solid #e5e7eb;
}

.notification-item.unread {
  border-left-color: #ec4899;
  background: #fef7f0;
}

.notification-text {
  font-size: 0.875rem;
  color: #374151;
  margin-bottom: 4px;
}

.notification-time {
  font-size: 0.75rem;
  color: #9ca3af;
}

.view-all-btn {
  width: 100%;
  padding: 8px;
  background: none;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  color: #6b7280;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.view-all-btn:hover {
  border-color: #ec4899;
  color: #ec4899;
}

.no-data {
  text-align: center;
  color: #9ca3af;
  font-size: 0.875rem;
  padding: 20px;
}

.notification-bell {
  margin-right: 20px;
}

/* Responsive */
@media (max-width: 768px) {
  .welcome-content {
    flex-direction: column;
    text-align: center;
    gap: 24px;
  }
  
  .greeting-title {
    font-size: 2rem;
  }
  
  .user-stats {
    gap: 20px;
  }
  
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .quick-actions {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .activities-grid {
    grid-template-columns: 1fr;
  }
}
</style>