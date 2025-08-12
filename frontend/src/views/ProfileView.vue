<template>
  <div class="profile-page">
    <!-- Header -->
    <div class="profile-header">
      <div class="header-content">
        <div class="avatar-section">
          <div class="avatar-container">
            <img v-if="user?.avatar" :src="getAvatarUrl(user.avatar)" :alt="user.name" class="avatar-image">
            <div v-else class="avatar-placeholder">
              <span class="avatar-initial">{{ user?.name?.charAt(0) || 'U' }}</span>
            </div>
            <input 
              ref="avatarInput" 
              type="file" 
              accept="image/*" 
              @change="handleAvatarUpload" 
              style="display: none;"
            >
            <button 
              class="avatar-edit-btn" 
              @click="$refs.avatarInput.click()" 
              :disabled="isUploadingAvatar"
            >
              <div v-if="isUploadingAvatar" class="loading-spinner"></div>
              <svg v-else class="edit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 616 0z"/>
              </svg>
            </button>
          </div>
          <div class="user-info">
            <h1 class="user-name">{{ user?.name }}</h1>
            <p class="user-email">{{ user?.email }}</p>
            <div v-if="user?.zodiac_sign" class="zodiac-info">
              <span class="zodiac-icon">{{ getZodiacIcon(user.zodiac_sign) }}</span>
              <span class="zodiac-name">{{ user.zodiac_sign }}</span>
            </div>
            <div v-if="expertRank" class="expert-rank">
              <span class="expert-badge">{{ expertRank }}</span>
            </div>
            <div v-if="premiumRank" class="premium-rank">
              <span class="premium-badge">{{ premiumRank }}</span>
            </div>
          </div>
        </div>
        <div class="stats-section">
          <div class="stat-card">
            <div class="stat-number">{{ gamificationStats.points }}</div>
            <div class="stat-label">Puan</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ gamificationStats.level }}</div>
            <div class="stat-label">Seviye</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ gamificationStats.streak }}</div>
            <div class="stat-label">Seri</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ calculateAge() || '-' }}</div>
            <div class="stat-label">Yaş</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="profile-content">
      
      <!-- Navigation Tabs -->
      <div class="profile-tabs">
        <button 
          v-for="tab in tabs" 
          :key="tab.key"
          @click="activeTab = tab.key"
          class="tab-button"
          :class="{ active: activeTab === tab.key }"
        >
          <span class="tab-icon">{{ tab.icon }}</span>
          <span class="tab-name">{{ tab.name }}</span>
        </button>
      </div>

      <!-- Personal Info Tab -->
      <div v-if="activeTab === 'personal'" class="tab-content">
        <div class="content-card">
          <h2 class="card-title">Kişisel Bilgiler</h2>
          <form @submit.prevent="updatePersonalInfo" class="profile-form">
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Ad Soyad</label>
                <input v-model="personalForm.name" type="text" class="form-input" required>
              </div>
              <div class="form-group">
                <label class="form-label">E-posta</label>
                <input v-model="personalForm.email" type="email" class="form-input" required>
              </div>
              <div class="form-group">
                <label class="form-label">Doğum Tarihi</label>
                <input v-model="personalForm.birth_date" type="date" class="form-input" @change="calculateZodiac">
              </div>
              <div class="form-group">
                <label class="form-label">Burç</label>
                <div class="zodiac-display">
                  <span v-if="personalForm.zodiac_sign" class="zodiac-result">
                    {{ getZodiacIcon(personalForm.zodiac_sign) }} {{ personalForm.zodiac_sign }}
                  </span>
                  <span v-else class="zodiac-empty">Doğum tarihi girildiğinde otomatik hesaplanacak</span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Hakkımda</label>
              <textarea v-model="personalForm.bio" class="form-textarea" rows="4" placeholder="Kendiniz hakkında kısa bilgi..."></textarea>
            </div>
            <button type="submit" class="save-button" :disabled="isUpdating">
              {{ isUpdating ? 'Kaydediliyor...' : 'Bilgileri Kaydet' }}
            </button>
          </form>
        </div>
      </div>

      <!-- Health Info Tab -->
      <div v-if="activeTab === 'health'" class="tab-content">
        <div class="content-card">
          <h2 class="card-title">Sağlık Bilgileri</h2>
          <form @submit.prevent="updateHealthInfo" class="profile-form">
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Son Regl Tarihi</label>
                <input v-model="healthForm.last_period_date" type="date" class="form-input">
              </div>
              <div class="form-group">
                <label class="form-label">Döngü Süresi (gün)</label>
                <select v-model="healthForm.cycle_length" class="form-select">
                  <option value="">Seçiniz</option>
                  <option v-for="day in 15" :key="day + 20" :value="day + 20">{{ day + 20 }} gün</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Sağlık Durumu</label>
              <div class="checkbox-group">
                <label v-for="condition in healthConditions" :key="condition" class="checkbox-label">
                  <input 
                    type="checkbox" 
                    :value="condition" 
                    v-model="healthForm.health_conditions"
                    class="checkbox-input"
                  >
                  <span class="checkbox-text">{{ condition }}</span>
                </label>
              </div>
            </div>
            <button type="submit" class="save-button" :disabled="isUpdating">
              {{ isUpdating ? 'Kaydediliyor...' : 'Sağlık Bilgilerini Kaydet' }}
            </button>
          </form>
        </div>
        
        <!-- Menstrual Cycle Info -->
        <div class="content-card" v-if="healthForm.last_period_date" style="margin-top: 24px;">
          <h2 class="card-title">📅 Regl Döngüsü Bilgileri</h2>
          <div class="cycle-info-grid">
            <div class="cycle-info-item">
              <div class="cycle-icon">🩸</div>
              <div class="cycle-details">
                <div class="cycle-title">Son Regl Tarihi</div>
                <div class="cycle-value">{{ formatDate(healthForm.last_period_date) }}</div>
                <div class="cycle-subtitle">{{ getDaysAgo(healthForm.last_period_date) }} gün önce</div>
              </div>
            </div>
            
            <div class="cycle-info-item">
              <div class="cycle-icon">⏰</div>
              <div class="cycle-details">
                <div class="cycle-title">Sonraki Regl</div>
                <div class="cycle-value">{{ getNextPeriodDate() }}</div>
                <div class="cycle-subtitle">{{ getNextPeriodDays() }}</div>
              </div>
            </div>
            
            <div class="cycle-info-item">
              <div class="cycle-icon">🥚</div>
              <div class="cycle-details">
                <div class="cycle-title">Ovulasyon Tarihi</div>
                <div class="cycle-value">{{ getOvulationDate() }}</div>
                <div class="cycle-subtitle">{{ getOvulationDays() }}</div>
              </div>
            </div>
            
            <div class="cycle-info-item">
              <div class="cycle-icon">💕</div>
              <div class="cycle-details">
                <div class="cycle-title">Verimli Dönem</div>
                <div class="cycle-value">{{ getFertileWindow() }}</div>
                <div class="cycle-subtitle">{{ getFertileStatus() }}</div>
              </div>
            </div>
          </div>
          
          <!-- Cycle Calendar -->
          <div class="cycle-calendar">
            <h3 class="calendar-title">Döngü Takvimi</h3>
            <div class="calendar-legend">
              <div class="legend-item">
                <div class="legend-color period"></div>
                <span>Regl Dönemi</span>
              </div>
              <div class="legend-item">
                <div class="legend-color fertile"></div>
                <span>Verimli Dönem</span>
              </div>
              <div class="legend-item">
                <div class="legend-color ovulation"></div>
                <span>Ovulasyon</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Interests Tab -->
      <div v-if="activeTab === 'interests'" class="tab-content">
        <div class="content-card">
          <h2 class="card-title">İlgi Alanları</h2>
          <form @submit.prevent="updateInterests" class="profile-form">
            <div class="interests-grid">
              <label v-for="interest in availableInterests" :key="interest" class="interest-card">
                <input 
                  type="checkbox" 
                  :value="interest" 
                  v-model="interestsForm.interests"
                  class="interest-checkbox"
                >
                <div class="interest-content">
                  <span class="interest-icon">{{ getInterestIcon(interest) }}</span>
                  <span class="interest-name">{{ interest }}</span>
                </div>
              </label>
            </div>
            <button type="submit" class="save-button" :disabled="isUpdating">
              {{ isUpdating ? 'Kaydediliyor...' : 'İlgi Alanlarını Kaydet' }}
            </button>
          </form>
        </div>
      </div>

      <!-- Goals Tab -->
      <div v-if="activeTab === 'goals'" class="tab-content">
        <div class="content-card">
          <h2 class="card-title">Hedeflerim</h2>
          <form @submit.prevent="updateGoals" class="profile-form">
            <div class="goals-section">
              <div class="form-group">
                <label class="form-label">Yeni Hedef Ekle</label>
                <div class="goal-input-group">
                  <input 
                    v-model="newGoal" 
                    type="text" 
                    class="form-input" 
                    placeholder="Hedefini yaz..."
                    @keyup.enter="addGoal"
                  >
                  <button type="button" @click="addGoal" class="add-goal-btn" :disabled="isUpdating">
                    {{ isUpdating ? 'Kaydediliyor...' : 'Ekle' }}
                  </button>
                </div>
              </div>
              <div class="goals-list">
                <div v-for="(goal, index) in goalsForm.goals" :key="index" class="goal-item">
                  <span class="goal-text">{{ goal }}</span>
                  <button type="button" @click="removeGoal(index)" class="remove-goal-btn" :disabled="isUpdating">
                    <svg class="remove-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            <button type="submit" class="save-button" :disabled="isUpdating">
              {{ isUpdating ? 'Kaydediliyor...' : 'Hedefleri Kaydet' }}
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const authStore = useAuthStore()
const user = computed(() => authStore.user)
const profile = ref(null)
const expertRank = ref(null)
const isExpert = ref(false)
const premiumRank = ref(null)
const isPremium = ref(false)
const gamificationStats = ref({
  points: 0,
  level: 1,
  streak: 0
})
const activeTab = ref('personal')
const isUpdating = ref(false)
const newGoal = ref('')
const avatarInput = ref(null)
const isUploadingAvatar = ref(false)

