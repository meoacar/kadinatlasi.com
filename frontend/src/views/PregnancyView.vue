<template>
  <div style="min-height: 100vh; background-color: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 16px;">🤱 Gebelik Takibi</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Gebelik sürecinizi takip edin</p>
      </div>

      <!-- Pregnancy Tracker Setup -->
      <div v-if="!pregnancyTracker && !loading" style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 32px; text-align: center;">
        <div style="font-size: 4rem; margin-bottom: 16px;">👶</div>
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">Gebelik Takibini Başlat</h2>
        <p style="color: #6b7280; margin-bottom: 24px;">Son adet tarihinizi girerek gebelik takibinizi başlatabilirsiniz.</p>
        
        <div style="max-width: 400px; margin: 0 auto;">
          <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Son Adet Tarihi</label>
          <input
            v-model="lastMenstrualPeriod"
            type="date"
            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; margin-bottom: 16px;"
          >
          <button
            @click="createPregnancyTracker"
            :disabled="!lastMenstrualPeriod || creating"
            style="width: 100%; background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: background 0.2s;"
            @mouseover="$event.target.style.background = '#db2777'"
            @mouseleave="$event.target.style.background = '#ec4899'"
          >
            {{ creating ? 'Oluşturuluyor...' : 'Takibi Başlat' }}
          </button>
        </div>
      </div>

      <!-- Pregnancy Tracker Dashboard -->
      <div v-if="pregnancyTracker && !loading">
        
        <!-- Current Week Info -->
        <div style="background: linear-gradient(135deg, #ec4899, #f472b6); border-radius: 8px; padding: 32px; margin-bottom: 32px; color: white; text-align: center;">
          <h2 style="font-size: 2rem; font-weight: bold; margin-bottom: 8px;">{{ pregnancyTracker.current_week }}. Hafta</h2>
          <p style="font-size: 1.25rem; opacity: 0.9; margin-bottom: 16px;">{{ pregnancyTracker.current_day }}. Gün</p>
          <div style="display: flex; justify-content: center; gap: 32px; margin-top: 24px;">
            <div>
              <div style="font-size: 1.5rem; font-weight: bold;">{{ daysPregnant }}</div>
              <div style="opacity: 0.8;">Gün Hamile</div>
            </div>
            <div>
              <div style="font-size: 1.5rem; font-weight: bold;">{{ weeksRemaining }}</div>
              <div style="opacity: 0.8;">Hafta Kaldı</div>
            </div>
          </div>
        </div>

        <!-- Weekly Guide -->
        <div v-if="weeklyGuide" style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 32px;">
          <h3 style="font-size: 1.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">{{ weeklyGuide.title }}</h3>
          <p style="color: #6b7280; margin-bottom: 24px;">{{ weeklyGuide.description }}</p>
          
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            
            <!-- Baby Development -->
            <div style="background: #fef3c7; border-radius: 8px; padding: 20px;">
              <h4 style="font-weight: bold; color: #92400e; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                👶 Bebek Gelişimi
              </h4>
              <p style="color: #78350f;">{{ weeklyGuide.baby_development }}</p>
              <div v-if="weeklyGuide.baby_size" style="margin-top: 12px; font-size: 0.875rem; color: #78350f;">
                <strong>Boyut:</strong> {{ weeklyGuide.baby_size }}
              </div>
              <div v-if="weeklyGuide.baby_weight" style="margin-top: 4px; font-size: 0.875rem; color: #78350f;">
                <strong>Ağırlık:</strong> {{ weeklyGuide.baby_weight }}
              </div>
            </div>

            <!-- Mother Changes -->
            <div style="background: #fce7f3; border-radius: 8px; padding: 20px;">
              <h4 style="font-weight: bold; color: #be185d; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                🤱 Anne Değişiklikleri
              </h4>
              <p style="color: #9d174d;">{{ weeklyGuide.mother_changes }}</p>
            </div>
          </div>

          <!-- Tips -->
          <div v-if="weeklyGuide.tips && weeklyGuide.tips.length" style="margin-top: 24px; background: #e0f2fe; border-radius: 8px; padding: 20px;">
            <h4 style="font-weight: bold; color: #0369a1; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
              💡 Bu Hafta İçin Öneriler
            </h4>
            <ul style="list-style: none; padding: 0; margin: 0;">
              <li v-for="tip in weeklyGuide.tips" :key="tip" style="color: #0c4a6e; margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                <span style="color: #0369a1;">•</span>
                {{ tip }}
              </li>
            </ul>
          </div>
        </div>

        <!-- Due Date Info -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; text-align: center;">
          <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 8px;">Tahmini Doğum Tarihi</h3>
          <p style="font-size: 1.5rem; color: #ec4899; font-weight: bold;">{{ formatDate(pregnancyTracker.due_date) }}</p>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" style="text-align: center; padding: 32px;">
        <div style="width: 48px; height: 48px; border: 2px solid #f3f4f6; border-top: 2px solid #ec4899; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        <p style="margin-top: 16px; color: #6b7280;">Yükleniyor...</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

interface PregnancyTracker {
  id: number
  user_id: number
  last_menstrual_period: string
  due_date: string
  current_week: number
  current_day: number
  weight_gain: number
  is_active: boolean
}

interface WeeklyGuide {
  id: number
  week_number: number
  title: string
  description: string
  baby_development: string
  mother_changes: string
  tips: string[]
  baby_size?: string
  baby_weight?: string
}

const authStore = useAuthStore()
const pregnancyTracker = ref<PregnancyTracker | null>(null)
const weeklyGuide = ref<WeeklyGuide | null>(null)
const daysPregnant = ref(0)
const weeksRemaining = ref(0)
const loading = ref(false)
const creating = ref(false)
const lastMenstrualPeriod = ref('')

const fetchPregnancyTracker = async () => {
  if (!authStore.isAuthenticated) return
  
  loading.value = true
  try {
    const response = await axios.get('/api/pregnancy/tracker')
    pregnancyTracker.value = response.data.tracker
    weeklyGuide.value = response.data.weekly_guide
    daysPregnant.value = response.data.days_pregnant
    weeksRemaining.value = response.data.weeks_remaining
  } catch (error: any) {
    if (error.response?.status !== 404) {
      console.error('Gebelik takibi yüklenirken hata:', error)
    }
  } finally {
    loading.value = false
  }
}

const createPregnancyTracker = async () => {
  if (!lastMenstrualPeriod.value) return
  
  creating.value = true
  try {
    await axios.post('/api/pregnancy/tracker', {
      last_menstrual_period: lastMenstrualPeriod.value
    })
    await fetchPregnancyTracker()
  } catch (error) {
    console.error('Gebelik takibi oluşturulurken hata:', error)
  } finally {
    creating.value = false
  }
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(() => {
  fetchPregnancyTracker()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>