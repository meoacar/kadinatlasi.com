import api from './api'

export interface LoginData {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
  birth_date?: string
  interests?: string[]
}

export interface User {
  id: number
  name: string
  email: string
  birth_date?: string
  zodiac_sign?: string
  avatar?: string
  is_expert?: boolean
  expert_rank?: string
  profile?: UserProfile
}

export interface UserProfile {
  id: number
  bio?: string
  interests: string[]
  points: number
  level: number
  last_period_date?: string
  cycle_length: number
}

export const authService = {
  async login(data: LoginData) {
    const response = await api.post('/login', data)
    if (response.data.success) {
      localStorage.setItem('auth_token', response.data.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.data.user))
    }
    return response.data
  },

  async register(data: RegisterData) {
    const response = await api.post('/register', data)
    if (response.data.success) {
      localStorage.setItem('auth_token', response.data.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.data.user))
    }
    return response.data
  },

  async logout() {
    try {
      await api.post('/logout')
    } finally {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
    }
  },

  async me() {
    const response = await api.get('/me')
    if (response.data.success) {
      localStorage.setItem('user', JSON.stringify(response.data.data.user))
    }
    return response.data
  },

  getUser(): User | null {
    const user = localStorage.getItem('user')
    return user ? JSON.parse(user) : null
  },

  getToken(): string | null {
    return localStorage.getItem('auth_token')
  },

  isAuthenticated(): boolean {
    return !!this.getToken()
  }
}