const tabs = [
  { key: 'personal', name: 'Kişisel Bilgiler', icon: '👤' },
  { key: 'health', name: 'Sağlık', icon: '🏥' },
  { key: 'interests', name: 'İlgi Alanları', icon: '❤️' },
  { key: 'goals', name: 'Hedeflerim', icon: '🎯' }
]

const personalForm = reactive({
  name: '',
  email: '',
  birth_date: '',
  zodiac_sign: '',
  bio: ''
})

const healthForm = reactive({
  last_period_date: '',
  cycle_length: '',
  health_conditions: []
})

const interestsForm = reactive({
  interests: []
})

const goalsForm = reactive({
  goals: [] as string[]
})

const healthConditions = [
  'Diyabet', 'Hipertansiyon', 'Astım', 'Alerji', 'Migren', 
  'Tiroid Hastalığı', 'PCOS', 'Endometriozis', 'Anemi'
]

const availableInterests = [
  'Sağlık', 'Fitness', 'Yoga', 'Meditasyon', 'Beslenme',
  'Güzellik', 'Moda', 'Makyaj', 'Cilt Bakımı', 'Saç Bakımı',
  'Gebelik', 'Annelik', 'Bebek Bakımı', 'Çocuk Gelişimi',
  'Kariyer', 'Girişimcilik', 'Kişisel Gelişim', 'Psikoloji',
  'Astroloji', 'Tarot', 'Meditasyon', 'Spiritüellik',
  'Yemek', 'Tarif', 'Diyet', 'Organik Yaşam'
]

