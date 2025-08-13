<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">📅 Regl Döngüsü Takvimi</h1>
        <p style="color: #6b7280;">Regl döngünüzü takip edin ve ovulasyon tarihini hesaplayın</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Bilgilerinizi Girin</h3>
          
          <form @submit.prevent="calculate" style="display: flex; flex-direction: column; gap: 16px;">
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Son Regl Tarihi</label>
              <input v-model="lastPeriod" type="date" required 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Döngü Uzunluğu (gün)</label>
              <select v-model="cycleLength" 
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="21">21 gün</option>
                <option value="24">24 gün</option>
                <option value="28">28 gün (ortalama)</option>
                <option value="30">30 gün</option>
                <option value="32">32 gün</option>
                <option value="35">35 gün</option>
              </select>
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'Takvimi Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Takvim Sonuçları</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 16px; background: #fce7f3; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold; color: #be185d;">{{ formatDate(result.nextPeriod) }}</div>
                <div style="font-size: 0.875rem; color: #be185d;">Sonraki Regl Tarihi</div>
                <div style="font-size: 0.75rem; color: #be185d; margin-top: 4px;">{{ result.daysUntilNext }} gün kaldı</div>
              </div>
              
              <div style="padding: 12px; background: #fef3c7; border-radius: 8px;">
                <div style="font-weight: 500; color: #d97706; margin-bottom: 4px;">Ovulasyon Tarihi</div>
                <div style="font-size: 1.25rem; font-weight: bold; color: #d97706;">{{ formatDate(result.ovulation) }}</div>
              </div>
              
              <div style="padding: 12px; background: #dcfce7; border-radius: 8px;">
                <div style="font-weight: 500; color: #166534; margin-bottom: 4px;">Verimli Dönem</div>
                <div style="font-size: 0.875rem; color: #166534;">
                  {{ formatDate(result.fertileStart) }} - {{ formatDate(result.fertileEnd) }}
                </div>
              </div>
              
              <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                <div style="font-weight: 500; color: #1e40af; margin-bottom: 4px;">Döngü Fazı</div>
                <div style="font-size: 0.875rem; color: #1e40af;">{{ result.currentPhase }}</div>
              </div>
              
              <div style="padding: 12px; background: #f0fdf4; border-radius: 8px;">
                <div style="font-size: 0.75rem; color: #15803d;">
                  <div><strong>Menstrual:</strong> 1-5. gün</div>
                  <div><strong>Foliküler:</strong> 1-13. gün</div>
                  <div><strong>Ovulasyon:</strong> 14. gün</div>
                  <div><strong>Luteal:</strong> 15-28. gün</div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Regl Döngüsü Hakkında</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5;">
              Regl döngüsü takibi, kadın sağlığı için önemli bir araçtır. Ovulasyon genellikle 
              bir sonraki regl döneminden 14 gün önce gerçekleşir.
            </p>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const lastPeriod = ref('')
const cycleLength = ref(28)
const loading = ref(false)
const result = ref<any>(null)

const calculate = () => {
  if (!lastPeriod.value) return
  
  loading.value = true
  
  setTimeout(() => {
    const lastPeriodDate = new Date(lastPeriod.value || '')
    const today = new Date()
    
    const nextPeriod = new Date(lastPeriodDate)
    nextPeriod.setDate(lastPeriodDate.getDate() + (cycleLength.value || 28))
    
    const ovulation = new Date(lastPeriodDate)
    ovulation.setDate(lastPeriodDate.getDate() + (cycleLength.value || 28) - 14)
    
    const fertileStart = new Date(ovulation)
    fertileStart.setDate(ovulation.getDate() - 5)
    
    const fertileEnd = new Date(ovulation)
    fertileEnd.setDate(ovulation.getDate() + 1)
    
    const daysSinceLastPeriod = Math.floor((today.getTime() - lastPeriodDate.getTime()) / (1000 * 60 * 60 * 24))
    const daysUntilNext = Math.floor((nextPeriod.getTime() - today.getTime()) / (1000 * 60 * 60 * 24))
    
    let currentPhase = ''
    if (daysSinceLastPeriod <= 5) currentPhase = 'Menstrual Faz'
    else if (daysSinceLastPeriod <= 13) currentPhase = 'Foliküler Faz'
    else if (daysSinceLastPeriod <= 15) currentPhase = 'Ovulasyon'
    else currentPhase = 'Luteal Faz'
    
    result.value = {
      nextPeriod,
      ovulation,
      fertileStart,
      fertileEnd,
      daysUntilNext: daysUntilNext > 0 ? daysUntilNext : 0,
      currentPhase
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