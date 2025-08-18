<template>
  <div style="min-height: 100vh; background-color: #f9fafb; padding: 2rem;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 1rem;">
          🗓️ Etkinlik Takvimi
        </h1>
        <p style="font-size: 1.125rem; color: #6b7280; max-width: 600px; margin: 0 auto;">
          Kadınlara özel atölyeler, seminerler ve buluşmalara katılın. Yeni beceriler öğrenin, deneyim paylaşın.
        </p>
      </div>

      <!-- Filters -->
      <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 3rem; justify-content: center;">
        <button 
          @click="selectedType = ''"
          :style="getButtonStyle(selectedType === '')"
        >
          🎆 Tüm Etkinlikler
        </button>
        <button 
          v-for="type in eventTypes" 
          :key="type.value"
          @click="selectedType = selectedType === type.value ? '' : type.value"
          :style="getButtonStyle(selectedType === type.value)"
        >
          {{ type.icon }} {{ type.label }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" style="text-align: center; padding: 3rem 0;">
        <div style="width: 4rem; height: 4rem; border: 3px solid #ec4899; border-top: 3px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        <p style="margin-top: 1rem; font-size: 1.125rem; color: #6b7280;">Etkinlikler yükleniyor...</p>
      </div>

      <!-- No Events -->
      <div v-else-if="events.length === 0" style="text-align: center; padding: 4rem 0;">
        <div style="font-size: 5rem; margin-bottom: 1.5rem;">📅</div>
        <h3 style="font-size: 1.5rem; font-weight: bold; color: #111827; margin-bottom: 1rem;">Henüz etkinlik yok</h3>
        <p style="font-size: 1.125rem; color: #6b7280;">Yakında yeni etkinlikler eklenecek!</p>
      </div>

      <!-- Events Grid -->
      <div v-else style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
        <div 
          v-for="event in events" 
          :key="event.id"
          style="background: white; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; transition: all 0.3s ease;"
          @mouseover="$event.target.style.transform = 'translateY(-5px)'"
          @mouseleave="$event.target.style.transform = 'translateY(0)'"
        >
          <!-- Event Image -->
          <div style="height: 200px; background: linear-gradient(135deg, #ec4899, #8b5cf6, #3b82f6); position: relative;">
            <div style="position: absolute; top: 1rem; left: 1rem; background: rgba(255,255,255,0.95); color: #ec4899; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
              {{ getEventTypeLabel(event.type) }}
            </div>
            <div style="position: absolute; bottom: 1rem; left: 1rem; color: white;">
              <div style="font-size: 2rem; font-weight: bold;">{{ formatDate(event.start_date, 'DD') }}</div>
              <div style="font-size: 0.875rem;">{{ formatDate(event.start_date, 'MMM YYYY') }}</div>
            </div>
            <div style="position: absolute; top: 1rem; right: 1rem; color: white; text-align: right;">
              <div style="font-size: 0.875rem; font-weight: 500;">{{ formatDate(event.start_date, 'HH:mm') }}</div>
            </div>
          </div>

          <!-- Event Content -->
          <div style="padding: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 0.75rem;">{{ event.title }}</h3>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem; line-height: 1.5;">{{ event.description }}</p>
            
            <!-- Event Details -->
            <div style="margin-bottom: 1.5rem;">
              <div style="display: flex; align-items: center; margin-bottom: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                <span style="margin-right: 0.75rem; font-size: 1.125rem;">🕒</span>
                <span style="font-weight: 500;">{{ formatDate(event.start_date, 'DD MMM YYYY, HH:mm') }}</span>
              </div>
              <div v-if="event.location" style="display: flex; align-items: center; margin-bottom: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                <span style="margin-right: 0.75rem; font-size: 1.125rem;">📍</span>
                <span>{{ event.location }}</span>
              </div>
              <div v-if="event.online_link" style="display: flex; align-items: center; margin-bottom: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                <span style="margin-right: 0.75rem; font-size: 1.125rem;">💻</span>
                <span style="color: #3b82f6; font-weight: 500;">Online Etkinlik</span>
              </div>
              <div v-if="event.max_participants" style="display: flex; align-items: center; margin-bottom: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                <span style="margin-right: 0.75rem; font-size: 1.125rem;">👥</span>
                <span style="font-weight: 500;">{{ event.attendees_count || 0 }}/{{ event.max_participants }} kişi</span>
              </div>
            </div>

            <!-- Price -->
            <div style="margin-bottom: 1.5rem;">
              <span v-if="event.is_free" style="display: inline-flex; align-items: center; background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                <span style="margin-right: 0.25rem;">✨</span>
                Ücretsiz
              </span>
              <span v-else style="display: inline-flex; align-items: center; background: #dbeafe; color: #1e40af; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                <span style="margin-right: 0.25rem;">💰</span>
                {{ event.price }}₺
              </span>
            </div>

            <!-- Action Button -->
            <button 
              @click="registerForEvent(event)"
              :disabled="event.is_full"
              :style="getActionButtonStyle(event.is_full)"
            >
              <span v-if="event.is_full">😔 Dolu</span>
              <span v-else>🎉 Katıl</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import api from '@/services/api'
import { useAuthStore } from '@/stores/auth'
import dayjs from 'dayjs'

const authStore = useAuthStore()
const events = ref([])
const loading = ref(true)
const selectedType = ref('')

const eventTypes = [
  { value: 'workshop', label: 'Atölye', icon: '🛠️' },
  { value: 'seminar', label: 'Seminer', icon: '📚' },
  { value: 'webinar', label: 'Webinar', icon: '💻' },
  { value: 'meetup', label: 'Buluşma', icon: '☕' }
]

const getButtonStyle = (isActive: boolean) => ({
  padding: '0.75rem 1.5rem',
  borderRadius: '9999px',
  fontWeight: '600',
  fontSize: '0.875rem',
  transition: 'all 0.2s ease',
  cursor: 'pointer',
  border: 'none',
  background: isActive ? 'linear-gradient(135deg, #ec4899, #8b5cf6)' : 'white',
  color: isActive ? 'white' : '#374151',
  boxShadow: isActive ? '0 10px 25px rgba(236, 72, 153, 0.3)' : '0 2px 10px rgba(0,0,0,0.1)',
  transform: 'scale(1)',
})

const getActionButtonStyle = (isFull: boolean) => ({
  width: '100%',
  padding: '0.75rem 1.5rem',
  borderRadius: '0.75rem',
  fontWeight: '600',
  fontSize: '0.875rem',
  transition: 'all 0.2s ease',
  cursor: isFull ? 'not-allowed' : 'pointer',
  border: 'none',
  background: isFull ? '#e5e7eb' : 'linear-gradient(135deg, #ec4899, #8b5cf6)',
  color: isFull ? '#9ca3af' : 'white',
  boxShadow: isFull ? 'none' : '0 4px 15px rgba(236, 72, 153, 0.3)',
})

const getEventTypeLabel = (type: string) => {
  const eventType = eventTypes.find(t => t.value === type)
  return eventType ? eventType.label : type
}

const formatDate = (date: string, format: string) => {
  return dayjs(date).format(format)
}

const loadEvents = async () => {
  try {
    loading.value = true
    const params: any = { upcoming: true }
    if (selectedType.value) {
      params.type = selectedType.value
    }
    
    const response = await api.get('/events', { params })
    
    if (response.data.success) {
      events.value = response.data.data.data || []
    }
  } catch (error) {
    console.error('Events loading error:', error)
  } finally {
    loading.value = false
  }
}

const registerForEvent = async (event: any) => {
  if (!authStore.isAuthenticated) {
    alert('Etkinliğe katılmak için giriş yapmalısınız!')
    return
  }

  try {
    const response = await api.post(`/events/${event.id}/register`)
    
    if (response.data.success) {
      alert(response.data.message)
      loadEvents()
    }
  } catch (error: any) {
    const message = error.response?.data?.message || 'Kayıt sırasında hata oluştu!'
    alert(message)
  }
}

watch(selectedType, () => {
  loadEvents()
})

onMounted(() => {
  loadEvents()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

button:hover {
  transform: scale(1.05) !important;
}
</style>