const getZodiacIcon = (sign: string) => {
  const icons = {
    'Koç': '♈', 'Boğa': '♉', 'İkizler': '♊', 'Yengeç': '♋',
    'Aslan': '♌', 'Başak': '♍', 'Terazi': '♎', 'Akrep': '♏',
    'Yay': '♐', 'Oğlak': '♑', 'Kova': '♒', 'Balık': '♓'
  }
  return icons[sign] || '⭐'
}

const getInterestIcon = (interest: string) => {
  const icons = {
    'Sağlık': '🏥', 'Fitness': '💪', 'Yoga': '🧘', 'Meditasyon': '🧘‍♀️',
    'Beslenme': '🥗', 'Güzellik': '💄', 'Moda': '👗', 'Makyaj': '💋',
    'Cilt Bakımı': '✨', 'Saç Bakımı': '💇‍♀️', 'Gebelik': '🤰',
    'Annelik': '👶', 'Bebek Bakımı': '🍼', 'Çocuk Gelişimi': '👨‍👩‍👧‍👦',
    'Kariyer': '💼', 'Girişimcilik': '🚀', 'Kişisel Gelişim': '📈',
    'Psikoloji': '🧠', 'Astroloji': '⭐', 'Tarot': '🔮',
    'Spiritüellik': '🙏', 'Yemek': '🍽️', 'Tarif': '👩‍🍳',
    'Diyet': '🥙', 'Organik Yaşam': '🌱'
  }
  return icons[interest] || '💖'
}

