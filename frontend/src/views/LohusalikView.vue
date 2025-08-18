<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #be185d; margin-bottom: 16px;">🤱 Lohusalık Rehberi</h1>
        <p style="font-size: 1.25rem; color: #831843;">Doğum sonrası dönemde size rehberlik edecek kapsamlı bilgiler</p>
      </div>

      <!-- Navigation Tabs -->
      <div style="display: flex; justify-content: center; margin-bottom: 40px; border-bottom: 2px solid #f3e8ff;">
        <div style="display: flex; gap: 32px;">
          <button @click="activeTab = 'guides'" 
                  :style="{ color: activeTab === 'guides' ? '#be185d' : '#6b7280', borderBottom: activeTab === 'guides' ? '3px solid #be185d' : 'none', padding: '12px 0', background: 'none', border: 'none', cursor: 'pointer', fontWeight: '600' }">
            Lohusalık Rehberi
          </button>
          <button @click="activeTab = 'breastfeeding'" 
                  :style="{ color: activeTab === 'breastfeeding' ? '#be185d' : '#6b7280', borderBottom: activeTab === 'breastfeeding' ? '3px solid #be185d' : 'none', padding: '12px 0', background: 'none', border: 'none', cursor: 'pointer', fontWeight: '600' }">
            Emzirme İpuçları
          </button>
          <button @click="activeTab = 'nutrition'" 
                  :style="{ color: activeTab === 'nutrition' ? '#be185d' : '#6b7280', borderBottom: activeTab === 'nutrition' ? '3px solid #be185d' : 'none', padding: '12px 0', background: 'none', border: 'none', cursor: 'pointer', fontWeight: '600' }">
            Beslenme Rehberi
          </button>
        </div>
      </div>

      <!-- Lohusalık Rehberi -->
      <div v-if="activeTab === 'guides'">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
          <div v-for="guide in guides" :key="guide.id" 
               style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(190, 24, 93, 0.1); border: 2px solid #fce7f3;">
            <div style="display: flex; align-items: center; margin-bottom: 16px;">
              <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #be185d, #ec4899); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                <span style="color: white; font-size: 1.5rem;">{{ guide.id }}</span>
              </div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #be185d; margin: 0;">{{ guide.title }}</h3>
                <span style="color: #6b7280; font-size: 0.875rem;">{{ guide.period }}</span>
              </div>
            </div>
            
            <p style="color: #374151; margin-bottom: 20px; line-height: 1.6;">{{ guide.content }}</p>
            
            <div style="margin-bottom: 20px;">
              <h4 style="color: #be185d; font-weight: 600; margin-bottom: 12px;">✅ Öneriler:</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li v-for="tip in guide.tips" :key="tip" 
                    style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; color: #374151;">
                  • {{ tip }}
                </li>
              </ul>
            </div>
            
            <div v-if="guide.warnings">
              <h4 style="color: #dc2626; font-weight: 600; margin-bottom: 12px;">⚠️ Dikkat Edilmesi Gerekenler:</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li v-for="warning in guide.warnings" :key="warning" 
                    style="padding: 8px 0; color: #dc2626; background: #fef2f2; padding: 8px 12px; border-radius: 8px; margin-bottom: 4px;">
                  ⚠️ {{ warning }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Emzirme İpuçları -->
      <div v-if="activeTab === 'breastfeeding'">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
          <div v-for="tip in breastfeedingTips" :key="tip.id" 
               style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(190, 24, 93, 0.1);">
            <h3 style="color: #be185d; font-size: 1.5rem; font-weight: 700; margin-bottom: 16px;">🤱 {{ tip.title }}</h3>
            <p style="color: #374151; margin-bottom: 20px; line-height: 1.6;">{{ tip.content }}</p>
            
            <div style="background: #fdf2f8; border-radius: 12px; padding: 16px;">
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li v-for="item in tip.tips" :key="item" 
                    style="padding: 8px 0; color: #831843; font-weight: 500;">
                  💡 {{ item }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Beslenme Rehberi -->
      <div v-if="activeTab === 'nutrition'" style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(190, 24, 93, 0.1);">
        <h2 style="color: #be185d; font-size: 2rem; font-weight: 700; margin-bottom: 24px; text-align: center;">🥗 {{ nutritionGuide.title }}</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px;">
          <!-- Günlük İhtiyaçlar -->
          <div style="background: #fdf2f8; border-radius: 12px; padding: 24px;">
            <h3 style="color: #be185d; font-size: 1.25rem; font-weight: 600; margin-bottom: 16px;">📊 Günlük İhtiyaçlar</h3>
            <div v-for="(value, key) in nutritionGuide.daily_needs" :key="key" 
                 style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3e8ff;">
              <span style="color: #374151; text-transform: capitalize;">{{ key.replace('_', ' ') }}:</span>
              <span style="color: #be185d; font-weight: 600;">{{ value }}</span>
            </div>
          </div>
          
          <!-- Önerilen Gıdalar -->
          <div style="background: #f0fdf4; border-radius: 12px; padding: 24px;">
            <h3 style="color: #16a34a; font-size: 1.25rem; font-weight: 600; margin-bottom: 16px;">✅ Önerilen Gıdalar</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
              <li v-for="food in nutritionGuide.recommended_foods" :key="food" 
                  style="padding: 8px 0; color: #166534; border-bottom: 1px solid #dcfce7;">
                🥬 {{ food }}
              </li>
            </ul>
          </div>
          
          <!-- Kaçınılması Gerekenler -->
          <div style="background: #fef2f2; border-radius: 12px; padding: 24px;">
            <h3 style="color: #dc2626; font-size: 1.25rem; font-weight: 600; margin-bottom: 16px;">❌ Kaçınılması Gerekenler</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
              <li v-for="avoid in nutritionGuide.avoid_foods" :key="avoid" 
                  style="padding: 8px 0; color: #991b1b; border-bottom: 1px solid #fecaca;">
                🚫 {{ avoid }}
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const activeTab = ref('guides')
const guides = ref([])
const breastfeedingTips = ref([])
const nutritionGuide = ref({
  title: '',
  daily_needs: {},
  recommended_foods: [],
  avoid_foods: []
})

const loadGuides = async () => {
  try {
    const response = await axios.get('/api/postpartum/guides')
    if (response.data.success) {
      guides.value = response.data.data
    }
  } catch (error) {
    console.error('Rehberler yüklenirken hata:', error)
  }
}

const loadBreastfeedingTips = async () => {
  try {
    const response = await axios.get('/api/postpartum/breastfeeding-tips')
    if (response.data.success) {
      breastfeedingTips.value = response.data.data
    }
  } catch (error) {
    console.error('Emzirme ipuçları yüklenirken hata:', error)
  }
}

const loadNutritionGuide = async () => {
  try {
    const response = await axios.get('/api/postpartum/nutrition-guide')
    if (response.data.success) {
      nutritionGuide.value = response.data.data
    }
  } catch (error) {
    console.error('Beslenme rehberi yüklenirken hata:', error)
  }
}

onMounted(() => {
  loadGuides()
  loadBreastfeedingTips()
  loadNutritionGuide()
})
</script>