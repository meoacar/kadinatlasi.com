<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-display font-bold text-gray-900 mb-4">🤱 Gebelik & Anne Rehberi</h1>
      <p class="text-xl text-gray-600">40 haftalık gebelik yolculuğunuzda yanınızdayız</p>
    </div>

    <!-- Gebelik Takibi -->
    <div v-if="pregnancyTracker" class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg p-6 mb-8">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Gebelik Takibiniz</h2>
        <div class="text-4xl font-bold text-primary-600">{{ pregnancyTracker.current_week }}. Hafta</div>
        <div class="text-lg text-gray-600">{{ pregnancyTracker.current_day }}. Gün</div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="text-center p-4 bg-white rounded-lg">
          <div class="text-2xl mb-2">📅</div>
          <div class="font-semibold">Hamilelik Süresi</div>
          <div class="text-primary-600">{{ pregnancyTracker.days_pregnant }} gün</div>
        </div>
        <div class="text-center p-4 bg-white rounded-lg">
          <div class="text-2xl mb-2">⏰</div>
          <div class="font-semibold">Kalan Süre</div>
          <div class="text-primary-600">{{ pregnancyTracker.weeks_remaining }} hafta</div>
        </div>
        <div class="text-center p-4 bg-white rounded-lg">
          <div class="text-2xl mb-2">📊</div>
          <div class="font-semibold">İlerleme</div>
          <div class="text-primary-600">%{{ Math.round((pregnancyTracker.current_week / 40) * 100) }}</div>
        </div>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
        <div 
          class="bg-gradient-to-r from-pink-500 to-purple-500 h-4 rounded-full transition-all duration-500"
          :style="{ width: `${(pregnancyTracker.current_week / 40) * 100}%` }"
        ></div>
      </div>

      <!-- Bu Haftaki Rehber -->
      <div v-if="currentWeekGuide" class="bg-white rounded-lg p-6">
        <h3 class="text-xl font-bold mb-4">{{ currentWeekGuide.title }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="font-semibold text-purple-700 mb-2">👶 Bebeğinizin Gelişimi</h4>
            <p class="text-gray-700 mb-4">{{ currentWeekGuide.baby_development }}</p>
            <div v-if="currentWeekGuide.baby_size" class="flex items-center space-x-2 text-sm text-gray-600">
              <span>📏 Boyut:</span>
              <span class="font-medium">{{ currentWeekGuide.baby_size }}</span>
            </div>
          </div>
          <div>
            <h4 class="font-semibold text-pink-700 mb-2">🤰 Vücudunuzdaki Değişimler</h4>
            <p class="text-gray-700">{{ currentWeekGuide.mother_changes }}</p>
          </div>
        </div>
        
        <div v-if="currentWeekGuide.tips" class="mt-6">
          <h4 class="font-semibold text-green-700 mb-2">💡 Bu Hafta İçin Öneriler</h4>
          <ul class="list-disc list-inside space-y-1">
            <li v-for="tip in currentWeekGuide.tips" :key="tip" class="text-gray-700">{{ tip }}</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Gebelik Takibi Başlatma -->
    <div v-else class="bg-white rounded-lg shadow-md p-6 mb-8">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Gebelik Takibinizi Başlatın</h2>
        <p class="text-gray-600">Son adet tarihinizi girerek kişisel gebelik takibinizi başlatabilirsiniz</p>
      </div>

      <form @submit.prevent="startPregnancyTracker" class="max-w-md mx-auto">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Son Adet Tarihi</label>
          <input
            v-model="lastMenstrualPeriod"
            type="date"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
          >
        </div>
        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg hover:bg-primary-600 transition-colors disabled:bg-gray-300"
        >
          {{ loading ? 'Başlatılıyor...' : 'Takibi Başlat' }}
        </button>
      </form>
    </div>

    <!-- Haftalık Rehber -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">40 Haftalık Gebelik Rehberi</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <button
          v-for="week in pregnancyWeeks"
          :key="week.id"
          @click="selectedWeek = week"
          :class="[
            'p-4 rounded-lg border-2 text-left transition-all',
            selectedWeek?.id === week.id
              ? 'border-primary-500 bg-primary-50'
              : 'border-gray-200 hover:border-primary-300'
          ]"
        >
          <div class="font-semibold text-primary-600">{{ week.week_number }}. Hafta</div>
          <div class="text-sm text-gray-600 mt-1">{{ week.title.split(' - ')[1] }}</div>
          <div v-if="week.baby_size" class="text-xs text-gray-500 mt-2">📏 {{ week.baby_size }}</div>
        </button>
      </div>
    </div>

    <!-- Seçili Hafta Detayı -->
    <div v-if="selectedWeek" class="bg-white rounded-lg shadow-md p-6 mb-8">
      <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ selectedWeek.title }}</h3>
      <p class="text-gray-700 mb-6">{{ selectedWeek.description }}</p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-purple-50 p-4 rounded-lg">
          <h4 class="font-semibold text-purple-700 mb-3">👶 Bebeğinizin Gelişimi</h4>
          <p class="text-gray-700 mb-3">{{ selectedWeek.baby_development }}</p>
          <div v-if="selectedWeek.baby_size" class="flex justify-between text-sm">
            <span>📏 Boyut: <strong>{{ selectedWeek.baby_size }}</strong></span>
            <span v-if="selectedWeek.baby_weight">⚖️ Ağırlık: <strong>{{ selectedWeek.baby_weight }}</strong></span>
          </div>
        </div>

        <div class="bg-pink-50 p-4 rounded-lg">
          <h4 class="font-semibold text-pink-700 mb-3">🤰 Vücudunuzdaki Değişimler</h4>
          <p class="text-gray-700">{{ selectedWeek.mother_changes }}</p>
        </div>
      </div>

      <div v-if="selectedWeek.tips" class="mt-6 bg-green-50 p-4 rounded-lg">
        <h4 class="font-semibold text-green-700 mb-3">💡 Bu Hafta İçin Öneriler</h4>
        <ul class="list-disc list-inside space-y-2">
          <li v-for="tip in selectedWeek.tips" :key="tip" class="text-gray-700">{{ tip }}</li>
        </ul>
      </div>
    </div>

    <!-- Doğum Sonrası Rehber -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-8">
      <div class="text-center">
        <div class="text-4xl mb-4">👶</div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Doğum Sonrası Rehber</h2>
        <p class="text-gray-600 mb-4">Doğum sonrası dönemde size rehberlik edecek bilgiler</p>
        <RouterLink 
          to="/dogum-sonrasi-rehber" 
          class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors"
        >
          Rehberi İncele
        </RouterLink>
      </div>
    </div>

    <!-- Bebek İsimleri -->
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">👶 Bebek İsimleri</h2>
        <RouterLink 
          to="/bebek-isimleri" 
          class="text-primary-600 hover:text-primary-700 font-medium"
        >
          Tümünü Gör →
        </RouterLink>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="name in popularBabyNames.slice(0, 6)" :key="name.id" class="p-4 border rounded-lg">
          <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold text-lg">{{ name.name }}</h3>
            <span :class="[
              'px-2 py-1 rounded text-xs font-medium',
              name.gender === 'kiz' ? 'bg-pink-100 text-pink-700' :
              name.gender === 'erkek' ? 'bg-blue-100 text-blue-700' :
              'bg-purple-100 text-purple-700'
            ]">
              {{ name.gender === 'kiz' ? 'Kız' : name.gender === 'erkek' ? 'Erkek' : 'Unisex' }}
            </span>
          </div>
          <p class="text-gray-600 text-sm mb-2">{{ name.meaning }}</p>
          <p class="text-gray-500 text-xs">{{ name.origin }} kökenli</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import axios from 'axios'

