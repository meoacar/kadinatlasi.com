<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 800px; margin: 0 auto;">
      
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">🤱 Gebelik Hesaplayıcı</h1>
        <p style="color: #6b7280;">Gebelik haftanızı ve tahmini doğum tarihini öğrenin</p>
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
                <option value="21">21 gün</option>
                <option value="24">24 gün</option>
                <option value="28">28 gün (standart)</option>
                <option value="30">30 gün</option>
                <option value="32">32 gün</option>
                <option value="35">35 gün</option>
              </select>
            </div>
            
            <button type="submit" :disabled="loading"
                    style="background: #ec4899; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
              {{ loading ? 'Hesaplanıyor...' : 'Gebelik Hesapla' }}
            </button>
          </form>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px;">
          <div v-if="result">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Gebelik Bilgileriniz</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div style="padding: 16px; background: #fef3c7; border-radius: 8px; text-align: center;">
                  <div style="font-size: 2rem; font-weight: bold; color: #d97706;">{{ result.weeks }}</div>
                  <div style="font-size: 0.875rem; color: #92400e;">Hafta</div>
                </div>
                <div style="padding: 16px; background: #fce7f3; border-radius: 8px; text-align: center;">
                  <div style="font-size: 2rem; font-weight: bold; color: #be185d;">{{ result.days }}</div>
                  <div style="font-size: 0.875rem; color: #be185d;">Gün</div>
                </div>
              </div>
              
              <div style="padding: 12px; background: #dcfce7; border-radius: 8px;">
                <div style="font-weight: 500; color: #166534; margin-bottom: 4px;">Tahmini Doğum Tarihi</div>
                <div style="font-size: 1.25rem; font-weight: bold; color: #166534;">{{ formatDate(result.dueDate) }}</div>
              </div>
              
              <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                <div style="font-weight: 500; color: #1e40af; margin-bottom: 4px;">Trimester</div>
                <div style="font-size: 0.875rem; color: #1e40af;">{{ result.trimester }}</div>
              </div>
              
              <div style="padding: 12px; background: #f0f9ff; border-radius: 8px;">
                <div style="font-weight: 500; color: #0369a1; margin-bottom: 8px;">{{ result.weeks }}. Hafta Gelişimi</div>
                <div style="font-size: 0.75rem; color: #0369a1;">{{ result.development }}</div>
              </div>
              
              <div style="padding: 12px; background: #fef7cd; border-radius: 8px;">
                <div style="font-size: 0.75rem; color: #a16207;">
                  <strong>Not:</strong> Bu hesaplama tahmini bir değerdir. Kesin bilgi için doktorunuza danışın.
                </div>
              </div>
            </div>
          </div>
          
          <div v-else>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 20px;">Gebelik Hesaplama</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 16px;">
              Gebelik hesaplayıcısı, son adet tarihinize göre gebelik haftanızı ve tahmini doğum tarihinizi hesaplar.
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
    const today = new Date()
    
    const daysSinceLastPeriod = Math.floor((today.getTime() - lastPeriodDate.getTime()) / (1000 * 60 * 60 * 24))
    const weeks = Math.floor(daysSinceLastPeriod / 7)
    const days = daysSinceLastPeriod % 7
    
    const dueDate = new Date(lastPeriodDate)
    dueDate.setDate(lastPeriodDate.getDate() + 280)
    
    let trimester = ''
    if (weeks <= 12) trimester = '1. Trimester (1-12. hafta)'
    else if (weeks <= 26) trimester = '2. Trimester (13-26. hafta)'
    else trimester = '3. Trimester (27-40. hafta)'
    
    const developments: { [key: number]: string } = {
      4: 'Kalp atmaya başlıyor',
      6: 'Beyin ve sinir sistemi gelişiyor',
      8: 'Organlar şekillenmeye başlıyor',
      10: 'Parmaklar belirginleşiyor',
      12: 'İlk trimester tamamlanıyor',
      16: 'Cinsiyet belirlenebilir',
      20: 'Hareketler hissedilmeye başlıyor',
      24: 'Akciğerler gelişiyor',
      28: 'Üçüncü trimester başlıyor',
      32: 'Kemikler sertleşiyor',
      36: 'Doğuma hazırlanıyor',
      40: 'Doğuma hazır!'
    }
    
    const closestWeek = Object.keys(developments)
      .map(Number)
      .reduce((prev, curr) => Math.abs(curr - weeks) < Math.abs(prev - weeks) ? curr : prev)
    
    const development = developments[closestWeek] || 'Sağlıklı gelişmeye devam ediyor'
    
    result.value = {
      weeks,
      days,
      dueDate,
      trimester,
      development
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