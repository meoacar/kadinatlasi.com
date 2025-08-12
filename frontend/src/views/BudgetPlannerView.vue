<template>
  <div class="budget-planner-page">
    <div class="page-header">
      <h1 class="page-title">💰 Kişisel Finans ve Bütçe Planlayıcı</h1>
      <p class="page-subtitle">Gelir ve giderlerinizi takip edin, bütçenizi planlayın</p>
    </div>

    <div class="planner-container">
      <!-- Gelir Gider Formu -->
      <div class="form-card">
        <h2 class="card-title">📊 Aylık Bütçe Hesaplama</h2>
        <form @submit.prevent="calculateBudget" class="budget-form">
          <div class="form-section">
            <h3 class="section-title">💵 Gelirler</h3>
            <div class="income-list">
              <div v-for="(income, index) in incomes" :key="index" class="income-item">
                <input v-model="income.name" placeholder="Gelir kaynağı" class="form-input" required>
                <input v-model.number="income.amount" type="number" placeholder="Tutar (₺)" class="form-input" required>
                <button type="button" @click="removeIncome(index)" class="remove-btn">×</button>
              </div>
            </div>
            <button type="button" @click="addIncome" class="add-btn">+ Gelir Ekle</button>
          </div>

          <div class="form-section">
            <h3 class="section-title">💸 Giderler</h3>
            <div class="expense-categories">
              <div v-for="category in expenseCategories" :key="category.name" class="category-group">
                <h4 class="category-title">{{ category.icon }} {{ category.name }}</h4>
                <div v-for="(expense, index) in category.expenses" :key="index" class="expense-item">
                  <input v-model="expense.name" :placeholder="category.placeholder" class="form-input" required>
                  <input v-model.number="expense.amount" type="number" placeholder="Tutar (₺)" class="form-input" required>
                  <button type="button" @click="removeExpense(category.name, index)" class="remove-btn">×</button>
                </div>
                <button type="button" @click="addExpense(category.name)" class="add-btn-small">+ Ekle</button>
              </div>
            </div>
          </div>

          <button type="submit" class="calculate-btn" :disabled="isCalculating">
            {{ isCalculating ? 'Hesaplanıyor...' : 'Bütçe Hesapla' }}
          </button>
        </form>
      </div>

      <!-- Sonuçlar -->
      <div v-if="budgetResult" class="results-card">
        <h2 class="card-title">📈 Bütçe Analizi</h2>
        
        <div class="summary-grid">
          <div class="summary-item income">
            <div class="summary-icon">💰</div>
            <div class="summary-info">
              <div class="summary-label">Toplam Gelir</div>
              <div class="summary-value">{{ formatCurrency(budgetResult.totalIncome) }}</div>
            </div>
          </div>
          
          <div class="summary-item expense">
            <div class="summary-icon">💸</div>
            <div class="summary-info">
              <div class="summary-label">Toplam Gider</div>
              <div class="summary-value">{{ formatCurrency(budgetResult.totalExpense) }}</div>
            </div>
          </div>
          
          <div class="summary-item" :class="budgetResult.balance >= 0 ? 'positive' : 'negative'">
            <div class="summary-icon">{{ budgetResult.balance >= 0 ? '✅' : '⚠️' }}</div>
            <div class="summary-info">
              <div class="summary-label">{{ budgetResult.balance >= 0 ? 'Kalan Miktar' : 'Açık' }}</div>
              <div class="summary-value">{{ formatCurrency(Math.abs(budgetResult.balance)) }}</div>
            </div>
          </div>
        </div>

        <!-- Kategori Analizi -->
        <div class="category-analysis">
          <h3 class="analysis-title">📊 Kategori Bazlı Analiz</h3>
          <div class="category-chart">
            <div v-for="category in budgetResult.categoryBreakdown" :key="category.name" class="category-bar">
              <div class="category-info">
                <span class="category-name">{{ category.name }}</span>
                <span class="category-amount">{{ formatCurrency(category.total) }}</span>
                <span class="category-percentage">({{ category.percentage }}%)</span>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" :style="{ width: category.percentage + '%' }"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Öneriler -->
        <div class="recommendations">
          <h3 class="recommendations-title">💡 Finansal Öneriler</h3>
          <div class="recommendation-list">
            <div v-for="recommendation in budgetResult.recommendations" :key="recommendation" class="recommendation-item">
              {{ recommendation }}
            </div>
          </div>
        </div>

        <!-- Tasarruf Hedefleri -->
        <div class="savings-goals">
          <h3 class="savings-title">🎯 Tasarruf Hedefleri</h3>
          <div class="goals-grid">
            <div class="goal-item">
              <div class="goal-label">Acil Durum Fonu</div>
              <div class="goal-amount">{{ formatCurrency(budgetResult.totalExpense * 3) }}</div>
              <div class="goal-note">3 aylık gider tutarı</div>
            </div>
            <div class="goal-item">
              <div class="goal-label">Aylık Tasarruf Hedefi</div>
              <div class="goal-amount">{{ formatCurrency(budgetResult.totalIncome * 0.2) }}</div>
              <div class="goal-note">Gelirin %20'si</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'

