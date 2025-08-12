import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService, type User, type LoginData, type RegisterData } from '@/services/auth'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!user.value)
  const userProfile = computed(() => user.value?.profile)

  const initAuth = () => {
    const savedUser = authService.getUser()
    if (savedUser && authService.isAuthenticated()) {
      user.value = savedUser
    }
  }

  const login = async (credentials: LoginData) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await authService.login(credentials)
      if (response.success) {
        user.value = response.data.user
        return true
      } else {
        error.value = response.message
        return false
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Giriş yapılırken bir hata oluştu'
      return false
    } finally {
      loading.value = false
    }
  }

  const register = async (data: RegisterData) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await authService.register(data)
      if (response.success) {
        user.value = response.data.user
        return true
      } else {
        error.value = response.message
        return false
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Kayıt olurken bir hata oluştu'
      return false
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    try {
      await authService.logout()
    } finally {
      user.value = null
    }
  }

  const fetchUser = async () => {
    try {
      const response = await authService.me()
      if (response.success) {
        user.value = response.data.user
      }
    } catch (err) {
      console.error('Failed to fetch user:', err)
    }
  }

  return {
    user,
    loading,
    error,
    isAuthenticated,
    userProfile,
    initAuth,
    login,
    register,
    logout,
    fetchUser
  }
})