<template>
  <div class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Doğum Sonrası Rehber</h1>
        <p class="text-gray-600">Doğum sonrası süreçte size rehberlik edecek bilgiler</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <div v-for="guide in guides" :key="guide.id" 
             class="bg-white rounded-2xl shadow-lg p-6 cursor-pointer hover:shadow-xl transition-shadow"
             @click="selectedGuide = guide">
          <div class="text-2xl mb-4">👶</div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">{{ guide.title }}</h3>
          <p class="text-pink-600 font-medium mb-3">{{ guide.period }}</p>
          <p class="text-gray-600 text-sm">{{ guide.content }}</p>
        </div>
      </div>

      <div v-if="selectedGuide" class="mt-8 bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ selectedGuide.title }}</h2>
        <div class="space-y-4">
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Öneriler</h3>
            <ul class="space-y-2">
              <li v-for="tip in selectedGuide.tips" :key="tip" class="flex items-center">
                <span class="text-green-500 mr-2">✓</span>
                {{ tip }}
              </li>
            </ul>
          </div>
          <div v-if="selectedGuide.warnings">
            <h3 class="text-lg font-semibold text-red-600 mb-2">Dikkat Edilmesi Gerekenler</h3>
            <ul class="space-y-2">
              <li v-for="warning in selectedGuide.warnings" :key="warning" class="flex items-center">
                <span class="text-red-500 mr-2">⚠️</span>
                {{ warning }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const guides = ref([])
const selectedGuide = ref(null)

onMounted(async () => {
  try {
    const response = await api.get('/postpartum/guides')
    guides.value = response.data.data
  } catch (error) {
    console.error('Rehber yüklenemedi:', error)
  }
})
</script>