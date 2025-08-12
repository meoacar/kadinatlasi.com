<template>
  <!-- Mood Tracker -->
  <div v-if="showTool && toolType === 'mood-tracker'" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 16px;">
    <div style="background: white; border-radius: 20px; max-width: 500px; width: 100%; padding: 32px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin: 0;">📊 Ruh Hali Takibi</h2>
        <button @click="closeTool" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">×</button>
      </div>
      
      <p style="color: #6b7280; margin-bottom: 24px;">Bugün kendinizi nasıl hissediyorsunuz?</p>
      
      <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px;">
        <button
          v-for="mood in moods"
          :key="mood.value"
          @click="selectedMood = mood.value"
          :style="{
            padding: '16px 8px',
            border: selectedMood === mood.value ? '3px solid #6366f1' : '2px solid #e5e7eb',
            borderRadius: '12px',
            background: selectedMood === mood.value ? '#f0f9ff' : 'white',
            cursor: 'pointer',
            textAlign: 'center',
            transition: 'all 0.2s ease'
          }"
        >
          <div style="font-size: 2rem; margin-bottom: 8px;">{{ mood.emoji }}</div>
          <div style="font-size: 0.75rem; font-weight: 500; color: #374151;">{{ mood.label }}</div>
        </button>
      </div>
      
      <textarea 
        v-model="moodNote"
        placeholder="Bugün neler yaşadınız? (İsteğe bağlı)"
        style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; height: 80px; resize: vertical; margin-bottom: 20px;"
      ></textarea>
      
      <button 
        @click="saveMood"
        :disabled="!selectedMood"
        style="width: 100%; background: #6366f1; color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; disabled:opacity-50;"
      >
        Kaydet
      </button>
    </div>
  </div>

  <!-- Breathing Exercise -->
  <div v-if="showTool && toolType === 'breathing'" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 16px;">
    <div style="background: white; border-radius: 20px; max-width: 500px; width: 100%; padding: 32px; text-align: center;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin: 0;">🫁 Nefes Egzersizi</h2>
        <button @click="closeTool" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">×</button>
      </div>
      
      <div v-if="!breathingActive" style="margin-bottom: 24px;">
        <p style="color: #6b7280; margin-bottom: 20px;">4-7-8 nefes tekniği ile rahatlamaya hazır mısınız?</p>
        <div style="background: #f0f9ff; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
          <h4 style="font-weight: 600; margin-bottom: 12px;">Nasıl Yapılır:</h4>
          <ul style="text-align: left; color: #6b7280; font-size: 0.875rem;">
            <li>4 saniye nefes alın</li>
            <li>7 saniye nefesi tutun</li>
            <li>8 saniye nefes verin</li>
            <li>Bu döngüyü 4 kez tekrarlayın</li>
          </ul>
        </div>
        <button @click="startBreathing" style="background: #6366f1; color: white; padding: 16px 32px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600;">
          Başla
        </button>
      </div>
      
      <div v-else>
        <div style="margin-bottom: 24px;">
          <div 
            :style="{
              width: '120px',
              height: '120px',
              borderRadius: '50%',
              background: 'linear-gradient(135deg, #6366f1, #8b5cf6)',
              margin: '0 auto 20px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              transform: breathingPhase === 'inhale' ? 'scale(1.2)' : breathingPhase === 'hold' ? 'scale(1.2)' : 'scale(0.8)',
              transition: 'transform 1s ease-in-out'
            }"
          >
            <span style="color: white; font-size: 1.5rem; font-weight: 600;">{{ breathingCount }}</span>
          </div>
          
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 8px;">
            {{ breathingPhase === 'inhale' ? 'Nefes Alın' : breathingPhase === 'hold' ? 'Tutun' : 'Nefes Verin' }}
          </h3>
          <p style="color: #6b7280;">{{ breathingCount }} saniye</p>
        </div>
        
        <button @click="stopBreathing" style="background: #ef4444; color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500;">
          Durdur
        </button>
      </div>
    </div>
  </div>

  <!-- Meditation -->
  <div v-if="showTool && toolType === 'meditation'" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 16px;">
    <div style="background: white; border-radius: 20px; max-width: 500px; width: 100%; padding: 32px; text-align: center;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin: 0;">🎵 Meditasyon</h2>
        <button @click="closeTool" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">×</button>
      </div>
      
      <div v-if="!meditationActive">
        <p style="color: #6b7280; margin-bottom: 24px;">Rehberli meditasyon seansı için süre ve ses seçin:</p>
        
        <!-- Duration Selection -->
        <div style="margin-bottom: 24px;">
          <h4 style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Süre Seçin:</h4>
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
            <button
              v-for="duration in meditationDurations"
              :key="duration.value"
              @click="selectedDuration = duration.value"
              :style="{
                padding: '16px',
                border: selectedDuration === duration.value ? '3px solid #6366f1' : '2px solid #e5e7eb',
                borderRadius: '12px',
                background: selectedDuration === duration.value ? '#f0f9ff' : 'white',
                cursor: 'pointer',
                transition: 'all 0.2s ease'
              }"
            >
              <div style="font-size: 1.5rem; margin-bottom: 8px;">{{ duration.emoji }}</div>
              <div style="font-size: 0.875rem; font-weight: 500;">{{ duration.label }}</div>
            </button>
          </div>
        </div>
        
        <!-- Sound Selection -->
        <div style="margin-bottom: 24px;">
          <h4 style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Rahatlatıcı Ses Seçin:</h4>
          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
            <button
              v-for="sound in meditationSounds"
              :key="sound.value"
              @click="selectedSound = sound.value"
              :style="{
                padding: '12px',
                border: selectedSound === sound.value ? '3px solid #10b981' : '2px solid #e5e7eb',
                borderRadius: '12px',
                background: selectedSound === sound.value ? '#ecfdf5' : 'white',
                cursor: 'pointer',
                transition: 'all 0.2s ease',
                display: 'flex',
                alignItems: 'center',
                gap: '8px'
              }"
            >
              <span style="font-size: 1.2rem;">{{ sound.emoji }}</span>
              <span style="font-size: 0.875rem; font-weight: 500;">{{ sound.label }}</span>
            </button>
          </div>
        </div>
        
        <button 
          @click="startMeditation"
          :disabled="!selectedDuration || !selectedSound"
          style="background: #6366f1; color: white; padding: 16px 32px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600; disabled:opacity-50;"
        >
          Meditasyonu Başlat
        </button>
      </div>
      
      <div v-else>
        <div style="margin-bottom: 24px;">
          <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; position: relative;">
            <div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; border: 3px solid rgba(16, 185, 129, 0.3); animation: pulse 2s infinite;"></div>
            <span style="color: white; font-size: 1.5rem; font-weight: 600;">{{ formatTime(meditationTimeLeft) }}</span>
          </div>
          
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Derin nefes alın ve rahatlamaya odaklanın</h3>
          <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 8px;">Düşüncelerinizi gözlemleyin, yargılamayın</p>
          <div v-if="selectedSound !== 'silence'" style="display: flex; align-items: center; justify-content: center; gap: 8px; color: #10b981; font-size: 0.875rem;">
            <span>{{ meditationSounds.find(s => s.value === selectedSound)?.emoji }}</span>
            <span>{{ meditationSounds.find(s => s.value === selectedSound)?.label }} çalıyor</span>
          </div>
        </div>
        
        <button @click="stopMeditation" style="background: #ef4444; color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500;">
          Bitir
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onUnmounted } from 'vue'

