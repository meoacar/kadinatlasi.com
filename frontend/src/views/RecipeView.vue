<template>
  <div style="min-height: 100vh; background-color: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 16px;">🍽️ Yemek Tarifleri</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Sağlıklı ve lezzetli tarifler</p>
      </div>

      <!-- Filters -->
      <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 32px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
          
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Öğün</label>
            <select 
              v-model="filters.category" 
              @change="fetchRecipes"
              style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
            >
              <option value="">Tümü</option>
              <option value="breakfast">Kahvaltı</option>
              <option value="lunch">Öğle Yemeği</option>
              <option value="dinner">Akşam Yemeği</option>
              <option value="snack">Atıştırmalık</option>
              <option value="dessert">Tatlı</option>
            </select>
          </div>

          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Diyet Tipi</label>
            <select 
              v-model="filters.diet_type" 
              @change="fetchRecipes"
              style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
            >
              <option value="">Tümü</option>
              <option value="normal">Normal</option>
              <option value="vegetarian">Vejetaryen</option>
              <option value="vegan">Vegan</option>
              <option value="keto">Keto</option>
              <option value="low_carb">Düşük Karbonhidrat</option>
            </select>
          </div>

          <div style="grid-column: span 2;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Tarif Ara</label>
            <input
              v-model="filters.search"
              @input="debounceSearch"
              type="text"
              placeholder="Tarif adı arayın..."
              style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
            >
          </div>
        </div>
      </div>

      <!-- Recipes List -->
      <div v-if="recipes.length" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
        <div 
          v-for="recipe in recipes" 
          :key="recipe.id"
          style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; transition: box-shadow 0.2s;"
          @mouseover="$event.target.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'"
          @mouseleave="$event.target.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)'"
        >
          <div style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
              <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827;">{{ recipe.name }}</h3>
              <span v-if="recipe.is_popular" style="padding: 4px 8px; background: #fef3c7; color: #92400e; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                ⭐ Popüler
              </span>
            </div>

            <p style="color: #6b7280; margin-bottom: 16px;">{{ recipe.description }}</p>

            <div style="display: flex; gap: 8px; margin-bottom: 16px;">
              <span :style="{
                padding: '4px 8px',
                borderRadius: '9999px',
                fontSize: '0.75rem',
                fontWeight: '500',
                backgroundColor: getCategoryColor(recipe.category).bg,
                color: getCategoryColor(recipe.category).text
              }">
                {{ getCategoryLabel(recipe.category) }}
              </span>
              <span :style="{
                padding: '4px 8px',
                borderRadius: '9999px',
                fontSize: '0.75rem',
                fontWeight: '500',
                backgroundColor: getDietTypeColor(recipe.diet_type).bg,
                color: getDietTypeColor(recipe.diet_type).text
              }">
                {{ getDietTypeLabel(recipe.diet_type) }}
              </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 16px;">
              <div style="text-align: center; padding: 8px; background: #f3f4f6; border-radius: 6px;">
                <div style="font-size: 1rem; font-weight: bold; color: #111827;">{{ recipe.total_time }}</div>
                <div style="font-size: 0.75rem; color: #6b7280;">Dakika</div>
              </div>
              <div style="text-align: center; padding: 8px; background: #f3f4f6; border-radius: 6px;">
                <div style="font-size: 1rem; font-weight: bold; color: #111827;">{{ recipe.calories_per_serving }}</div>
                <div style="font-size: 0.75rem; color: #6b7280;">Kalori</div>
              </div>
              <div style="text-align: center; padding: 8px; background: #f3f4f6; border-radius: 6px;">
                <div style="font-size: 1rem; font-weight: bold; color: #111827;">{{ recipe.servings }}</div>
                <div style="font-size: 0.75rem; color: #6b7280;">Porsiyon</div>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 16px;">
              <div style="text-align: center; padding: 8px; background: #fef3c7; border-radius: 6px;">
                <div style="font-size: 0.875rem; font-weight: bold; color: #92400e;">{{ recipe.protein }}g</div>
                <div style="font-size: 0.75rem; color: #92400e;">Protein</div>
              </div>
              <div style="text-align: center; padding: 8px; background: #e0f2fe; border-radius: 6px;">
                <div style="font-size: 0.875rem; font-weight: bold; color: #0369a1;">{{ recipe.carbs }}g</div>
                <div style="font-size: 0.75rem; color: #0369a1;">Karbonhidrat</div>
              </div>
              <div style="text-align: center; padding: 8px; background: #fce7f3; border-radius: 6px;">
                <div style="font-size: 0.875rem; font-weight: bold; color: #be185d;">{{ recipe.fat }}g</div>
                <div style="font-size: 0.75rem; color: #be185d;">Yağ</div>
              </div>
            </div>

            <div style="margin-bottom: 16px;">
              <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 8px;">Malzemeler:</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li 
                  v-for="ingredient in recipe.ingredients.slice(0, 3)" 
                  :key="ingredient"
                  style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;"
                >
                  <span style="color: #ec4899;">•</span>
                  {{ ingredient }}
                </li>
                <li v-if="recipe.ingredients.length > 3" style="color: #9ca3af; font-size: 0.875rem; font-style: italic;">
                  +{{ recipe.ingredients.length - 3 }} malzeme daha...
                </li>
              </ul>
            </div>

            <div style="display: flex; justify-content: between; align-items: center;">
              <span :style="{
                padding: '4px 8px',
                borderRadius: '9999px',
                fontSize: '0.75rem',
                fontWeight: '500',
                backgroundColor: getDifficultyColor(recipe.difficulty).bg,
                color: getDifficultyColor(recipe.difficulty).text
              }">
                {{ getDifficultyLabel(recipe.difficulty) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" style="text-align: center; padding: 32px;">
        <div style="width: 48px; height: 48px; border: 2px solid #f3f4f6; border-top: 2px solid #ec4899; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        <p style="margin-top: 16px; color: #6b7280;">Tarifler yükleniyor...</p>
      </div>

      <!-- No results -->
      <div v-if="!loading && recipes.length === 0" style="text-align: center; padding: 32px;">
        <div style="font-size: 4rem; margin-bottom: 16px;">🍽️</div>
        <p style="color: #6b7280;">Aradığınız kriterlere uygun tarif bulunamadı.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

interface Recipe {
  id: number
  name: string
  description: string
  category: string
  diet_type: string
  prep_time_minutes: number
  cook_time_minutes: number
  servings: number
  calories_per_serving: number
  protein: number
  carbs: number
  fat: number
  ingredients: string[]
  instructions: string
  difficulty: string
  is_popular: boolean
}

const recipes = ref<Recipe[]>([])
const loading = ref(false)
const filters = ref({
  category: '',
  diet_type: '',
  search: ''
})

let searchTimeout: NodeJS.Timeout | null = null

const fetchRecipes = async () => {
  loading.value = true
  try {
    const params = {
      category: filters.value.category || undefined,
      diet_type: filters.value.diet_type || undefined,
      search: filters.value.search || undefined
    }

    const response = await axios.get('/api/recipes', { params })
    recipes.value = response.data.data.map((recipe: any) => ({
      ...recipe,
      total_time: recipe.prep_time_minutes + recipe.cook_time_minutes
    }))
  } catch (error) {
    console.error('Tarifler yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const debounceSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchRecipes()
  }, 500)
}

const getCategoryColor = (category: string) => {
  const colors = {
    breakfast: { bg: '#fef3c7', text: '#92400e' },
    lunch: { bg: '#e0f2fe', text: '#0369a1' },
    dinner: { bg: '#fce7f3', text: '#be185d' },
    snack: { bg: '#dcfce7', text: '#166534' },
    dessert: { bg: '#f3e8ff', text: '#7c3aed' }
  }
  return colors[category as keyof typeof colors] || { bg: '#f3f4f6', text: '#374151' }
}

const getDietTypeColor = (dietType: string) => {
  const colors = {
    normal: { bg: '#f3f4f6', text: '#374151' },
    vegetarian: { bg: '#dcfce7', text: '#166534' },
    vegan: { bg: '#dcfce7', text: '#15803d' },
    keto: { bg: '#fee2e2', text: '#dc2626' },
    low_carb: { bg: '#fef3c7', text: '#92400e' }
  }
  return colors[dietType as keyof typeof colors] || { bg: '#f3f4f6', text: '#374151' }
}

const getDifficultyColor = (difficulty: string) => {
  const colors = {
    easy: { bg: '#dcfce7', text: '#166534' },
    medium: { bg: '#fef3c7', text: '#92400e' },
    hard: { bg: '#fee2e2', text: '#dc2626' }
  }
  return colors[difficulty as keyof typeof colors] || { bg: '#f3f4f6', text: '#374151' }
}

const getCategoryLabel = (category: string) => {
  const labels = {
    breakfast: 'Kahvaltı',
    lunch: 'Öğle',
    dinner: 'Akşam',
    snack: 'Atıştırmalık',
    dessert: 'Tatlı'
  }
  return labels[category as keyof typeof labels] || category
}

const getDietTypeLabel = (dietType: string) => {
  const labels = {
    normal: 'Normal',
    vegetarian: 'Vejetaryen',
    vegan: 'Vegan',
    keto: 'Keto',
    low_carb: 'Düşük Karb'
  }
  return labels[dietType as keyof typeof labels] || dietType
}

const getDifficultyLabel = (difficulty: string) => {
  const labels = {
    easy: 'Kolay',
    medium: 'Orta',
    hard: 'Zor'
  }
  return labels[difficulty as keyof typeof labels] || difficulty
}

onMounted(() => {
  fetchRecipes()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>