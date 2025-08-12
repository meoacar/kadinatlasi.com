import api from './api'

export interface Notification {
  id: number
  type: string
  title: string
  message: string
  data?: any
  read_at: string | null
  created_at: string
  updated_at: string
}

export interface NotificationResponse {
  success: boolean
  data: Notification[]
}

export interface UnreadCountResponse {
  success: boolean
  data: { count: number }
}

class NotificationService {
  async getNotifications(limit = 20): Promise<Notification[]> {
    try {
      const response = await api.get<NotificationResponse>('/notifications', {
        params: { limit }
      })
      return response.data.success ? response.data.data : []
    } catch (error) {
      console.error('Get notifications error:', error)
      return []
    }
  }

  async getUnreadCount(): Promise<number> {
    try {
      const response = await api.get<UnreadCountResponse>('/notifications/unread-count')
      return response.data.success ? response.data.data.count : 0
    } catch (error) {
      console.error('Get unread count error:', error)
      return 0
    }
  }

  async markAsRead(notificationId: number): Promise<boolean> {
    try {
      const response = await api.post(`/notifications/${notificationId}/read`)
      return response.data.success
    } catch (error) {
      console.error('Mark as read error:', error)
      return false
    }
  }

  async markAllAsRead(): Promise<boolean> {
    try {
      const response = await api.post('/notifications/mark-all-read')
      return response.data.success
    } catch (error) {
      console.error('Mark all as read error:', error)
      return false
    }
  }

  getNotificationIcon(type: string): string {
    const icons: Record<string, string> = {
      forum_reply: '💬',
      blog_comment: '📝',
      menstrual_reminder: '🌸',
      water_reminder: '💧',
      exercise_reminder: '💪',
      default: '🔔'
    }
    return icons[type] || icons.default
  }

  getNotificationColor(type: string): string {
    const colors: Record<string, string> = {
      forum_reply: 'text-blue-600',
      blog_comment: 'text-green-600',
      menstrual_reminder: 'text-pink-600',
      water_reminder: 'text-blue-400',
      exercise_reminder: 'text-orange-600',
      default: 'text-gray-600'
    }
    return colors[type] || colors.default
  }
}

export default new NotificationService()