const props = defineProps({
  toolType: String,
  showTool: Boolean
})

const emit = defineEmits(['close'])

// Mood Tracker
const selectedMood = ref('')
const moodNote = ref('')
const moods = [
  { value: 'very-sad', emoji: '😢', label: 'Çok Üzgün' },
  { value: 'sad', emoji: '😔', label: 'Üzgün' },
  { value: 'neutral', emoji: '😐', label: 'Normal' },
  { value: 'happy', emoji: '😊', label: 'Mutlu' },
  { value: 'very-happy', emoji: '😄', label: 'Çok Mutlu' }
]

// Breathing Exercise
const breathingActive = ref(false)
const breathingPhase = ref('inhale') // inhale, hold, exhale
const breathingCount = ref(4)
const breathingInterval = ref(null)
const breathingCycle = ref(0)

// Meditation
const meditationActive = ref(false)
const selectedDuration = ref(null)
const selectedSound = ref(null)
const meditationTimeLeft = ref(0)
const meditationInterval = ref(null)
const currentAudio = ref(null)
const meditationDurations = [
  { value: 300, emoji: '🌱', label: '5 Dakika' },
  { value: 600, emoji: '🌿', label: '10 Dakika' },
  { value: 900, emoji: '🌳', label: '15 Dakika' }
]
const meditationSounds = [
  { value: 'rain', emoji: '🌧️', label: 'Yağmur Sesi', url: 'https://www.soundjay.com/misc/sounds/rain-01.wav' },
  { value: 'ocean', emoji: '🌊', label: 'Okyanus Dalgaları', url: 'https://www.soundjay.com/misc/sounds/ocean-wave-1.wav' },
  { value: 'forest', emoji: '🌲', label: 'Orman Sesleri', url: 'https://www.soundjay.com/misc/sounds/forest-1.wav' },
  { value: 'birds', emoji: '🐦', label: 'Kuş Sesleri', url: 'https://www.soundjay.com/misc/sounds/birds-1.wav' },
  { value: 'wind', emoji: '🍃', label: 'Rüzgar Sesi', url: 'https://www.soundjay.com/misc/sounds/wind-1.wav' },
  { value: 'silence', emoji: '🤫', label: 'Sessizlik', url: null }
]

