<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { Bars3Icon, XMarkIcon, UserCircleIcon } from '@heroicons/vue/24/outline'
import NotificationDropdown from '@/components/NotificationDropdown.vue'
import api from '@/services/api'

const authStore = useAuthStore()
const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)
const dropdownOpen = ref(null)
const userPremiumStatus = ref(null)

const checkPremiumStatus = async () => {
  if (!authStore.isAuthenticated) return
  
  try {
    const response = await api.get('/subscription/my')
    if (response.data.success && response.data.data) {
      userPremiumStatus.value = response.data.data
    }
  } catch (error) {
    console.error('Premium status check error:', error)
  }
}

const isPremiumUser = computed(() => {
  return authStore.user?.email === 'admin@kadinatlasi.com' || 
         (userPremiumStatus.value && userPremiumStatus.value.status === 'active')
})

const closeDropdowns = () => {
  dropdownOpen.value = null
  userMenuOpen.value = false
}

const handleClickOutside = (event) => {
  if (!event.target.closest('.dropdown-container') && !event.target.closest('.user-dropdown')) {
    closeDropdowns()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  checkPremiumStatus()
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

const navigation = [
  { name: 'Ana Sayfa', href: '/', icon: '🏠' },
  { name: 'Blog', href: '/blog', icon: '📝' },
  { 
    name: 'Hesaplama Araçları', 
    href: '#',
    icon: '🧮',
    dropdown: [
      { name: 'VKİ Hesaplayıcı', href: '/hesaplama/vki', icon: '⚖️' },
      { name: 'Regl Takvimi', href: '/hesaplama/regl-takvimi', icon: '📅' },
      { name: 'Gebelik Hesaplayıcı', href: '/hesaplama/gebelik', icon: '🤰' },
      { name: 'Kalori Hesaplayıcı', href: '/hesaplama/kalori', icon: '🍎' },
      { name: 'Su İhtiyacı', href: '/hesaplama/su-ihtiyaci', icon: '💧' },
      { name: 'Finans Planlayıcı', href: '/hesaplama/finans', icon: '💰' }
    ]
  },
  { 
    name: 'Sağlık & Yaşam', 
    href: '#',
    icon: '🏥',
    dropdown: [
      { name: 'Kadın Sağlığı', href: '/saglik', icon: '💊' },
      { name: 'Psikoloji', href: '/psikoloji', icon: '🧠' },
      { name: 'Astroloji', href: '/astroloji', icon: '⭐' },
      { name: 'Fitness & Diyet', href: '/fitness', icon: '💪' },
      { name: 'Tarifler', href: '/tarifler', icon: '🍽️' },
      { name: 'Kariyer & Girişimcilik', href: '/kariyer', icon: '💼' }
    ]
  },
  { 
    name: 'Anne & Bebek', 
    href: '#',
    icon: '🤱',
    dropdown: [
      { name: 'Gebelik Takibi', href: '/gebelik-takibi', icon: '🤰' },
      { name: 'Bebek İsimleri', href: '/bebek-isimleri', icon: '👶' },
      { name: 'Bebek Bakımı', href: '/bebek-bakimi', icon: '🍼' },
      { name: 'Annelik Rehberi', href: '/annelik-rehberi', icon: '👩‍👧‍👦' }
    ]
  },
  { 
    name: 'Güzellik & Moda', 
    href: '#',
    icon: '💄',
    dropdown: [
      { name: 'Güzellik İpuçları', href: '/guzellik', icon: '✨' },
      { name: 'Makyaj Rehberi', href: '/makyaj', icon: '💄' },
      { name: 'Moda Trendleri', href: '/moda', icon: '👗' },
      { name: 'Stil Önerileri', href: '/stil', icon: '👠' },
      { name: 'Kıyafet Kombinleri', href: '/kombinler', icon: '👚' }
    ]
  },
  { name: 'Forum', href: '/forum', icon: '💬' },
  { name: 'Alışveriş', href: '/shop', icon: '🛍️' },
  { 
    name: 'Daha Fazla', 
    href: '#',
    icon: '📚',
    dropdown: [
      { name: 'Etkinlikler', href: '/etkinlikler', icon: '🗓️' },
      { name: 'Kurslar', href: '/courses', icon: '📚' },
      { name: 'Destek Kaynakları', href: '/destek-kaynaklari', icon: '🤝' },
      { name: 'Görevler & Başarımlar', href: '/gorevler-basarimlar', icon: '🏆' },
      { name: 'Premium Üyelik', href: '/premium', icon: '⭐' },
      { name: 'Hakkımızda', href: '/hakkimizda', icon: 'ℹ️' },
      { name: 'İletişim', href: '/iletisim', icon: '📞' }
    ]
  }
]

const handleLogout = async () => {
  await authStore.logout()
}
</script>

<template>
  <header class="modern-header">
    <nav class="nav-container">
      <div class="nav-content">
        <!-- Logo -->
        <div class="logo-container">
          <RouterLink to="/" class="logo-link">
            <div class="logo-icon">
              <span>K</span>
            </div>
            <span class="logo-text">KadınAtlası</span>
          </RouterLink>
        </div>

        <!-- Desktop Navigation -->
        <div class="desktop-nav nav-menu">
          <template v-for="item in navigation" :key="item.name">
            <!-- Dropdown Menu Item -->
            <div v-if="item.dropdown" class="dropdown-container">
              <button
                @click="dropdownOpen = dropdownOpen === item.name ? null : item.name"
                class="dropdown-button"
                :class="{ active: dropdownOpen === item.name }"
              >
                {{ item.name }}
                <svg class="dropdown-arrow" :class="{ rotated: dropdownOpen === item.name }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              
              <!-- Dropdown Content -->
              <div v-if="dropdownOpen === item.name" class="dropdown-menu">
                <RouterLink
                  v-for="subItem in item.dropdown"
                  :key="subItem.name"
                  :to="subItem.href"
                  @click="dropdownOpen = null"
                  class="dropdown-item"
                >
                  <span class="dropdown-dot"></span>
                  {{ subItem.name }}
                </RouterLink>

              </div>
            </div>
            
            <!-- Regular Menu Item -->
            <RouterLink
              v-else
              :to="item.href"
              class="nav-link"
            >
              {{ item.name }}
            </RouterLink>
          </template>
        </div>



        <!-- User Menu -->
        <div class="desktop-nav user-menu">
          <template v-if="authStore.isAuthenticated">
            <RouterLink to="/cart" class="cart-link">
              <svg class="cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"/>
              </svg>
            </RouterLink>
            <NotificationDropdown />
            <div class="user-dropdown">
              <button @click="userMenuOpen = !userMenuOpen" class="user-button">
                <UserCircleIcon class="user-icon" />
                <span>{{ authStore.user?.name }}</span>
                <svg class="dropdown-arrow" :class="{ rotated: userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              
              <div v-if="userMenuOpen" class="user-dropdown-menu">
                <RouterLink to="/dashboard" @click="userMenuOpen = false" class="user-menu-item">
                  <span class="menu-emoji">📊</span>
                  Dashboard
                </RouterLink>
                
                <RouterLink to="/profile" @click="userMenuOpen = false" class="user-menu-item">
                  <span class="menu-emoji">👤</span>
                  Profilim
                </RouterLink>
                
                <RouterLink to="/uzman-soru-sor" @click="userMenuOpen = false" class="user-menu-item">
                  <span class="menu-emoji">❓</span>
                  Soru Sor
                </RouterLink>
                
                <RouterLink to="/sorularim" @click="userMenuOpen = false" class="user-menu-item">
                  <span class="menu-emoji">📝</span>
                  Sorularım
                </RouterLink>
                
                <RouterLink v-if="authStore.user?.is_expert" to="/uzman-sorulari" @click="userMenuOpen = false" class="user-menu-item">
                  <span class="menu-emoji">🎓</span>
                  Uzman Soruları
                </RouterLink>
                
                <a v-if="authStore.user?.email === 'admin@kadinatlasi.com'" href="http://localhost:8000/admin" target="_blank" @click="userMenuOpen = false" class="user-menu-item">
                  <span class="menu-emoji">⚙️</span>
                  Admin Panel
                </a>
                
                <div class="menu-divider"></div>
                
                <button @click="handleLogout" class="user-menu-item logout">
                  <span class="menu-emoji">🚪</span>
                  Çıkış Yap
                </button>
              </div>
            </div>
          </template>
          <template v-else>
            <RouterLink to="/login" class="auth-link login-link">Giriş</RouterLink>
            <RouterLink to="/register" class="auth-link register-link">Üye Ol</RouterLink>
          </template>
        </div>

        <!-- Mobile menu button -->
        <div class="mobile-nav">
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="mobile-menu-button">
            <Bars3Icon v-if="!mobileMenuOpen" class="menu-icon" />
            <XMarkIcon v-else class="menu-icon" />
          </button>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div v-if="mobileMenuOpen" class="mobile-nav mobile-menu">
        <div class="mobile-menu-items">
          <template v-for="item in navigation" :key="item.name">
            <div v-if="item.dropdown" class="mobile-dropdown">
              <button @click="dropdownOpen = dropdownOpen === item.name ? null : item.name" class="mobile-dropdown-button">
                {{ item.name }}
                <svg class="dropdown-arrow" :class="{ rotated: dropdownOpen === item.name }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              
              <div v-if="dropdownOpen === item.name" class="mobile-dropdown-content">
                <RouterLink
                  v-for="subItem in item.dropdown"
                  :key="subItem.name"
                  :to="subItem.href"
                  @click="mobileMenuOpen = false; dropdownOpen = null"
                  class="mobile-dropdown-item"
                >
                  {{ subItem.name }}
                </RouterLink>

              </div>
            </div>
            
            <RouterLink v-else :to="item.href" @click="mobileMenuOpen = false" class="mobile-nav-link">
              {{ item.name }}
            </RouterLink>
          </template>
        </div>
        
        <div class="mobile-auth-section">
          <template v-if="authStore.isAuthenticated">
            <RouterLink to="/dashboard" @click="mobileMenuOpen = false" class="mobile-nav-link">Profilim</RouterLink>
            <button @click="handleLogout" class="mobile-nav-link logout">Çıkış</button>
          </template>
          <template v-else>
            <RouterLink to="/login" @click="mobileMenuOpen = false" class="mobile-nav-link">Giriş</RouterLink>
            <RouterLink to="/register" @click="mobileMenuOpen = false" class="mobile-nav-link register">Üye Ol</RouterLink>
          </template>
        </div>
      </div>
    </nav>
  </header>
</template>

<style scoped>
/* Modern Header Styles */
.modern-header {
  background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(253,242,248,0.95) 100%);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(236, 72, 153, 0.1);
  box-shadow: 0 8px 32px rgba(236, 72, 153, 0.08);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 24px;
}

.nav-content {
  display: flex;
  height: 70px;
  justify-content: space-between;
  align-items: center;
  gap: 24px;
}

/* Logo Styles */
.logo-container {
  display: flex;
  align-items: center;
}

.logo-link {
  display: flex;
  align-items: center;
  text-decoration: none;
  padding: 12px 16px;
  border-radius: 16px;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.logo-link::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(236, 72, 153, 0.1), transparent);
  transition: left 0.6s;
}

