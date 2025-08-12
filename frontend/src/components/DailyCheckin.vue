<template>
  <div class="daily-checkin">
    <!-- Check-in Status -->
    <div v-if="!checkedIn" class="checkin-form">
      <div class="checkin-header">
        <h3 class="checkin-title">🌟 Günlük Check-in</h3>
        <p class="checkin-subtitle">Bugünkü aktivitelerinizi kaydedin ve puan kazanın!</p>
      </div>

      <form @submit.prevent="submitCheckin" class="checkin-content">
        <!-- Aktiviteler -->
        <div class="activities-section">
          <h4 class="section-title">Bugün yaptığınız aktiviteler:</h4>
          <div class="activities-grid">
            <label v-for="activity in availableActivities" :key="activity.key" class="activity-item">
              <input 
                type="checkbox" 
                :value="activity.key"
                v-model="selectedActivities"
                class="activity-checkbox"
              >
              <div class="activity-content">
                <span class="activity-icon">{{ activity.icon }}</span>
                <span class="activity-name">{{ activity.name }}</span>
              </div>
            </label>
          </div>
        </div>

        <!-- Ruh Hali -->
        <div class="mood-section">
          <h4 class="section-title">Bugün nasıl hissediyorsunuz?</h4>
          <div class="mood-options">
            <button 
              v-for="mood in moodOptions" 
              :key="mood.value"
              type="button"
              @click="selectedMood = mood.value"
              :class="['mood-button', { active: selectedMood === mood.value }]"
            >
              <span class="mood-icon">{{ mood.icon }}</span>
              <span class="mood-label">{{ mood.label }}</span>
            </button>
          </div>
        </div>

        <!-- Notlar -->
        <div class="notes-section">
          <h4 class="section-title">Bugün hakkında not (opsiyonel):</h4>
          <textarea 
            v-model="notes"
            placeholder="Bugün nasıl geçti? Neler hissettiniz?"
            class="notes-textarea"
            maxlength="500"
          ></textarea>
        </div>

        <button 
          type="submit" 
          :disabled="!canSubmit || loading"
          class="submit-button"
        >
          <span v-if="loading">Kaydediliyor...</span>
          <span v-else>✨ Check-in Yap ({{ calculatePoints() }} puan)</span>
        </button>
      </form>
    </div>

    <!-- Already Checked In -->
    <div v-else class="checkin-success">
      <div class="success-icon">🎉</div>
      <h3 class="success-title">Bugün check-in yaptınız!</h3>
      <p class="success-message">{{ todayCheckin?.points_earned }} puan kazandınız</p>
      
      <div class="checkin-stats">
        <div class="stat-item">
          <span class="stat-number">{{ streak }}</span>
          <span class="stat-label">Gün Serisi</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">{{ stats.total_points || 0 }}</span>
          <span class="stat-label">Toplam Puan</span>
        </div>
      </div>

      <div class="today-activities">
        <h4>Bugünkü Aktiviteleriniz:</h4>
        <div class="activity-tags">
          <span 
            v-for="activity in getTodayActivities()" 
            :key="activity"
            class="activity-tag"
          >
            {{ getActivityName(activity) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/services/api'

const checkedIn = ref(false)
const todayCheckin = ref(null)
const streak = ref(0)
const stats = ref({})
const loading = ref(false)

const selectedActivities = ref([])
const selectedMood = ref(null)
const notes = ref('')

const availableActivities = [
  { key: 'water', name: 'Su İçtim', icon: '💧' },
  { key: 'exercise', name: 'Egzersiz Yaptım', icon: '💪' },
  { key: 'meditation', name: 'Meditasyon', icon: '🧘‍♀️' },
  { key: 'reading', name: 'Kitap Okudum', icon: '📚' },
  { key: 'skincare', name: 'Cilt Bakımı', icon: '✨' },
  { key: 'healthy_meal', name: 'Sağlıklı Beslenme', icon: '🥗' },
  { key: 'walk', name: 'Yürüyüş', icon: '🚶‍♀️' },
  { key: 'sleep', name: 'Kaliteli Uyku', icon: '😴' }
]

const moodOptions = [
  { value: 1, icon: '😢', label: 'Kötü' },
  { value: 2, icon: '😕', label: 'Üzgün' },
  { value: 3, icon: '😐', label: 'Normal' },
  { value: 4, icon: '😊', label: 'İyi' },
  { value: 5, icon: '😍', label: 'Harika' }
]

const canSubmit = computed(() => {
  return selectedActivities.value.length > 0 && selectedMood.value !== null
})

const calculatePoints = () => {
  const basePoints = 10
  const bonusPoints = selectedActivities.value.length * 5
  return basePoints + bonusPoints
}

const getActivityName = (key: string) => {
  const activity = availableActivities.find(a => a.key === key)
  return activity ? activity.name : key
}

const getTodayActivities = () => {
  return todayCheckin.value?.activities || []
}

const loadTodayStatus = async () => {
  try {
    const response = await api.get('/checkin/today')
    if (response.data.success) {
      checkedIn.value = response.data.data.checked_in
      todayCheckin.value = response.data.data.checkin
      streak.value = response.data.data.streak
    }
  } catch (error) {
    console.error('Today status loading error:', error)
  }
}

const loadStats = async () => {
  try {
    const response = await api.get('/checkin/stats')
    if (response.data.success) {
      stats.value = response.data.data
    }
  } catch (error) {
    console.error('Stats loading error:', error)
  }
}

const submitCheckin = async () => {
  if (!canSubmit.value) return
  
  loading.value = true
  try {
    const response = await api.post('/checkin', {
      activities: selectedActivities.value,
      mood_score: selectedMood.value,
      notes: notes.value
    })

    if (response.data.success) {
      checkedIn.value = true
      todayCheckin.value = response.data.data.checkin
      streak.value = response.data.data.streak
      stats.value.total_points = response.data.data.total_points
      
      // Reset form
      selectedActivities.value = []
      selectedMood.value = null
      notes.value = ''
    }
  } catch (error) {
    console.error('Check-in error:', error)
    alert('Check-in yapılırken hata oluştu!')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadTodayStatus()
  loadStats()
})
</script>

<style scoped>
.daily-checkin {
  background: white;
  border-radius: 20px;
  padding: 24px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  border: 1px solid rgba(236, 72, 153, 0.1);
}

.checkin-header {
  text-align: center;
  margin-bottom: 24px;
}

.checkin-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 8px;
}

.checkin-subtitle {
  color: #6b7280;
  font-size: 0.95rem;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 16px;
}

.activities-section {
  margin-bottom: 24px;
}

.activities-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
}

