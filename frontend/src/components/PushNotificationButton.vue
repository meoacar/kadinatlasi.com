<template>
  <div class="push-notification-wrapper">
    <button
      v-if="!isSubscribed"
      @click="enableNotifications"
      :disabled="loading"
      class="px-3 py-1.5 bg-pink-500 text-white text-sm rounded-md hover:bg-pink-600 transition-colors disabled:opacity-50"
    >
      <span v-if="loading">Açılıyor...</span>
      <span v-else>Aç</span>
    </button>

    <div v-else class="flex items-center gap-2">
      <button
        @click="disableNotifications"
        :disabled="loading"
        class="px-3 py-1.5 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 transition-colors disabled:opacity-50"
      >
        <span v-if="loading">Kapatılıyor...</span>
        <span v-else>Kapat</span>
      </button>
      
      <button
        @click="sendTestNotification"
        class="px-2 py-1.5 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600 transition-colors"
        title="Test bildirimi gönder"
      >
        ⚡
      </button>
    </div>

    <!-- Notification Permission Banner -->
    <div
      v-if="showPermissionBanner && !isSubscribed"
      class="fixed top-4 right-4 bg-white border border-pink-200 rounded-lg shadow-lg p-4 max-w-sm z-50"
    >
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h0z"/>
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="text-sm font-medium text-gray-900">Bildirimleri Açın</h3>
          <p class="text-sm text-gray-600 mt-1">
            Önemli güncellemeler ve hatırlatmalar için bildirimleri etkinleştirin.
          </p>
          <div class="flex gap-2 mt-3">
            <button
              @click="enableNotifications"
              class="text-sm bg-pink-500 text-white px-3 py-1 rounded hover:bg-pink-600"
            >
              Etkinleştir
            </button>
            <button
              @click="dismissBanner"
              class="text-sm text-gray-500 hover:text-gray-700"
            >
              Daha Sonra
            </button>
          </div>
        </div>
        <button
          @click="dismissBanner"
          class="flex-shrink-0 text-gray-400 hover:text-gray-600"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import pushNotificationService from '@/services/pushNotificationService'
import api from '@/services/api'

const isSubscribed = ref(false)
const loading = ref(false)
const showPermissionBanner = ref(false)

onMounted(async () => {
  await checkSubscriptionStatus()
  
  // Banner'ı 3 saniye sonra göster (eğer abone değilse)
  setTimeout(() => {
    if (!isSubscribed.value && Notification.permission !== 'denied') {
      showPermissionBanner.value = true
    }
  }, 3000)
})

async function checkSubscriptionStatus() {
  try {
    isSubscribed.value = await pushNotificationService.isSubscribed()
  } catch (error) {
    console.error('Subscription status check error:', error)
  }
}

async function enableNotifications() {
  loading.value = true
  try {
    const success = await pushNotificationService.subscribe()
    if (success) {
      isSubscribed.value = true
      showPermissionBanner.value = false
      
      // Test bildirimi gönder
      if ('serviceWorker' in navigator) {
        const registration = await navigator.serviceWorker.ready
        registration.showNotification('🎉 Bildirimler Aktif!', {
          body: 'Artık önemli güncellemeleri kaçırmayacaksınız.',
          icon: '/icons/icon-192x192.png',
          badge: '/icons/badge-72x72.png'
        })
      }
    } else {
      alert('Bildirimler etkinleştirilemedi. Lütfen tarayıcı ayarlarınızı kontrol edin.')
    }
  } catch (error) {
    console.error('Enable notifications error:', error)
    alert('Bir hata oluştu. Lütfen tekrar deneyin.')
  } finally {
    loading.value = false
  }
}

async function disableNotifications() {
  loading.value = true
  try {
    const success = await pushNotificationService.unsubscribe()
    if (success) {
      isSubscribed.value = false
    }
  } catch (error) {
    console.error('Disable notifications error:', error)
  } finally {
    loading.value = false
  }
}

async function sendTestNotification() {
  try {
    const response = await api.post('/push-notifications/test')
    if (response.data.success) {
      alert('Test bildirimi gönderildi!')
    }
  } catch (error) {
    console.error('Test notification error:', error)
    alert('Test bildirimi gönderilemedi')
  }
}

function dismissBanner() {
  showPermissionBanner.value = false
  // 24 saat boyunca banner'ı gösterme
  localStorage.setItem('notification-banner-dismissed', Date.now().toString())
}
</script>