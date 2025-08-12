<template>
  <div>
    <!-- Breadcrumb -->
    <nav style="background: white; border-bottom: 1px solid #e5e7eb; padding: 12px 0;">
      <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; color: #6b7280;">
          <router-link to="/" style="color: #e57399; text-decoration: none;">Ana Sayfa</router-link>
          <span>›</span>
          <span style="color: #111827; font-weight: 500;">Blog</span>
        </div>
      </div>
    </nav>

    <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%);">
      <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        
        <!-- Hero Section -->
        <header style="text-align: center; margin-bottom: 48px;">
          <h1 style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 16px;">
            📝 Blog & İçerikler
          </h1>
          <p style="font-size: 1.25rem; color: #6b7280; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Kadın sağlığı, güzellik ve yaşam tarzından ilham verici yazılar
          </p>
        </header>

        <!-- Stats Section -->
        <section style="margin-bottom: 48px;">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.totalPosts.toLocaleString() }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Toplam Yazı</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">+{{ stats.todayPosts }} bugün</div>
            </div>
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.totalViews.toLocaleString() }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Toplam Okuma</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">+{{ stats.todayViews }} bugün</div>
            </div>
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.totalCategories }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Kategori</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">Çeşitli konular</div>
            </div>
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.expertPosts.toLocaleString() }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Uzman Yazısı</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">Doktor & Uzmanlar</div>
            </div>
          </div>
        </section>

        <!-- Blog Posts -->
        <div class="blog-grid" style="display: grid; grid-template-columns: 1fr 300px; gap: 32px;">
          <!-- Main Content -->
          <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
              <h2 style="font-size: 2rem; font-weight: 700; color: #111827;">
                🔥 Son Yazılar
              </h2>
              <div style="display: flex; gap: 8px;">
                <button v-for="filter in filters" :key="filter.key"
                        @click="activeFilter = filter.key"
                        :style="`padding: 8px 16px; border-radius: 20px; border: none; cursor: pointer; font-weight: 500; transition: all 0.2s; ${activeFilter === filter.key ? 'background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white;' : 'background: white; color: #6b7280; border: 1px solid #e5e7eb;'}`">
                  {{ filter.label }}
                </button>
              </div>
            </div>
            
            <!-- Blog Posts List -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #f3f4f6;">
              <div v-if="filteredPosts.length" style="divide-y: 1px solid #f3f4f6;">
                <article v-for="post in filteredPosts" :key="post.id"
                         @click="viewPost(post)"
                         style="padding: 24px; cursor: pointer; transition: all 0.2s; position: relative;"
                         @mouseover="$event.currentTarget.style.backgroundColor = '#fafafa'"
                         @mouseleave="$event.currentTarget.style.backgroundColor = 'white'">
                  
                  <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                    <div style="flex: 1;">
                      <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div v-if="post.featured_image" style="width: 80px; height: 60px; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                          <img :src="post.featured_image" :alt="post.title" style="width: 100%; height: 100%; object-cover;">
                        </div>
                        <div v-if="post.isPinned" style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                          📌 Öne Çıkan
                        </div>
                        <div v-if="post.isPopular" style="background: #fee2e2; color: #dc2626; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                          🔥 Popüler
                        </div>
                        <div v-if="post.isExpert" style="background: #dcfce7; color: #16a34a; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                          ✅ Uzman Yazısı
                        </div>
                        <PremiumBadge 
                          v-if="post.is_premium" 
                          :isPremium="true" 
                          :premiumType="post.premium_type" 
                          size="small" 
                        />
                      </div>
                      
                      <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px; line-height: 1.4;">
                        {{ post.title }}
                      </h3>
                      
                      <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 16px; line-height: 1.5;">
                        {{ post.excerpt || (post.content && post.content.substring(0, 150) + '...') }}
                      </p>
                      
                      <div style="display: flex; align-items: center; gap: 20px; font-size: 0.875rem; color: #9ca3af;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                          <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #e57399, #be185d); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem;">
                            {{ (post.user?.name || 'A').charAt(0) }}
                          </div>
                          <span style="font-weight: 500; color: #374151;">{{ post.user?.name || 'Admin' }}</span>
                          <span v-if="post.user?.isExpert" style="background: #dbeafe; color: #1d4ed8; padding: 2px 6px; border-radius: 8px; font-size: 0.75rem; font-weight: 500;">
                            Uzman
                          </span>
                        </div>
                        <span>{{ formatDate(post.created_at || post.published_at) }}</span>
                        <span>{{ post.views_count || 0 }} görüntüleme</span>
                        <span>{{ post.likes_count || 0 }} beğeni</span>
                      </div>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; align-items: end; gap: 8px;">
                      <div style="background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 100%); color: #be185d; padding: 6px 12px; border-radius: 16px; font-size: 0.75rem; font-weight: 600;">
                        {{ post.category?.name || 'Genel' }}
                      </div>
                      <div v-if="post.tags && post.tags.length" style="display: flex; flex-wrap: wrap; gap: 4px; justify-content: end;">
                        <span v-for="tag in post.tags.slice(0, 2)" :key="tag" 
                              style="background: #f3f4f6; color: #6b7280; padding: 2px 6px; border-radius: 8px; font-size: 0.75rem;">
                          #{{ tag }}
                        </span>
                      </div>
                    </div>
                  </div>
                </article>
              </div>

              <div v-if="loading" style="padding: 60px; text-align: center;">
                <div style="width: 40px; height: 40px; border: 3px solid #f3f4f6; border-top: 3px solid #e57399; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                <p style="margin-top: 16px; color: #6b7280; font-weight: 500;">Yazılar yükleniyor...</p>
              </div>

              <div v-if="!loading && filteredPosts.length === 0" style="padding: 60px; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 16px;">📝</div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Henüz yazı yok</h3>
                <p style="color: #6b7280; margin-bottom: 24px;">Bu kategoride henüz yazı bulunmuyor.</p>
              </div>
            </div>
          </div>
          
          <!-- Sidebar -->
          <div class="sidebar" style="display: flex; flex-direction: column; gap: 24px;">
            <Advertisement position="sidebar" type="sidebar" :maxAds="2" />
            
            <!-- Popular Posts Widget -->
            <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 20px;">
                🔥 Popüler Yazılar
              </h3>
              <div style="display: flex; flex-direction: column; gap: 16px;">
                <div v-for="post in posts.slice(0, 5)" :key="'popular-' + post.id" 
                     @click="viewPost(post)"
                     style="display: flex; gap: 12px; cursor: pointer; padding: 12px; border-radius: 8px; transition: background 0.2s;"
                     @mouseover="$event.currentTarget.style.backgroundColor = '#f9fafb'"
                     @mouseleave="$event.currentTarget.style.backgroundColor = 'transparent'">
                  <img v-if="post.featured_image" 
                       :src="post.featured_image" 
                       :alt="post.title"
                       style="width: 60px; height: 60px; object-cover; border-radius: 8px; flex-shrink: 0;">
                  <div style="flex: 1; min-width: 0;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 4px; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                      {{ post.title }}
                    </h4>
                    <div style="font-size: 0.75rem; color: #6b7280;">
                      {{ post.views_count || 0 }} görüntüleme
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Categories Widget -->
            <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 20px;">
                📚 Kategoriler
              </h3>
              <div style="display: flex; flex-direction: column; gap: 8px;">
                <button v-for="category in categories" :key="category.id"
                        @click="selectCategory(category)"
                        style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border: none; background: transparent; cursor: pointer; border-radius: 8px; transition: background 0.2s; text-align: left;"
                        @mouseover="$event.currentTarget.style.backgroundColor = '#f9fafb'"
                        @mouseleave="$event.currentTarget.style.backgroundColor = 'transparent'">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.2rem;">{{ category.icon }}</span>
                    <span style="font-size: 0.875rem; font-weight: 500; color: #374151;">{{ category.name }}</span>
                  </div>
                  <span style="font-size: 0.75rem; color: #6b7280; background: #f3f4f6; padding: 2px 6px; border-radius: 8px;">
                    {{ category.postsCount }}
                  </span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import Advertisement from '@/components/Advertisement.vue'