const calculateZodiac = () => {
  if (!personalForm.birth_date) return
  
  const date = new Date(personalForm.birth_date)
  const month = date.getMonth() + 1
  const day = date.getDate()
  
  if ((month === 3 && day >= 21) || (month === 4 && day <= 19)) {
    personalForm.zodiac_sign = 'Koç'
  } else if ((month === 4 && day >= 20) || (month === 5 && day <= 20)) {
    personalForm.zodiac_sign = 'Boğa'
  } else if ((month === 5 && day >= 21) || (month === 6 && day <= 20)) {
    personalForm.zodiac_sign = 'İkizler'
  } else if ((month === 6 && day >= 21) || (month === 7 && day <= 22)) {
    personalForm.zodiac_sign = 'Yengeç'
  } else if ((month === 7 && day >= 23) || (month === 8 && day <= 22)) {
    personalForm.zodiac_sign = 'Aslan'
  } else if ((month === 8 && day >= 23) || (month === 9 && day <= 22)) {
    personalForm.zodiac_sign = 'Başak'
  } else if ((month === 9 && day >= 23) || (month === 10 && day <= 22)) {
    personalForm.zodiac_sign = 'Terazi'
  } else if ((month === 10 && day >= 23) || (month === 11 && day <= 21)) {
    personalForm.zodiac_sign = 'Akrep'
  } else if ((month === 11 && day >= 22) || (month === 12 && day <= 21)) {
    personalForm.zodiac_sign = 'Yay'
  } else if ((month === 12 && day >= 22) || (month === 1 && day <= 19)) {
    personalForm.zodiac_sign = 'Oğlak'
  } else if ((month === 1 && day >= 20) || (month === 2 && day <= 18)) {
    personalForm.zodiac_sign = 'Kova'
  } else {
    personalForm.zodiac_sign = 'Balık'
  }
}

const addGoal = async () => {
  if (newGoal.value.trim()) {
    goalsForm.goals.push(newGoal.value.trim())
    newGoal.value = ''
    // Otomatik kaydet (alert olmadan)
    await updateGoals(false)
  }
}

const removeGoal = async (index: number) => {
  goalsForm.goals.splice(index, 1)
  // Otomatik kaydet (alert olmadan)
  await updateGoals(false)
}

const calculateAge = () => {
  if (!user.value?.birth_date) return null
  
  const birthDate = new Date(user.value.birth_date)
  const today = new Date()
  let age = today.getFullYear() - birthDate.getFullYear()
  const monthDiff = today.getMonth() - birthDate.getMonth()
  
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--
  }
  
  return age
}

const formatDate = (dateString: string) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('tr-TR', { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric' 
  })
}

const getDaysAgo = (dateString: string) => {
  if (!dateString) return 0
  const date = new Date(dateString)
  const today = new Date()
  const diffTime = today.getTime() - date.getTime()
  return Math.floor(diffTime / (1000 * 60 * 60 * 24))
}

const getNextPeriodDate = () => {
  if (!healthForm.last_period_date || !healthForm.cycle_length) return '-'
  const lastDate = new Date(healthForm.last_period_date)
  const cycleLength = parseInt(healthForm.cycle_length) || 28
  const nextDate = new Date(lastDate.getTime() + cycleLength * 24 * 60 * 60 * 1000)
  return nextDate.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long' })
}

