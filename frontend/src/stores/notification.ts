import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import notificationService, { type Notification } from '@/services/notificationService'

export const useNotificationStore = defineStore('notification', () => {
  const notifications = ref<Notification[]>([])
  const unreadCount = ref(0)
  const loading = ref(false)

  const unreadNotifications = computed(() => 
    notifications.value.filter(n => !n.read_at)
  )

  const readNotifications = computed(() => 
    notifications.value.filter(n => n.read_at)
  )

  async function fetchNotifications(limit = 20) {
    loading.value = true
    try {
      notifications.value = await notificationService.getNotifications(limit)
    } finally {
      loading.value = false
    }
  }

  async function fetchUnreadCount() {
    unreadCount.value = await notificationService.getUnreadCount()
  }

  async function markAsRead(notificationId: number) {
    const success = await notificationService.markAsRead(notificationId)
    if (success) {
      const notification = notifications.value.find(n => n.id === notificationId)
      if (notification && !notification.read_at) {
        notification.read_at = new Date().toISOString()
        unreadCount.value = Math.max(0, unreadCount.value - 1)
      }
    }
    return success
  }

  async function markAllAsRead() {
    const success = await notificationService.markAllAsRead()
    if (success) {
      notifications.value.forEach(notification => {
        if (!notification.read_at) {
          notification.read_at = new Date().toISOString()
        }
      })
      unreadCount.value = 0
    }
    return success
  }

  function addNotification(notification: Notification) {
    notifications.value.unshift(notification)
    if (!notification.read_at) {
      unreadCount.value++
    }
  }

  // Initialize
  function init() {
    fetchNotifications()
    fetchUnreadCount()
  }

  return {
    notifications,
    unreadCount,
    loading,
    unreadNotifications,
    readNotifications,
    fetchNotifications,
    fetchUnreadCount,
    markAsRead,
    markAllAsRead,
    addNotification,
    init
  }
})