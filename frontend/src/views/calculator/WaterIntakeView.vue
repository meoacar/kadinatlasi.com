<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">💧 Su İhtiyacı Hesaplayıcı</h1>
        <p style="color: #6b7280;">Günlük su ihtiyacınızı hesaplayın</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Bilgilerinizi Girin</h3>
          
          <form @submit.prevent="calculate" style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div>
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Kilo (kg)</label>
                <input v-model.number="weight" type="number" step="0.1" required 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                       placeholder="65">
              </div>
              <div>
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Yaş</label>
                <input v-model.number="age" type="number" required 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                       placeholder="25">
              </div>
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Aktivite Seviyesi</label>
              <select v-model="activityLevel" required
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">Seçiniz</option>
                <option value="sedentary">Hareketsiz (masa başı iş)</option>
                <option value="light">Az Aktif (hafif egzersiz)</option>
                <option value="moderate">Orta Aktif (orta egzersiz)</option>
                <option value="active">Çok Aktif (yoğun egzersiz)</option>
                <option value="very_active">Aşırı Aktif (profesyonel sporcu)</option>
              </select>
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">İklim Koşulları</label>
              <select v-model="climate" required
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">Seçiniz</option>
                <option value="temperate">Ilıman (15-25°C)</option>
                <option value="hot">Sıcak (25-35°C)</option>
                <option value="very_hot">Çok Sıcak (35°C+)</option>
                <option value="cold">Soğuk (0-15°C)</option>
              </select>
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 12px;">Özel Durumlar</label>
              <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                  <input v-model="isPregnant" type="checkbox" style="margin-right: 8px;">
                  <span style="font-size: 0.875rem;">Hamile</span>
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                  <input v-model="isBreastfeeding" type="checkbox" style="margin-right: 8px;">
                  <span style="font-size: 0.875rem;">Emziren</span>
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                  <input v-model="isSick" type="checkbox" style="margin-right: 8px;">
                  <span style="font-size: 0.875rem;">Hasta (ateş, kusma vb.)</span>
                </label>
              </div>
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'Su İhtiyacı Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Su İhtiyacınız</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 16px; background: #dbeafe; border-radius: 8px; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #1e40af;">{{ result.dailyWater }}</div>
                <div style="font-size: 0.875rem; color: #1d4ed8;">ml / gün</div>
                <div style="font-size: 0.75rem; color: #1d4ed8; margin-top: 4px;">≈ {{ Math.round(result.dailyWater / 250) }} bardak</div>
              </div>
              
              <div style="padding: 16px; background: #f0f9ff; border-radius: 8px;">
                <div style="font-weight: 500; color: #0369a1; margin-bottom: 12px;">Günlük Su İçme Programı</div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                  <div style="display: flex; justify-content: space-between; padding: 8px; background: white; border-radius: 6px;">
                    <span style="font-size: 0.875rem; color: #374151;">Sabah (07:00-09:00)</span>
                    <span style="font-weight: 500; color: #0369a1;">2 bardak</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 8px; background: white; border-radius: 6px;">
                    <span style="font-size: 0.875rem; color: #374151;">Öğle (12:00-14:00)</span>
                    <span style="font-weight: 500; color: #0369a1;">2 bardak</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 8px; background: white; border-radius: 6px;">
                    <span style="font-size: 0.875rem; color: #374151;">Akşam (18:00-20:00)</span>
                    <span style="font-weight: 500; color: #0369a1;">2 bardak</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 8px; background: white; border-radius: 6px;">
                    <span style="font-size: 0.875rem; color: #374151;">Gün içi araşında</span>
                    <span style="font-weight: 500; color: #0369a1;">{{ Math.max(0, Math.round(result.dailyWater / 250) - 6) }} bardak</span>
                  </div>
                </div>
              </div>
              
              <div style="padding: 12px; background: #dcfce7; border-radius: 8px;">
                <div style="font-weight: 500; color: #166534; margin-bottom: 4px;">Öneriler</div>
                <div style="font-size: 0.875rem; color: #166534;">{{ result.advice }}</div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Su İhtiyacı Hakkında</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 16px;">
              Günlük su ihtiyacınızı hesaplayın ve sağlıklı hidrasyon planınızı oluşturun.
            </p>
            <div style="font-size: 0.75rem; color: #6b7280;">
              <div><strong>Temel İhtiyaç:</strong> Kilo başına 30-35ml</div>
              <div><strong>Aktivite:</strong> Egzersizle %20-30 artış</div>
              <div><strong>İklim:</strong> Sıcak havada %10-15 artış</div>
              <div><strong>Özel Durumlar:</strong> Hamilelik/emzirme +300-500ml</div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const authStore = useAuthStore()
const weight = ref<number>()
const age = ref<number>()
const activityLevel = ref('')
const climate = ref('')
const isPregnant = ref(false)
const isBreastfeeding = ref(false)
const isSick = ref(false)
const loading = ref(false)
const result = ref<any>(null)

const calculate = () => {
  if (!weight.value || !age.value || !activityLevel.value || !climate.value) return
  
  loading.value = true
  
  setTimeout(() => {
    // Base water need: 35ml per kg
    let dailyWater = weight.value * 35
    
    // Activity adjustment
    const activityMultipliers: { [key: string]: number } = {
      sedentary: 1,
      light: 1.1,
      moderate: 1.2,
      active: 1.3,
      very_active: 1.4
    }
    dailyWater *= activityMultipliers[activityLevel.value]
    
    // Climate adjustment
    const climateMultipliers: { [key: string]: number } = {
      cold: 0.95,
      temperate: 1,
      hot: 1.15,
      very_hot: 1.25
    }
    dailyWater *= climateMultipliers[climate.value]
    
    // Special conditions
    if (isPregnant.value) dailyWater += 300
    if (isBreastfeeding.value) dailyWater += 500
    if (isSick.value) dailyWater += 400
    
    let advice = 'Su içmeyi günün her saatine yayın'
    if (dailyWater > 3000) {
      advice = 'Yüksek su ihtiyacınız var. Sık sık su için'
    } else if (dailyWater < 2000) {
      advice = 'Temel su ihtiyacınızı karşılamaya odaklanın'
    }
    
    result.value = {
      dailyWater: Math.round(dailyWater),
      advice
    }
    
    // Track gamification action
    if (authStore.isAuthenticated) {
      trackWaterCalculation()
    }
    
    loading.value = false
  }, 500)
}

const trackWaterCalculation = async () => {
  try {
    await api.post('/gamification/track', {
      action_type: 'calculator_water_use',
      action_target: 'water_calculator',
      metadata: {
        dailyWater: result.value?.dailyWater,
        weight: weight.value,
        timestamp: new Date().toISOString()
      }
    })
  } catch (error) {
    console.error('Water tracking error:', error)
  }
}
</script>

<style>
@media (max-width: 768px) {
  div[style*="grid-template-columns: 1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>