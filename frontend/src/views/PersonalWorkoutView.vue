<template>
  <div class="personal-workout-page">
    <div class="page-header">
      <h1 class="page-title">🏋️‍♀️ Kişiye Özel Egzersiz Programı</h1>
      <p class="page-subtitle">Hedeflerinize uygun özel egzersiz programı oluşturun</p>
    </div>

    <div class="workout-container">
      <!-- Program Oluşturma Formu -->
      <div v-if="!generatedProgram" class="program-form-card">
        <h2 class="form-title">Program Tercihleriniz</h2>
        <form @submit.prevent="generateProgram" class="program-form">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Fitness Seviyeniz</label>
              <select v-model="preferences.fitness_level" class="form-select" required>
                <option value="">Seçiniz</option>
                <option value="beginner">Başlangıç</option>
                <option value="intermediate">Orta</option>
                <option value="advanced">İleri</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Hedefiniz</label>
              <select v-model="preferences.goal" class="form-select" required>
                <option value="">Seçiniz</option>
                <option value="weight_loss">Kilo Verme</option>
                <option value="muscle_gain">Kas Kazanma</option>
                <option value="endurance">Dayanıklılık</option>
                <option value="flexibility">Esneklik</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Haftalık Gün Sayısı</label>
              <select v-model="preferences.available_days" class="form-select" required>
                <option value="">Seçiniz</option>
                <option value="3">3 Gün</option>
                <option value="4">4 Gün</option>
                <option value="5">5 Gün</option>
                <option value="6">6 Gün</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Seans Süresi (dakika)</label>
              <select v-model="preferences.session_duration" class="form-select" required>
                <option value="">Seçiniz</option>
                <option value="30">30 Dakika</option>
                <option value="45">45 Dakika</option>
                <option value="60">60 Dakika</option>
                <option value="90">90 Dakika</option>
              </select>
            </div>
          </div>
          
          <button type="submit" class="generate-btn" :disabled="isGenerating">
            {{ isGenerating ? 'Program Oluşturuluyor...' : 'Program Oluştur' }}
          </button>
        </form>
      </div>

      <!-- Oluşturulan Program -->
      <div v-if="generatedProgram" class="program-result">
        <div class="program-header">
          <h2 class="program-title">{{ generatedProgram.title }}</h2>
          <button @click="resetProgram" class="reset-btn">Yeni Program Oluştur</button>
        </div>
        
        <div class="program-details">
          <div class="detail-item">
            <span class="detail-label">Süre:</span>
            <span class="detail-value">{{ generatedProgram.duration_weeks }} hafta</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Haftalık:</span>
            <span class="detail-value">{{ generatedProgram.days_per_week }} gün</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Seans:</span>
            <span class="detail-value">{{ generatedProgram.session_duration }} dakika</span>
          </div>
        </div>
        
        <div class="exercises-section">
          <h3 class="section-title">Egzersizler</h3>
          <div class="exercises-grid">
            <div v-for="(exercise, index) in generatedProgram.exercises" :key="index" class="exercise-card">
              <div class="exercise-number">{{ index + 1 }}</div>
              <div class="exercise-name">{{ exercise }}</div>
            </div>
          </div>
        </div>
        
        <div class="tips-section">
          <h3 class="section-title">💡 İpuçları</h3>
          <ul class="tips-list">
            <li v-for="tip in generatedProgram.tips" :key="tip" class="tip-item">{{ tip }}</li>
          </ul>
        </div>
      </div>

      <!-- Egzersiz Videoları -->
      <div class="videos-section">
        <h2 class="section-title">🎥 Egzersiz Videoları</h2>
        <div class="videos-grid">
          <div v-for="video in exerciseVideos" :key="video.id" class="video-card">
            <div class="video-thumbnail">
              <img :src="video.thumbnail" :alt="video.title" class="thumbnail-image">
              <div class="video-duration">{{ video.duration }}</div>
              <div class="play-button">▶</div>
            </div>
            <div class="video-info">
              <h3 class="video-title">{{ video.title }}</h3>
              <p class="video-description">{{ video.description }}</p>
              <div class="video-meta">
                <span class="instructor">👩‍🏫 {{ video.instructor }}</span>
                <span class="difficulty">📊 {{ getDifficultyLabel(video.difficulty) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const preferences = ref({
  fitness_level: '',
  goal: '',
  available_days: '',
  session_duration: ''
})

const generatedProgram = ref(null)
const exerciseVideos = ref([])
const isGenerating = ref(false)

const generateProgram = async () => {
  isGenerating.value = true
  try {
    const response = await api.post('/fitness/personal-program', preferences.value)
    if (response.data.success) {
      generatedProgram.value = response.data.program
    }
  } catch (error) {
    console.error('Program generation error:', error)
    alert('Program oluşturulurken bir hata oluştu!')
  } finally {
    isGenerating.value = false
  }
}

const resetProgram = () => {
  generatedProgram.value = null
  preferences.value = {
    fitness_level: '',
    goal: '',
    available_days: '',
    session_duration: ''
  }
}

const loadExerciseVideos = async () => {
  try {
    const response = await api.get('/exercise-videos')
    if (response.data.success) {
      exerciseVideos.value = response.data.data
    }
  } catch (error) {
    console.error('Videos load error:', error)
  }
}

const getDifficultyLabel = (difficulty: string) => {
  const labels = {
    'beginner': 'Başlangıç',
    'intermediate': 'Orta',
    'advanced': 'İleri'
  }
  return labels[difficulty] || difficulty
}

onMounted(() => {
  loadExerciseVideos()
})
</script>

<style scoped>
.personal-workout-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  padding: 20px;
}

.page-header {
  text-align: center;
  margin-bottom: 40px;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1e40af;
  margin-bottom: 8px;
}

.page-subtitle {
  font-size: 1.2rem;
  color: #64748b;
}

.workout-container {
  max-width: 1200px;
  margin: 0 auto;
}

.program-form-card {
  background: white;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  margin-bottom: 40px;
}

.form-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 24px;
  text-align: center;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
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

.form-select {
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.generate-btn {
  width: 100%;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
  border: none;
  padding: 16px 32px;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.generate-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

.generate-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.program-result {
  background: white;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  margin-bottom: 40px;
}

.program-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.program-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1f2937;
}

.reset-btn {
  background: #ef4444;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.875rem;
}

.program-details {
  display: flex;
  gap: 32px;
  margin-bottom: 32px;
  flex-wrap: wrap;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.detail-label {
  font-size: 0.875rem;
  color: #6b7280;
}

.detail-value {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
}

.exercises-section {
  margin-bottom: 32px;
}

.section-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 16px;
}

.exercises-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.exercise-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: #f8fafc;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

.exercise-number {
  width: 32px;
  height: 32px;
  background: #3b82f6;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}

.exercise-name {
  font-weight: 500;
  color: #374151;
}

.tips-section {
  background: #f0f9ff;
  padding: 24px;
  border-radius: 12px;
}

.tips-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.tip-item {
  padding: 8px 0;
  color: #374151;
  position: relative;
  padding-left: 24px;
}

.tip-item::before {
  content: '💡';
  position: absolute;
  left: 0;
}

.videos-section {
  background: white;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.videos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
}

.video-card {
  background: #f8fafc;
  border-radius: 16px;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.video-card:hover {
  transform: translateY(-4px);
}

.video-thumbnail {
  position: relative;
  aspect-ratio: 16/9;
  background: #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.thumbnail-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.video-duration {
  position: absolute;
  bottom: 8px;
  right: 8px;
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.75rem;
}

.play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 60px;
  height: 60px;
  background: rgba(59, 130, 246, 0.9);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  cursor: pointer;
}

.video-info {
  padding: 20px;
}

.video-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 8px;
}

.video-description {
  color: #6b7280;
  font-size: 0.875rem;
  margin-bottom: 12px;
}

.video-meta {
  display: flex;
  justify-content: space-between;
  font-size: 0.75rem;
  color: #9ca3af;
}

@media (max-width: 768px) {
  .page-title {
    font-size: 2rem;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .program-header {
    flex-direction: column;
    gap: 16px;
    text-align: center;
  }
  
  .program-details {
    justify-content: center;
  }
  
  .videos-grid {
    grid-template-columns: 1fr;
  }
}
</style>