import PremiumBadge from '@/components/PremiumBadge.vue'
import { useSEO } from '@/composables/useSEO'

useSEO({
  title: 'Blog - Kadın Sağlığı ve Yaşam Rehberi | KadınAtlası.com',
  description: 'Kadın sağlığı, güzellik, gebelik, diyet ve yaşam hakkında uzman yazıları ve tavsiyeleri.',
  keywords: 'kadın blogu, sağlık yazıları, gebelik, güzellik, diyet, yaşam tarzı, uzman tavsiyeleri',
  type: 'website'
})

const router = useRouter()

const loading = ref(false)
const activeFilter = ref('recent')
const selectedCategoryId = ref<number | null>(null)

const categories = ref([
  {
    id: 1,
    name: 'Sağlık',
    icon: '🏥',
    postsCount: 245
  },
  {
    id: 2,
    name: 'Gebelik & Anne',
    icon: '🤱',
    postsCount: 189
  },
  {
    id: 3,
    name: 'Güzellik & Bakım',
    icon: '💄',
    postsCount: 156
  },
  {
    id: 4,
    name: 'Moda & Stil',
    icon: '👗',
    postsCount: 134
  },
  {
    id: 5,
    name: 'Astroloji',
    icon: '⭐',
    postsCount: 98
  },
  {
    id: 6,
    name: 'Diyet & Fitness',
    icon: '💪',
    postsCount: 167
  }
])

