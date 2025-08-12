<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); padding: 32px 0;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 16px;">
      
      <!-- Header -->
      <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(236, 72, 153, 0.1);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
          <h1 style="font-size: 2rem; font-weight: bold; color: #1f2937; margin: 0;">🔔 Bildirimler</h1>
          <div style="display: flex; align-items: center; gap: 16px;">
            <span style="font-size: 0.875rem; color: #6b7280; background: #f3f4f6; padding: 6px 12px; border-radius: 20px;">
              {{ notificationStore.unreadCount }} okunmamış
            </span>
            <button
              v-if="notificationStore.unreadCount > 0"
              @click="markAllAsRead"
              style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 8px; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s ease;"
              onmouseover="this.style.background='#db2777'"
              onmouseout="this.style.background='#ec4899'"
            >
              Tümünü Okundu İşaretle
            </button>
          </div>
        </div>

        <!-- Filters -->
        <div style="display: flex; gap: 8px;">
          <button
            @click="selectedFilter = 'all'"
            :style="{
              padding: '8px 16px',
              borderRadius: '20px',
              fontSize: '0.875rem',
              fontWeight: '500',
              border: 'none',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              background: selectedFilter === 'all' ? '#ec4899' : '#f3f4f6',
              color: selectedFilter === 'all' ? 'white' : '#374151'
            }"
          >
            Tümü ({{ notificationStore.notifications.length }})
          </button>
          <button
            @click="selectedFilter = 'unread'"
            :style="{
              padding: '8px 16px',
              borderRadius: '20px',
              fontSize: '0.875rem',
              fontWeight: '500',
              border: 'none',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              background: selectedFilter === 'unread' ? '#ec4899' : '#f3f4f6',
              color: selectedFilter === 'unread' ? 'white' : '#374151'
            }"
          >
            Okunmamış ({{ notificationStore.unreadNotifications.length }})
          </button>
          <button
            @click="selectedFilter = 'read'"
            :style="{
              padding: '8px 16px',
              borderRadius: '20px',
              fontSize: '0.875rem',
              fontWeight: '500',
              border: 'none',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              background: selectedFilter === 'read' ? '#ec4899' : '#f3f4f6',
              color: selectedFilter === 'read' ? 'white' : '#374151'
            }"
          >
            Okunmuş ({{ notificationStore.readNotifications.length }})
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="notificationStore.loading" style="background: white; border-radius: 16px; padding: 48px; text-align: center;">
        <div style="width: 48px; height: 48px; border: 4px solid #f3f4f6; border-top: 4px solid #ec4899; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 16px;"></div>
        <p style="color: #6b7280; margin: 0;">Bildirimler yükleniyor...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredNotifications.length === 0" style="background: white; border-radius: 16px; padding: 48px; text-align: center;">
        <div style="font-size: 4rem; margin-bottom: 16px;">📭</div>
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 8px;">{{ getEmptyMessage() }}</h2>
        <p style="color: #6b7280; margin: 0;">Yeni bildirimler geldiğinde burada görünecek.</p>
      </div>

      <!-- Notifications List -->
      <div v-else style="display: flex; flex-direction: column; gap: 12px;">
        <div
          v-for="notification in filteredNotifications"
          :key="notification.id"
          @click="handleNotificationClick(notification)"
          :style="{
            background: notification.read_at ? 'white' : 'linear-gradient(135deg, #fef3f2 0%, #fdf2f8 100%)',
            borderRadius: '16px',
            padding: '20px',
            cursor: 'pointer',
            transition: 'all 0.2s ease',
            boxShadow: '0 2px 10px rgba(236, 72, 153, 0.08)',
            border: notification.read_at ? '1px solid #f3f4f6' : '1px solid rgba(236, 72, 153, 0.2)'
          }"
          onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 20px rgba(236, 72, 153, 0.15)'"
          onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(236, 72, 153, 0.08)'"
        >
          <div style="display: flex; align-items: start; gap: 16px;">
            <div style="font-size: 2rem; flex-shrink: 0;">
              {{ getNotificationIcon(notification.type) }}
            </div>
            <div style="flex: 1; min-width: 0;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0;">
                  {{ notification.title }}
                </h3>
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 0.75rem; color: #6b7280; white-space: nowrap;">
                    {{ formatDate(notification.created_at) }}
                  </span>
                  <div v-if="!notification.read_at" style="width: 8px; height: 8px; background: #ec4899; border-radius: 50%; flex-shrink: 0;"></div>
                </div>
              </div>
              <p style="color: #6b7280; margin: 0 0 12px 0; line-height: 1.5;">
                {{ notification.message }}
              </p>
              <div v-if="notification.data" style="display: inline-block; background: rgba(236, 72, 153, 0.1); color: #ec4899; padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">
                {{ getNotificationTypeLabel(notification.type) }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Load More -->
      <div v-if="!notificationStore.loading && filteredNotifications.length > 0" style="text-align: center; margin-top: 24px;">
        <button
          @click="loadMore"
          style="background: white; color: #374151; padding: 12px 24px; border-radius: 12px; font-weight: 500; border: 1px solid #e5e7eb; cursor: pointer; transition: all 0.2s ease;"
          onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#ec4899'"
          onmouseout="this.style.background='white'; this.style.borderColor='#e5e7eb'"
        >
          Daha Fazla Yükle
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useNotificationStore } from '@/stores/notification'
import notificationService from '@/services/notificationService'

const notificationStore = useNotificationStore()
const selectedFilter = ref('all')

const filteredNotifications = computed(() => {
  switch (selectedFilter.value) {
    case 'unread':
      return notificationStore.unreadNotifications
    case 'read':
      return notificationStore.readNotifications
    default:
      return notificationStore.notifications
  }
})

const markAllAsRead = async () => {
  await notificationStore.markAllAsRead()
}

const handleNotificationClick = async (notification) => {
  if (!notification.read_at) {
    await notificationStore.markAsRead(notification.id)
  }
  
  if (notification.data) {
    if (notification.type === 'forum_reply' && notification.data.topic_id) {
      window.location.href = `/forum/konu/${notification.data.topic_id}`
    } else if (notification.type === 'blog_comment' && notification.data.blog_id) {
      window.location.href = `/blog/${notification.data.blog_id}`
    }
  }
}

const loadMore = () => {
  notificationStore.fetchNotifications(notificationStore.notifications.length + 20)
}

const getNotificationIcon = (type) => {
  return notificationService.getNotificationIcon(type)
}

const getNotificationTypeLabel = (type) => {
  const labels = {
    forum_reply: 'Forum Yanıtı',
    blog_comment: 'Blog Yorumu',
    menstrual_reminder: 'Regl Hatırlatması',
    water_reminder: 'Su Hatırlatması',
    exercise_reminder: 'Egzersiz Hatırlatması'
  }
  return labels[type] || 'Bildirim'
}

const getEmptyMessage = () => {
  switch (selectedFilter.value) {
    case 'unread':
      return 'Okunmamış bildiriminiz yok'
    case 'read':
      return 'Okunmuş bildiriminiz yok'
    default:
      return 'Henüz bildiriminiz yok'
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60))
  
  if (diffInMinutes < 1) return 'Şimdi'
  if (diffInMinutes < 60) return `${diffInMinutes}dk önce`
  if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}s önce`
  if (diffInMinutes < 10080) return `${Math.floor(diffInMinutes / 1440)}g önce`
  
  return date.toLocaleDateString('tr-TR')
}

onMounted(() => {
  notificationStore.init()
})
</script>

<style>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>