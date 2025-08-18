<template>
  <div style="min-height: 100vh; background: #f9fafb; padding: 32px 16px;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">💰 Finans Planlayıcı</h1>
        <p style="font-size: 1.125rem; color: #6b7280;">Bütçenizi planlayın ve tasarruf hedeflerinize ulaşın</p>
      </div>

      <div class="main-grid">
        <div style="background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px;">
          <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px; text-align: center;">📋 Bilgilerinizi Girin</h2>
          <form @submit.prevent="calculateFinancialPlan" style="display: flex; flex-direction: column; gap: 24px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Aylık Gelir (TL)</label>
                <input
                  v-model.number="form.monthlyIncome"
                  type="number"
                  step="0.01"
                  required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s;"
                  placeholder="15000"
                  @focus="$event.target.style.borderColor = '#ec4899'"
                  @blur="$event.target.style.borderColor = '#e5e7eb'"
                />
              </div>
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Aylık Gider (TL)</label>
                <input
                  v-model.number="form.monthlyExpenses"
                  type="number"
                  step="0.01"
                  required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s;"
                  placeholder="10000"
                  @focus="$event.target.style.borderColor = '#ec4899'"
                  @blur="$event.target.style.borderColor = '#e5e7eb'"
                />
              </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Tasarruf Hedefi (TL)</label>
                <input
                  v-model.number="form.savingsGoal"
                  type="number"
                  step="0.01"
                  required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s;"
                  placeholder="50000"
                  @focus="$event.target.style.borderColor = '#ec4899'"
                  @blur="$event.target.style.borderColor = '#e5e7eb'"
                />
              </div>
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 8px;">Hedef Süre (Ay)</label>
                <input
                  v-model.number="form.goalMonths"
                  type="number"
                  required
                  style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s;"
                  placeholder="12"
                  @focus="$event.target.style.borderColor = '#ec4899'"
                  @blur="$event.target.style.borderColor = '#e5e7eb'"
                />
              </div>
            </div>
            <button
              type="submit"
              :disabled="loading"
              style="width: 100%; background: linear-gradient(135deg, #ec4899, #8b5cf6); color: white; padding: 16px; border-radius: 12px; font-size: 1.125rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);"
              :style="{ opacity: loading ? 0.7 : 1 }"
              @mouseover="$event.target.style.transform = 'translateY(-2px)'"
              @mouseleave="$event.target.style.transform = 'translateY(0)'"
            >
              {{ loading ? '🔄 Hesaplanıyor...' : '📊 Hesapla' }}
            </button>
          </form>
        </div>

        <div v-if="result" style="background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px;">
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px; text-align: center;">📊 Finans Planınız</h3>
          <div style="display: flex; flex-direction: column; gap: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
              <div style="text-align: center; padding: 20px; background: #f0fdf4; border-radius: 12px; border: 2px solid #bbf7d0;">
                <div style="font-size: 1.25rem; font-weight: bold; color: #16a34a; margin-bottom: 4px;">{{ formatCurrency(result.monthly_savings) }}</div>
                <div style="font-size: 0.875rem; color: #15803d;">Aylık Tasarruf</div>
              </div>
              <div style="text-align: center; padding: 20px; background: #eff6ff; border-radius: 12px; border: 2px solid #93c5fd;">
                <div style="font-size: 1.25rem; font-weight: bold; color: #2563eb; margin-bottom: 4px;">{{ formatCurrency(result.required_monthly_savings) }}</div>
                <div style="font-size: 0.875rem; color: #1d4ed8;">Gereken Tasarruf</div>
              </div>
              <div style="text-align: center; padding: 20px; border-radius: 12px; border: 2px solid;" :style="{ background: result.can_reach_goal ? '#f0fdf4' : '#fef2f2', borderColor: result.can_reach_goal ? '#bbf7d0' : '#fecaca' }">
                <div style="font-size: 1.25rem; font-weight: bold; margin-bottom: 4px;" :style="{ color: result.can_reach_goal ? '#16a34a' : '#dc2626' }">
                  {{ result.can_reach_goal ? '✓ Mümkün' : '⚠ Zor' }}
                </div>
                <div style="font-size: 0.875rem;" :style="{ color: result.can_reach_goal ? '#15803d' : '#b91c1c' }">Hedefe Ulaşma</div>
              </div>
            </div>
            <div v-if="result.actual_months_to_goal" style="padding: 16px; background: #fefce8; border-radius: 12px; border: 2px solid #fde047;">
              <div style="font-size: 1rem; font-weight: 500; color: #a16207; text-align: center;">📅 Gerçek Süre: {{ result.actual_months_to_goal }} ay</div>
            </div>
            <div style="padding: 20px; background: #f8fafc; border-radius: 12px; border: 2px solid #e2e8f0;">
              <div style="font-size: 1rem; font-weight: 600; color: #1e293b; margin-bottom: 16px; text-align: center;">📊 50/30/20 Bütçe Kuralı</div>
              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; text-align: center;">
                <div style="padding: 16px; background: #eff6ff; border-radius: 8px;">
                  <div style="font-size: 1.125rem; font-weight: bold; color: #2563eb; margin-bottom: 4px;">{{ formatCurrency(result.recommended_needs) }}</div>
                  <div style="font-size: 0.875rem; color: #1d4ed8;">🏠 İhtiyaçlar</div>
                </div>
                <div style="padding: 16px; background: #faf5ff; border-radius: 8px;">
                  <div style="font-size: 1.125rem; font-weight: bold; color: #9333ea; margin-bottom: 4px;">{{ formatCurrency(result.recommended_wants) }}</div>
                  <div style="font-size: 0.875rem; color: #7c3aed;">🎉 İstekler</div>
                </div>
                <div style="padding: 16px; background: #f0fdf4; border-radius: 8px;">
                  <div style="font-size: 1.125rem; font-weight: bold; color: #16a34a; margin-bottom: 4px;">{{ formatCurrency(result.recommended_savings) }}</div>
                  <div style="font-size: 0.875rem; color: #15803d;">💰 Tasarruf</div>
                </div>
              </div>
            </div>
            <div style="padding: 16px; background: #f1f5f9; border-radius: 12px; border-left: 4px solid #ec4899;">
              <div style="font-size: 0.875rem; color: #475569; line-height: 1.5;">
                💡 <strong>Tavsiye:</strong> {{ result.advice }}
              </div>
            </div>
          </div>
        </div>

        <div v-else style="background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 32px;">
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px; text-align: center;">📊 Finans Planlama Rehberi</h3>
          <div style="font-size: 1rem; color: #4b5563; line-height: 1.6;">
            <p style="margin-bottom: 24px; text-align: center;">Gelir-gider dengenizi analiz edin ve tasarruf hedeflerinize ulaşın.</p>
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div style="padding: 16px; background: #f0f9ff; border-radius: 12px; border-left: 4px solid #0ea5e9;">
                <div style="font-weight: 600; color: #0c4a6e; margin-bottom: 4px;">📊 50/30/20 Kuralı</div>
                <div style="font-size: 0.875rem; color: #0369a1;">%50 İhtiyaçlar, %30 İstekler, %20 Tasarruf</div>
              </div>
              <div style="padding: 16px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
                <div style="font-weight: 600; color: #92400e; margin-bottom: 4px;">🎆 Acil Fon</div>
                <div style="font-size: 0.875rem; color: #b45309;">3-6 aylık gider tutarında acil durum fonu</div>
              </div>
              <div style="padding: 16px; background: #f0fdf4; border-radius: 12px; border-left: 4px solid #22c55e;">
                <div style="font-weight: 600; color: #166534; margin-bottom: 4px;">📈 Yatırım</div>
                <div style="font-size: 0.875rem; color: #15803d;">Uzun vadeli büyüme için yatırım planlayın</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { calculatorService } from '@/services/calculator'