const getNextPeriodDays = () => {
  if (!healthForm.last_period_date || !healthForm.cycle_length) return ''
  const lastDate = new Date(healthForm.last_period_date)
  const cycleLength = parseInt(healthForm.cycle_length) || 28
  const nextDate = new Date(lastDate.getTime() + cycleLength * 24 * 60 * 60 * 1000)
  const today = new Date()
  const diffDays = Math.ceil((nextDate.getTime() - today.getTime()) / (24 * 60 * 60 * 1000))
  
  if (diffDays < 0) return `${Math.abs(diffDays)} gün geç kaldı`
  if (diffDays === 0) return 'Bugün'
  if (diffDays === 1) return 'Yarın'
  return `${diffDays} gün sonra`
}

const getOvulationDate = () => {
  if (!healthForm.last_period_date || !healthForm.cycle_length) return '-'
  const lastDate = new Date(healthForm.last_period_date)
  const cycleLength = parseInt(healthForm.cycle_length) || 28
  const ovulationDate = new Date(lastDate.getTime() + (cycleLength - 14) * 24 * 60 * 60 * 1000)
  return ovulationDate.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long' })
}

const getOvulationDays = () => {
  if (!healthForm.last_period_date || !healthForm.cycle_length) return ''
  const lastDate = new Date(healthForm.last_period_date)
  const cycleLength = parseInt(healthForm.cycle_length) || 28
  const ovulationDate = new Date(lastDate.getTime() + (cycleLength - 14) * 24 * 60 * 60 * 1000)
  const today = new Date()
  const diffDays = Math.ceil((ovulationDate.getTime() - today.getTime()) / (24 * 60 * 60 * 1000))
  
  if (diffDays < -1) return `${Math.abs(diffDays)} gün önce geçti`
  if (diffDays === -1) return 'Dün geçti'
  if (diffDays === 0) return 'Bugün!'
  if (diffDays === 1) return 'Yarın'
  return `${diffDays} gün sonra`
}

const getFertileWindow = () => {
  if (!healthForm.last_period_date || !healthForm.cycle_length) return '-'
  const lastDate = new Date(healthForm.last_period_date)
  const cycleLength = parseInt(healthForm.cycle_length) || 28
  const ovulationDate = new Date(lastDate.getTime() + (cycleLength - 14) * 24 * 60 * 60 * 1000)
  const fertileStart = new Date(ovulationDate.getTime() - 5 * 24 * 60 * 60 * 1000)
  const fertileEnd = new Date(ovulationDate.getTime() + 1 * 24 * 60 * 60 * 1000)
  
  return `${fertileStart.getDate()}-${fertileEnd.getDate()} ${fertileEnd.toLocaleDateString('tr-TR', { month: 'long' })}`
}

const getFertileStatus = () => {
  if (!healthForm.last_period_date || !healthForm.cycle_length) return ''
  const lastDate = new Date(healthForm.last_period_date)
  const cycleLength = parseInt(healthForm.cycle_length) || 28
  const ovulationDate = new Date(lastDate.getTime() + (cycleLength - 14) * 24 * 60 * 60 * 1000)
  const fertileStart = new Date(ovulationDate.getTime() - 5 * 24 * 60 * 60 * 1000)
  const fertileEnd = new Date(ovulationDate.getTime() + 1 * 24 * 60 * 60 * 1000)
  const today = new Date()
  
  if (today >= fertileStart && today <= fertileEnd) {
    return 'Verimli dönemdesiniz 🌸'
  }
  return 'Verimli dönem dışında'
}

const getAvatarUrl = (avatarPath: string) => {
  if (!avatarPath) return ''
  if (avatarPath.startsWith('http')) return avatarPath
  return `http://localhost:8000/storage/${avatarPath}`
}

