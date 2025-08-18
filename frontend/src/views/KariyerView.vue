<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 2rem 0;">
    <!-- Hero Section -->
    <div style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%); color: white; padding: 4rem 0; margin-bottom: 3rem;">
      <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center;">
        <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1.5rem; color: white;">💼 Kariyer & Girişimcilik</h1>
        <p style="font-size: 1.3rem; max-width: 800px; margin: 0 auto; opacity: 0.95;">İş hayatında başarılı olmak, kariyer hedeflerinize ulaşmak ve girişimcilik yolculuğunuzda size rehberlik edecek içerikler</p>
      </div>
    </div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
      <!-- Quick Actions -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
        <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 6px solid #3b82f6; transition: all 0.3s ease;">
          <div style="font-size: 4rem; margin-bottom: 1.5rem;">📝</div>
          <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">CV Hazırlama</h3>
          <p style="color: #6b7280; margin-bottom: 1.5rem; line-height: 1.6;">Profesyonel CV örnekleri ve hazırlama ipuçları</p>
          <button @click="$router.push('/cv-hazirlama')" style="background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 10px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Başla</button>
        </div>
        
        <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 6px solid #8b5cf6; transition: all 0.3s ease;">
          <div style="font-size: 4rem; margin-bottom: 1.5rem;">🚀</div>
          <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Girişimcilik Rehberi</h3>
          <p style="color: #6b7280; margin-bottom: 1.5rem; line-height: 1.6;">İş kurma süreçleri ve girişimcilik tavsiyeleri</p>
          <button @click="$router.push('/blog')" style="background: #8b5cf6; color: white; padding: 0.75rem 1.5rem; border-radius: 10px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Keşfet</button>
        </div>
        
        <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 6px solid #ec4899; transition: all 0.3s ease;">
          <div style="font-size: 4rem; margin-bottom: 1.5rem;">💡</div>
          <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Kariyer Danışmanlığı</h3>
          <p style="color: #6b7280; margin-bottom: 1.5rem; line-height: 1.6;">Uzmanlardan kariyer tavsiyeleri alın</p>
          <button @click="$router.push('/uzman-soru-sor')" style="background: #ec4899; color: white; padding: 0.75rem 1.5rem; border-radius: 10px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Danış</button>
        </div>
      </div>

      <!-- Content Sections -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 4rem;">
        <!-- Blog Posts -->
        <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
          <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem; color: #1f2937; display: flex; align-items: center;">
            <span style="font-size: 2.5rem; margin-right: 1rem;">📚</span>
            Son Yazılar
          </h2>
          <div v-if="loading" style="text-align: center; padding: 3rem 0;">
            <div style="width: 3rem; height: 3rem; border: 3px solid #f3f4f6; border-top: 3px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
          </div>
          <div v-else-if="posts.length === 0" style="text-align: center; padding: 3rem 0; color: #6b7280;">
            Henüz bu kategoride yazı bulunmuyor.
          </div>
          <div v-else style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div v-for="post in posts" :key="post.id" 
                 style="border-left: 4px solid #e5e7eb; padding-left: 1.5rem; cursor: pointer; transition: all 0.3s ease;"
                 @click="$router.push(`/blog/${post.id}`)">
              <h3 style="font-weight: 600; font-size: 1.2rem; margin-bottom: 0.5rem; color: #1f2937;">{{ post.title }}</h3>
              <p style="color: #6b7280; margin-bottom: 0.5rem; line-height: 1.5;">{{ post.excerpt }}</p>
              <div style="font-size: 0.875rem; color: #9ca3af;">{{ formatDate(post.created_at) }}</div>
            </div>
          </div>
          <div style="text-center; margin-top: 2rem;">
            <router-link to="/blog" style="color: #3b82f6; font-weight: 600; text-decoration: none;">
              Tüm Yazıları Gör →
            </router-link>
          </div>
        </div>

        <!-- Career Tips -->
        <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
          <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem; color: #1f2937; display: flex; align-items: center;">
            <span style="font-size: 2.5rem; margin-right: 1rem;">🎯</span>
            Kariyer İpuçları
          </h2>
          <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div v-for="tip in careerTips" :key="tip.id" 
                 style="padding: 1.5rem; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-radius: 12px; border-left: 4px solid #3b82f6;">
              <h4 style="font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">{{ tip.title }}</h4>
              <p style="color: #6b7280; line-height: 1.5;">{{ tip.description }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Stories -->
      <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem; text-align: center; color: #1f2937; display: flex; align-items: center; justify-content: center;">
          <span style="font-size: 2.5rem; margin-right: 1rem;">🌟</span>
          Başarı Hikayeleri
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
          <div v-for="story in successStories" :key="story.id" 
               style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 16px;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">{{ story.avatar }}</div>
            <h3 style="font-weight: 700; font-size: 1.2rem; margin-bottom: 0.5rem; color: #1f2937;">{{ story.name }}</h3>
            <p style="color: #3b82f6; font-weight: 600; margin-bottom: 1rem;">{{ story.title }}</p>
            <p style="color: #6b7280; line-height: 1.5;">{{ story.story }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const loading = ref(true)
const posts = ref([])

const careerTips = ref([
  {
    id: 1,
    title: "Ağ Kurmanın Önemi",
    description: "Profesyonel ağınızı genişletmek kariyer fırsatlarınızı artırır."
  },
  {
    id: 2,
    title: "Sürekli Öğrenme",
    description: "Sektörünüzdeki yenilikleri takip edin ve kendinizi geliştirin."
  },
  {
    id: 3,
    title: "Hedef Belirleme",
    description: "Kısa ve uzun vadeli kariyer hedeflerinizi net bir şekilde belirleyin."
  },
  {
    id: 4,
    title: "Kişisel Marka",
    description: "Profesyonel kimliğinizi oluşturun ve sosyal medyada aktif olun."
  }
])

const successStories = ref([
  {
    id: 1,
    name: "Ayşe Demir",
    title: "Teknoloji Girişimcisi",
    avatar: "👩‍💻",
    story: "Yazılım geliştirme alanında kurduğu startup ile 50+ kişilik ekip oluşturdu."
  },
  {
    id: 2,
    name: "Zeynep Kaya",
    title: "Pazarlama Direktörü",
    description: "Uluslararası şirkette kariyer basamaklarını hızla tırmandı.",
    avatar: "👩‍💼",
    story: "10 yılda asistan pozisyonundan direktörlüğe yükseldi."
  },
  {
    id: 3,
    name: "Elif Özkan",
    title: "E-ticaret Girişimcisi",
    avatar: "🛍️",
    story: "Ev hanımıyken başladığı online mağazası bugün milyonluk ciro yapıyor."
  }
])

const loadPosts = async () => {
  try {
    const response = await api.get('/blog-posts', {
      params: { category: 'kariyer-girisimcilik', limit: 5 }
    })
    if (response.data.success) {
      posts.value = response.data.data
    }
  } catch (error) {
    console.error('Blog yazıları yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(() => {
  loadPosts()
})
</script>

<style>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  div[style*="grid-template-columns: 1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>