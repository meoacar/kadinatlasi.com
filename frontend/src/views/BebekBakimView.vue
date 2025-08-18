<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #0369a1; margin-bottom: 16px;">👶 Bebek Bakımı İpuçları</h1>
        <p style="font-size: 1.25rem; color: #0c4a6e;">Bebeğinizin sağlıklı gelişimi için pratik bilgiler ve öneriler</p>
      </div>

      <!-- Categories -->
      <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 16px; margin-bottom: 40px;">
        <button 
          v-for="category in categories" 
          :key="category.id"
          @click="selectedCategory = category.id"
          :style="{ 
            background: selectedCategory === category.id ? '#0369a1' : 'white', 
            color: selectedCategory === category.id ? 'white' : '#0369a1',
            border: '2px solid #0369a1',
            padding: '12px 24px',
            borderRadius: '25px',
            cursor: 'pointer',
            fontWeight: '600',
            transition: 'all 0.3s ease'
          }"
        >
          {{ category.icon }} {{ category.name }}
        </button>
      </div>

      <!-- Tips Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
        <div 
          v-for="tip in filteredTips" 
          :key="tip.id" 
          style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(3, 105, 161, 0.1); border: 2px solid #e0f2fe;"
        >
          <div style="display: flex; align-items: center; margin-bottom: 16px;">
            <div :style="{ 
              width: '60px', 
              height: '60px', 
              background: getCategoryColor(tip.category), 
              borderRadius: '50%', 
              display: 'flex', 
              alignItems: 'center', 
              justifyContent: 'center', 
              marginRight: '16px' 
            }">
              <span style="font-size: 1.5rem;">{{ getCategoryIcon(tip.category) }}</span>
            </div>
            <div>
              <h3 style="font-size: 1.25rem; font-weight: 700; color: #0369a1; margin: 0;">{{ tip.title }}</h3>
              <span style="background: #e0f2fe; color: #0c4a6e; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">
                {{ tip.age_range }}
              </span>
            </div>
          </div>
          
          <p style="color: #374151; margin-bottom: 20px; line-height: 1.6;">{{ tip.content }}</p>
          
          <div style="background: #f0f9ff; border-radius: 12px; padding: 16px;">
            <h4 style="color: #0369a1; font-weight: 600; margin-bottom: 12px;">💡 Pratik İpuçları:</h4>
            <ul style="list-style: none; padding: 0; margin: 0;">
              <li 
                v-for="item in tip.tips" 
                :key="item" 
                style="padding: 8px 0; color: #0c4a6e; border-bottom: 1px solid #e0f2fe; display: flex; align-items: flex-start;"
              >
                <span style="color: #0369a1; margin-right: 8px; font-weight: bold;">•</span>
                <span>{{ item }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Age-based Quick Guide -->
      <div style="margin-top: 48px; background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(3, 105, 161, 0.1);">
        <h2 style="color: #0369a1; font-size: 1.75rem; font-weight: 700; margin-bottom: 24px; text-align: center;">📅 Yaş Gruplarına Göre Hızlı Rehber</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
          <div style="background: #fef3c7; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 12px;">🍼</div>
            <h3 style="color: #92400e; font-weight: 600; margin-bottom: 8px;">0-3 Ay</h3>
            <p style="color: #78350f; font-size: 0.875rem;">Beslenme, uyku düzeni, temel bakım</p>
          </div>
          
          <div style="background: #dbeafe; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 12px;">😊</div>
            <h3 style="color: #1e40af; font-weight: 600; margin-bottom: 8px;">3-6 Ay</h3>
            <p style="color: #1e3a8a; font-size: 0.875rem;">Sosyal gelişim, ek gıdaya hazırlık</p>
          </div>
          
          <div style="background: #dcfce7; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 12px;">🥄</div>
            <h3 style="color: #166534; font-weight: 600; margin-bottom: 8px;">6-12 Ay</h3>
            <p style="color: #14532d; font-size: 0.875rem;">Ek gıda, motor gelişim, diş çıkarma</p>
          </div>
          
          <div style="background: #fce7f3; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 12px;">🚶</div>
            <h3 style="color: #be185d; font-weight: 600; margin-bottom: 8px;">12+ Ay</h3>
            <p style="color: #831843; font-size: 0.875rem;">Yürüme, konuşma, bağımsızlık</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const selectedCategory = ref('all')
const tips = ref([])
const categories = ref([])

const filteredTips = computed(() => {
  if (selectedCategory.value === 'all') {
    return tips.value
  }
  return tips.value.filter(tip => tip.category === selectedCategory.value)
})

const getCategoryIcon = (category: string) => {
  const icons = {
    feeding: '🍼',
    sleep: '😴',
    hygiene: '🛁',
    development: '👶',
    health: '🏥',
    nutrition: '🥄'
  }
  return icons[category] || '👶'
}

const getCategoryColor = (category: string) => {
  const colors = {
    feeding: 'linear-gradient(135deg, #fbbf24, #f59e0b)',
    sleep: 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
    hygiene: 'linear-gradient(135deg, #06b6d4, #0891b2)',
    development: 'linear-gradient(135deg, #10b981, #059669)',
    health: 'linear-gradient(135deg, #ef4444, #dc2626)',
    nutrition: 'linear-gradient(135deg, #f97316, #ea580c)'
  }
  return colors[category] || 'linear-gradient(135deg, #6b7280, #4b5563)'
}

const loadTips = async () => {
  try {
    const response = await axios.get('/api/baby-care/tips')
    if (response.data.success) {
      tips.value = response.data.data
    }
  } catch (error) {
    console.error('İpuçları yüklenirken hata:', error)
  }
}

const loadCategories = async () => {
  try {
    const response = await axios.get('/api/baby-care/categories')
    if (response.data.success) {
      categories.value = [
        { id: 'all', name: 'Tümü', icon: '👶' },
        ...response.data.data
      ]
    }
  } catch (error) {
    console.error('Kategoriler yüklenirken hata:', error)
  }
}

onMounted(() => {
  loadTips()
  loadCategories()
})
</script>