const handleAvatarUpload = async (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (!file) return
  
  // Dosya boyutu kontrolü (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('Dosya boyutu 5MB\'dan küçük olmalıdır!')
    return
  }
  
  // Dosya tipi kontrolü
  if (!file.type.startsWith('image/')) {
    alert('Lütfen sadece resim dosyası seçin!')
    return
  }
  
  isUploadingAvatar.value = true
  
  try {
    const formData = new FormData()
    formData.append('avatar', file)
    
    const response = await api.post('/profile/avatar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    if (response.data.success) {
      // Kullanıcı avatar'ını güncelle
      authStore.user = response.data.user
      alert('Profil resmi başarıyla güncellendi!')
      await loadProfile()
    }
  } catch (error) {
    console.error('Avatar upload error:', error)
    alert('Profil resmi yüklenirken bir hata oluştu!')
  } finally {
    isUploadingAvatar.value = false
    // Input'u temizle
    if (avatarInput.value) {
      avatarInput.value.value = ''
    }
  }
}

const loadGamificationStats = async () => {
  try {
    const response = await api.get('/gamification/stats')
    if (response.data.success) {
      gamificationStats.value = {
        points: response.data.data.points || 0,
        level: response.data.data.level || 1,
        streak: response.data.data.daily_streak || 0
      }
    }
  } catch (error) {
    console.error('Gamification stats yüklenirken hata:', error)
  }
}

const loadProfile = async () => {
  try {
    const response = await api.get('/profile')
    console.log('Profile response:', response.data)
    
    if (response.data.success) {
      const userData = response.data.user
      const profileData = response.data.profile
      
      profile.value = profileData
      authStore.user = userData
      expertRank.value = response.data.expert_rank
      isExpert.value = response.data.is_expert
      premiumRank.value = response.data.premium_rank
      isPremium.value = response.data.is_premium
      console.log('Expert data:', { expert_rank: response.data.expert_rank, is_expert: response.data.is_expert })
      console.log('Premium data:', { premium_rank: response.data.premium_rank, is_premium: response.data.is_premium })
      
      // Fill forms with proper date formatting
      personalForm.name = userData.name || ''
      personalForm.email = userData.email || ''
      personalForm.birth_date = userData.birth_date ? userData.birth_date.split('T')[0] : ''
      personalForm.zodiac_sign = userData.zodiac_sign || ''
      personalForm.bio = profileData?.bio || ''
      
      healthForm.last_period_date = profileData?.last_period_date ? profileData.last_period_date.split('T')[0] : ''
      healthForm.cycle_length = profileData?.cycle_length || ''
      healthForm.health_conditions = profileData?.health_info?.conditions || []
      
      interestsForm.interests = profileData?.interests || []
      goalsForm.goals = Array.isArray(profileData?.goals) ? profileData.goals : []
    }
    
    // Load gamification stats
    await loadGamificationStats()
  } catch (error) {
    console.error('Profil yüklenirken hata:', error)
    if (error.response?.status === 401) {
      alert('Oturum süreniz dolmuş. Lütfen tekrar giriş yapın.')
    }
  }
}

const updatePersonalInfo = async () => {
  isUpdating.value = true
  try {
    console.log('Updating personal info:', {
      name: personalForm.name,
      email: personalForm.email,
      birth_date: personalForm.birth_date,
      zodiac_sign: personalForm.zodiac_sign,
      bio: personalForm.bio
    })
    
    const response = await api.put('/profile', {
      name: personalForm.name,
      email: personalForm.email,
      birth_date: personalForm.birth_date,
      zodiac_sign: personalForm.zodiac_sign,
      bio: personalForm.bio
    })
    
    console.log('Update response:', response.data)
    
    if (response.data.success) {
      profile.value = response.data.profile
      authStore.user = response.data.user
      alert('Kişisel bilgiler başarıyla güncellendi!')
      await loadProfile() // Reload to ensure data consistency
    }
  } catch (error) {
    console.error('Güncelleme hatası:', error)
    alert('Güncelleme sırasında bir hata oluştu: ' + (error.response?.data?.message || error.message))
  } finally {
    isUpdating.value = false
  }
}

