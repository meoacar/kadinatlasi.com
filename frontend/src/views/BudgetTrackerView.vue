<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 1400px; margin: 0 auto;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">💰 Bütçe Takibi</h1>
        <p style="font-size: 1.125rem; color: #6b7280;">Gelir ve giderlerinizi takip edin, bütçenizi kontrol altında tutun</p>
      </div>

      <!-- Stats Cards -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 24px; border-radius: 16px; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);">
          <div style="display: flex; align-items: center; justify-content: between;">
            <div>
              <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 4px;">Toplam Gelir</div>
              <div style="font-size: 2rem; font-weight: bold;">{{ formatCurrency(stats.total_income) }}</div>
            </div>
            <div style="font-size: 2.5rem;">📈</div>
          </div>
        </div>
        
        <div style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 24px; border-radius: 16px; box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);">
          <div style="display: flex; align-items: center; justify-content: between;">
            <div>
              <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 4px;">Toplam Gider</div>
              <div style="font-size: 2rem; font-weight: bold;">{{ formatCurrency(stats.total_expense) }}</div>
            </div>
            <div style="font-size: 2.5rem;">📉</div>
          </div>
        </div>
        
        <div :style="{ background: stats.balance >= 0 ? 'linear-gradient(135deg, #3b82f6, #1d4ed8)' : 'linear-gradient(135deg, #f59e0b, #d97706)', color: 'white', padding: '24px', borderRadius: '16px', boxShadow: stats.balance >= 0 ? '0 10px 25px rgba(59, 130, 246, 0.3)' : '0 10px 25px rgba(245, 158, 11, 0.3)' }">
          <div style="display: flex; align-items: center; justify-content: between;">
            <div>
              <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 4px;">Net Bakiye</div>
              <div style="font-size: 2rem; font-weight: bold;">{{ formatCurrency(stats.balance) }}</div>
            </div>
            <div style="font-size: 2.5rem;">{{ stats.balance >= 0 ? '💰' : '⚠️' }}</div>
          </div>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 400px; gap: 32px; align-items: start;">
        
        <!-- Entries List -->
        <div style="background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827;">📋 Kayıtlar</h2>
            <button @click="showAddForm = true" style="background: linear-gradient(135deg, #ec4899, #8b5cf6); color: white; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600;">
              ➕ Yeni Kayıt
            </button>
          </div>

          <!-- Month Filter -->
          <div style="display: flex; gap: 16px; margin-bottom: 24px;">
            <select v-model="selectedMonth" @change="loadData" style="padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px;">
              <option v-for="month in months" :key="month.value" :value="month.value">{{ month.label }}</option>
            </select>
            <select v-model="selectedYear" @change="loadData" style="padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px;">
              <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
            </select>
          </div>

          <!-- Entries -->
          <div v-if="entries.length" style="display: flex; flex-direction: column; gap: 12px; max-height: 600px; overflow-y: auto;">
            <div v-for="entry in entries" :key="entry.id" style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #f8fafc; border-radius: 12px; border-left: 4px solid;" :style="{ borderLeftColor: entry.category.color }">
              <div style="display: flex; align-items: center; gap: 12px;">
                <div style="font-size: 1.5rem;">{{ entry.category.icon }}</div>
                <div>
                  <div style="font-weight: 600; color: #111827;">{{ entry.title }}</div>
                  <div style="font-size: 0.875rem; color: #6b7280;">{{ entry.category.name }} • {{ formatDate(entry.entry_date) }}</div>
                </div>
              </div>
              <div style="display: flex; align-items: center; gap: 12px;">
                <div style="font-weight: bold; font-size: 1.125rem;" :style="{ color: entry.type === 'income' ? '#10b981' : '#ef4444' }">
                  {{ entry.type === 'income' ? '+' : '-' }}{{ formatCurrency(entry.amount) }}
                </div>
                <button @click="deleteEntry(entry.id)" style="color: #ef4444; background: none; border: none; cursor: pointer; padding: 4px;">🗑️</button>
              </div>
            </div>
          </div>
          
          <div v-else style="text-align: center; padding: 48px; color: #6b7280;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📊</div>
            <div>Henüz kayıt yok. İlk kaydınızı ekleyin!</div>
          </div>
        </div>

        <!-- Add Entry Form -->
        <div style="background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px;">
          <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 24px;">{{ showAddForm ? '➕ Yeni Kayıt' : '📊 Kategori Dağılımı' }}</h3>
          
          <div v-if="showAddForm">
            <form @submit.prevent="addEntry" style="display: flex; flex-direction: column; gap: 16px;">
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Tür</label>
                <select v-model="form.type" required style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;">
                  <option value="income">💰 Gelir</option>
                  <option value="expense">💸 Gider</option>
                </select>
              </div>
              
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Kategori</label>
                <select v-model="form.budget_category_id" required style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;">
                  <option v-for="category in filteredCategories" :key="category.id" :value="category.id">
                    {{ category.icon }} {{ category.name }}
                  </option>
                </select>
              </div>
              
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Başlık</label>
                <input v-model="form.title" type="text" required style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;" placeholder="Örn: Market alışverişi">
              </div>
              
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Tutar (TL)</label>
                <input v-model.number="form.amount" type="number" step="0.01" required style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;" placeholder="0.00">
              </div>
              
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Tarih</label>
                <input v-model="form.entry_date" type="date" required style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;">
              </div>
              
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Açıklama</label>
                <textarea v-model="form.description" style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; resize: vertical;" rows="3" placeholder="İsteğe bağlı açıklama"></textarea>
              </div>
              
              <div style="display: flex; gap: 12px;">
                <button type="submit" :disabled="loading" style="flex: 1; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
                  {{ loading ? '⏳ Kaydediliyor...' : '✅ Kaydet' }}
                </button>
                <button type="button" @click="showAddForm = false" style="background: #6b7280; color: white; padding: 12px 16px; border-radius: 8px; border: none; cursor: pointer;">❌</button>
              </div>
            </form>
          </div>
          
          <!-- Category Stats -->
          <div v-else>
            <div v-if="stats.expenses_by_category?.length" style="display: flex; flex-direction: column; gap: 12px;">
              <div v-for="category in stats.expenses_by_category" :key="category.category" style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px; border-left: 4px solid;" :style="{ borderLeftColor: category.color }">
                <div style="font-weight: 500;">{{ category.category }}</div>
                <div style="font-weight: bold; color: #ef4444;">{{ formatCurrency(category.total) }}</div>
              </div>
            </div>
            <div v-else style="text-align: center; padding: 24px; color: #6b7280;">
              <div style="font-size: 2rem; margin-bottom: 8px;">📊</div>
              <div>Bu ay henüz gider yok</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import api from '@/services/api'

