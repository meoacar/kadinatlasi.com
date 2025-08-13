<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">🎯 İdeal Kilo Hesaplayıcı</h1>
        <p style="color: #6b7280;">Farklı formüllerle ideal kilonuzu hesaplayın</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Bilgilerinizi Girin</h3>
          
          <form @submit.prevent="calculate" style="display: flex; flex-direction: column; gap: 16px;">
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Boy (cm)</label>
              <input v-model.number="height" type="number" required 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                     placeholder="170">
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
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Yaş</label>
              <input v-model.number="age" type="number" 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                     placeholder="25">
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">İdeal Kilo Sonuçları</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                <div style="font-weight: 500; color: #1e40af;">Robinson Formülü</div>
                <div style="font-size: 1.25rem; font-weight: bold; color: #1e40af;">{{ result.robinson }} kg</div>
              </div>
              
              <div style="padding: 12px; background: #dcfce7; border-radius: 8px;">
                <div style="font-weight: 500; color: #166534;">Miller Formülü</div>
                <div style="font-size: 1.25rem; font-weight: bold; color: #166534;">{{ result.miller }} kg</div>
              </div>
              
              <div style="padding: 12px; background: #fef3c7; border-radius: 8px;">
                <div style="font-weight: 500; color: #d97706;">Devine Formülü</div>
                <div style="font-size: 1.25rem; font-weight: bold; color: #d97706;">{{ result.devine }} kg</div>
              </div>
              
              <div style="padding: 12px; background: #fce7f3; border-radius: 8px;">
                <div style="font-weight: 500; color: #be185d;">Hamwi Formülü</div>
                <div style="font-size: 1.25rem; font-weight: bold; color: #be185d;">{{ result.hamwi }} kg</div>
              </div>
              
              <div style="padding: 16px; background: #f3f4f6; border-radius: 8px; text-align: center; margin-top: 8px;">
                <div style="font-weight: 500; color: #374151;">Ortalama İdeal Kilo</div>
                <div style="font-size: 1.5rem; font-weight: bold; color: #111827;">{{ result.average }} kg</div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">İdeal Kilo Hakkında</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5;">
              İdeal kilo hesaplaması için farklı formüller kullanılır. Her formül farklı faktörleri 
              dikkate alır ve sonuçlar arasında küçük farklar olabilir.
            </p>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const height = ref<number>()
const gender = ref('')
const age = ref<number>()
const loading = ref(false)
const result = ref<any>(null)

const calculate = () => {
  if (!height.value || !gender.value) return
  
  loading.value = true
  
  setTimeout(() => {
    const h = height.value || 0
    const isFemale = gender.value === 'female'
    
    // Robinson Formula
    const robinson = isFemale 
      ? 49 + (1.7 * (h - 152.4) / 2.54)
      : 52 + (1.9 * (h - 152.4) / 2.54)
    
    // Miller Formula  
    const miller = isFemale
      ? 53.1 + (1.36 * (h - 152.4) / 2.54)
      : 56.2 + (1.41 * (h - 152.4) / 2.54)
    
    // Devine Formula
    const devine = isFemale
      ? 45.5 + (2.3 * (h - 152.4) / 2.54)
      : 50 + (2.3 * (h - 152.4) / 2.54)
    
    // Hamwi Formula
    const hamwi = isFemale
      ? 45.5 + (2.2 * (h - 152.4) / 2.54)
      : 48 + (2.7 * (h - 152.4) / 2.54)
    
    const average = (robinson + miller + devine + hamwi) / 4
    
    result.value = {
      robinson: Math.round(robinson * 10) / 10,
      miller: Math.round(miller * 10) / 10,
      devine: Math.round(devine * 10) / 10,
      hamwi: Math.round(hamwi * 10) / 10,
      average: Math.round(average * 10) / 10
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