const updateHealthInfo = async () => {
  isUpdating.value = true
  try {
    const response = await api.put('/profile', {
      last_period_date: healthForm.last_period_date,
      cycle_length: healthForm.cycle_length,
      health_info: {
        conditions: healthForm.health_conditions
      }
    })
    
    if (response.data.success) {
      profile.value = response.data.profile
      alert('Sağlık bilgileri başarıyla güncellendi!')
      await loadProfile()
    }
  } catch (error) {
    console.error('Güncelleme hatası:', error)
    alert('Güncelleme sırasında bir hata oluştu: ' + (error.response?.data?.message || error.message))
  } finally {
    isUpdating.value = false
  }
}

const updateInterests = async () => {
  isUpdating.value = true
  try {
    const response = await api.put('/profile', {
      interests: interestsForm.interests
    })
    
    if (response.data.success) {
      profile.value = response.data.profile
      alert('İlgi alanları başarıyla güncellendi!')
      await loadProfile()
    }
  } catch (error) {
    console.error('Güncelleme hatası:', error)
    alert('Güncelleme sırasında bir hata oluştu: ' + (error.response?.data?.message || error.message))
  } finally {
    isUpdating.value = false
  }
}

const loadPremiumSubscription = async () => {
  try {
    const response = await api.get('/premium/subscription')
    if (response.data.success && response.data.subscription) {
      const subscription = response.data.subscription
      isPremium.value = response.data.is_premium
      
      const planNames = {
        'basic': '⭐ Temel Üye',
        'premium': '💎 Premium Üye', 
        'vip': '👑 VIP Üye'
      }
      
      premiumRank.value = planNames[subscription.plan_type] || '⭐ Premium Üye'
    }
  } catch (error) {
    console.error('Premium subscription loading error:', error)
  }
}

const updateGoals = async (showAlert = true) => {
  isUpdating.value = true
  try {
    console.log('Updating goals:', goalsForm.goals)
    const response = await api.put('/profile', {
      goals: goalsForm.goals
    })
    
    if (response.data.success) {
      profile.value = response.data.profile
      if (showAlert) {
        alert('Hedefler başarıyla güncellendi!')
      }
      // Reload profile to ensure consistency
      await loadProfile()
    }
  } catch (error) {
    console.error('Güncelleme hatası:', error)
    alert('Güncelleme sırasında bir hata oluştu: ' + (error.response?.data?.message || error.message))
  } finally {
    isUpdating.value = false
  }
}

onMounted(async () => {
  // Check if user is authenticated
  if (!authStore.isAuthenticated) {
    console.log('User not authenticated, redirecting to login')
    return
  }
  
  console.log('Current user from auth store:', authStore.user)
  await loadProfile()
})
</script>

<style scoped>
.profile-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%);
}

.profile-header {
  background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
  padding: 40px 20px;
  color: white;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 40px;
}

.avatar-section {
  display: flex;
  align-items: center;
  gap: 24px;
}

.avatar-container {
  position: relative;
}

.avatar-image,
.avatar-placeholder {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 4px solid rgba(255, 255, 255, 0.3);
}

.avatar-placeholder {
  background: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-initial {
  font-size: 3rem;
  font-weight: 700;
  color: white;
}

.avatar-edit-btn {
  position: absolute;
  bottom: 8px;
  right: 8px;
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  color: #ec4899;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.avatar-edit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.avatar-edit-btn:hover {
  background: white;
  transform: scale(1.1);
}

.edit-icon {
  width: 20px;
  height: 20px;
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.user-name {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0;
}

.user-email {
  font-size: 1.1rem;
  opacity: 0.9;
  margin: 0;
}

.zodiac-info {
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.2);
  padding: 8px 16px;
  border-radius: 20px;
  width: fit-content;
}

.zodiac-icon {
  font-size: 1.5rem;
}

.zodiac-name {
  font-weight: 600;
}

.expert-rank {
  margin-top: 8px;
}

.expert-badge {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.9rem;
  display: inline-block;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  animation: expertGlow 2s ease-in-out infinite alternate;
}

@keyframes expertGlow {
  0% { box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
  100% { box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5); }
}

.premium-rank {
  margin-top: 8px;
}

.premium-badge {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.9rem;
  display: inline-block;
  box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
  animation: premiumGlow 2s ease-in-out infinite alternate;
}

@keyframes premiumGlow {
  0% { box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }
  100% { box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5); }
}

.stats-section {
  display: flex;
  gap: 24px;
}