const saveMood = async () => {
  try {
    // Backend'e kaydet
    const response = await fetch('http://localhost:8000/api/mood-tracker', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({
        mood: selectedMood.value,
        note: moodNote.value,
        date: new Date().toISOString().split('T')[0]
      })
    })
    
    if (response.ok) {
      alert('Ruh haliniz kaydedildi! 📊 Dashboard\'ınızda görüntüleyebilirsiniz.')
    } else {
      // Fallback: localStorage'a kaydet
      const moodEntry = {
        mood: selectedMood.value,
        note: moodNote.value,
        date: new Date().toISOString().split('T')[0],
        timestamp: new Date().toISOString()
      }
      
      const existingMoods = JSON.parse(localStorage.getItem('moodTracker') || '[]')
      existingMoods.push(moodEntry)
      localStorage.setItem('moodTracker', JSON.stringify(existingMoods))
      
      alert('Ruh haliniz yerel olarak kaydedildi! 📊')
    }
  } catch (error) {
    // Hata durumunda localStorage'a kaydet
    const moodEntry = {
      mood: selectedMood.value,
      note: moodNote.value,
      date: new Date().toISOString().split('T')[0],
      timestamp: new Date().toISOString()
    }
    
    const existingMoods = JSON.parse(localStorage.getItem('moodTracker') || '[]')
    existingMoods.push(moodEntry)
    localStorage.setItem('moodTracker', JSON.stringify(existingMoods))
    
    alert('Ruh haliniz yerel olarak kaydedildi! 📊')
  }
  
  closeTool()
}

const startBreathing = () => {
  breathingActive.value = true
  breathingPhase.value = 'inhale'
  breathingCount.value = 4
  breathingCycle.value = 0
  
  breathingInterval.value = setInterval(() => {
    if (breathingCount.value > 1) {
      breathingCount.value--
    } else {
      // Fase değiştir
      if (breathingPhase.value === 'inhale') {
        breathingPhase.value = 'hold'
        breathingCount.value = 7
      } else if (breathingPhase.value === 'hold') {
        breathingPhase.value = 'exhale'
        breathingCount.value = 8
      } else {
        breathingCycle.value++
        if (breathingCycle.value >= 4) {
          stopBreathing()
          alert('Nefes egzersizi tamamlandı! 🫁 Kendinizi daha rahat hissediyor musunuz?')
          return
        }
        breathingPhase.value = 'inhale'
        breathingCount.value = 4
      }
    }
  }, 1000)
}

const stopBreathing = () => {
  breathingActive.value = false
  if (breathingInterval.value) {
    clearInterval(breathingInterval.value)
    breathingInterval.value = null
  }
}

const startMeditation = () => {
  meditationActive.value = true
  meditationTimeLeft.value = selectedDuration.value
  
  // Play selected sound
  if (selectedSound.value !== 'silence') {
    const soundData = meditationSounds.find(s => s.value === selectedSound.value)
    if (soundData && soundData.url) {
      // Create a simple tone generator for demo purposes
      playMeditationSound(selectedSound.value)
    }
  }
  
  meditationInterval.value = setInterval(() => {
    if (meditationTimeLeft.value > 0) {
      meditationTimeLeft.value--
    } else {
      stopMeditation()
      alert('Meditasyon tamamlandı! 🧘‍♀️ Harika iş çıkardınız!')
    }
  }, 1000)
}

const stopMeditation = () => {
  meditationActive.value = false
  if (meditationInterval.value) {
    clearInterval(meditationInterval.value)
    meditationInterval.value = null
  }
  // Stop audio
  if (currentAudio.value) {
    currentAudio.value.pause()
    currentAudio.value = null
  }
}

