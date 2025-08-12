<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">👶 Doğum Tarihi Hesaplayıcı</h1>
        <p style="color: #6b7280;">Tahmini doğum tarihinizi hesaplayın</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Bilgilerinizi Girin</h3>
          
          <form @submit.prevent="calculate" style="display: flex; flex-direction: column; gap: 16px;">
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Son Adet Tarihi</label>
              <input v-model="lastPeriod" type="date" required 
                     style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            
            <div>
              <label style="display: block; font-weight: 500; margin-bottom: 8px;">Döngü Süresi (gün)</label>
              <select v-model="cycleLength" 
                      style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="28">28 gün (standart)</option>
                <option value="21">21 gün</option>
                <option value="24">24 gün</option>
                <option value="30">30 gün</option>
                <option value="32">32 gün</option>
                <option value="35">35 gün</option>
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
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Doğum Tarihi Bilgileri</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="padding: 16px; background: #fef3c7; border-radius: 8px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: bold; color: #d97706;">{{ formatDate(result.dueDate) }}</div>
                <div style="font-size: 0.875rem; color: #92400e;">Tahmini Doğum Tarihi</div>
              </div>
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div style="padding: 12px; background: #dcfce7; border-radius: 8px; text-align: center;">
                  <div style="font-weight: bold; color: #166534;">{{ result.weeksPregnant }}</div>
                  <div style="font-size: 0.75rem; color: #15803d;">Gebelik Haftası</div>
                </div>
                <div style="padding: 12px; background: #fce7f3; border-radius: 8px; text-align: center;">
                  <div style="font-weight: bold; color: #be185d;">{{ result.daysLeft }}</div>
                  <div style="font-size: 0.75rem; color: #be185d;">Kalan Gün</div>
                </div>
              </div>
              
              <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                <div style="font-weight: 500; color: #1e40af; margin-bottom: 4px;">Trimester</div>
                <div style="font-size: 0.875rem; color: #1e40af;">{{ result.trimester }}</div>
              </div>
              
              <div style="padding: 12px; background: #f0f9ff; border-radius: 8px;">
                <div style="font-weight: 500; color: #0369a1; margin-bottom: 4px;">Doğum Aralığı</div>
                <div style="font-size: 0.875rem; color: #0369a1;">
                  {{ formatDate(result.earlyDate) }} - {{ formatDate(result.lateDate) }}
                </div>
              </div>
              
              <div style="padding: 12px; background: #f3f4f6; border-radius: 8px;">
                <div style="font-size: 0.75rem; color: #6b7280;">
                  <strong>Not:</strong> Bu tahmini bir tarihtir. Gerçek doğum tarihi ±2 hafta değişebilir.
                </div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Doğum Tarihi Hakkında</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 16px;">
              Doğum tarihi hesaplaması son adet tarihinden itibaren 280 gün (40 hafta) eklenerek yapılır.
            </p>
            <div style="font-size: 0.75rem; color: #6b7280;">
              <div><strong>1. Trimester:</strong> 1-12. hafta</div>
              <div><strong>2. Trimester:</strong> 13-26. hafta</div>
              <div><strong>3. Trimester:</strong> 27-40. hafta</div>
            </div>
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
    const lastPeriodDate = new Date(lastPeriod.value)
    const dueDate = new Date(lastPeriodDate)
    dueDate.setDate(lastPeriodDate.getDate() + 280)
    
    const earlyDate = new Date(dueDate)
    earlyDate.setDate(dueDate.getDate() - 14)
    
    const lateDate = new Date(dueDate)
    lateDate.setDate(dueDate.getDate() + 14)
    
    const today = new Date()
    const daysSinceLastPeriod = Math.floor((today.getTime() - lastPeriodDate.getTime()) / (1000 * 60 * 60 * 24))
    const weeksPregnant = Math.floor(daysSinceLastPeriod / 7)
    
    const daysLeft = Math.floor((dueDate.getTime() - today.getTime()) / (1000 * 60 * 60 * 24))
    
    let trimester = ''
    if (weeksPregnant <= 12) trimester = '1. Trimester'
    else if (weeksPregnant <= 26) trimester = '2. Trimester'
    else trimester = '3. Trimester'
    
    result.value = {
      dueDate,
      earlyDate,
      lateDate,
      weeksPregnant: `${weeksPregnant} hafta`,
      daysLeft: daysLeft > 0 ? `${daysLeft} gün` : 'Doğum zamanı geldi',
      trimester
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