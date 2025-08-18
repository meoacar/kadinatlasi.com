<template>
  <div class="contact-page">
    <div class="contact-container">
      <!-- Header Section -->
      <div class="contact-header">
        <h1 class="page-title">İletişim</h1>
        <p class="page-subtitle">
          Bizimle iletişime geçin. Sorularınız, önerileriniz ve geri bildirimleriniz bizim için değerli.
        </p>
      </div>

      <div class="contact-content">
        <!-- Contact Form -->
        <div class="contact-form-section">
          <div class="form-card">
            <h2 class="form-title">Mesaj Gönder</h2>
            
            <form @submit.prevent="submitForm" class="contact-form">
              <div class="form-group">
                <label for="name" class="form-label">Ad Soyad *</label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="form-input"
                  :class="{ 'error': errors.name }"
                  placeholder="Adınız ve soyadınız"
                  required
                >
                <span v-if="errors.name" class="error-message">{{ errors.name[0] }}</span>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="email" class="form-label">E-posta *</label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="form-input"
                    :class="{ 'error': errors.email }"
                    placeholder="ornek@email.com"
                    required
                  >
                  <span v-if="errors.email" class="error-message">{{ errors.email[0] }}</span>
                </div>

                <div class="form-group">
                  <label for="phone" class="form-label">Telefon</label>
                  <input
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="form-input"
                    :class="{ 'error': errors.phone }"
                    placeholder="0555 123 45 67"
                  >
                  <span v-if="errors.phone" class="error-message">{{ errors.phone[0] }}</span>
                </div>
              </div>

              <div class="form-group">
                <label for="subject" class="form-label">Konu *</label>
                <input
                  id="subject"
                  v-model="form.subject"
                  type="text"
                  class="form-input"
                  :class="{ 'error': errors.subject }"
                  placeholder="Mesajınızın konusu"
                  required
                >
                <span v-if="errors.subject" class="error-message">{{ errors.subject[0] }}</span>
              </div>

              <div class="form-group">
                <label for="message" class="form-label">Mesaj *</label>
                <textarea
                  id="message"
                  v-model="form.message"
                  class="form-textarea"
                  :class="{ 'error': errors.message }"
                  placeholder="Mesajınızı buraya yazın..."
                  rows="6"
                  required
                ></textarea>
                <span v-if="errors.message" class="error-message">{{ errors.message[0] }}</span>
                <div class="character-count">{{ form.message.length }}/2000</div>
              </div>

              <button
                type="submit"
                class="submit-button"
                :disabled="loading"
              >
                <span v-if="loading" class="loading-spinner"></span>
                {{ loading ? 'Gönderiliyor...' : 'Mesaj Gönder' }}
              </button>
            </form>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="contact-info-section">
          <div class="info-card">
            <h3 class="info-title">İletişim Bilgileri</h3>
            
            <div class="info-items">
              <div v-if="contactInfo.contact?.contact_email" class="info-item">
                <div class="info-icon">
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.89 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>E-posta</h4>
                  <p>{{ contactInfo.contact.contact_email }}</p>
                </div>
              </div>

              <div v-if="contactInfo.contact?.contact_phone" class="info-item">
                <div class="info-icon">
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Telefon</h4>
                  <p>{{ contactInfo.contact.contact_phone }}</p>
                </div>
              </div>

              <div v-if="contactInfo.contact?.contact_address" class="info-item">
                <div class="info-icon">
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Adres</h4>
                  <p>{{ contactInfo.contact.contact_address }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="info-card">
            <h3 class="info-title">Çalışma Saatleri</h3>
            <div class="working-hours">
              <div class="hour-item">
                <span>Pazartesi - Cuma</span>
                <span>09:00 - 18:00</span>
              </div>
              <div class="hour-item">
                <span>Cumartesi</span>
                <span>10:00 - 16:00</span>
              </div>
              <div class="hour-item">
                <span>Pazar</span>
                <span>Kapalı</span>
              </div>
            </div>
          </div>

          <div class="info-card">
            <h3 class="info-title">Sosyal Medya</h3>
            <div class="social-links">
              <a v-if="contactInfo.social?.social_facebook" :href="contactInfo.social.social_facebook" target="_blank" class="social-link">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Facebook
              </a>
              <a v-if="contactInfo.social?.social_instagram" :href="contactInfo.social.social_instagram" target="_blank" class="social-link">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                Instagram
              </a>
              <a v-if="contactInfo.social?.social_twitter" :href="contactInfo.social.social_twitter" target="_blank" class="social-link">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
                Twitter
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccess" class="success-modal" @click="showSuccess = false">
      <div class="success-content" @click.stop>
        <div class="success-icon">✓</div>
        <h3>Mesajınız Gönderildi!</h3>
        <p>Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.</p>
        <button @click="showSuccess = false" class="success-button">Tamam</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import api from '@/services/api'

const loading = ref(false)
const showSuccess = ref(false)
const errors = ref({})
const contactInfo = ref({})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: ''
})