.logo-link:hover::before {
  left: 100%;
}

.logo-link:hover {
  background: rgba(236, 72, 153, 0.05);
  transform: translateY(-2px);
}

.logo-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
  box-shadow: 0 2px 10px rgba(236, 72, 153, 0.2);
  transition: all 0.3s ease;
}

.logo-link:hover .logo-icon {
  transform: rotate(360deg) scale(1.1);
  box-shadow: 0 6px 25px rgba(236, 72, 153, 0.4);
}

.logo-icon span {
  color: white;
  font-weight: 800;
  font-size: 1.4rem;
}

.logo-text {
  font-size: 1.5rem;
  font-weight: 800;
  background: linear-gradient(135deg, #ec4899, #be185d);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: -0.01em;
}

/* Navigation Menu */
.nav-menu {
  display: flex;
  align-items: center;
  gap: 4px;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #374151;
  text-decoration: none;
  padding: 8px 12px;
  font-size: 0.875rem;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.nav-link::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #ec4899, #f472b6);
  transition: all 0.3s ease;
  transform: translateX(-50%);
}

.nav-link:hover::before {
  width: 80%;
}

.nav-link:hover {
  background: rgba(236, 72, 153, 0.08);
  color: #ec4899;
  transform: translateY(-1px);
}

/* Dropdown Styles */
.dropdown-container {
  position: relative;
}

.dropdown-button {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #374151;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 8px 12px;
  font-size: 0.875rem;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.dropdown-button::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #ec4899, #f472b6);
  transition: all 0.3s ease;
  transform: translateX(-50%);
}

.dropdown-button:hover::before,
.dropdown-button.active::before {
  width: 80%;
}

.dropdown-button:hover,
.dropdown-button.active {
  background: rgba(236, 72, 153, 0.08);
  color: #ec4899;
  transform: translateY(-1px);
}

.dropdown-arrow {
  width: 16px;
  height: 16px;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.dropdown-arrow.rotated {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: calc(100% + 8px);
  left: 0;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 16px;
  box-shadow: 0 20px 40px rgba(236, 72, 153, 0.15);
  border: 1px solid rgba(236, 72, 153, 0.1);
  min-width: 240px;
  z-index: 50;
  overflow: hidden;
  animation: dropdownSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes dropdownSlide {
  from {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.dropdown-item {
  display: flex;
  align-items: center;
  padding: 16px 24px;
  color: #374151;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  position: relative;
}

.dropdown-item::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  width: 0;
  height: 100%;
  background: linear-gradient(90deg, rgba(236, 72, 153, 0.1), transparent);
  transition: width 0.3s ease;
}

.dropdown-item:hover::before {
  width: 100%;
}

.dropdown-item:hover {
  color: #ec4899;
  padding-left: 32px;
}

.dropdown-dot {
  width: 8px;
  height: 8px;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  border-radius: 50%;
  margin-right: 16px;
  opacity: 0.7;
  transition: all 0.3s ease;
}

.dropdown-item:hover .dropdown-dot {
  opacity: 1;
  transform: scale(1.2);
}

.premium-item {
  background: linear-gradient(90deg, rgba(245, 158, 11, 0.05), transparent);
  border-top: 1px solid rgba(245, 158, 11, 0.2);
}

.premium-dot {
  background: linear-gradient(135deg, #f59e0b, #d97706);
}

.premium-badge {
  margin-left: auto;
  font-size: 0.8rem;
}

.premium-mobile-item {
  background: linear-gradient(90deg, rgba(245, 158, 11, 0.05), transparent);
  border-top: 1px solid rgba(245, 158, 11, 0.2);
  font-weight: 600;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  align-items: center;
  gap: 12px;
}

.premium-btn, .courses-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 6px 12px;
  border-radius: 16px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.75rem;
  transition: all 0.3s ease;
}

.premium-btn {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.premium-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.courses-btn {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.courses-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.premium-icon, .courses-icon {
  font-size: 0.9rem;
}

/* User Menu */
.user-menu {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-dropdown {
  position: relative;
}

.user-button {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #374151;
  background: rgba(236, 72, 153, 0.05);
  border: 1px solid rgba(236, 72, 153, 0.15);
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 20px;
  transition: all 0.2s ease;
  font-weight: 500;
  font-size: 0.875rem;
}

.user-button:hover {
  background: rgba(236, 72, 153, 0.1);
  color: #ec4899;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(236, 72, 153, 0.2);
}

.user-icon {
  height: 24px;
  width: 24px;
}

.user-dropdown-menu {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 16px;
  box-shadow: 0 20px 40px rgba(236, 72, 153, 0.15);
  border: 1px solid rgba(236, 72, 153, 0.1);
  min-width: 240px;
  z-index: 50;
  overflow: hidden;
  animation: dropdownSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.user-menu-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 20px;
  color: #374151;
  text-decoration: none;
  transition: all 0.3s ease;
  font-weight: 500;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
}

.user-menu-item:hover {
  background: rgba(236, 72, 153, 0.05);
  color: #ec4899;
}

.user-menu-item.logout {
  color: #dc2626;
}

.user-menu-item.logout:hover {
  background: rgba(220, 38, 38, 0.05);
  color: #dc2626;
}

.menu-icon {
  width: 18px;
  height: 18px;
}

.menu-emoji {
  font-size: 1.1rem;
  width: 20px;
  text-align: center;
}

.menu-divider {
  height: 1px;
  background: rgba(236, 72, 153, 0.1);
  margin: 8px 0;
}

/* Cart Link */
.cart-link {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #374151;
  text-decoration: none;
  padding: 10px;
  border-radius: 12px;
  transition: all 0.2s ease;
  background: rgba(236, 72, 153, 0.08);
  border: 1px solid rgba(236, 72, 153, 0.2);
  position: relative;
}

.cart-link:hover {
  background: rgba(236, 72, 153, 0.15);
  color: #ec4899;
  transform: translateY(-1px) scale(1.05);
  box-shadow: 0 4px 15px rgba(236, 72, 153, 0.25);
}

.cart-icon {
  width: 22px;
  height: 22px;
  stroke-width: 2.5;
}

.cart-text {
  font-size: 0.9rem;
}

/* Auth Links */
.auth-link {
  text-decoration: none;
  padding: 8px 16px;
  font-size: 0.875rem;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.login-link {
  color: #374151;
}

.login-link:hover {
  background: rgba(236, 72, 153, 0.08);
  color: #ec4899;
  transform: translateY(-1px);
}

.register-link {
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
  box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
}

.register-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
}

/* Mobile Menu */
.mobile-menu-button {
  color: #374151;
  background: rgba(236, 72, 153, 0.05);
  border: 1px solid rgba(236, 72, 153, 0.2);
  padding: 12px;
  cursor: pointer;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.mobile-menu-button:hover {
  background: rgba(236, 72, 153, 0.1);
  color: #ec4899;
}

.mobile-menu {
  border-top: 1px solid rgba(236, 72, 153, 0.1);
  padding: 24px 0;
  background: linear-gradient(135deg, rgba(253,242,248,0.8), rgba(255,255,255,0.8));
  backdrop-filter: blur(10px);
}

.mobile-menu-items {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.mobile-nav-link {
  display: block;
  padding: 16px 20px;
  font-size: 1.1rem;
  font-weight: 600;
  color: #374151;
  text-decoration: none;
  border-radius: 12px;
  transition: all 0.3s ease;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
}

.mobile-nav-link:hover {
  background: rgba(236, 72, 153, 0.08);
  color: #ec4899;
}

.mobile-nav-link.register {
  color: #ec4899;
  font-weight: 700;
}

.mobile-dropdown-button {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  padding: 16px 20px;
  font-size: 1.1rem;
  font-weight: 600;
  color: #374151;
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.mobile-dropdown-button:hover {
  background: rgba(236, 72, 153, 0.08);
  color: #ec4899;
}

.mobile-dropdown-content {
  padding-left: 20px;
  border-left: 3px solid rgba(236, 72, 153, 0.2);
  margin-left: 20px;
  margin-top: 8px;
}

.mobile-dropdown-item {
  display: block;
  padding: 12px 16px;
  font-size: 1rem;
  color: #6b7280;
  text-decoration: none;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.mobile-dropdown-item:hover {
  background: rgba(236, 72, 153, 0.05);
  color: #ec4899;
}

.mobile-auth-section {
  border-top: 1px solid rgba(236, 72, 153, 0.1);
  padding-top: 20px;
  margin-top: 20px;
}

/* Responsive */
@media (min-width: 768px) {
  .desktop-nav {
    display: flex !important;
  }
  .mobile-nav {
    display: none !important;
  }
}

@media (max-width: 767px) {
  .desktop-nav {
    display: none !important;
  }
  .mobile-nav {
    display: block !important;
  }
  
  .nav-container {
    padding: 0 16px;
  }
  
  .nav-content {
    height: 70px;
  }
  
  .logo-text {
    font-size: 1.5rem;
  }
  
  .logo-icon {
    width: 40px;
    height: 40px;
    margin-right: 12px;
  }
}
</style>