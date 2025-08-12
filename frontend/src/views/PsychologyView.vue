<template>
  <div style="min-height: 100vh; background-color: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 16px;">🧠 Psikolojik Destek</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Ruh halinizi takip edin ve kendinizi daha iyi hissedin</p>
      </div>

      <!-- Tab Navigation -->
      <div style="display: flex; justify-content: center; margin-bottom: 32px;">
        <div style="background: white; border-radius: 8px; padding: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <button
            @click="activeTab = 'mood'"
            :style="{
              padding: '8px 24px',
              borderRadius: '6px',
              border: 'none',
              cursor: 'pointer',
              fontWeight: '500',
              transition: 'all 0.2s',
              backgroundColor: activeTab === 'mood' ? '#ec4899' : 'transparent',
              color: activeTab === 'mood' ? 'white' : '#6b7280'
            }"
          >
            Ruh Hali Takibi
          </button>
          <button
            @click="activeTab = 'quotes'"
            :style="{
              padding: '8px 24px',
              borderRadius: '6px',
              border: 'none',
              cursor: 'pointer',
              fontWeight: '500',
              transition: 'all 0.2s',
              backgroundColor: activeTab === 'quotes' ? '#ec4899' : 'transparent',
              color: activeTab === 'quotes' ? 'white' : '#6b7280'
            }"
          >
            Motivasyon
          </button>
          <button
            @click="activeTab = 'tests'"
            :style="{
              padding: '8px 24px',
              borderRadius: '6px',
              border: 'none',
              cursor: 'pointer',
              fontWeight: '500',
              transition: 'all 0.2s',
              backgroundColor: activeTab === 'tests' ? '#ec4899' : 'transparent',
              color: activeTab === 'tests' ? 'white' : '#6b7280'
            }"
          >
            Psikolojik Testler
          </button>
          <button
            @click="activeTab = 'support'"
            :style="{
              padding: '8px 24px',
              borderRadius: '6px',
              border: 'none',
              cursor: 'pointer',
              fontWeight: '500',
              transition: 'all 0.2s',
              backgroundColor: activeTab === 'support' ? '#ec4899' : 'transparent',
              color: activeTab === 'support' ? 'white' : '#6b7280'
            }"
          >
            Destek Kaynakları
          </button>
        </div>
      </div>

      <!-- Mood Tracker Tab -->
      <div v-if="activeTab === 'mood'">
        <div v-if="!authStore.isAuthenticated" style="text-align: center; padding: 64px 32px;">
          <div style="font-size: 4rem; margin-bottom: 16px;">🔒</div>
          <h2 style="font-size: 1.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">Giriş Yapın</h2>
          <p style="color: #6b7280; margin-bottom: 24px;">Ruh hali takibi için giriş yapmanız gerekiyor.</p>
          <router-link to="/login" style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500;">
            Giriş Yap
          </router-link>
        </div>

        <div v-else>
          <!-- Today's Mood -->
          <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 32px;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #111827; margin-bottom: 24px; text-align: center;">
              Bugün Nasıl Hissediyorsun?
            </h2>
            
            <div style="display: flex; justify-content: center; gap: 16px; margin-bottom: 32px;">
              <button
                v-for="mood in moodOptions"
                :key="mood.value"
                @click="selectedMood = mood.value"
                :style="{
                  padding: '16px',
                  borderRadius: '50%',
                  border: selectedMood === mood.value ? '3px solid #ec4899' : '2px solid #e5e7eb',
                  background: 'white',
                  cursor: 'pointer',
                  fontSize: '2rem',
                  transition: 'all 0.2s',
                  transform: selectedMood === mood.value ? 'scale(1.1)' : 'scale(1)'
                }"
                :title="mood.label"
              >
                {{ mood.emoji }}
              </button>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">
                  Enerji Seviyesi: {{ energyLevel }}
                </label>
                <input
                  v-model="energyLevel"
                  type="range"
                  min="1"
                  max="10"
                  style="width: 100%; height: 6px; border-radius: 3px; background: #e5e7eb; outline: none;"
                >
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #6b7280; margin-top: 4px;">
                  <span>Düşük</span>
                  <span>Yüksek</span>
                </div>
              </div>

              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">
                  Stres Seviyesi: {{ stressLevel }}
                </label>
                <input
                  v-model="stressLevel"
                  type="range"
                  min="1"
                  max="10"
                  style="width: 100%; height: 6px; border-radius: 3px; background: #e5e7eb; outline: none;"
                >
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #6b7280; margin-top: 4px;">
                  <span>Düşük</span>
                  <span>Yüksek</span>
                </div>
              </div>
            </div>

            <div style="margin-bottom: 24px;">
              <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Notlar (İsteğe bağlı)
              </label>
              <textarea
                v-model="moodNotes"
                placeholder="Bugün nasıl hissettiğinizi açıklayın..."
                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; resize: vertical; min-height: 80px;"
              ></textarea>
            </div>

            <div style="text-align: center;">
              <button
                @click="saveMood"
                :disabled="!selectedMood || saving"
                style="background: #ec4899; color: white; padding: 12px 32px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: background 0.2s;"
                @mouseover="$event.target.style.background = '#db2777'"
                @mouseleave="$event.target.style.background = '#ec4899'"
              >
                {{ saving ? 'Kaydediliyor...' : 'Ruh Halini Kaydet' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Quotes Tab -->
      <div v-if="activeTab === 'quotes'">
        <!-- Daily Quote -->
        <div v-if="dailyQuote" style="background: linear-gradient(135deg, #ec4899, #f472b6); border-radius: 8px; padding: 32px; margin-bottom: 32px; color: white; text-align: center;">
          <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 16px;">Günün Sözü</h2>
          <blockquote style="font-size: 1.25rem; font-style: italic; margin-bottom: 16px; line-height: 1.6;">
            "{{ dailyQuote.quote }}"
          </blockquote>
          <p v-if="dailyQuote.author" style="opacity: 0.9;">- {{ dailyQuote.author }}</p>
        </div>

        <!-- Quote Categories -->
        <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 32px; flex-wrap: wrap;">
          <button
            v-for="category in quoteCategories"
            :key="category.value"
            @click="selectedQuoteCategory = category.value; fetchQuotes()"
            :style="{
              padding: '8px 16px',
              borderRadius: '9999px',
              border: 'none',
              cursor: 'pointer',
              fontSize: '0.875rem',
              fontWeight: '500',
              transition: 'all 0.2s',
              backgroundColor: selectedQuoteCategory === category.value ? '#ec4899' : '#f3f4f6',
              color: selectedQuoteCategory === category.value ? 'white' : '#374151'
            }"
          >
            {{ category.label }}
          </button>
        </div>

        <!-- Quotes List -->
        <div v-if="quotes.length" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
          <div 
            v-for="quote in quotes" 
            :key="quote.id"
            style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; text-align: center;"
          >
            <blockquote style="font-size: 1.125rem; font-style: italic; color: #374151; margin-bottom: 16px; line-height: 1.6;">
              "{{ quote.quote }}"
            </blockquote>
            <p v-if="quote.author" style="color: #6b7280; font-weight: 500;">- {{ quote.author }}</p>
          </div>
        </div>
      </div>

      <!-- Tests Tab -->
      <div v-if="activeTab === 'tests'">
        <div v-if="tests.length" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
          <div 
            v-for="test in tests" 
            :key="test.id"
            style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; transition: box-shadow 0.2s;"
            @mouseover="$event.target.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'"
            @mouseleave="$event.target.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)'"
          >
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 12px;">{{ test.title }}</h3>
            <p style="color: #6b7280; margin-bottom: 16px;">{{ test.description }}</p>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
              <span style="padding: 4px 8px; background: #e0f2fe; color: #0369a1; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                {{ getCategoryLabel(test.category) }}
              </span>
              <span style="color: #6b7280; font-size: 0.875rem;">
                ⏱️ {{ test.duration_minutes }} dakika
              </span>
            </div>

            <button
              style="width: 100%; background: #ec4899; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: background 0.2s;"
              @mouseover="$event.target.style.background = '#db2777'"
              @mouseleave="$event.target.style.background = '#ec4899'"
            >
              Teste Başla
            </button>
          </div>
        </div>
      </div>

      <!-- Support Tab -->
      <div v-if="activeTab === 'support'" style="text-align: center; padding: 32px;">
        <div style="font-size: 4rem; margin-bottom: 16px;">🆘</div>
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">Online Destek Kaynakları</h2>
        <p style="color: #6b7280; margin-bottom: 24px;">Size yardımcı olabilecek destek kaynakları ve acil durum hatları</p>
        <router-link 
          to="/destek-kaynaklari" 
          style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500;"
        >
          Destek Kaynaklarını İncele
        </router-link>
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
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const activeTab = ref('mood')
const loading = ref(false)
const saving = ref(false)

// Mood tracking
const selectedMood = ref('')
const energyLevel = ref(5)
const stressLevel = ref(5)
const moodNotes = ref('')

const moodOptions = [
  { value: 'very_sad', label: 'Çok Üzgün', emoji: '😢' },
  { value: 'sad', label: 'Üzgün', emoji: '😔' },
  { value: 'neutral', label: 'Normal', emoji: '😐' },
  { value: 'happy', label: 'Mutlu', emoji: '😊' },
  { value: 'very_happy', label: 'Çok Mutlu', emoji: '😄' }
]

// Quotes
const dailyQuote = ref(null)
const quotes = ref([])
const selectedQuoteCategory = ref('motivation')
const quoteCategories = ref([])

// Tests
const tests = ref([])

const fetchDailyQuote = async () => {
  try {
    const response = await axios.get('/api/psychology/daily-quote')
    dailyQuote.value = response.data
  } catch (error) {
    console.error('Günlük söz yüklenirken hata:', error)
  }
}

const fetchQuotes = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/psychology/quotes', {
      params: { category: selectedQuoteCategory.value }
    })
    quotes.value = response.data.data
  } catch (error) {
    console.error('Sözler yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const fetchTests = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/psychology/tests')
    tests.value = response.data
  } catch (error) {
    console.error('Testler yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/psychology/categories')
    quoteCategories.value = response.data.quote_categories
  } catch (error) {
    console.error('Kategoriler yüklenirken hata:', error)
  }
}

const saveMood = async () => {
  if (!selectedMood.value) return
  
  saving.value = true
  try {
    await axios.post('/api/psychology/mood-tracker', {
      date: new Date().toISOString().split('T')[0],
      mood: selectedMood.value,
      energy_level: energyLevel.value,
      stress_level: stressLevel.value,
      notes: moodNotes.value || null
    })
    
    alert('Ruh haliniz başarıyla kaydedildi!')
  } catch (error) {
    console.error('Ruh hali kaydedilirken hata:', error)
    alert('Bir hata oluştu. Lütfen tekrar deneyin.')
  } finally {
    saving.value = false
  }
}

const getCategoryLabel = (category: string) => {
  const labels = {
    personality: 'Kişilik',
    stress: 'Stres',
    anxiety: 'Kaygı',
    depression: 'Depresyon',
    self_esteem: 'Öz Güven'
  }
  return labels[category as keyof typeof labels] || category
}

onMounted(() => {
  fetchDailyQuote()
  fetchCategories()
  fetchQuotes()
  fetchTests()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>