<template>
  <div class="page-view">
    <div v-if="loading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Sayfa yükleniyor...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <div class="error-icon">⚠️</div>
      <h2>Sayfa Bulunamadı</h2>
      <p>{{ error }}</p>
      <RouterLink to="/" class="back-home-btn">Ana Sayfaya Dön</RouterLink>
    </div>

    <div v-else-if="page" class="page-content">
      <div class="page-header">
        <h1 class="page-title">{{ page.title }}</h1>
        <p v-if="page.excerpt" class="page-excerpt">{{ page.excerpt }}</p>
      </div>

      <div class="page-body" v-html="page.content"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const route = useRoute()
const page = ref(null)
const loading = ref(true)
const error = ref('')

const loadPage = async () => {
  try {
    loading.value = true
    const slug = route.params.slug as string
    const response = await api.get(`/pages/${slug}`)
    
    if (response.data.success) {
      page.value = response.data.data
      
      // Set page title
      if (page.value.meta_title) {
        document.title = page.value.meta_title
      } else {
        document.title = `${page.value.title} - KadınAtlası.com`
      }
      
      // Set meta description
      if (page.value.meta_description) {
        const metaDesc = document.querySelector('meta[name="description"]')
        if (metaDesc) {
          metaDesc.setAttribute('content', page.value.meta_description)
        }
      }
    } else {
      error.value = response.data.message
    }
  } catch (err) {
    error.value = 'Sayfa yüklenirken bir hata oluştu'
    console.error('Page loading error:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadPage()
})
</script>

<style scoped>
.page-view {
  min-height: 80vh;
  padding: 2rem 0;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  gap: 1rem;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f4f6;
  border-top: 4px solid #ec4899;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  text-align: center;
  gap: 1rem;
}

.error-icon {
  font-size: 4rem;
}

.error-container h2 {
  color: #dc2626;
  font-size: 2rem;
  margin: 0;
}

.error-container p {
  color: #6b7280;
  font-size: 1.1rem;
  margin: 0;
}

.back-home-btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  margin-top: 1rem;
}

.back-home-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.4);
}

.page-content {
  max-width: 800px;
  margin: 0 auto;
  padding: 0 1rem;
}

.page-header {
  text-align: center;
  margin-bottom: 3rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #e5e7eb;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.page-excerpt {
  font-size: 1.2rem;
  color: #6b7280;
  line-height: 1.6;
  margin: 0;
}

.page-body {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #374151;
}

/* HTML content styling */
.page-body :deep(h1) {
  font-size: 2.2rem;
  font-weight: 800;
  color: #1f2937;
  margin: 2.5rem 0 1.5rem 0;
  padding-bottom: 0.75rem;
  border-bottom: 3px solid #ec4899;
}

.page-body :deep(h2) {
  font-size: 1.8rem;
  font-weight: 700;
  color: #1f2937;
  margin: 2rem 0 1rem 0;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #ec4899;
}

.page-body :deep(h3) {
  font-size: 1.4rem;
  font-weight: 600;
  color: #374151;
  margin: 1.5rem 0 0.75rem 0;
}

.page-body :deep(h4) {
  font-size: 1.2rem;
  font-weight: 600;
  color: #4b5563;
  margin: 1.25rem 0 0.5rem 0;
}

.page-body :deep(h5) {
  font-size: 1.1rem;
  font-weight: 600;
  color: #6b7280;
  margin: 1rem 0 0.5rem 0;
}

.page-body :deep(p) {
  margin-bottom: 1.5rem;
}

.page-body :deep(ul), .page-body :deep(ol) {
  margin: 1.5rem 0;
  padding-left: 2rem;
}

.page-body :deep(li) {
  margin-bottom: 0.5rem;
}

.page-body :deep(strong) {
  color: #ec4899;
  font-weight: 600;
}

.page-body :deep(a) {
  color: #ec4899;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.page-body :deep(a:hover) {
  color: #be185d;
  text-decoration: underline;
}

.page-body :deep(blockquote) {
  border-left: 4px solid #ec4899;
  padding-left: 1.5rem;
  margin: 1.5rem 0;
  font-style: italic;
  color: #6b7280;
  background: #f9fafb;
  padding: 1rem 1.5rem;
  border-radius: 0 8px 8px 0;
}

.page-body :deep(code) {
  background: #f3f4f6;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-family: 'Courier New', monospace;
  font-size: 0.9em;
  color: #ec4899;
}

.page-body :deep(pre) {
  background: #1f2937;
  color: #f9fafb;
  padding: 1.5rem;
  border-radius: 8px;
  overflow-x: auto;
  margin: 1.5rem 0;
}

.page-body :deep(pre code) {
  background: transparent;
  color: inherit;
  padding: 0;
}

@media (max-width: 768px) {
  .page-content {
    padding: 0 1rem;
  }
  
  .page-title {
    font-size: 2rem;
  }
  
  .page-excerpt {
    font-size: 1.1rem;
  }
  
  .page-body {
    font-size: 1rem;
  }
  
  .page-body :deep(h2) {
    font-size: 1.5rem;
  }
  
  .page-body :deep(h3) {
    font-size: 1.2rem;
  }
}
</style>