<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-4">Debug Bilgileri</h1>
    
    <div class="space-y-4">
      <div class="bg-white p-4 rounded-lg shadow">
        <h2 class="font-bold">Auth Store</h2>
        <pre>{{ JSON.stringify(authStore, null, 2) }}</pre>
      </div>
      
      <div class="bg-white p-4 rounded-lg shadow">
        <h2 class="font-bold">Local Storage Token</h2>
        <pre>{{ token }}</pre>
      </div>
      
      <div class="bg-white p-4 rounded-lg shadow">
        <h2 class="font-bold">API Test</h2>
        <button @click="testAPI" class="bg-blue-500 text-white px-4 py-2 rounded">Test Profile API</button>
        <pre v-if="apiResult">{{ JSON.stringify(apiResult, null, 2) }}</pre>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const authStore = useAuthStore()
const token = ref('')
const apiResult = ref(null)

const testAPI = async () => {
  try {
    const response = await api.get('/profile')
    apiResult.value = response.data
  } catch (error) {
    apiResult.value = { error: error.message, response: error.response?.data }
  }
}

onMounted(() => {
  token.value = localStorage.getItem('auth_token') || 'No token found'
})
</script>