.activity-item {
  display: flex;
  align-items: center;
  padding: 12px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.activity-item:hover {
  border-color: #ec4899;
  background: #fdf2f8;
}

.activity-item:has(.activity-checkbox:checked) {
  border-color: #ec4899;
  background: linear-gradient(135deg, #fdf2f8, #f3e8ff);
}

.activity-checkbox {
  display: none;
}

.activity-content {
  display: flex;
  align-items: center;
  gap: 8px;
}

.activity-icon {
  font-size: 1.2rem;
}

.activity-name {
  font-weight: 500;
  color: #374151;
}

.mood-section {
  margin-bottom: 24px;
}

.mood-options {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.mood-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 12px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  background: white;
  cursor: pointer;
  transition: all 0.2s ease;
  flex: 1;
  min-width: 80px;
}

.mood-button:hover {
  border-color: #ec4899;
  background: #fdf2f8;
}

.mood-button.active {
  border-color: #ec4899;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
}

.mood-icon {
  font-size: 1.5rem;
}

.mood-label {
  font-size: 0.875rem;
  font-weight: 500;
}

.notes-section {
  margin-bottom: 24px;
}

.notes-textarea {
  width: 100%;
  min-height: 80px;
  padding: 12px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  resize: vertical;
  font-family: inherit;
  transition: border-color 0.2s ease;
}

.notes-textarea:focus {
  outline: none;
  border-color: #ec4899;
}

.submit-button {
  width: 100%;
  padding: 16px;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.submit-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.submit-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.checkin-success {
  text-align: center;
}

.success-icon {
  font-size: 4rem;
  margin-bottom: 16px;
}

.success-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 8px;
}

.success-message {
  color: #16a34a;
  font-weight: 600;
  margin-bottom: 24px;
}

.checkin-stats {
  display: flex;
  justify-content: center;
  gap: 32px;
  margin-bottom: 24px;
}

.stat-item {
  text-align: center;
}

.stat-number {
  display: block;
  font-size: 2rem;
  font-weight: 900;
  color: #ec4899;
}

.stat-label {
  color: #6b7280;
  font-size: 0.875rem;
  font-weight: 500;
}

.today-activities h4 {
  font-size: 1rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 12px;
}

.activity-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  justify-content: center;
}

.activity-tag {
  background: #fce7f3;
  color: #be185d;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
}

@media (max-width: 768px) {
  .activities-grid {
    grid-template-columns: 1fr;
  }
  
  .mood-options {
    justify-content: center;
  }
  
  .checkin-stats {
    gap: 16px;
  }
}
</style>