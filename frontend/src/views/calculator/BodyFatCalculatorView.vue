<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">📊 Vücut Yağ Oranı</h1>
        <p style="color: #6b7280;">Vücut yağ oranınızı ve kas kütlenizi hesaplayın</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Bilgilerinizi Girin</h3>
          
          <form @submit.prevent="calculate" style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div>
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Yaş</label>
                <input v-model.number="age" type="number" required 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                       placeholder="25">
              </div>
              <div>
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Cinsiyet</label>
                <select v-model="gender" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                  <option value="">Seçiniz</option>
                  <option value="female">Kadın</option>
                  <option value="male">Erkek</option>
                </select>
              </div>
            </div>
            
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
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div>
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Boyun (cm)</label>
                <input v-model.number="neck" type="number" step="0.1" required 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                       placeholder="35">
              </div>
              <div>
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Bel (cm)</label>
                <input v-model.number="waist" type="number" step="0.1" required 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                       placeholder="75">
              </div>
            </div>
            
            <div v-if="gender === 'female'">
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Kalça (cm)</label>
              <input v-model.number="hip" type="number" step="0.1" 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                     placeholder="95">
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Sonuçlarınız</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 16px; background: #fef3c7; border-radius: 8px; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #d97706;">%{{ result.bodyFat }}</div>
                <div style="font-size: 0.875rem; color: #92400e;">Vücut Yağ Oranı</div>
              </div>
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div style="padding: 12px; background: #dcfce7; border-radius: 8px; text-align: center;">
                  <div style="font-weight: bold; color: #166534;">{{ result.leanMass }} kg</div>
                  <div style="font-size: 0.75rem; color: #15803d;">Yağsız Kütle</div>
                </div>
                <div style="padding: 12px; background: #fce7f3; border-radius: 8px; text-align: center;">
                  <div style="font-weight: bold; color: #be185d;">{{ result.fatMass }} kg</div>
                  <div style="font-size: 0.75rem; color: #be185d;">Yağ Kütlesi</div>
                </div>
              </div>
              
              <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                <div style="font-weight: 500; color: #1e40af; margin-bottom: 4px;">Kategori</div>
                <div style="font-size: 0.875rem; color: #1e40af;">{{ result.category }}</div>
              </div>
              
              <div style="padding: 12px; background: #f3f4f6; border-radius: 8px;">
                <div style="font-size: 0.75rem; color: #6b7280;">
                  <strong>Sağlıklı Aralık:</strong> {{ result.healthyRange }}
                </div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Vücut Yağ Oranı</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 16px;">
              Vücut yağ oranı, toplam vücut ağırlığınızın yüzde kaçının yağ olduğunu gösterir.
            </p>
            <div style="font-size: 0.75rem; color: #6b7280;">
              <div><strong>Kadınlar için sağlıklı:</strong> %21-33</div>
              <div><strong>Erkekler için sağlıklı:</strong> %8-19</div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const age = ref<number>()
const gender = ref('')
const weight = ref<number>()
const height = ref<number>()
const neck = ref<number>()
const waist = ref<number>()
const hip = ref<number>()
const loading = ref(false)
const result = ref<any>(null)

const calculate = () => {
  if (!age.value || !gender.value || !weight.value || !height.value || !neck.value || !waist.value) return
  if (gender.value === 'female' && !hip.value) return
  
  loading.value = true
  
  setTimeout(() => {
    let bodyFat: number
    
    if (gender.value === 'male') {
      bodyFat = 495 / (1.0324 - 0.19077 * Math.log10(waist.value - neck.value) + 0.15456 * Math.log10(height.value)) - 450
    } else {
      bodyFat = 495 / (1.29579 - 0.35004 * Math.log10(waist.value + hip.value! - neck.value) + 0.22100 * Math.log10(height.value)) - 450
    }
    
    const fatMass = (bodyFat / 100) * weight.value
    const leanMass = weight.value - fatMass
    
    let category = ''
    let healthyRange = ''
    
    if (gender.value === 'male') {
      healthyRange = '%8-19'
      if (bodyFat < 6) category = 'Çok düşük'
      else if (bodyFat < 14) category = 'Sporcu'
      else if (bodyFat < 18) category = 'Fitness'
      else if (bodyFat < 25) category = 'Ortalama'
      else category = 'Yüksek'
    } else {
      healthyRange = '%21-33'
      if (bodyFat < 16) category = 'Çok düşük'
      else if (bodyFat < 20) category = 'Sporcu'
      else if (bodyFat < 25) category = 'Fitness'
      else if (bodyFat < 32) category = 'Ortalama'
      else category = 'Yüksek'
    }
    
    result.value = {
      bodyFat: Math.round(bodyFat * 10) / 10,
      fatMass: Math.round(fatMass * 10) / 10,
      leanMass: Math.round(leanMass * 10) / 10,
      category,
      healthyRange
    }
    
    loading.value = false
  }, 500)
}
</script>

<style>
@media (max-width: 768px) {
  div[style*="grid-template-columns: 1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>