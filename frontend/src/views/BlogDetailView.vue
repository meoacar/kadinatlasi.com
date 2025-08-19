<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%); padding: 0;">
    <!-- Loading -->
    <div v-if="loading" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
      <div style="width: 50px; height: 50px; border: 4px solid #ec4899; border-top: 4px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
    </div>

    <!-- Error -->
    <div v-else-if="error" style="max-width: 800px; margin: 0 auto; padding: 4rem 2rem; text-align: center;">
      <h1 style="font-size: 2rem; font-weight: 800; color: #1f2937; margin-bottom: 1.5rem;">📝 Yazı bulunamadı</h1>
      <button 
        @click="$router.push('/blog')"
        style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white; padding: 0.75rem 2rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; box-shadow: 0 10px 25px rgba(236, 72, 153, 0.3); transition: all 0.3s ease;"
        @mouseover="$event.target.style.transform = 'translateY(-2px)'; $event.target.style.boxShadow = '0 15px 35px rgba(236, 72, 153, 0.4)'"
        @mouseleave="$event.target.style.transform = 'translateY(0)'; $event.target.style.boxShadow = '0 10px 25px rgba(236, 72, 153, 0.3)'"
      >
        🏠 Blog'a Dön
      </button>
    </div>

    <!-- Post -->
    <div v-else-if="post" style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%); padding: 2rem 0;">
      <div style="max-width: 900px; margin: 0 auto; padding: 0 1rem;">
        <!-- Back button -->
        <button 
          @click="$router.push('/blog')"
          style="margin-bottom: 2rem; background: white; color: #ec4899; padding: 0.75rem 1.5rem; border: none; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;"
          @mouseover="$event.target.style.transform = 'translateY(-3px)'; $event.target.style.boxShadow = '0 15px 40px rgba(0,0,0,0.15)'"
          @mouseleave="$event.target.style.transform = 'translateY(0)'; $event.target.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)'"
        >
          ← 📚 Blog'a Dön
        </button>

        <!-- Post content -->
        <article style="background: white; border-radius: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 3rem; margin-bottom: 2rem; position: relative; overflow: hidden;">
          <!-- Decorative gradient overlay -->
          <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, #ec4899, #8b5cf6, #3b82f6, #10b981, #f59e0b, #ef4444); opacity: 0.8;"></div>
        <!-- Featured image -->
        <div v-if="post.featured_image" class="mb-6">
          <img 
            :src="post.featured_image" 
            :alt="post.title"
            class="w-full h-64 object-cover rounded-lg"
          >
        </div>

        <!-- Title -->
        <h1 style="font-size: 3rem; font-weight: 900; background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 50%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 2rem 0 1.5rem 0; line-height: 1.2; text-align: center;">
          {{ post.title }}
        </h1>

        <!-- Meta -->
        <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 1.5rem; margin: 2rem 0; padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 20px; border: 1px solid #e2e8f0;">
          <span style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-weight: 500;">
            📅 {{ formatDate(post.created_at) }}
          </span>
          <span v-if="post.category" style="display: flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 600;">
            💼 {{ post.category.name }}
          </span>
          <span v-if="post.user" style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-weight: 500;">
            ✍️ {{ post.user.name }}
          </span>
          <span v-if="post.views_count" style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-weight: 500;">
            👁️ {{ post.views_count }} görüntülenme
          </span>
        </div>

        <!-- Content -->
        <div style="font-size: 1.125rem; line-height: 1.8; color: #374151; margin-top: 2rem;">
          <div v-html="decodeHtml(post.content)" style="
            h1, h2, h3, h4, h5, h6: { 
              color: #1f2937; 
              font-weight: 700; 
              margin: 2rem 0 1rem 0;
            }
            h2: { 
              color: #ec4899; 
              font-size: 1.75rem; 
              border-bottom: 2px solid #ec4899; 
              padding-bottom: 0.5rem;
            }
            p: { 
              margin: 1.5rem 0; 
              color: #4b5563;
            }
            ul, ol: { 
              margin: 1.5rem 0; 
              padding-left: 2rem;
            }
            li: { 
              margin: 0.5rem 0; 
              color: #4b5563;
            }
            strong: { 
              color: #1f2937; 
              font-weight: 700;
            }
          "></div>
        </div>

        <!-- Tags -->
        <div v-if="post.tags && post.tags.length" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
          <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <span 
              v-for="tag in post.tags" 
              :key="tag"
              style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); color: #64748b; padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0;"
            >
              #{{ tag }}
            </span>
          </div>
        </div>

        <!-- Like and Share Section -->
        <div style="margin-top: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #fef7ff 0%, #fdf4ff 100%); border-radius: 20px; border: 1px solid #f3e8ff; display: flex; justify-content: center; align-items: center; gap: 1rem;">
          <!-- Like Button -->
          <button 
            @click="toggleLike"
            :disabled="likingPost"
            style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
            :style="isLiked ? 'background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white; box-shadow: 0 10px 25px rgba(236, 72, 153, 0.3);' : 'background: white; color: #ec4899; border: 2px solid #ec4899;'"
            @mouseover="!isLiked && ($event.target.style.background = 'linear-gradient(135deg, #ec4899 0%, #be185d 100%)', $event.target.style.color = 'white')"
            @mouseleave="!isLiked && ($event.target.style.background = 'white', $event.target.style.color = '#ec4899')"
          >
            <span>{{ isLiked ? '❤️' : '🤍' }}</span>
            <span>{{ likingPost ? 'Kaydediliyor...' : (isLiked ? 'Beğenildi' : 'Beğen') }}</span>
            <span v-if="post.likes_count > 0">({{ post.likes_count }})</span>
          </button>

          <!-- Share Button -->
          <button 
            @click="sharePost"
            style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);"
            @mouseover="$event.target.style.transform = 'translateY(-2px)'; $event.target.style.boxShadow = '0 15px 35px rgba(59, 130, 246, 0.4)'"
            @mouseleave="$event.target.style.transform = 'translateY(0)'; $event.target.style.boxShadow = '0 10px 25px rgba(59, 130, 246, 0.3)'"
          >
            <span>🔗</span>
            <span>Paylaş</span>
          </button>
        </div>
      </article>

      <!-- Comments Section -->
      <div style="background: white; border-radius: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 2rem; position: relative; overflow: hidden;">
        <!-- Decorative gradient overlay -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6, #ec4899); opacity: 0.8;"></div>
        
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 1rem 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
          💬 Yorumlar
          <span v-if="comments.length > 0" style="background: #ec4899; color: white; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.875rem;">
            {{ comments.length }}
          </span>
        </h3>

        <!-- Add Comment Form -->
        <div v-if="authStore.isAuthenticated" style="margin-bottom: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 16px; border: 1px solid #e2e8f0;">
          <div style="display: flex; align-items: flex-start; gap: 1rem;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem;">
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() || '👤' }}
            </div>
            <div style="flex: 1;">
              <textarea 
                v-model="newComment"
                :disabled="submittingComment"
                placeholder="Yorumunuzu yazın..."
                style="width: 100%; min-height: 100px; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; resize: vertical; transition: all 0.3s ease;"
                @focus="$event.target.style.borderColor = '#ec4899'; $event.target.style.outline = 'none'"
                @blur="$event.target.style.borderColor = '#e2e8f0'"
              ></textarea>
              <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #64748b; font-size: 0.875rem;">
                  {{ newComment.length }}/500 karakter
                </span>
                <button 
                  @click="submitComment"
                  :disabled="!newComment.trim() || newComment.length > 500 || submittingComment"
                  style="padding: 0.75rem 1.5rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                  :style="(!newComment.trim() || newComment.length > 500 || submittingComment) ? 'background: #e2e8f0; color: #94a3b8; cursor: not-allowed;' : 'background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white; box-shadow: 0 10px 25px rgba(236, 72, 153, 0.3);'"
                >
                  {{ submittingComment ? 'Gönderiliyor...' : 'Yorum Yap' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Login to comment -->
        <div v-else style="margin-bottom: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #fef7ff 0%, #fdf4ff 100%); border-radius: 16px; border: 1px solid #f3e8ff; text-align: center;">
          <p style="color: #64748b; margin-bottom: 1rem;">Yorum yapmak için giriş yapmalısınız.</p>
          <button 
            @click="$router.push('/login')"
            style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; box-shadow: 0 10px 25px rgba(236, 72, 153, 0.3); transition: all 0.3s ease;"
            @mouseover="$event.target.style.transform = 'translateY(-2px)'; $event.target.style.boxShadow = '0 15px 35px rgba(236, 72, 153, 0.4)'"
            @mouseleave="$event.target.style.transform = 'translateY(0)'; $event.target.style.boxShadow = '0 10px 25px rgba(236, 72, 153, 0.3)'"
          >
            🔐 Giriş Yap
          </button>
        </div>

        <!-- Comments List -->
        <div v-if="commentsLoading" style="text-align: center; padding: 2rem;">
          <div style="width: 40px; height: 40px; border: 3px solid #ec4899; border-top: 3px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
          <p style="color: #64748b; margin-top: 1rem;">Yorumlar yükleniyor...</p>
        </div>

        <div v-else-if="comments.length === 0" style="text-align: center; padding: 3rem; color: #64748b;">
          <div style="font-size: 3rem; margin-bottom: 1rem;">💭</div>
          <p style="font-size: 1.1rem;">Henüz yorum yapılmamış.</p>
          <p style="font-size: 0.9rem; margin-top: 0.5rem;">İlk yorumu siz yapın!</p>
        </div>

        <div v-else style="space-y: 1.5rem;">
          <div 
            v-for="comment in comments" 
            :key="comment.id"
            style="padding: 1.5rem; background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); border-radius: 16px; border: 1px solid #e5e5e5; margin-bottom: 1rem; transition: all 0.3s ease;"
            @mouseover="$event.target.style.transform = 'translateY(-2px)'; $event.target.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)'"
            @mouseleave="$event.target.style.transform = 'translateY(0)'; $event.target.style.boxShadow = 'none'"
          >
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
              <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                {{ comment.user?.name?.charAt(0)?.toUpperCase() || '👤' }}
              </div>
              <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                  <span style="font-weight: 600; color: #1f2937;">{{ comment.user?.name || 'Anonim' }}</span>
                  <span style="color: #64748b; font-size: 0.875rem;">{{ formatDate(comment.created_at) }}</span>
                </div>
                <p style="color: #4b5563; line-height: 1.6; margin: 0;">{{ comment.content }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { blogService, type BlogPost } from '@/services/blogService'
import { useAuthStore } from '@/stores/auth'
import { useSEO } from '@/composables/useSEO'
import api from '@/services/api'

const route = useRoute()
const authStore = useAuthStore()

const post = ref<BlogPost | null>(null)
const loading = ref(true)
const error = ref(false)

// Comments
const comments = ref([])
const commentsLoading = ref(false)
const newComment = ref('')
const submittingComment = ref(false)

// Likes
const isLiked = ref(false)
const likingPost = ref(false)

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('tr-TR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const decodeHtml = (html: string) => {
  const textarea = document.createElement('textarea')
  textarea.innerHTML = html
  return textarea.value
}

// Comments Functions
const fetchComments = async () => {
  if (!post.value) return
  
  commentsLoading.value = true
  try {
    const response = await api.get(`/blog-posts/${post.value.id}/comments`)
    if (response.data.success) {
      comments.value = response.data.data
    }
  } catch (error) {
    console.error('Yorumlar yüklenirken hata:', error)
  } finally {
    commentsLoading.value = false
  }
}

const submitComment = async () => {
  if (!newComment.value.trim() || !post.value) return
  
  submittingComment.value = true
  try {
    const response = await api.post(`/blog-posts/${post.value.id}/comments`, {
      content: newComment.value.trim()
    })
    
    if (response.data.success) {
      // Yorumu listeye ekle
      comments.value.unshift(response.data.data)
      newComment.value = ''
      // Success toast göster
      console.log('Yorum başarıyla eklendi!')
    }
  } catch (error) {
    console.error('Yorum eklenirken hata:', error)
    alert('Yorum eklenirken bir hata oluştu.')
  } finally {
    submittingComment.value = false
  }
}

// Like Functions
const checkIfLiked = async () => {
  if (!post.value || !authStore.isAuthenticated) return
  
  try {
    const response = await api.get(`/blog-posts/${post.value.id}/like-status`)
    if (response.data.success) {
      isLiked.value = response.data.isLiked
    }
  } catch (error) {
    console.error('Beğeni durumu kontrol edilirken hata:', error)
  }
}

const toggleLike = async () => {
  if (!post.value || !authStore.isAuthenticated) {
    alert('Beğenmek için giriş yapmalısınız.')
    return
  }
  
  likingPost.value = true
  try {
    const response = await api.post(`/blog-posts/${post.value.id}/toggle-like`)
    
    if (response.data.success) {
      isLiked.value = response.data.isLiked
      // Beğeni sayısını güncelle
      if (post.value) {
        post.value.likes_count = response.data.likesCount
      }
    }
  } catch (error) {
    console.error('Beğeni işlemi sırasında hata:', error)
    alert('Beğeni işlemi sırasında bir hata oluştu.')
  } finally {
    likingPost.value = false
  }
}

const sharePost = async () => {
  if (!post.value) return
  
  const url = window.location.href
  const title = post.value.title
  
  if (navigator.share) {
    try {
      await navigator.share({
        title: title,
        url: url
      })
    } catch (error) {
      console.log('Paylaşım iptal edildi')
    }
  } else {
    // Fallback: URL'yi kopyala
    try {
      await navigator.clipboard.writeText(url)
      alert('Link kopyalandı!')
    } catch (error) {
      // Manuel seçim için fallback
      window.prompt('Bu linki kopyalayın:', url)
    }
  }
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
    
    // SEO Meta Tags
    useSEO({
      title: `${result.title} | KadınAtlası Blog`,
      description: result.excerpt || result.content?.replace(/<[^>]*>/g, '').substring(0, 160) || 'KadınAtlası Blog yazısı',
      keywords: result.tags?.join(', ') || 'kadın, sağlık, yaşam, blog',
      type: 'article',
      url: `https://kadinatlasi.com/blog/${result.id}`,
      image: result.featured_image || 'https://kadinatlasi.com/logo.png',
      author: result.user?.name || 'KadınAtlası',
      publishedTime: result.published_at,
      modifiedTime: result.updated_at,
      section: result.category?.name || 'Blog',
      tags: result.tags || []
    })
    
    // Yorumları ve beğeni durumunu yükle
    await Promise.all([
      fetchComments(),
      checkIfLiked()
    ])
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
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes fadeIn {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}

.prose {
  line-height: 1.7;
}

.prose p {
  margin-bottom: 1rem;
  color: #4b5563;
  line-height: 1.8;
}

.prose h1, .prose h2, .prose h3 {
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: bold;
}

.prose h1 { 
  font-size: 1.875rem; 
  color: #1f2937;
}

.prose h2 { 
  font-size: 1.5rem; 
  color: #ec4899;
  border-bottom: 2px solid #ec4899;
  padding-bottom: 0.5rem;
}

.prose h3 { 
  font-size: 1.25rem; 
  color: #1f2937;
}

.prose ul, .prose ol {
  margin-bottom: 1rem;
  padding-left: 1.5rem;
}

.prose li {
  color: #4b5563;
  margin: 0.5rem 0;
}

.prose strong {
  color: #1f2937;
  font-weight: 700;
}

.prose blockquote {
  border-left: 4px solid #ec4899;
  padding: 1rem 1rem 1rem 2rem;
  margin: 1.5rem 0;
  font-style: italic;
  color: #6b7280;
  background: linear-gradient(135deg, #fef7ff 0%, #fdf4ff 100%);
  border-radius: 0 12px 12px 0;
}

/* Mobile responsive */
@media (max-width: 768px) {
  h1 {
    font-size: 2rem !important;
  }
}
</style>