<template>
  <div>
    <!-- Breadcrumb -->
    <nav style="background: white; border-bottom: 1px solid #e5e7eb; padding: 12px 0;">
      <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; color: #6b7280;">
          <router-link to="/" style="color: #e57399; text-decoration: none;">Ana Sayfa</router-link>
          <span>›</span>
          <router-link to="/dashboard" style="color: #e57399; text-decoration: none;">Dashboard</router-link>
          <span>›</span>
          <span style="color: #111827; font-weight: 500;">Siparişlerim</span>
        </div>
      </div>
    </nav>

    <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%);">
      <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        
        <!-- Header -->
        <header style="text-align: center; margin-bottom: 48px;">
          <h1 style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 16px;">
            🛒 Siparişlerim
          </h1>
          <p style="font-size: 1.25rem; color: #6b7280; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Tüm siparişlerinizi buradan takip edebilirsiniz
          </p>
        </header>

        <!-- Orders List -->
        <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #f3f4f6;">
          
          <!-- Loading -->
          <div v-if="loading" style="padding: 60px; text-align: center;">
            <div style="width: 40px; height: 40px; border: 3px solid #f3f4f6; border-top: 3px solid #e57399; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
            <p style="margin-top: 16px; color: #6b7280; font-weight: 500;">Siparişler yükleniyor...</p>
          </div>

          <!-- Empty State -->
          <div v-else-if="orders.length === 0" style="padding: 60px; text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 16px;">🛒</div>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Henüz sipariş yok</h3>
            <p style="color: #6b7280; margin-bottom: 24px;">İlk siparişinizi vermek için mağazamızı keşfedin</p>
            <router-link to="/shop"
                        style="display: inline-block; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 12px 24px; border-radius: 20px; text-decoration: none; font-weight: 600;">
              Alışverişe Başla
            </router-link>
          </div>

          <!-- Orders -->
          <div v-else>
            <div v-for="order in orders" :key="order.id" 
                 style="padding: 24px; border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                 @mouseover="$event.currentTarget.style.backgroundColor = '#fafafa'"
                 @mouseleave="$event.currentTarget.style.backgroundColor = 'white'">
              
              <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                <!-- Order Info -->
                <div style="flex: 1;">
                  <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827;">
                      {{ order.order_number }}
                    </h3>
                    <span :class="getOrderStatusClass(order.status)" 
                          style="padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                      {{ getOrderStatusText(order.status) }}
                    </span>
                    <span :class="getPaymentStatusClass(order.payment_status)" 
                          style="padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                      {{ getPaymentStatusText(order.payment_status) }}
                    </span>
                  </div>
                  
                  <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 12px; font-size: 0.875rem; color: #6b7280;">
                    <span>{{ formatDate(order.created_at) }}</span>
                    <span>{{ order.items?.length || 0 }} ürün</span>
                    <span>{{ order.payment_method === 'card' ? 'Kredi Kartı' : 'Havale/EFT' }}</span>
                  </div>
                  
                  <!-- Order Items Preview -->
                  <div v-if="order.items && order.items.length" style="margin-bottom: 12px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                      <span v-for="item in order.items.slice(0, 3)" :key="item.id"
                            style="background: #f3f4f6; color: #6b7280; padding: 4px 8px; border-radius: 8px; font-size: 0.75rem;">
                        {{ item.product_name }} ({{ item.quantity }}x)
                      </span>
                      <span v-if="order.items.length > 3"
                            style="background: #e5e7eb; color: #6b7280; padding: 4px 8px; border-radius: 8px; font-size: 0.75rem;">
                        +{{ order.items.length - 3 }} diğer
                      </span>
                    </div>
                  </div>
                  
                  <!-- Billing Info -->
                  <div v-if="order.billing_info" style="font-size: 0.875rem; color: #6b7280;">
                    <span>{{ order.billing_info.first_name }} {{ order.billing_info.last_name }}</span>
                    <span style="margin-left: 12px;">{{ order.billing_info.city }}</span>
                  </div>
                </div>
                
                <!-- Order Total -->
                <div style="text-align: right;">
                  <div style="font-size: 1.5rem; font-weight: 800; color: #e57399; margin-bottom: 8px;">
                    {{ formatPrice(order.total) }} ₺
                  </div>
                  <div style="font-size: 0.875rem; color: #6b7280;">
                    <div>Ara Toplam: {{ formatPrice(order.subtotal) }} ₺</div>
                    <div>Kargo: {{ order.shipping_cost > 0 ? formatPrice(order.shipping_cost) + ' ₺' : 'Ücretsiz' }}</div>
                  </div>
                  
                  <!-- Actions -->
                  <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                    <button @click="viewOrderDetails(order)"
                            style="background: #e57399; color: white; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.875rem; font-weight: 500;">
                      Detayları Gör
                    </button>
                    <button v-if="order.status === 'delivered'"
                            style="background: white; color: #6b7280; padding: 8px 16px; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; font-size: 0.875rem;">
                      Tekrar Sipariş Ver
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Order Details Modal -->
    <div v-if="selectedOrder" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 16px;">
      <div style="background: white; border-radius: 20px; padding: 32px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827;">Sipariş Detayları</h2>
          <button @click="selectedOrder = null"
                  style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">
            ×
          </button>
        </div>
        
        <!-- Order Info -->
        <div style="margin-bottom: 24px; padding: 20px; background: #f9fafb; border-radius: 12px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
              <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 4px;">Sipariş No</div>
              <div style="font-weight: 600;">{{ selectedOrder.order_number }}</div>
            </div>
            <div>
              <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 4px;">Tarih</div>
              <div style="font-weight: 600;">{{ formatDate(selectedOrder.created_at) }}</div>
            </div>
            <div>
              <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 4px;">Durum</div>
              <span :class="getOrderStatusClass(selectedOrder.status)" 
                    style="padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                {{ getOrderStatusText(selectedOrder.status) }}
              </span>
            </div>
            <div>
              <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 4px;">Ödeme</div>
              <span :class="getPaymentStatusClass(selectedOrder.payment_status)" 
                    style="padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                {{ getPaymentStatusText(selectedOrder.payment_status) }}
              </span>
            </div>
          </div>
        </div>
        
        <!-- Order Items -->
        <div style="margin-bottom: 24px;">
          <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 16px;">Sipariş Kalemleri</h3>
          <div v-for="item in selectedOrder.items" :key="item.id"
               style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
            <div>
              <div style="font-weight: 600; margin-bottom: 4px;">{{ item.product_name }}</div>
              <div style="font-size: 0.875rem; color: #6b7280;">{{ item.quantity }} adet × {{ formatPrice(item.product_price) }} ₺</div>
            </div>
            <div style="font-weight: 600; color: #e57399;">
              {{ formatPrice(item.total) }} ₺
            </div>
          </div>
        </div>
        
        <!-- Billing Info -->
        <div v-if="selectedOrder.billing_info" style="margin-bottom: 24px;">
          <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 16px;">Fatura Bilgileri</h3>
          <div style="background: #f9fafb; padding: 16px; border-radius: 12px;">
            <div style="margin-bottom: 8px;">
              <strong>{{ selectedOrder.billing_info.first_name }} {{ selectedOrder.billing_info.last_name }}</strong>
            </div>
            <div style="margin-bottom: 8px;">{{ selectedOrder.billing_info.email }}</div>
            <div style="margin-bottom: 8px;">{{ selectedOrder.billing_info.phone }}</div>
            <div style="margin-bottom: 8px;">{{ selectedOrder.billing_info.address }}</div>
            <div>{{ selectedOrder.billing_info.city }} {{ selectedOrder.billing_info.zip_code }}</div>
          </div>
        </div>
        
        <!-- Order Summary -->
        <div style="background: #f9fafb; padding: 20px; border-radius: 12px;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span>Ara Toplam:</span>
            <span>{{ formatPrice(selectedOrder.subtotal) }} ₺</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span>Kargo:</span>
            <span>{{ selectedOrder.shipping_cost > 0 ? formatPrice(selectedOrder.shipping_cost) + ' ₺' : 'Ücretsiz' }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.125rem; padding-top: 8px; border-top: 1px solid #e5e7eb;">
            <span>Toplam:</span>
            <span style="color: #e57399;">{{ formatPrice(selectedOrder.total) }} ₺</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const orders = ref([])
const loading = ref(true)
const selectedOrder = ref(null)

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('tr-TR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatPrice = (price: number) => {
  return price.toFixed(2).replace('.', ',')
}

const getOrderStatusText = (status: string) => {
  const statuses = {
    'pending': 'Beklemede',
    'processing': 'Hazırlanıyor',
    'shipped': 'Kargoda',
    'delivered': 'Teslim Edildi',
    'cancelled': 'İptal Edildi'
  }
  return statuses[status] || status
}

const getOrderStatusClass = (status: string) => {
  const classes = {
    'pending': 'status-pending',
    'processing': 'status-processing',
    'shipped': 'status-shipped',
    'delivered': 'status-delivered',
    'cancelled': 'status-cancelled'
  }
  return classes[status] || 'status-pending'
}

const getPaymentStatusText = (status: string) => {
  const statuses = {
    'pending': 'Ödeme Bekliyor',
    'paid': 'Ödendi',
    'failed': 'Ödeme Başarısız',
    'refunded': 'İade Edildi'
  }
  return statuses[status] || status
}

const getPaymentStatusClass = (status: string) => {
  const classes = {
    'pending': 'payment-pending',
    'paid': 'payment-paid',
    'failed': 'payment-failed',
    'refunded': 'payment-refunded'
  }
  return classes[status] || 'payment-pending'
}

const viewOrderDetails = (order: any) => {
  selectedOrder.value = order
}

const fetchOrders = async () => {
  try {
    const response = await fetch('/api/orders', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      }
    })
    
    const result = await response.json()
    
    if (result.success) {
      orders.value = result.data.data || result.data || []
    }
  } catch (error) {
    console.error('Siparişler yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchOrders()
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.status-pending {
  background: #fef3c7;
  color: #92400e;
}

.status-processing {
  background: #dbeafe;
  color: #1d4ed8;
}

.status-shipped {
  background: #e0e7ff;
  color: #5b21b6;
}

.status-delivered {
  background: #dcfce7;
  color: #16a34a;
}

.status-cancelled {
  background: #fee2e2;
  color: #dc2626;
}

.payment-pending {
  background: #fef3c7;
  color: #92400e;
}

.payment-paid {
  background: #dcfce7;
  color: #16a34a;
}

.payment-failed {
  background: #fee2e2;
  color: #dc2626;
}

.payment-refunded {
  background: #e0e7ff;
  color: #5b21b6;
}
</style>