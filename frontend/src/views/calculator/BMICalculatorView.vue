<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">⚖️ VKİ Hesaplayıcı</h1>
        <p style="color: #6b7280;">Vücut kitle indeksinizi hesaplayın</p>
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
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Boy (cm)</label>
                <input v-model.number="height" type="number" required 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                       placeholder="170">
              </div>
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Yaş (opsiyonel)</label>
              <input v-model.number="age" type="number" 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                     placeholder="28">
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'VKİ Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Sonuçlarınız</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 16px; background: #fef3c7; border-radius: 8px; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #d97706;">{{ result.bmi }}</div>
                <div style="font-size: 0.875rem; color: #92400e;">{{ result.category }}</div>
              </div>
              
              <div style="padding: 12px; background: #dcfce7; border-radius: 8px;">
                <div style="font-weight: 500; color: #166534; margin-bottom: 4px;">İdeal Kilo Aralığı</div>
                <div style="font-size: 0.875rem; color: #166534;">{{ result.idealMin }} - {{ result.idealMax }} kg</div>
              </div>
              
              <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                <div style="font-weight: 500; color: #1e40af; margin-bottom: 4px;">Kilo Durumu</div>
                <div style="font-size: 0.875rem; color: #1e40af;">{{ result.advice }}</div>
              </div>
              
              <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 12px;">
                <div style="padding: 8px; background: #dcfce7; border-radius: 6px; text-align: center;">
                  <div style="font-size: 0.75rem; font-weight: 500; color: #166534;">Zayıf</div>
                  <div style="font-size: 0.625rem; color: #15803d;">&lt;18.5</div>
                </div>
                <div style="padding: 8px; background: #dbeafe; border-radius: 6px; text-align: center;">
                  <div style="font-size: 0.75rem; font-weight: 500; color: #1e40af;">Normal</div>
                  <div style="font-size: 0.625rem; color: #1d4ed8;">18.5-24.9</div>
                </div>
                <div style="padding: 8px; background: #fef3c7; border-radius: 6px; text-align: center;">
                  <div style="font-size: 0.75rem; font-weight: 500; color: #d97706;">Fazla</div>
                  <div style="font-size: 0.625rem; color: #b45309;">25.0-29.9</div>
                </div>
                <div style="padding: 8px; background: #fecaca; border-radius: 6px; text-align: center;">
                  <div style="font-size: 0.75rem; font-weight: 500; color: #dc2626;">Obez</div>
                  <div style="font-size: 0.625rem; color: #b91c1c;">≥30.0</div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">VKİ Hakkında</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5;">
              Vücut Kitle İndeksi (VKİ), boy ve kilo oranına göre hesaplanan sağlık göstergesidir.
            </p>
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
const height = ref<number>()
const age = ref<number>()
const loading = ref(false)
const result = ref<any>(null)

const calculate = () => {
  if (!weight.value || !height.value) return
  
  loading.value = true
  
  setTimeout(() => {
    const heightM = height.value / 100
    const bmi = weight.value / (heightM * heightM)
    
    let category = ''
    let advice = ''
    
    if (bmi < 18.5) {
      category = 'Zayıf'
      advice = 'Kilo almanız önerilir'
    } else if (bmi < 25) {
      category = 'Normal'
      advice = 'İdeal kilonuzdasınız'
    } else if (bmi < 30) {
      category = 'Fazla Kilolu'
      advice = 'Kilo vermeniz önerilir'
    } else {
      category = 'Obez'
      advice = 'Doktor kontrolü önerilir'
    }
    
    const idealMin = Math.round(18.5 * heightM * heightM * 10) / 10
    const idealMax = Math.round(24.9 * heightM * heightM * 10) / 10
    
    result.value = {
      bmi: Math.round(bmi * 10) / 10,
      category,
      advice,
      idealMin,
      idealMax
    }
    
    // Track gamification action
    if (authStore.isAuthenticated) {
      trackBMICalculation()
    }
    
    loading.value = false
  }, 500)
}

const trackBMICalculation = async () => {
  try {
    await api.post('/gamification/track', {
      action_type: 'calculator_bmi_use',
      action_target: 'bmi_calculator',
      metadata: {
        bmi: result.value?.bmi,
        category: result.value?.category,
        timestamp: new Date().toISOString()
      }
    })
  } catch (error) {
    console.error('BMI tracking error:', error)
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