const loading = ref(false)
const result = ref(null)

const form = reactive({
  monthlyIncome: null,
  monthlyExpenses: null,
  savingsGoal: null,
  goalMonths: null
})

const calculateFinancialPlan = async () => {
  console.log('Form data:', form)
  loading.value = true
  try {
    const apiData = {
      monthly_income: form.monthlyIncome,
      monthly_expenses: form.monthlyExpenses,
      savings_goal: form.savingsGoal,
      goal_months: form.goalMonths
    }
    console.log('API data:', apiData)
    const response = await calculatorService.financialPlanner(apiData)
    console.log('API response:', response)
    result.value = response.data
  } catch (error) {
    console.error('Calculation error:', error)
    alert('Hesaplama hatası: ' + (error.response?.data?.message || error.message))
  } finally {
    loading.value = false
  }
}

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}
</script>

<style scoped>
.main-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
  align-items: start;
}

@media (max-width: 768px) {
  .main-grid {
    grid-template-columns: 1fr !important;
  }
  
  div[style*="grid-template-columns: 1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
  
  div[style*="grid-template-columns: 1fr 1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}

@media (max-width: 640px) {
  div[style*="padding: 32px"] {
    padding: 20px !important;
  }
  
  h1[style*="font-size: 2.5rem"] {
    font-size: 2rem !important;
  }
}
</style>