const pregnancyTracker = ref(null)
const currentWeekGuide = ref(null)
const pregnancyWeeks = ref([])
const selectedWeek = ref(null)
const popularBabyNames = ref([])
const lastMenstrualPeriod = ref('')
const loading = ref(false)

const fetchPregnancyTracker = async () => {
  try {
    const response = await axios.get('/api/pregnancy/tracker')
    pregnancyTracker.value = response.data.tracker
    currentWeekGuide.value = response.data.weekly_guide
  } catch (error) {
    console.log('Gebelik takibi bulunamadı')
  }
}

const fetchPregnancyWeeks = async () => {
  try {
    const response = await axios.get('/api/pregnancy/weeks')
    pregnancyWeeks.value = response.data
  } catch (error) {
    console.error('Gebelik haftaları yüklenirken hata:', error)
  }
}

const fetchPopularBabyNames = async () => {
  try {
    const response = await axios.get('/api/baby-names?popular=1')
    popularBabyNames.value = response.data.data
  } catch (error) {
    console.error('Bebek isimleri yüklenirken hata:', error)
  }
}

const startPregnancyTracker = async () => {
  loading.value = true
  try {
    await axios.post('/api/pregnancy/tracker', {
      last_menstrual_period: lastMenstrualPeriod.value
    })
    await fetchPregnancyTracker()
    alert('Gebelik takibiniz başarıyla başlatıldı!')
  } catch (error) {
    console.error('Gebelik takibi başlatılırken hata:', error)
    alert('Bir hata oluştu. Lütfen tekrar deneyin.')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchPregnancyTracker()
  fetchPregnancyWeeks()
  fetchPopularBabyNames()
})
</script>