<template>
  <div style="min-height: 100vh; background: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 48px;">
        <div>
          <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 8px;">📋 Sorularım</h1>
          <p style="font-size: 1.25rem; color: #6b7280;">Uzmanlara sorduğunuz soruları takip edin</p>
        </div>
        <router-link
          to="/uzman-soru-sor"
          style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background-color 0.2s;"
          @mouseover="$event.target.style.background = '#db2777'"
          @mouseleave="$event.target.style.background = '#ec4899'"
        >
          + Yeni Soru Sor
        </router-link>
      </div>

      <!-- Loading -->
      <div v-if="loading" style="text-align: center; padding: 64px;">
        <div style="width: 48px; height: 48px; border: 3px solid #f3f4f6; border-top: 3px solid #ec4899; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        <p style="margin-top: 16px; color: #6b7280;">Sorular yükleniyor...</p>
      </div>

      <!-- Questions List -->
      <div v-else-if="questions.data?.length" style="display: grid; gap: 24px;">
        <div
          v-for="question in questions.data"
          :key="question.id"
          style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;"
        >
          <div style="padding: 24px;">
            
            <!-- Question Header -->
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;">
              <span style="background: #fce7f3; color: #be185d; padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">
                {{ question.category?.name }}
              </span>
              <span
                :style="{
                  background: question.status === 'pending' ? '#fef3c7' : '#d1fae5',
                  color: question.status === 'pending' ? '#92400e' : '#065f46',
                  padding: '4px 12px',
                  borderRadius: '12px',
                  fontSize: '0.75rem',
                  fontWeight: '500'
                }"
              >
                {{ question.status === 'pending' ? '⏳ Bekliyor' : '✅ Cevaplandı' }}
              </span>
              <span style="color: #9ca3af; font-size: 0.875rem; margin-left: auto;">
                {{ formatDate(question.created_at) }}
              </span>
            </div>

            <!-- Question Content -->
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 12px; line-height: 1.4;">
              {{ question.title }}
            </h3>
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
              <p style="color: #374151; line-height: 1.6; margin: 0;">{{ question.question }}</p>
            </div>

            <!-- Answer Section -->
            <div v-if="question.answer" style="border-top: 1px solid #e5e7eb; padding-top: 16px;">
              <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 40px; height: 40px; background: #ec4899; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                  {{ question.expert?.name?.charAt(0) || 'U' }}
                </div>
                <div>
                  <p style="font-weight: 600; color: #111827; margin: 0; font-size: 0.875rem;">{{ question.expert?.name }}</p>
                  <p style="color: #6b7280; margin: 0; font-size: 0.75rem;">{{ question.expert?.expert_rank }}</p>
                </div>
              </div>
              <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px;">
                <p style="color: #166534; line-height: 1.6; margin: 0; margin-bottom: 8px;">{{ question.answer }}</p>
                <p style="color: #16a34a; font-size: 0.75rem; margin: 0;">
                  {{ formatDate(question.answered_at) }} tarihinde cevaplandı
                </p>
              </div>
            </div>

            <!-- Pending Message -->
            <div v-else style="border-top: 1px solid #e5e7eb; padding-top: 16px;">
              <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 16px; display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 1.25rem;">⏳</span>
                <div>
                  <p style="color: #92400e; font-weight: 600; margin: 0; margin-bottom: 4px;">Cevap Bekleniyor</p>
                  <p style="color: #a16207; font-size: 0.875rem; margin: 0;">Uzmanımız en kısa sürede sorunuzu cevaplayacaktır.</p>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Pagination -->
        <div v-if="questions.last_page > 1" style="display: flex; justify-content: center; gap: 8px; margin-top: 32px;">
          <button
            v-for="page in questions.last_page"
            :key="page"
            @click="loadQuestions(page)"
            :style="{
              padding: '8px 16px',
              border: 'none',
              borderRadius: '6px',
              cursor: 'pointer',
              fontWeight: '500',
              background: page === questions.current_page ? '#ec4899' : '#f3f4f6',
              color: page === questions.current_page ? 'white' : '#374151'
            }"
          >
            {{ page }}
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else style="text-align: center; padding: 64px;">
        <div style="font-size: 4rem; margin-bottom: 16px;">❓</div>
        <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Henüz soru sormadınız</h3>
        <p style="color: #6b7280; font-size: 1.125rem; margin-bottom: 24px;">Uzmanlarımıza ilk sorunuzu sorun!</p>
        <router-link
          to="/uzman-soru-sor"
          style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background-color 0.2s;"
          @mouseover="$event.target.style.background = '#db2777'"
          @mouseleave="$event.target.style.background = '#ec4899'"
        >
          İlk Sorunuzu Sorun
        </router-link>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import expertQuestionService, { type ExpertQuestion } from '@/services/expertQuestionService'

const loading = ref(true)
const questions = ref<{
  data: ExpertQuestion[]
  current_page: number
  last_page: number
}>({
  data: [],
  current_page: 1,
  last_page: 1
})

const loadQuestions = async (page = 1) => {
  loading.value = true
  try {
    questions.value = await expertQuestionService.getMyQuestions(page)
  } catch (error) {
    console.error('Sorular yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadQuestions()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>