interface Income {
  name: string
  amount: number
}

interface Expense {
  name: string
  amount: number
}

interface ExpenseCategory {
  name: string
  icon: string
  placeholder: string
  expenses: Expense[]
}

const incomes = ref<Income[]>([
  { name: 'Maaş', amount: 0 }
])

const expenseCategories = reactive<ExpenseCategory[]>([
  {
    name: 'Zorunlu Giderler',
    icon: '🏠',
    placeholder: 'Kira, elektrik, su vb.',
    expenses: [{ name: '', amount: 0 }]
  },
  {
    name: 'Yiyecek & İçecek',
    icon: '🍽️',
    placeholder: 'Market, restoran vb.',
    expenses: [{ name: '', amount: 0 }]
  },
  {
    name: 'Ulaşım',
    icon: '🚗',
    placeholder: 'Benzin, otobüs, taksi vb.',
    expenses: [{ name: '', amount: 0 }]
  },
  {
    name: 'Sağlık & Güzellik',
    icon: '💄',
    placeholder: 'Doktor, kozmetik vb.',
    expenses: [{ name: '', amount: 0 }]
  },
  {
    name: 'Eğlence & Sosyal',
    icon: '🎉',
    placeholder: 'Sinema, kafe vb.',
    expenses: [{ name: '', amount: 0 }]
  },
  {
    name: 'Giyim',
    icon: '👗',
    placeholder: 'Kıyafet, ayakkabı vb.',
    expenses: [{ name: '', amount: 0 }]
  }
])

const budgetResult = ref(null)
const isCalculating = ref(false)

const addIncome = () => {
  incomes.value.push({ name: '', amount: 0 })
}

const removeIncome = (index: number) => {
  if (incomes.value.length > 1) {
    incomes.value.splice(index, 1)
  }
}

const addExpense = (categoryName: string) => {
  const category = expenseCategories.find(cat => cat.name === categoryName)
  if (category) {
    category.expenses.push({ name: '', amount: 0 })
  }
}

const removeExpense = (categoryName: string, index: number) => {
  const category = expenseCategories.find(cat => cat.name === categoryName)
  if (category && category.expenses.length > 1) {
    category.expenses.splice(index, 1)
  }
}

const calculateBudget = () => {
  isCalculating.value = true
  
  setTimeout(() => {
    const totalIncome = incomes.value.reduce((sum, income) => sum + (income.amount || 0), 0)
    
    const categoryBreakdown = expenseCategories.map(category => {
      const total = category.expenses.reduce((sum, expense) => sum + (expense.amount || 0), 0)
      return {
        name: category.name,
        total,
        percentage: totalIncome > 0 ? Math.round((total / totalIncome) * 100) : 0
      }
    }).filter(cat => cat.total > 0)
    
    const totalExpense = categoryBreakdown.reduce((sum, cat) => sum + cat.total, 0)
    const balance = totalIncome - totalExpense
    
    const recommendations = generateRecommendations(totalIncome, totalExpense, balance, categoryBreakdown)
    
    budgetResult.value = {
      totalIncome,
      totalExpense,
      balance,
      categoryBreakdown,
      recommendations
    }
    
    isCalculating.value = false
  }, 1000)
}