const playMeditationSound = (soundType) => {
  // Create Web Audio API context for generating relaxing sounds
  const audioContext = new (window.AudioContext || window.webkitAudioContext)()
  
  if (soundType === 'rain') {
    // Generate rain-like white noise
    const bufferSize = audioContext.sampleRate * 2
    const buffer = audioContext.createBuffer(1, bufferSize, audioContext.sampleRate)
    const data = buffer.getChannelData(0)
    
    for (let i = 0; i < bufferSize; i++) {
      data[i] = (Math.random() * 2 - 1) * 0.1 // Low volume white noise
    }
    
    const source = audioContext.createBufferSource()
    const gainNode = audioContext.createGain()
    
    source.buffer = buffer
    source.loop = true
    gainNode.gain.value = 0.3
    
    source.connect(gainNode)
    gainNode.connect(audioContext.destination)
    source.start()
    
    currentAudio.value = { pause: () => source.stop() }
  } else if (soundType === 'ocean') {
    // Generate ocean wave sounds
    const oscillator = audioContext.createOscillator()
    const gainNode = audioContext.createGain()
    
    oscillator.type = 'sine'
    oscillator.frequency.setValueAtTime(60, audioContext.currentTime)
    gainNode.gain.value = 0.2
    
    // Add some variation
    setInterval(() => {
      if (oscillator.frequency) {
        oscillator.frequency.setValueAtTime(60 + Math.random() * 20, audioContext.currentTime)
      }
    }, 2000)
    
    oscillator.connect(gainNode)
    gainNode.connect(audioContext.destination)
    oscillator.start()
    
    currentAudio.value = { pause: () => oscillator.stop() }
  } else if (soundType === 'forest') {
    // Generate forest ambience
    const bufferSize = audioContext.sampleRate * 4
    const buffer = audioContext.createBuffer(1, bufferSize, audioContext.sampleRate)
    const data = buffer.getChannelData(0)
    
    for (let i = 0; i < bufferSize; i++) {
      data[i] = (Math.random() * 2 - 1) * 0.05 // Very soft noise
    }
    
    const source = audioContext.createBufferSource()
    const gainNode = audioContext.createGain()
    const filter = audioContext.createBiquadFilter()
    
    source.buffer = buffer
    source.loop = true
    filter.type = 'lowpass'
    filter.frequency.value = 800
    gainNode.gain.value = 0.15
    
    source.connect(filter)
    filter.connect(gainNode)
    gainNode.connect(audioContext.destination)
    source.start()
    
    currentAudio.value = { pause: () => source.stop() }
  } else if (soundType === 'birds') {
    // Generate bird chirping sounds
    const createChirp = () => {
      const oscillator = audioContext.createOscillator()
      const gainNode = audioContext.createGain()
      
      oscillator.type = 'sine'
      oscillator.frequency.setValueAtTime(800 + Math.random() * 400, audioContext.currentTime)
      gainNode.gain.setValueAtTime(0, audioContext.currentTime)
      gainNode.gain.linearRampToValueAtTime(0.1, audioContext.currentTime + 0.1)
      gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.3)
      
      oscillator.connect(gainNode)
      gainNode.connect(audioContext.destination)
      oscillator.start()
      oscillator.stop(audioContext.currentTime + 0.3)
    }
    
    const chirpInterval = setInterval(() => {
      if (Math.random() > 0.7) createChirp()
    }, 1000)
    
    currentAudio.value = { pause: () => clearInterval(chirpInterval) }
  } else if (soundType === 'wind') {
    // Generate wind sounds
    const bufferSize = audioContext.sampleRate * 3
    const buffer = audioContext.createBuffer(1, bufferSize, audioContext.sampleRate)
    const data = buffer.getChannelData(0)
    
    for (let i = 0; i < bufferSize; i++) {
      data[i] = (Math.random() * 2 - 1) * 0.08
    }
    
    const source = audioContext.createBufferSource()
    const gainNode = audioContext.createGain()
    const filter = audioContext.createBiquadFilter()
    
    source.buffer = buffer
    source.loop = true
    filter.type = 'highpass'
    filter.frequency.value = 200
    gainNode.gain.value = 0.2
    
    source.connect(filter)
    filter.connect(gainNode)
    gainNode.connect(audioContext.destination)
    source.start()
    
    currentAudio.value = { pause: () => source.stop() }
  }
}

const formatTime = (seconds) => {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins}:${secs.toString().padStart(2, '0')}`
}

const closeTool = () => {
  // Reset all states
  selectedMood.value = ''
  moodNote.value = ''
  stopBreathing()
  stopMeditation()
  selectedDuration.value = null
  selectedSound.value = null
  
  emit('close')
}

onUnmounted(() => {
  stopBreathing()
  stopMeditation()
})
</script>

<style>
@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.1); opacity: 0.7; }
  100% { transform: scale(1); opacity: 1; }
}
</style>