.stat-card {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 24px;
  text-align: center;
  min-width: 100px;
}

.stat-number {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 8px;
}

.stat-label {
  font-size: 0.9rem;
  opacity: 0.9;
}

.profile-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 20px;
}

.profile-tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 32px;
  background: white;
  padding: 8px;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.tab-button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  background: none;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 600;
  color: #6b7280;
}

.tab-button.active {
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.tab-icon {
  font-size: 1.2rem;
}

.tab-content {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.content-card {
  background: white;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 24px;
}

.profile-form {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  font-weight: 600;
  color: #374151;
}

.form-input,
.form-select,
.form-textarea {
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #ec4899;
  box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.zodiac-display {
  padding: 12px 16px;
  background: #f9fafb;
  border-radius: 12px;
  border: 2px solid #e5e7eb;
}

.zodiac-result {
  font-weight: 600;
  color: #ec4899;
  font-size: 1.1rem;
}

.zodiac-empty {
  color: #6b7280;
  font-style: italic;
}

.checkbox-group {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.checkbox-input {
  width: 18px;
  height: 18px;
  accent-color: #ec4899;
}

.interests-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.interest-card {
  display: flex;
  align-items: center;
  padding: 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.interest-card:hover {
  border-color: #ec4899;
  transform: translateY(-2px);
}

.interest-checkbox {
  display: none;
}

.interest-checkbox:checked + .interest-content {
  color: #ec4899;
}

.interest-checkbox:checked + .interest-content::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(236, 72, 153, 0.1);
  border-radius: 10px;
}

.interest-content {
  display: flex;
  align-items: center;
  gap: 12px;
  position: relative;
  width: 100%;
}

.interest-icon {
  font-size: 1.5rem;
}

.interest-name {
  font-weight: 600;
}

.goal-input-group {
  display: flex;
  gap: 12px;
}

.add-goal-btn {
  background: #ec4899;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.add-goal-btn:hover {
  background: #db2777;
  transform: translateY(-1px);
}

.goals-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.goal-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  background: #f9fafb;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
}

.goal-text {
  font-weight: 500;
}

.remove-goal-btn {
  background: #ef4444;
  color: white;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.remove-goal-btn:hover {
  background: #dc2626;
  transform: scale(1.1);
}

.remove-icon {
  width: 16px;
  height: 16px;
}

.save-button {
  background: linear-gradient(135deg, #ec4899, #8b5cf6);
  color: white;
  border: none;
  padding: 16px 32px;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  align-self: flex-start;
}

.save-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.save-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.cycle-info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.cycle-info-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px;
  background: linear-gradient(135deg, #fdf2f8, #f3e8ff);
  border-radius: 12px;
  border: 1px solid #f3e8ff;
}

.cycle-icon {
  font-size: 2rem;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
}

.cycle-details {
  flex: 1;
}

.cycle-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #6b7280;
  margin-bottom: 4px;
}

.cycle-value {
  font-size: 1.125rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 2px;
}

.cycle-subtitle {
  font-size: 0.75rem;
  color: #9ca3af;
}

.cycle-calendar {
  background: #f9fafb;
  padding: 20px;
  border-radius: 12px;
  margin-top: 20px;
}

.calendar-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.calendar-legend {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.875rem;
  color: #6b7280;
}

.legend-color {
  width: 16px;
  height: 16px;
  border-radius: 4px;
}

.legend-color.period {
  background: #ef4444;
}

.legend-color.fertile {
  background: #10b981;
}

.legend-color.ovulation {
  background: #f59e0b;
}

.loading-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid transparent;
  border-top: 2px solid currentColor;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    text-align: center;
    gap: 24px;
  }
  
  .avatar-section {
    flex-direction: column;
    text-align: center;
  }
  
  .user-name {
    font-size: 2rem;
  }
  
  .profile-tabs {
    flex-wrap: wrap;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .interests-grid {
    grid-template-columns: 1fr;
  }
  
  .goal-input-group {
    flex-direction: column;
  }
  
  .cycle-info-grid {
    grid-template-columns: 1fr;
  }
  
  .calendar-legend {
    justify-content: center;
  }
}
</style>