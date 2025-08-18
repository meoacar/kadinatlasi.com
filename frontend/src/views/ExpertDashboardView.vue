<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Uzman Paneli</h1>
        <p class="text-gray-600 mt-2">Size yönlendirilen soruları cevaplayın</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Bekleyen Sorular</p>
              <p class="text-2xl font-semibold text-gray-900">{{ pendingCount }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Cevaplanan</p>
              <p class="text-2xl font-semibold text-gray-900">{{ answeredCount }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Toplam Soru</p>
              <p class="text-2xl font-semibold text-gray-900">{{ totalCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-pink-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Sorular yükleniyor...</p>
      </div>

      <!-- Questions List -->
      <div v-else-if="questions.data?.length" class="space-y-6">
        <div
          v-for="question in questions.data"
          :key="question.id"
          class="bg-white rounded-lg shadow-md p-6"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                  {{ question.category?.name }}
                </span>
                <span
                  :class="{
                    'bg-yellow-100 text-yellow-800': question.status === 'pending',
                    'bg-green-100 text-green-800': question.status === 'answered'
                  }"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                >
                  {{ question.status === 'pending' ? 'Bekliyor' : 'Cevaplandı' }}
                </span>
              </div>
              <h3 class="text-lg font-semibold text-gray-900 mb-2">
                {{ question.title }}
              </h3>
              <p class="text-gray-600 mb-3">{{ question.question }}</p>
              <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                  <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                  </svg>
                </div>
                <span>{{ question.user?.name }}</span>
                <span>•</span>
                <span>{{ formatDate(question.created_at) }}</span>
              </div>
            </div>
          </div>

          <!-- Answer Form -->
          <div v-if="question.status === 'pending'" class="mt-4 pt-4 border-t border-gray-200">
            <form @submit.prevent="answerQuestion(question.id)">
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Cevabınız
                </label>
                <textarea
                  v-model="answerForms[question.id]"
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                  placeholder="Soruya detaylı cevabınızı yazın..."
                  required
                  maxlength="5000"
                ></textarea>
                <p class="text-sm text-gray-500 mt-1">
                  {{ (answerForms[question.id] || '').length }}/5000 karakter
                </p>
              </div>
              <button
                type="submit"
                :disabled="answeringQuestions.includes(question.id)"
                class="bg-pink-600 text-white px-4 py-2 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 disabled:opacity-50"
              >
                {{ answeringQuestions.includes(question.id) ? 'Cevap Gönderiliyor...' : 'Cevap Gönder' }}
              </button>
            </form>
          </div>

          <!-- Existing Answer -->
          <div v-else-if="question.answer" class="mt-4 pt-4 border-t border-gray-200">
            <div class="bg-green-50 rounded-lg p-4">
              <p class="text-sm font-medium text-green-800 mb-2">Cevabınız:</p>
              <p class="text-gray-800">{{ question.answer }}</p>
              <p class="text-sm text-gray-500 mt-2">
                {{ formatDate(question.answered_at) }} tarihinde cevaplandı
              </p>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="questions.last_page > 1" class="flex justify-center mt-8">
          <nav class="flex items-center space-x-2">
            <button
              v-for="page in questions.last_page"
              :key="page"
              @click="loadQuestions(page)"
              :class="{
                'bg-pink-600 text-white': page === questions.current_page,
                'bg-white text-gray-700 hover:bg-gray-50': page !== questions.current_page
              }"
              class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-md"
            >
              {{ page }}
            </button>
          </nav>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz soru yok</h3>
        <p class="mt-1 text-sm text-gray-500">Size yönlendirilen sorular burada görünecek.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import expertQuestionService, { type ExpertQuestion } from '@/services/expertQuestionService'
import { useToast } from '@/composables/useToast'

const { showToast } = useToast()

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

const answerForms = ref<Record<number, string>>({})
const answeringQuestions = ref<number[]>([])

const pendingCount = computed(() => 
  questions.value.data.filter(q => q.status === 'pending').length
)

const answeredCount = computed(() => 
  questions.value.data.filter(q => q.status === 'answered').length
)

const totalCount = computed(() => questions.value.data.length)

const loadQuestions = async (page = 1) => {
  loading.value = true
  try {
    questions.value = await expertQuestionService.getPendingQuestions(page)
  } catch (error) {
    console.error('Sorular yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const answerQuestion = async (questionId: number) => {
  const answer = answerForms.value[questionId]
  if (!answer?.trim()) return

  answeringQuestions.value.push(questionId)
  try {
    const response = await expertQuestionService.answerQuestion(questionId, { answer })
    showToast(response.message, 'success')
    answerForms.value[questionId] = ''
    await loadQuestions(questions.value.current_page)
  } catch (error: any) {
    showToast(error.response?.data?.message || 'Cevap gönderilirken hata oluştu', 'error')
  } finally {
    answeringQuestions.value = answeringQuestions.value.filter(id => id !== questionId)
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