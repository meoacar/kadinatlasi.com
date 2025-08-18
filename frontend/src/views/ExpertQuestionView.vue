<template>
  <div style="min-height: 100vh; background: #f9fafb;">
    <div style="max-width: 800px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">❓ Uzmanlara Soru Sor</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Alanında uzman kişilerden profesyonel destek alın</p>
      </div>

      <!-- Question Limits Info -->
      <div v-if="questionLimits" style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 12px; padding: 20px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
          <h3 style="color: #0369a1; font-weight: 600; margin: 0;">
            📊 {{ questionLimits.membership_type }} Üyeliğiniz
          </h3>
          <span style="background: #0ea5e9; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.875rem; font-weight: 500;">
            {{ questionLimits.month }}
          </span>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="flex: 1; background: #e0f2fe; border-radius: 8px; height: 8px;">
            <div 
              style="background: #0ea5e9; height: 100%; border-radius: 8px; transition: width 0.3s;"
              :style="{ width: questionLimits.limit > 0 ? (questionLimits.used / questionLimits.limit * 100) + '%' : '0%' }"
            ></div>
          </div>
          <span style="color: #0369a1; font-weight: 600; font-size: 0.875rem;">
            {{ questionLimits.used }} / {{ questionLimits.limit }} soru
          </span>
        </div>
        <p style="color: #0369a1; margin: 8px 0 0 0; font-size: 0.875rem;">
          Bu ay {{ questionLimits.remaining }} soru hakkınız kaldı.
        </p>
      </div>

      <!-- Form -->
      <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 32px;">
        <form @submit.prevent="submitQuestion">
          
          <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Kategori Seçin</label>
            <select
              v-model="form.category_id"
              style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; background: white;"
              required
            >
              <option value="">Hangi konuda yardım istiyorsunuz?</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Soru Başlığı</label>
            <input
              v-model="form.title"
              type="text"
              style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;"
              placeholder="Sorunuzun kısa başlığını yazın"
              required
              maxlength="255"
            />
          </div>

          <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Soru Detayı</label>
            <textarea
              v-model="form.question"
              rows="6"
              style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; resize: vertical;"
              placeholder="Sorunuzu detaylı bir şekilde açıklayın..."
              required
              maxlength="2000"
            ></textarea>
            <p style="text-align: right; color: #9ca3af; font-size: 0.875rem; margin-top: 4px;">
              {{ form.question.length }}/2000 karakter
            </p>
          </div>

          <div style="margin-bottom: 32px;">
            <label style="display: flex; align-items: center; cursor: pointer;">
              <input
                v-model="form.is_public"
                type="checkbox"
                style="margin-right: 8px; width: 16px; height: 16px;"
              />
              <span style="color: #374151; font-size: 0.875rem;">Bu soruyu herkese açık yap (diğer kullanıcılar da görebilir)</span>
            </label>
          </div>

          <button
            type="submit"
            :disabled="loading || (questionLimits && !questionLimits.can_ask)"
            :style="{ 
              width: '100%',
              color: 'white',
              padding: '16px',
              border: 'none',
              borderRadius: '8px',
              fontSize: '1.125rem',
              fontWeight: '600',
              cursor: 'pointer',
              transition: 'background-color 0.2s',
              opacity: (loading || (questionLimits && !questionLimits.can_ask)) ? 0.5 : 1,
              background: (questionLimits && !questionLimits.can_ask) ? '#9ca3af' : '#ec4899'
            }"
          >
            <span v-if="loading">Gönderiliyor...</span>
            <span v-else-if="questionLimits && !questionLimits.can_ask">Soru Limitiniz Doldu</span>
            <span v-else>Soruyu Gönder</span>
          </button>
          
          <div v-if="questionLimits && !questionLimits.can_ask" style="text-align: center; margin-top: 16px;">
            <p style="color: #dc2626; font-size: 0.875rem; margin-bottom: 12px;">
              Bu ay soru sorma limitinizi doldurdunuz. Daha fazla soru sormak için üyeliğinizi yükseltin.
            </p>
            <router-link
              to="/premium"
              style="background: #f59e0b; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.875rem;"
            >
              ⭐ Premium'a Geç
            </router-link>
          </div>
        </form>
      </div>

      <!-- Info Box -->
      <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 24px;">
        <h3 style="color: #1e40af; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
          <span style="font-size: 1.25rem;">💡</span>
          Nasıl Çalışır?
        </h3>
        <ul style="color: #1e40af; line-height: 1.6; margin: 0; padding-left: 20px;">
          <li style="margin-bottom: 8px;">Sorunuzu ilgili kategoride uzman kişilere yönlendiriyoruz</li>
          <li style="margin-bottom: 8px;">Uzmanlarımız en kısa sürede sorunuzu cevaplayacak</li>
          <li style="margin-bottom: 8px;">Cevap geldiğinde bildirim alacaksınız</li>
          <li>"Sorularım" bölümünden tüm sorularınızı takip edebilirsiniz</li>
        </ul>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import expertQuestionService, { type CreateQuestionData } from '@/services/expertQuestionService'
import { useToast } from '@/composables/useToast'

const router = useRouter()
const { showToast } = useToast()

const loading = ref(false)
const categories = ref([])
const questionLimits = ref(null)

const form = ref<CreateQuestionData>({
  category_id: 0,
  title: '',
  question: '',
  is_public: false
})

const loadCategories = async () => {
  try {
    categories.value = await expertQuestionService.getCategories()
  } catch (error) {
    console.error('Kategoriler yüklenirken hata:', error)
  }
}

const loadQuestionLimits = async () => {
  try {
    questionLimits.value = await expertQuestionService.getQuestionLimits()
  } catch (error) {
    console.error('Soru limitleri yüklenirken hata:', error)
  }
}

const submitQuestion = async () => {
  loading.value = true
  try {
    const response = await expertQuestionService.createQuestion(form.value)
    showToast(response.message, 'success')
    router.push('/sorularim')
  } catch (error: any) {
    showToast(error.response?.data?.message || 'Soru gönderilirken hata oluştu', 'error')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadCategories()
  loadQuestionLimits()
})
</script>