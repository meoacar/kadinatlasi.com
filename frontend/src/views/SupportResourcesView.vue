<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 2rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
      
      <!-- Hero Section -->
      <div style="text-align: center; margin-bottom: 3rem; background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="font-size: 3rem; font-weight: 800; color: #92400e; margin-bottom: 1rem;">🤝 Destek Kaynakları</h1>
        <p style="font-size: 1.3rem; color: #78350f; max-width: 800px; margin: 0 auto;">Zor anlarınızda yanınızda olan profesyonel destek hizmetleri ve yardım hatları</p>
      </div>

      <!-- Emergency Section -->
      <div style="background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 20px; padding: 2rem; margin-bottom: 3rem; border: 2px solid #f87171;">
        <h2 style="font-size: 2rem; font-weight: 700; color: #dc2626; margin-bottom: 1.5rem; text-align: center;">
          🚨 Acil Durum Hatları
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
          <div v-for="resource in emergencyResources" :key="resource.id" 
               style="background: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);">
            <h3 style="font-size: 1.3rem; font-weight: 700; color: #dc2626; margin-bottom: 1rem;">{{ resource.title }}</h3>
            <p style="color: #7f1d1d; margin-bottom: 1.5rem; line-height: 1.5;">{{ resource.description }}</p>
            <a :href="'tel:' + resource.phone" 
               style="display: inline-block; background: #dc2626; color: white; padding: 1rem 2rem; border-radius: 25px; text-decoration: none; font-weight: 700; font-size: 1.2rem; transition: all 0.3s ease;">
              📞 {{ resource.phone }}
            </a>
            <div style="margin-top: 1rem; font-size: 0.875rem; color: #991b1b;">
              {{ resource.working_hours?.[0] || '24/7' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Categories -->
      <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 3rem;">
        <button v-for="category in categories" :key="category.key"
                @click="selectedCategory = category.key"
                :style="{
                  background: selectedCategory === category.key ? '#f59e0b' : 'white',
                  color: selectedCategory === category.key ? 'white' : '#92400e',
                  border: '2px solid #f59e0b',
                  padding: '1rem 2rem',
                  borderRadius: '25px',
                  cursor: 'pointer',
                  fontWeight: '600',
                  transition: 'all 0.3s ease'
                }">
          {{ category.icon }} {{ category.name }}
        </button>
      </div>

      <!-- Resources Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
        <div v-for="resource in filteredResources" :key="resource.id"
             style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 6px solid #f59e0b;">
          
          <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-right: 1rem;">{{ getCategoryIcon(resource.category) }}</div>
            <div>
              <h3 style="font-size: 1.4rem; font-weight: 700; color: #92400e; margin-bottom: 0.5rem;">{{ resource.title }}</h3>
              <div style="display: flex; gap: 0.5rem;">
                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 600;">
                  {{ getCategoryName(resource.category) }}
                </span>
                <span v-if="resource.is_free" style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 600;">
                  Ücretsiz
                </span>
              </div>
            </div>
          </div>

          <p style="color: #78350f; margin-bottom: 2rem; line-height: 1.6;">{{ resource.description }}</p>

          <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div v-if="resource.phone" style="display: flex; align-items: center; gap: 1rem;">
              <a :href="'tel:' + resource.phone" 
                 style="background: #f59e0b; color: white; padding: 0.75rem 1.5rem; border-radius: 15px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                📞 {{ resource.phone }}
              </a>
              <div v-if="resource.working_hours" style="font-size: 0.875rem; color: #92400e;">
                {{ resource.working_hours[0] }}
              </div>
            </div>

            <div v-if="resource.url" style="display: flex; align-items: center;">
              <a :href="resource.url" target="_blank" 
                 style="background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 15px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                🌐 Web Sitesi
              </a>
            </div>

            <div v-if="resource.email" style="display: flex; align-items: center;">
              <a :href="'mailto:' + resource.email" 
                 style="background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 15px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                ✉️ {{ resource.email }}
              </a>
            </div>

            <div v-if="resource.address" style="padding: 1rem; background: #fef3c7; border-radius: 10px; margin-top: 1rem;">
              <div style="font-weight: 600; color: #92400e; margin-bottom: 0.5rem;">📍 Adres:</div>
              <div style="color: #78350f; font-size: 0.875rem;">{{ resource.address }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Help Text -->
      <div style="background: white; border-radius: 20px; padding: 2rem; margin-top: 3rem; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #92400e; margin-bottom: 1rem;">💝 Unutmayın</h3>
        <p style="color: #78350f; line-height: 1.6; max-width: 600px; margin: 0 auto;">
          Yalnız değilsiniz. Zor anlarınızda profesyonel yardım almaktan çekinmeyin. 
          Bu kaynaklar sizin için burada ve her zaman ulaşılabilir durumda.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

const selectedCategory = ref('all')
const resources = ref([])
const categories = ref([])
const loading = ref(false)

const emergencyResources = computed(() => {
  return resources.value.filter(r => r.is_emergency)
})

const filteredResources = computed(() => {
  if (selectedCategory.value === 'all') {
    return resources.value.filter(r => !r.is_emergency)
  }
  return resources.value.filter(r => r.category === selectedCategory.value && !r.is_emergency)
})

const getCategoryIcon = (category: string) => {
  const icons = {
    psychological: '🧠',
    medical: '🏥',
    legal: '⚖️',
    social: '🤝',
    emergency: '🚨'
  }
  return icons[category] || '📋'
}

const getCategoryName = (category: string) => {
  const names = {
    psychological: 'Psikolojik Destek',
    medical: 'Tıbbi Destek',
    legal: 'Hukuki Destek',
    social: 'Sosyal Destek',
    emergency: 'Acil Durum'
  }
  return names[category] || category
}

const loadResources = async () => {
  loading.value = true
  try {
    const response = await api.get('/support-resources')
    if (response.data.success) {
      resources.value = response.data.data
    }
  } catch (error) {
    console.error('Destek kaynakları yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const loadCategories = async () => {
  try {
    const response = await api.get('/support-resources/categories')
    if (response.data.success) {
      categories.value = [
        { key: 'all', name: 'Tümü', icon: '📋' },
        ...response.data.data
      ]
    }
  } catch (error) {
    console.error('Kategoriler yüklenirken hata:', error)
  }
}

onMounted(() => {
  loadResources()
  loadCategories()
})
</script>