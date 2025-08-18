<template>
  <!-- Workout Timer Modal -->
  <div v-if="showWorkoutTimer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">⏰ Antrenman Zamanlayıcısı</h2>
        <button @click="closeWorkoutTimer" class="text-gray-500 hover:text-gray-700">✕</button>
      </div>
      
      <div class="text-center mb-6">
        <div class="text-6xl font-bold text-blue-600 mb-4">{{ formatTime(timerSeconds) }}</div>
        <div class="mb-4">
          <select v-model="selectedWorkoutType" class="w-full p-3 border rounded-lg">
            <option value="hiit">HIIT Antrenmanı</option>
            <option value="cardio">Kardiyovasküler</option>
            <option value="strength">Güç Antrenmanı</option>
            <option value="yoga">Yoga/Stretching</option>
          </select>
        </div>
        <div class="flex gap-2 justify-center">
          <button @click="startTimer" v-if="!timerRunning" class="bg-green-500 text-white px-6 py-2 rounded-lg">Başla</button>
          <button @click="pauseTimer" v-if="timerRunning" class="bg-yellow-500 text-white px-6 py-2 rounded-lg">Duraklat</button>
          <button @click="stopTimer" class="bg-red-500 text-white px-6 py-2 rounded-lg">Durdur</button>
        </div>
      </div>
      
      <div class="grid grid-cols-3 gap-2 mb-4">
        <button @click="setTimer(300)" class="bg-gray-100 p-2 rounded text-sm">5 dk</button>
        <button @click="setTimer(600)" class="bg-gray-100 p-2 rounded text-sm">10 dk</button>
        <button @click="setTimer(900)" class="bg-gray-100 p-2 rounded text-sm">15 dk</button>
        <button @click="setTimer(1200)" class="bg-gray-100 p-2 rounded text-sm">20 dk</button>
        <button @click="setTimer(1800)" class="bg-gray-100 p-2 rounded text-sm">30 dk</button>
        <button @click="setTimer(2700)" class="bg-gray-100 p-2 rounded text-sm">45 dk</button>
      </div>
    </div>
  </div>

  <!-- Body Tracker Modal -->
  <div v-if="showBodyTracker" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">📏 Vücut Ölçüm Takibi</h2>
        <button @click="closeBodyTracker" class="text-gray-500 hover:text-gray-700">✕</button>
      </div>
      
      <form @submit.prevent="saveBodyMeasurement" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Kilo (kg)</label>
            <input v-model="bodyData.weight" type="number" step="0.1" class="w-full p-2 border rounded-lg" placeholder="65.5">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Boy (cm)</label>
            <input v-model="bodyData.height" type="number" class="w-full p-2 border rounded-lg" placeholder="165">
          </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Göğüs (cm)</label>
            <input v-model="bodyData.chest" type="number" step="0.1" class="w-full p-2 border rounded-lg" placeholder="90">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Bel (cm)</label>
            <input v-model="bodyData.waist" type="number" step="0.1" class="w-full p-2 border rounded-lg" placeholder="70">
          </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Kalça (cm)</label>
            <input v-model="bodyData.hips" type="number" step="0.1" class="w-full p-2 border rounded-lg" placeholder="95">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Kol (cm)</label>
            <input v-model="bodyData.arms" type="number" step="0.1" class="w-full p-2 border rounded-lg" placeholder="28">
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium mb-1">Uyluk (cm)</label>
          <input v-model="bodyData.thighs" type="number" step="0.1" class="w-full p-2 border rounded-lg" placeholder="55">
        </div>
        
        <button type="submit" :disabled="saving" class="w-full bg-pink-500 text-white py-3 rounded-lg font-semibold">
          {{ saving ? 'Kaydediliyor...' : 'Ölçümleri Kaydet' }}
        </button>
      </form>
    </div>
  </div>

  <!-- Exercise Generator Modal -->
  <div v-if="showExerciseGenerator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">🎲 Rastgele Egzersiz</h2>
        <button @click="closeExerciseGenerator" class="text-gray-500 hover:text-gray-700">✕</button>
      </div>
      
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Kategori Seçin</label>
        <select v-model="selectedCategory" class="w-full p-3 border rounded-lg">
          <option value="all">Tüm Kategoriler</option>
          <option value="cardio">Kardiyovasküler</option>
          <option value="strength">Güç Antrenmanı</option>
          <option value="flexibility">Esneklik & Yoga</option>
          <option value="hiit">HIIT</option>
        </select>
      </div>
      
      <div v-if="randomExercise" class="text-center mb-6 p-4 bg-gray-50 rounded-lg">
        <h3 class="text-lg font-semibold mb-2">{{ randomExercise.name }}</h3>
        <p class="text-gray-600 mb-3">{{ randomExercise.description }}</p>
        <div class="flex justify-center gap-4 text-sm">
          <span class="bg-blue-100 px-3 py-1 rounded-full">⏱️ {{ randomExercise.duration }} dk</span>
          <span class="bg-red-100 px-3 py-1 rounded-full">🔥 {{ randomExercise.calories }} kalori</span>
        </div>
      </div>
      
      <button @click="generateRandomExercise" class="w-full bg-purple-500 text-white py-3 rounded-lg font-semibold mb-3">
        Yeni Egzersiz Öner
      </button>
      
      <button v-if="randomExercise" @click="startExercise" class="w-full bg-green-500 text-white py-3 rounded-lg font-semibold">
        Bu Egzersize Başla
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const authStore = useAuthStore()