const loadContactInfo = async () => {
  try {
    const response = await api.get('/footer')
    if (response.data.success) {
      contactInfo.value = response.data.data
    }
  } catch (error) {
    console.error('Contact info loading error:', error)
  }
}

const submitForm = async () => {
  loading.value = true
  errors.value = {}

  try {
    const response = await api.post('/contact', form)
    
    if (response.data.success) {
      showSuccess.value = true
      // Reset form
      Object.keys(form).forEach(key => {
        form[key] = ''
      })
    }
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      alert('Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyiniz.')
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadContactInfo()
})
</script>

<style scoped>
.contact-page {
  min-height: 80vh;
  padding: 2rem 0;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.contact-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.contact-header {
  text-align: center;
  margin-bottom: 3rem;
}

.page-title {
  font-size: 3rem;
  font-weight: 800;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 1rem;
}

.page-subtitle {
  font-size: 1.2rem;
  color: #6b7280;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}

.contact-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 3rem;
  align-items: start;
}

.form-card, .info-card {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(236, 72, 153, 0.1);
}

.form-title, .info-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 1.5rem;
  position: relative;
}

.form-title::after, .info-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 50px;
  height: 3px;
  background: linear-gradient(90deg, #ec4899, #f472b6);
  border-radius: 2px;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.form-input, .form-textarea {
  padding: 0.875rem 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fafafa;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: #ec4899;
  background: white;
  box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.form-input.error, .form-textarea.error {
  border-color: #ef4444;
  background: #fef2f2;
}

.form-textarea {
  resize: vertical;
  min-height: 120px;
}

.character-count {
  font-size: 0.8rem;
  color: #6b7280;
  text-align: right;
  margin-top: 0.25rem;
}

.error-message {
  color: #ef4444;
  font-size: 0.8rem;
  margin-top: 0.25rem;
}

.submit-button {
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 1rem;
}

.submit-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(236, 72, 153, 0.4);
}

.submit-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.loading-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.contact-info-section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.info-items {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.info-item {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.info-icon {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.info-icon svg {
  width: 24px;
  height: 24px;
  color: white;
}

.info-content h4 {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.25rem;
}

.info-content p {
  color: #6b7280;
  margin: 0;
}

.working-hours {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.hour-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.hour-item:last-child {
  border-bottom: none;
}

.social-links {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.social-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  border-radius: 12px;
  background: #f8fafc;
  color: #374151;
  text-decoration: none;
  transition: all 0.3s ease;
}

.social-link:hover {
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
  transform: translateX(5px);
}

.social-link svg {
  width: 20px;
  height: 20px;
}

.success-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.success-content {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  text-align: center;
  max-width: 400px;
  margin: 1rem;
}

.success-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #10b981, #34d399);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: white;
  margin: 0 auto 1rem;
}

.success-content h3 {
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.success-content p {
  color: #6b7280;
  margin-bottom: 1.5rem;
}

.success-button {
  background: linear-gradient(135deg, #ec4899, #f472b6);
  color: white;
  border: none;
  padding: 0.75rem 2rem;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.success-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.4);
}

@media (max-width: 768px) {
  .contact-content {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .page-title {
    font-size: 2rem;
  }
  
  .form-card, .info-card {
    padding: 1.5rem;
  }
}
</style>