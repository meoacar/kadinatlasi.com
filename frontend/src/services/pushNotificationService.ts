import api from './api'

export interface PushSubscription {
  endpoint: string
  keys: {
    p256dh: string
    auth: string
  }
}

class PushNotificationService {
  private vapidPublicKey = 'BPREclVbHndwdDi0XPgeKYbgQF4OmS-qOC703ppM0SwQLFFP8JkNBCCF-VQIf4Pb_UB3n4WYz2m0VvqONcL0Wlg'

  async requestPermission(): Promise<NotificationPermission> {
    if (!('Notification' in window)) {
      throw new Error('Bu tarayıcı bildirim desteği sunmuyor')
    }

    if (Notification.permission === 'granted') {
      return 'granted'
    }

    if (Notification.permission !== 'denied') {
      const permission = await Notification.requestPermission()
      return permission
    }

    return Notification.permission
  }

  async subscribe(): Promise<boolean> {
    try {
      const permission = await this.requestPermission()
      if (permission !== 'granted') {
        return false
      }

      if (!('serviceWorker' in navigator)) {
        throw new Error('Service Worker desteklenmiyor')
      }

      const registration = await navigator.serviceWorker.ready
      
      const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey)
      })

      const subscriptionData = {
        endpoint: subscription.endpoint,
        keys: {
          p256dh: this.arrayBufferToBase64(subscription.getKey('p256dh')!),
          auth: this.arrayBufferToBase64(subscription.getKey('auth')!)
        }
      }

      await api.post('/push-notifications/subscribe', subscriptionData)
      return true
    } catch (error) {
      console.error('Push notification subscription error:', error)
      return false
    }
  }

  async unsubscribe(): Promise<boolean> {
    try {
      if (!('serviceWorker' in navigator)) {
        return false
      }

      const registration = await navigator.serviceWorker.ready
      const subscription = await registration.pushManager.getSubscription()
      
      if (subscription) {
        await subscription.unsubscribe()
        await api.post('/push-notifications/unsubscribe')
      }
      
      return true
    } catch (error) {
      console.error('Push notification unsubscribe error:', error)
      return false
    }
  }

  async isSubscribed(): Promise<boolean> {
    try {
      if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return false
      }

      const registration = await navigator.serviceWorker.ready
      const subscription = await registration.pushManager.getSubscription()
      
      return subscription !== null
    } catch (error) {
      console.error('Check subscription error:', error)
      return false
    }
  }

  private urlBase64ToUint8Array(base64String: string): Uint8Array {
    const padding = '='.repeat((4 - base64String.length % 4) % 4)
    const base64 = (base64String + padding)
      .replace(/-/g, '+')
      .replace(/_/g, '/')

    const rawData = window.atob(base64)
    const outputArray = new Uint8Array(rawData.length)

    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i)
    }
    return outputArray
  }

  private arrayBufferToBase64(buffer: ArrayBuffer): string {
    const bytes = new Uint8Array(buffer)
    let binary = ''
    for (let i = 0; i < bytes.byteLength; i++) {
      binary += String.fromCharCode(bytes[i])
    }
    return window.btoa(binary)
  }
}

export default new PushNotificationService()