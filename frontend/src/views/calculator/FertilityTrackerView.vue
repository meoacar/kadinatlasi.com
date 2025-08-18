<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">🌸 Doğurganlık Takibi</h1>
        <p style="color: #6b7280;">Doğurganlık oranınızı ve hamile kalma şansınızı hesaplayın</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Bilgilerinizi Girin</h3>
          
          <form @submit.prevent="calculate" style="display: flex; flex-direction: column; gap: 16px;">
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Yaş</label>
              <input v-model.number="age" type="number" required 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"
                     placeholder="28">
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Son Regl Tarihi</label>
              <input v-model="lastPeriod" type="date" required 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Döngü Düzeni</label>
              <select v-model="cycleRegularity" required
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">Seçiniz</option>
                <option value="regular">Düzenli (±2 gün)</option>
                <option value="irregular">Düzensiz (±5 gün)</option>
                <option value="very_irregular">Çok düzensiz (>5 gün)</option>
              </select>
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Ortalama Döngü Süresi</label>
              <select v-model="cycleLength" 
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="21">21 gün</option>
                <option value="24">24 gün</option>
                <option value="28">28 gün</option>
                <option value="30">30 gün</option>
                <option value="32">32 gün</option>
                <option value="35">35 gün</option>
              </select>
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Kaç aydır deniyorsunuz?</label>
              <select v-model="tryingMonths" 
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="1">1-3 ay</option>
                <option value="6">4-6 ay</option>
                <option value="12">7-12 ay</option>
                <option value="18">12+ ay</option>
              </select>
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Doğurganlık Analizi</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 16px; background: #fef3c7; border-radius: 8px; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #d97706;">%{{ result.fertilityScore }}</div>
                <div style="font-size: 0.875rem; color: #92400e;">Doğurganlık Skoru</div>
              </div>
              
              <div style="padding: 12px; background: #dcfce7; border-radius: 8px;">
                <div style="font-weight: 500; color: #166534; margin-bottom: 4px;">Aylık Hamile Kalma Şansı</div>
                <div style="font-size: 1.25rem; font-weight: bold; color: #166534;">%{{ result.monthlyChance }}</div>
              </div>
              
              <div style="padding: 12px; background: #fce7f3; border-radius: 8px;">
                <div style="font-weight: 500; color: #be185d; margin-bottom: 4px;">Sonraki Verimli Dönem</div>
                <div style="font-size: 0.875rem; color: #be185d;">
                  {{ formatDate(result.nextFertileStart) }} - {{ formatDate(result.nextFertileEnd) }}
                </div>
              </div>
              
              <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                <div style="font-weight: 500; color: #1e40af; margin-bottom: 4px;">Öneriler</div>
                <div style="font-size: 0.875rem; color: #1e40af;">{{ result.recommendation }}</div>
              </div>
              
              <div style="padding: 12px; background: #f0fdf4; border-radius: 8px;">
                <div style="font-weight: 500; color: #15803d; margin-bottom: 8px;">Yaşa Göre Doğurganlık</div>
                <div style="font-size: 0.75rem; color: #15803d;">
                  <div>20-24 yaş: %86 (12 ay içinde)</div>
                  <div>25-29 yaş: %78 (12 ay içinde)</div>
                  <div>30-34 yaş: %63 (12 ay içinde)</div>
                  <div>35-39 yaş: %52 (12 ay içinde)</div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Doğurganlık Hakkında</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 16px;">
              Doğurganlık yaş, döngü düzeni, genel sağlık durumu gibi birçok faktörden etkilenir.
            </p>
            <div style="font-size: 0.75rem; color: #6b7280;">
              <div><strong>En verimli yaş:</strong> 20-24 yaş arası</div>
              <div><strong>Verimli dönem:</strong> Ovulasyondan 5 gün önce - 1 gün sonra</div>
              <div><strong>Normal süre:</strong> 12 ay içinde hamile kalma normal</div>
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
const lastPeriod = ref('')
const cycleRegularity = ref('')
const cycleLength = ref(28)
const tryingMonths = ref(1)
const loading = ref(false)
const result = ref<any>(null)

const calculate = () => {
  if (!age.value || !lastPeriod.value || !cycleRegularity.value) return
  
  loading.value = true
  
  setTimeout(() => {
    // Age factor
    let ageFactor = 1
    const ageValue = age.value || 0
    if (ageValue <= 24) ageFactor = 0.86
    else if (ageValue <= 29) ageFactor = 0.78
    else if (ageValue <= 34) ageFactor = 0.63
    else if (ageValue <= 39) ageFactor = 0.52
    else ageFactor = 0.36
    
    // Cycle regularity factor
    let regularityFactor = 1
    if (cycleRegularity.value === 'regular') regularityFactor = 1
    else if (cycleRegularity.value === 'irregular') regularityFactor = 0.8
    else regularityFactor = 0.6
    
    // Trying duration factor
    let durationFactor = 1
    if (tryingMonths.value <= 3) durationFactor = 1
    else if (tryingMonths.value <= 6) durationFactor = 0.9
    else if (tryingMonths.value <= 12) durationFactor = 0.8
    else durationFactor = 0.7
    
    const fertilityScore = Math.round(ageFactor * regularityFactor * durationFactor * 100)
    const monthlyChance = Math.round(ageFactor * regularityFactor * 25)
    
    // Calculate next fertile period
    const lastPeriodDate = new Date(lastPeriod.value)
    const nextOvulation = new Date(lastPeriodDate)
    nextOvulation.setDate(lastPeriodDate.getDate() + cycleLength.value - 14)
    
    const nextFertileStart = new Date(nextOvulation)
    nextFertileStart.setDate(nextOvulation.getDate() - 5)
    
    const nextFertileEnd = new Date(nextOvulation)
    nextFertileEnd.setDate(nextOvulation.getDate() + 1)
    
    let recommendation = ''
    if (fertilityScore >= 70) {
      recommendation = 'Doğurganlık oranınız yüksek. Düzenli ilişki ile başarı şansınız iyi.'
    } else if (fertilityScore >= 50) {
      recommendation = 'Orta düzeyde doğurganlık. Sağlıklı yaşam ve düzenli takip önemli.'
    } else {
      recommendation = 'Doktor kontrolü önerilir. Uzman desteği alabilirsiniz.'
    }
    
    result.value = {
      fertilityScore,
      monthlyChance,
      nextFertileStart,
      nextFertileEnd,
      recommendation
    }
    
    loading.value = false
  }, 500)
}

const formatDate = (date: Date) => {
  return date.toLocaleDateString('tr-TR')
}
</script>

<style>
@media (max-width: 768px) {
  div[style*="grid-template-columns: 1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>