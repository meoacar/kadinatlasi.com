<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fce7f3 0%, #f9fafb 100%); display: flex; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 48px; width: 100%; max-width: 400px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: bold; color: #111827; margin-bottom: 8px;">Giriş Yap</h1>
        <p style="color: #6b7280;">Hesabınıza giriş yapın</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleLogin" style="display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Email -->
        <div>
          <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">
            E-posta
          </label>
          <input
            v-model="form.email"
            type="email"
            required
            style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s, box-shadow 0.2s;"
            placeholder="ornek@email.com"
            @focus="$event.target.style.borderColor = '#ec4899'; $event.target.style.boxShadow = '0 0 0 3px rgba(236, 72, 153, 0.1)'"
            @blur="$event.target.style.borderColor = '#d1d5db'; $event.target.style.boxShadow = 'none'"
          >
        </div>

        <!-- Password -->
        <div>
          <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">
            Şifre
          </label>
          <input
            v-model="form.password"
            type="password"
            required
            style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s, box-shadow 0.2s;"
            placeholder="••••••••"
            @focus="$event.target.style.borderColor = '#ec4899'; $event.target.style.boxShadow = '0 0 0 3px rgba(236, 72, 153, 0.1)'"
            @blur="$event.target.style.borderColor = '#d1d5db'; $event.target.style.boxShadow = 'none'"
          >
        </div>

        <!-- Remember & Forgot -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <input v-model="form.remember" type="checkbox" style="accent-color: #ec4899;">
            <span style="font-size: 0.875rem; color: #374151;">Beni hatırla</span>
          </label>
          <a href="#" style="font-size: 0.875rem; color: #ec4899; text-decoration: none;">
            Şifremi unuttum
          </a>
        </div>

        <!-- Error Message -->
        <div v-if="error" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; font-size: 0.875rem;">
          {{ error }}
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="loading"
          style="width: 100%; background: #ec4899; color: white; padding: 12px; border-radius: 8px; font-size: 1rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;"
          :style="{ opacity: loading ? 0.7 : 1 }"
          @mouseover="handleButtonHover($event, true)"
          @mouseleave="handleButtonHover($event, false)"
        >
          <div v-if="loading" style="width: 16px; height: 16px; border: 2px solid transparent; border-top: 2px solid white; border-radius: 50%; animation: spin 1s linear infinite;"></div>
          {{ loading ? 'Giriş yapılıyor...' : 'Giriş Yap' }}
        </button>

      </form>

      <!-- Divider -->
      <div style="margin: 32px 0; position: relative; text-align: center;">
        <div style="position: absolute; inset: 0; display: flex; align-items: center;">
          <div style="width: 100%; border-top: 1px solid #e5e7eb;"></div>
        </div>
        <span style="background: white; padding: 0 16px; color: #6b7280; font-size: 0.875rem;">veya</span>
      </div>

      <!-- Register Link -->
      <div style="text-align: center;">
        <p style="color: #6b7280; font-size: 0.875rem;">
          Hesabınız yok mu? 
          <router-link to="/register" style="color: #ec4899; text-decoration: none; font-weight: 500;">
            Üye olun
          </router-link>
        </p>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
  remember: false
})

const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  
  try {
    await authStore.login(form.value)
    router.push('/dashboard')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Giriş yapılırken bir hata oluştu'
  } finally {
    loading.value = false
  }
}

const handleButtonHover = (event: Event, isHover: boolean) => {
  if (!loading.value) {
    const target = event.target as HTMLElement
    target.style.backgroundColor = isHover ? '#db2777' : '#ec4899'
  }
}
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>