const generateRecommendations = (income: number, expense: number, balance: number, categories: any[]) => {
  const recommendations = []
  
  if (balance < 0) {
    recommendations.push('⚠️ Giderleriniz gelirinizden fazla! Harcamalarınızı gözden geçirin.')
  } else if (balance < income * 0.1) {
    recommendations.push('💡 Tasarruf oranınız düşük. Giderlerinizi azaltmaya çalışın.')
  } else {
    recommendations.push('✅ Bütçeniz dengeli görünüyor!')
  }
  
  // Kategori önerileri
  categories.forEach(cat => {
    if (cat.percentage > 30 && cat.name !== 'Zorunlu Giderler') {
      recommendations.push(`📊 ${cat.name} kategorisinde harcamalarınız yüksek (%${cat.percentage})`)
    }
  })
  
  if (balance > income * 0.2) {
    recommendations.push('💰 Harika! Yatırım yapmayı düşünebilirsiniz.')
  }
  
  return recommendations
}

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY'
  }).format(amount)
}
</script>

<style scoped>
.budget-planner-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
  padding: 20px;
}

.page-header {
  text-align: center;
  margin-bottom: 40px;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #166534;
  margin-bottom: 8px;
}

.page-subtitle {
  font-size: 1.2rem;
  color: #64748b;
}

.planner-container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  gap: 32px;
}

.form-card, .results-card {
  background: white;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.card-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 24px;
}

.form-section {
  margin-bottom: 32px;
}

.section-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.income-list, .expense-item {
  display: flex;
  gap: 12px;
  align-items: center;
  margin-bottom: 12px;
}

.form-input {
  flex: 1;
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: #10b981;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.remove-btn {
  width: 32px;
  height: 32px;
  background: #ef4444;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.add-btn, .add-btn-small {
  background: #10b981;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 500;
}

.add-btn-small {
  padding: 4px 8px;
  font-size: 0.75rem;
}

.category-group {
  margin-bottom: 24px;
  padding: 20px;
  background: #f9fafb;
  border-radius: 12px;
}

.category-title {
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 12px;
}

.calculate-btn {
  width: 100%;
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  border: none;
  padding: 16px 32px;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.calculate-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

.calculate-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
}

.summary-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px;
  border-radius: 12px;
  border: 2px solid transparent;
}

.summary-item.income {
  background: linear-gradient(135deg, #dcfce7, #bbf7d0);
  border-color: #10b981;
}

.summary-item.expense {
  background: linear-gradient(135deg, #fee2e2, #fecaca);
  border-color: #ef4444;
}

.summary-item.positive {
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  border-color: #3b82f6;
}

.summary-item.negative {
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  border-color: #f59e0b;
}

.summary-icon {
  font-size: 2rem;
}

.summary-label {
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 4px;
}

.summary-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.category-analysis {
  margin-bottom: 32px;
}

.analysis-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.category-bar {
  margin-bottom: 16px;
}

.category-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.category-name {
  font-weight: 500;
  color: #374151;
}

.category-amount {
  font-weight: 600;
  color: #1f2937;
}

.category-percentage {
  font-size: 0.875rem;
  color: #6b7280;
}

.progress-bar {
  height: 8px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(135deg, #10b981, #059669);
  transition: width 0.3s ease;
}

.recommendations {
  margin-bottom: 32px;
}

.recommendations-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.recommendation-item {
  padding: 12px 16px;
  background: #f0f9ff;
  border-left: 4px solid #3b82f6;
  border-radius: 8px;
  margin-bottom: 8px;
  color: #1e40af;
}

.savings-goals {
  background: #f9fafb;
  padding: 24px;
  border-radius: 12px;
}

.savings-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.goals-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.goal-item {
  text-align: center;
  padding: 16px;
  background: white;
  border-radius: 8px;
}

.goal-label {
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 8px;
}

.goal-amount {
  font-size: 1.25rem;
  font-weight: 700;
  color: #10b981;
  margin-bottom: 4px;
}

.goal-note {
  font-size: 0.75rem;
  color: #9ca3af;
}

@media (max-width: 768px) {
  .page-title {
    font-size: 2rem;
  }
  
  .income-list, .expense-item {
    flex-direction: column;
    align-items: stretch;
  }
  
  .summary-grid {
    grid-template-columns: 1fr;
  }
  
  .category-info {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }
}
</style>