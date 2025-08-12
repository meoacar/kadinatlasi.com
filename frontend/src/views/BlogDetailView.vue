<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Loading -->
    <div v-if="loading" class="flex justify-center items-center min-h-screen">
      <div class="animate-spin w-8 h-8 border-2 border-pink-500 border-t-transparent rounded-full"></div>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="max-w-4xl mx-auto px-4 py-16 text-center">
      <h1 class="text-2xl font-bold text-gray-900 mb-4">Yazı bulunamadı</h1>
      <button 
        @click="$router.push('/blog')"
        class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600"
      >
        Blog'a Dön
      </button>
    </div>

    <!-- Post -->
    <div v-else-if="post" class="max-w-4xl mx-auto px-4 py-8">
      <!-- Back button -->
      <button 
        @click="$router.push('/blog')"
        class="mb-6 text-pink-500 hover:text-pink-600 flex items-center gap-2"
      >
        ← Blog'a Dön
      </button>

      <!-- Post content -->
      <article class="bg-white rounded-lg border border-gray-200 p-8">
        <!-- Featured image -->
        <div v-if="post.featured_image" class="mb-6">
          <img 
            :src="post.featured_image" 
            :alt="post.title"
            class="w-full h-64 object-cover rounded-lg"
          >
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
          {{ post.title }}
        </h1>

        <!-- Meta -->
        <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b">
          <span>{{ formatDate(post.created_at) }}</span>
          <span v-if="post.category">{{ post.category.name }}</span>
          <span v-if="post.user">{{ post.user.name }}</span>
          <span v-if="post.views_count">{{ post.views_count }} görüntülenme</span>
        </div>

        <!-- Content -->
        <div class="prose max-w-none">
          <div v-html="post.content"></div>
        </div>

        <!-- Tags -->
        <div v-if="post.tags && post.tags.length" class="mt-8 pt-6 border-t">
          <div class="flex flex-wrap gap-2">
            <span 
              v-for="tag in post.tags" 
              :key="tag"
              class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm"
            >
              #{{ tag }}
            </span>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { blogService, type BlogPost } from '@/services/blogService'

const route = useRoute()

const post = ref<BlogPost | null>(null)
const loading = ref(true)
const error = ref(false)

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('tr-TR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const fetchPost = async () => {
  const id = Number(route.params.id)
  if (!id) {
    error.value = true
    loading.value = false
    return
  }

  const result = await blogService.getPost(id)
  if (result) {
    post.value = result
  } else {
    error.value = true
  }
  loading.value = false
}

onMounted(() => {
  fetchPost()
})
</script>

<style>
.prose {
  line-height: 1.7;
}

.prose p {
  margin-bottom: 1rem;
}

.prose h1, .prose h2, .prose h3 {
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: bold;
}

.prose h1 { font-size: 1.875rem; }
.prose h2 { font-size: 1.5rem; }
.prose h3 { font-size: 1.25rem; }

.prose ul, .prose ol {
  margin-bottom: 1rem;
  padding-left: 1.5rem;
}

.prose blockquote {
  border-left: 4px solid #ec4899;
  padding-left: 1rem;
  margin: 1.5rem 0;
  font-style: italic;
  color: #6b7280;
}
</style>