const stats = ref({
  totalPosts: 1247,
  todayPosts: 12,
  totalViews: 45892,
  todayViews: 234,
  totalCategories: 8,
  expertPosts: 156
})

const posts = ref([
  {
    id: 1,
    title: 'Hamilelikte Beslenme: İlk 3 Ay Rehberi',
    excerpt: 'Hamileliğin ilk üç ayında bebeğinizin sağlıklı gelişimi için hangi besinleri tüketmelisiniz? Uzman doktorumuzdan öneriler...',
    content: 'Hamileliğin ilk üç ayı hem anne hem de bebek için kritik bir dönemdir. Bu dönemde doğru beslenme alışkanlıkları edinmek...',
    featured_image: 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400',
    created_at: '2024-01-15T10:30:00Z',
    views_count: 2847,
    likes_count: 156,
    tags: ['hamilelik', 'beslenme', 'sağlık'],
    category: { id: 2, name: 'Gebelik & Anne' },
    user: { name: 'Dr. Ayşe Kaya', isExpert: true },
    isPinned: true,
    isPopular: true,
    isExpert: true
  },
  {
    id: 2,
    title: 'Cilt Bakımında Doğal Yöntemler',
    excerpt: 'Kimyasal ürünler yerine doğal malzemelerle cilt bakımı nasıl yapılır? Evde hazırlayabileceğiniz maskeler ve serumlar...',
    content: 'Cilt bakımında doğal yöntemler son yıllarda oldukça popüler hale geldi. Evde kolayca hazırlayabileceğiniz...',
    featured_image: 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400',
    created_at: '2024-01-15T09:15:00Z',
    views_count: 1923,
    likes_count: 89,
    tags: ['cilt bakımı', 'doğal', 'güzellik'],
    category: { id: 3, name: 'Güzellik & Bakım' },
    user: { name: 'Elif Güzel', isExpert: false },
    isPinned: false,
    isPopular: true,
    isExpert: false
  }
])

const filters = ref([
  { key: 'recent', label: '🕒 En Yeni' },
  { key: 'popular', label: '👑 Popüler' },
  { key: 'trending', label: '🔥 Trend' },
  { key: 'expert', label: '👩⚕️ Uzman' }
])

const filteredPosts = computed(() => {
  let filtered = [...posts.value]
  
  switch (activeFilter.value) {
    case 'recent':
      return filtered.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())
    case 'popular':
      return filtered.sort((a, b) => (b.views_count || 0) - (a.views_count || 0))
    case 'trending':
      return filtered.filter(post => post.isPopular).sort((a, b) => (b.views_count || 0) - (a.views_count || 0))
    case 'expert':
      return filtered.filter(post => post.isExpert)
    default:
      return filtered
  }
})

const formatDate = (dateString: string) => {
  if (!dateString) return ''
  
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return 'Az önce'
  if (diffInHours < 24) return `${diffInHours} saat önce`
  if (diffInHours < 48) return 'Dün'
  
  return date.toLocaleDateString('tr-TR', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const viewPost = (post: any) => {
  router.push(`/blog/${post.id}`)
}

const selectCategory = (category: any) => {
  selectedCategoryId.value = category.id
}

onMounted(async () => {
  loading.value = true
  
  try {
    const response = await api.get('/blog-posts')
    if (response.data.success && response.data.data) {
      const apiData = response.data.data.data || response.data.data
      if (apiData && apiData.length > 0) {
        posts.value = apiData.map(post => ({
          ...post,
          tags: post.tags || [],
          isPinned: post.is_featured || false,
          isPopular: (post.views_count || 0) > 1000,
          isExpert: post.user?.is_expert || false
        }))
      }
    }
  } catch (error) {
    console.log('API hatası, örnek veriler kullanılıyor:', error)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 1024px) {
  .blog-grid {
    grid-template-columns: 1fr !important;
  }
}

@media (max-width: 768px) {
  .sidebar {
    display: none !important;
  }
}
</style>