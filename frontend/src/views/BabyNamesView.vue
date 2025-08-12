<template>
  <div style="min-height: 100vh; background-color: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 16px;">👶 Bebek İsimleri</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Bebeğiniz için en güzel ismi bulun</p>
      </div>

      <!-- Filters -->
      <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 32px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
          
          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Cinsiyet</label>
            <select 
              v-model="filters.gender" 
              @change="fetchBabyNames"
              style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
            >
              <option value="all">Tümü</option>
              <option value="kiz">Kız</option>
              <option value="erkek">Erkek</option>
              <option value="unisex">Unisex</option>
            </select>
          </div>

          <div>
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Popülerlik</label>
            <select 
              v-model="filters.popular" 
              @change="fetchBabyNames"
              style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
            >
              <option value="">Tümü</option>
              <option value="1">Popüler İsimler</option>
            </select>
          </div>

          <div style="grid-column: span 2;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">İsim Ara</label>
            <div style="position: relative;">
              <input
                v-model="filters.search"
                @input="debounceSearch"
                type="text"
                placeholder="İsim arayın..."
                style="width: 100%; padding: 8px 12px 8px 40px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
              >
              <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                <svg style="width: 20px; height: 20px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Names List -->
      <div v-if="babyNames.length" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div 
          v-for="name in babyNames" 
          :key="name.id"
          style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; transition: box-shadow 0.2s;"
          @mouseover="$event.target.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'"
          @mouseleave="$event.target.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)'"
        >
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827;">{{ name.name }}</h3>
            <div style="display: flex; gap: 8px;">
              <span :style="{
                padding: '4px 8px',
                borderRadius: '9999px',
                fontSize: '0.75rem',
                fontWeight: '500',
                backgroundColor: name.gender === 'kiz' ? '#fce7f3' : name.gender === 'erkek' ? '#dbeafe' : '#e9d5ff',
                color: name.gender === 'kiz' ? '#be185d' : name.gender === 'erkek' ? '#1e40af' : '#7c3aed'
              }">
                {{ name.gender === 'kiz' ? 'Kız' : name.gender === 'erkek' ? 'Erkek' : 'Unisex' }}
              </span>
              <span v-if="name.is_popular" style="padding: 4px 8px; background: #fef3c7; color: #92400e; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                ⭐ Popüler
              </span>
            </div>
          </div>

          <div style="margin-bottom: 12px;">
            <h4 style="font-weight: 600; color: #374151; margin-bottom: 4px;">Anlamı:</h4>
            <p style="color: #6b7280;">{{ name.meaning }}</p>
          </div>

          <div style="margin-bottom: 12px;">
            <h4 style="font-weight: 600; color: #374151; margin-bottom: 4px;">Kökeni:</h4>
            <p style="color: #6b7280;">{{ name.origin }}</p>
          </div>

          <div v-if="name.description" style="margin-bottom: 12px;">
            <h4 style="font-weight: 600; color: #374151; margin-bottom: 4px;">Açıklama:</h4>
            <p style="color: #6b7280; font-size: 0.875rem;">{{ name.description }}</p>
          </div>

          <div v-if="name.popularity_rank" style="font-size: 0.875rem; color: #9ca3af;">
            Popülerlik sırası: #{{ name.popularity_rank }}
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" style="text-align: center; padding: 32px;">
        <div style="width: 48px; height: 48px; border: 2px solid #f3f4f6; border-top: 2px solid #ec4899; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        <p style="margin-top: 16px; color: #6b7280;">İsimler yükleniyor...</p>
      </div>

      <!-- No results -->
      <div v-if="!loading && babyNames.length === 0" style="text-align: center; padding: 32px;">
        <div style="font-size: 4rem; margin-bottom: 16px;">👶</div>
        <p style="color: #6b7280;">Aradığınız kriterlere uygun isim bulunamadı.</p>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" style="display: flex; justify-content: center; gap: 8px;">
        <button
          v-for="page in visiblePages"
          :key="page"
          @click="changePage(page)"
          :style="{
            padding: '8px 16px',
            borderRadius: '8px',
            fontWeight: '500',
            transition: 'all 0.2s',
            border: 'none',
            cursor: 'pointer',
            backgroundColor: page === pagination.current_page ? '#ec4899' : '#f3f4f6',
            color: page === pagination.current_page ? 'white' : '#374151'
          }"
          @mouseover="handlePageHover($event, page, true)"
          @mouseleave="handlePageHover($event, page, false)"
        >
          {{ page }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

interface BabyName {
  id: number
  name: string
  gender: string
  origin: string
  meaning: string
  description?: string
  popularity_rank?: number
  is_popular: boolean
}

const babyNames = ref<BabyName[]>([])
const loading = ref(false)
const filters = ref({
  gender: 'all',
  popular: '',
  search: ''
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0
})

let searchTimeout: NodeJS.Timeout | null = null

const visiblePages = computed(() => {
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const pages = []
  
  const start = Math.max(1, current - 2)
  const end = Math.min(last, current + 2)
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

const fetchBabyNames = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      gender: filters.value.gender !== 'all' ? filters.value.gender : undefined,
      popular: filters.value.popular || undefined,
      search: filters.value.search || undefined
    }

    const response = await axios.get('/api/baby-names', { params })
    babyNames.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      per_page: response.data.per_page,
      total: response.data.total
    }
  } catch (error) {
    console.error('Bebek isimleri yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const debounceSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchBabyNames(1)
  }, 500)
}

const changePage = (page: number) => {
  fetchBabyNames(page)
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const handlePageHover = (event: Event, page: number, isHover: boolean) => {
  if (page !== pagination.value.current_page) {
    const target = event.target as HTMLElement
    target.style.backgroundColor = isHover ? '#fce7f3' : '#f3f4f6'
  }
}

onMounted(() => {
  fetchBabyNames()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>