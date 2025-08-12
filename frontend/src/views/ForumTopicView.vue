<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div v-if="loading" class="text-center py-8">
      <div class="text-gray-500">Yükleniyor...</div>
    </div>

    <div v-else-if="topic" class="space-y-6">
      <!-- Topic Header -->
      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center gap-2 mb-4">
          <span
            v-if="topic.is_pinned"
            class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full"
          >
            📌 Sabitlenmiş
          </span>
          <span
            v-if="topic.is_locked"
            class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full"
          >
            🔒 Kilitli
          </span>
          <span
            class="px-2 py-1 text-xs rounded-full"
            :style="{ backgroundColor: topic.category?.color + '20', color: topic.category?.color }"
          >
            {{ topic.category?.name }}
          </span>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ topic.title }}</h1>

        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
          <div class="flex items-center">
            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
              <span class="text-primary-600 font-medium">
                {{ topic.user?.name?.charAt(0) }}
              </span>
            </div>
            <div>
              <div class="font-medium text-gray-900">{{ topic.user?.name }}</div>
              <div>{{ formatDate(topic.created_at) }}</div>
            </div>
          </div>
          
          <div class="text-right">
            <div>{{ topic.posts_count }} yanıt</div>
            <div>{{ topic.views_count }} görüntüleme</div>
          </div>
        </div>

        <div class="prose max-w-none">
          <p class="whitespace-pre-wrap">{{ topic.content }}</p>
        </div>
      </div>

      <!-- Posts -->
      <div v-if="topic.posts && topic.posts.length > 0" class="space-y-4">
        <h2 class="text-xl font-bold text-gray-900">Yanıtlar</h2>
        
        <div
          v-for="post in topic.posts"
          :key="post.id"
          class="bg-white rounded-lg border border-gray-200 p-6"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                <span class="text-primary-600 font-medium">
                  {{ post.user?.name?.charAt(0) }}
                </span>
              </div>
              <div>
                <div class="font-medium text-gray-900">{{ post.user?.name }}</div>
                <div class="text-sm text-gray-500">{{ formatDate(post.created_at) }}</div>
              </div>
            </div>
            
            <div v-if="authStore.user?.id === post.user_id" class="flex gap-2">
              <button
                @click="editPost(post)"
                class="text-sm text-blue-600 hover:text-blue-800"
              >
                Düzenle
              </button>
              <button
                @click="deletePost(post.id)"
                class="text-sm text-red-600 hover:text-red-800"
              >
                Sil
              </button>
            </div>
          </div>

          <div class="prose max-w-none">
            <p class="whitespace-pre-wrap">{{ post.content }}</p>
          </div>
        </div>
      </div>

      <!-- Reply Form -->
      <div v-if="authStore.isAuthenticated && !topic.is_locked" class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold mb-4">Yanıt Yaz</h3>
        
        <form @submit.prevent="submitReply" class="space-y-4">
          <textarea
            v-model="newReply"
            rows="4"
            placeholder="Yanıtınızı yazın..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
            required
          ></textarea>
          
          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="replyLoading || !newReply.trim()"
              class="btn-primary disabled:opacity-50"
            >
              {{ replyLoading ? 'Gönderiliyor...' : 'Yanıt Gönder' }}
            </button>
          </div>
        </form>
      </div>

      <div v-else-if="!authStore.isAuthenticated" class="bg-gray-50 rounded-lg p-6 text-center">
        <p class="text-gray-600 mb-4">Yanıt yazmak için giriş yapmalısınız</p>
        <RouterLink to="/login" class="btn-primary">
          Giriş Yap
        </RouterLink>
      </div>

      <div v-else-if="topic.is_locked" class="bg-red-50 rounded-lg p-6 text-center">
        <p class="text-red-600">Bu konu kilitli, yeni yanıt yazamazsınız</p>
      </div>

      <!-- Back Button -->
      <div class="pt-6">
        <RouterLink to="/forum" class="text-primary-500 hover:text-primary-600">
          ← Forum'a Dön
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const route = useRoute()
const authStore = useAuthStore()

const topic = ref<any>(null)
const loading = ref(false)
const newReply = ref('')
const replyLoading = ref(false)

const fetchTopic = async () => {
  loading.value = true
  try {
    const response = await api.get(`/forum/topics/${route.params.id}`)
    if (response.data.success) {
      topic.value = response.data.data
    }
  } catch (error) {
    console.error('Topic fetch error:', error)
  } finally {
    loading.value = false
  }
}

const submitReply = async () => {
  if (!newReply.value.trim()) return
  
  replyLoading.value = true
  try {
    const response = await api.post(`/forum/topics/${route.params.id}/posts`, {
      content: newReply.value
    })
    
    if (response.data.success) {
      newReply.value = ''
      await fetchTopic() // Refresh to get updated posts
    }
  } catch (error) {
    console.error('Reply submit error:', error)
  } finally {
    replyLoading.value = false
  }
}

const editPost = (post: any) => {
  // TODO: Implement edit functionality
  console.log('Edit post:', post)
}

const deletePost = async (postId: number) => {
  if (!confirm('Bu yanıtı silmek istediğinizden emin misiniz?')) return
  
  try {
    const response = await api.delete(`/forum/posts/${postId}`)
    if (response.data.success) {
      await fetchTopic() // Refresh to get updated posts
    }
  } catch (error) {
    console.error('Delete post error:', error)
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  fetchTopic()
})
</script>