const loading = ref(false)
const showAddForm = ref(false)
const entries = ref([])
const categories = ref([])
const stats = ref({
  total_income: 0,
  total_expense: 0,
  balance: 0,
  expenses_by_category: []
})

const selectedMonth = ref(new Date().getMonth() + 1)
const selectedYear = ref(new Date().getFullYear())

const form = reactive({
  type: 'expense',
  budget_category_id: null,
  title: '',
  amount: null,
  entry_date: new Date().toISOString().split('T')[0],
  description: ''
})

const months = [
  { value: 1, label: 'Ocak' }, { value: 2, label: 'Şubat' }, { value: 3, label: 'Mart' },
  { value: 4, label: 'Nisan' }, { value: 5, label: 'Mayıs' }, { value: 6, label: 'Haziran' },
  { value: 7, label: 'Temmuz' }, { value: 8, label: 'Ağustos' }, { value: 9, label: 'Eylül' },
  { value: 10, label: 'Ekim' }, { value: 11, label: 'Kasım' }, { value: 12, label: 'Aralık' }
]

const years = Array.from({ length: 5 }, (_, i) => new Date().getFullYear() - 2 + i)

const filteredCategories = computed(() => {
  return categories.value.filter(cat => cat.type === form.type)
})

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR')
}

const loadCategories = async () => {
  try {
    const response = await api.get('/budget/categories')
    if (response.data.success) {
      categories.value = response.data.data
    }
  } catch (error) {
    console.error('Categories loading error:', error)
  }
}

const loadEntries = async () => {
  try {
    const response = await api.get('/budget/entries', {
      params: { month: selectedMonth.value, year: selectedYear.value }
    })
    if (response.data.success) {
      entries.value = response.data.data
    }
  } catch (error) {
    console.error('Entries loading error:', error)
  }
}

const loadStats = async () => {
  try {
    const response = await api.get('/budget/stats', {
      params: { month: selectedMonth.value, year: selectedYear.value }
    })
    if (response.data.success) {
      stats.value = response.data.data
    }
  } catch (error) {
    console.error('Stats loading error:', error)
  }
}

const loadData = async () => {
  await Promise.all([loadEntries(), loadStats()])
}

const addEntry = async () => {
  loading.value = true
  try {
    const response = await api.post('/budget/entries', form)
    if (response.data.success) {
      showAddForm.value = false
      Object.assign(form, {
        type: 'expense',
        budget_category_id: null,
        title: '',
        amount: null,
        entry_date: new Date().toISOString().split('T')[0],
        description: ''
      })
      await loadData()
    }
  } catch (error) {
    console.error('Add entry error:', error)
    alert('Kayıt eklenirken hata oluştu!')
  } finally {
    loading.value = false
  }
}

const deleteEntry = async (id) => {
  if (!confirm('Bu kaydı silmek istediğinizden emin misiniz?')) return
  
  try {
    await api.delete(`/budget/entries/${id}`)
    await loadData()
  } catch (error) {
    console.error('Delete entry error:', error)
    alert('Kayıt silinirken hata oluştu!')
  }
}

onMounted(async () => {
  await loadCategories()
  await loadData()
})
</script>

<style scoped>
@media (max-width: 768px) {
  div[style*="grid-template-columns: 1fr 400px"] {
    grid-template-columns: 1fr !important;
  }
}
</style>