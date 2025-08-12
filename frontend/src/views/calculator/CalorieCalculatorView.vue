<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">🍎 Kalori Hesaplayıcı</h1>
        <p style="color: #6b7280;">Günlük kalori ihtiyacınızı hesaplayın</p>
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
                       placeholder="165">
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
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Hedef</label>
              <select v-model="goal" required
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">Seçiniz</option>
                <option value="lose">Kilo Vermek</option>
                <option value="maintain">Kiloyu Korumak</option>
                <option value="gain">Kilo Almak</option>
              </select>
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'Kalori Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Kalori İhtiyacınız</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                <div style="padding: 12px; background: #dbeafe; border-radius: 8px; text-align: center;">
                  <div style="font-weight: bold; color: #1e40af;">{{ Math.round(result.bmr) }}</div>
                  <div style="font-size: 0.75rem; color: #1d4ed8;">BMR</div>
                </div>
                <div style="padding: 12px; background: #dcfce7; border-radius: 8px; text-align: center;">
                  <div style="font-weight: bold; color: #166534;">{{ Math.round(result.maintenance) }}</div>
                  <div style="font-size: 0.75rem; color: #15803d;">Günlük</div>
                </div>
                <div style="padding: 12px; background: #fef3c7; border-radius: 8px; text-align: center;">
                  <div style="font-weight: bold; color: #d97706;">{{ Math.round(result.target) }}</div>
                  <div style="font-size: 0.75rem; color: #b45309;">Hedef</div>
                </div>
              </div>
              
              <div style="padding: 16px; background: #f0f9ff; border-radius: 8px;">
                <div style="font-weight: 500; color: #0369a1; margin-bottom: 12px;">Makro Besinler (Günlük)</div>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                  <div style="text-align: center; padding: 8px; background: white; border-radius: 6px;">
                    <div style="font-weight: bold; color: #dc2626;">{{ Math.round(result.target * 0.5 / 4) }}g</div>
                    <div style="font-size: 0.75rem; color: #b91c1c;">Karbonhidrat</div>
                    <div style="font-size: 0.625rem; color: #6b7280;">%50</div>
                  </div>
                  <div style="text-align: center; padding: 8px; background: white; border-radius: 6px;">
                    <div style="font-weight: bold; color: #2563eb;">{{ Math.round(result.target * 0.2 / 4) }}g</div>
                    <div style="font-size: 0.75rem; color: #1d4ed8;">Protein</div>
                    <div style="font-size: 0.625rem; color: #6b7280;">%20</div>
                  </div>
                  <div style="text-align: center; padding: 8px; background: white; border-radius: 6px;">
                    <div style="font-weight: bold; color: #059669;">{{ Math.round(result.target * 0.3 / 9) }}g</div>
                    <div style="font-size: 0.75rem; color: #047857;">Yağ</div>
                    <div style="font-size: 0.625rem; color: #6b7280;">%30</div>
                  </div>
                </div>
              </div>
              
              <div style="padding: 12px; background: #fce7f3; border-radius: 8px;">
                <div style="font-weight: 500; color: #be185d; margin-bottom: 4px;">Öneri</div>
                <div style="font-size: 0.875rem; color: #be185d;">{{ result.advice }}</div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Kalori Hesaplama</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 16px;">
              Günlük kalori ihtiyacınızı hesaplayın ve sağlıklı beslenme planınızı oluşturun.
            </p>
            <div style="font-size: 0.75rem; color: #6b7280;">
              <div><strong>BMR:</strong> Dinlenme halinde yakılan kalori</div>
              <div><strong>TDEE:</strong> Toplam günlük enerji harcaması</div>
              <div><strong>Hedef:</strong> Amacınıza göre ayarlanmış kalori</div>
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
const activityLevel = ref('')
const goal = ref('')
const loading = ref(false)
const result = ref<any>(null)

const calculate = () => {
  if (!age.value || !gender.value || !weight.value || !height.value || !activityLevel.value || !goal.value) return
  
  loading.value = true
  
  setTimeout(() => {
    // BMR calculation (Mifflin-St Jeor Equation)
    let bmr: number
    if (gender.value === 'male') {
      bmr = 10 * weight.value + 6.25 * height.value - 5 * age.value + 5
    } else {
      bmr = 10 * weight.value + 6.25 * height.value - 5 * age.value - 161
    }
    
    // Activity multiplier
    const activityMultipliers: { [key: string]: number } = {
      sedentary: 1.2,
      light: 1.375,
      moderate: 1.55,
      active: 1.725,
      very_active: 1.9
    }
    
    const maintenance = bmr * activityMultipliers[activityLevel.value]
    
    // Goal adjustment
    let target = maintenance
    let advice = ''
    
    if (goal.value === 'lose') {
      target = maintenance - 500
      advice = 'Kilo vermek için günde 500 kalori açık verin'
    } else if (goal.value === 'gain') {
      target = maintenance + 500
      advice = 'Kilo almak için günde 500 kalori fazla alın'
    } else {
      advice = 'Kilonuzu korumak için bu kalori miktarını hedefleyin'
    }
    
    result.value = {
      bmr,
      maintenance,
      target,
      advice
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