// Workout Timer
const showWorkoutTimer = ref(false)
const timerSeconds = ref(0)
const timerRunning = ref(false)
const timerInterval = ref(null)
const selectedWorkoutType = ref('hiit')

// Body Tracker
const showBodyTracker = ref(false)
const saving = ref(false)
const bodyData = ref({
  weight: '',
  height: '',
  chest: '',
  waist: '',
  hips: '',
  arms: '',
  thighs: ''
})

// Exercise Generator
const showExerciseGenerator = ref(false)
const selectedCategory = ref('all')
const randomExercise = ref(null)

const formatTime = (seconds) => {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
}

const setTimer = (seconds) => {
  timerSeconds.value = seconds
}

const startTimer = () => {
  timerRunning.value = true
  timerInterval.value = setInterval(() => {
    if (timerSeconds.value > 0) {
      timerSeconds.value--
    } else {
      completeWorkout()
    }
  }, 1000)
}

const pauseTimer = () => {
  timerRunning.value = false
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
}

const stopTimer = () => {
  timerRunning.value = false
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
  timerSeconds.value = 0
}

const completeWorkout = async () => {
  timerRunning.value = false
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
  
  if (authStore.isAuthenticated) {
    try {
      const duration = Math.floor((Date.now() - startTime) / 60000) // minutes
      const estimatedCalories = duration * 8 // rough estimate
      
      await api.post('/fitness/workout-timer', {
        duration,
        type: selectedWorkoutType.value,
        calories_burned: estimatedCalories
      })
      
      alert('🎉 Tebrikler! Antrenmanınız tamamlandı ve kaydedildi.')
    } catch (error) {
      console.error('Workout save error:', error)
    }
  } else {
    alert('🎉 Tebrikler! Antrenmanınız tamamlandı.')
  }
  
  timerSeconds.value = 0
}

let startTime = 0

const saveBodyMeasurement = async () => {
  if (!authStore.isAuthenticated) {
    alert('Ölçümleri kaydetmek için giriş yapmalısınız.')
    return
  }
  
  saving.value = true
  try {
    await api.post('/fitness/body-measurement', bodyData.value)
    alert('✅ Vücut ölçümleriniz başarıyla kaydedildi!')
    closeBodyTracker()
  } catch (error) {
    console.error('Body measurement save error:', error)
    alert('❌ Kaydetme sırasında bir hata oluştu.')
  } finally {
    saving.value = false
  }
}

const generateRandomExercise = async () => {
  try {
    const response = await api.get('/fitness/random-exercise', {
      params: { category: selectedCategory.value }
    })
    randomExercise.value = response.data.exercise
  } catch (error) {
    console.error('Random exercise error:', error)
  }
}

const startExercise = () => {
  if (randomExercise.value) {
    setTimer(randomExercise.value.duration * 60)
    closeExerciseGenerator()
    showWorkoutTimer.value = true
    startTime = Date.now()
  }
}

// Modal controls
const openWorkoutTimer = () => {
  showWorkoutTimer.value = true
  setTimer(1200) // 20 minutes default
}

const closeWorkoutTimer = () => {
  showWorkoutTimer.value = false
  stopTimer()
}

const openBodyTracker = () => {
  showBodyTracker.value = true
}

const closeBodyTracker = () => {
  showBodyTracker.value = false
  bodyData.value = {
    weight: '',
    height: '',
    chest: '',
    waist: '',
    hips: '',
    arms: '',
    thighs: ''
  }
}

const openExerciseGenerator = () => {
  showExerciseGenerator.value = true
  generateRandomExercise()
}

const closeExerciseGenerator = () => {
  showExerciseGenerator.value = false
  randomExercise.value = null
}

// Cleanup
onUnmounted(() => {
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
})

// Expose methods to parent
defineExpose({
  openWorkoutTimer,
  openBodyTracker,
  openExerciseGenerator
})
</script>