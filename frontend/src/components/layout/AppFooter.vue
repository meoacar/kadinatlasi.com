<template>
  <footer class="modern-footer">
    <div class="footer-container">
      <!-- Main Footer Content -->
      <div class="footer-main">
        <!-- Company Info -->
        <div class="footer-section company-info">
          <div class="logo-section">
            <div class="logo-icon">
              <span>K</span>
            </div>
            <h3 class="company-name">KadınAtlası</h3>
          </div>
          <p class="company-description">
            {{ footerData.general?.footer_description || 'KadınAtlası.com, kadınların günlük hayatını kolaylaştıran dijital platform.' }}
          </p>
          
          <!-- Social Media -->
          <div class="social-links">
            <a v-if="footerData.social?.social_facebook" :href="footerData.social.social_facebook" target="_blank" class="social-link facebook">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <a v-if="footerData.social?.social_instagram" :href="footerData.social.social_instagram" target="_blank" class="social-link instagram">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
            <a v-if="footerData.social?.social_twitter" :href="footerData.social.social_twitter" target="_blank" class="social-link twitter">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
              </svg>
            </a>
            <a v-if="footerData.social?.social_youtube" :href="footerData.social.social_youtube" target="_blank" class="social-link youtube">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
            </a>
            <a v-if="footerData.social?.social_linkedin" :href="footerData.social.social_linkedin" target="_blank" class="social-link linkedin">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
          <h4 class="section-title">Hızlı Linkler</h4>
          <ul class="link-list">
            <li v-for="link in footerData.links?.quick_links || defaultQuickLinks" :key="link.name">
              <RouterLink :to="link.url" class="footer-link">{{ link.name }}</RouterLink>
            </li>
          </ul>
        </div>

        <!-- Categories -->
        <div class="footer-section">
          <h4 class="section-title">Kategoriler</h4>
          <ul class="link-list">
            <li v-for="link in footerData.links?.category_links || defaultCategoryLinks" :key="link.name">
              <RouterLink :to="link.url" class="footer-link">{{ link.name }}</RouterLink>
            </li>
          </ul>
        </div>

        <!-- Contact & Newsletter -->
        <div class="footer-section contact-section">
          <h4 class="section-title">İletişim</h4>
          <div class="contact-info">
            <div v-if="footerData.contact?.contact_email" class="contact-item">
              <svg class="contact-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.89 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
              </svg>
              <a :href="`mailto:${footerData.contact.contact_email}`" class="contact-link">
                {{ footerData.contact.contact_email }}
              </a>
            </div>
            <div v-if="footerData.contact?.contact_phone" class="contact-item">
              <svg class="contact-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
              </svg>
              <span class="contact-link">{{ footerData.contact.contact_phone }}</span>
            </div>
            <div v-if="footerData.contact?.contact_address" class="contact-item">
              <svg class="contact-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              <span class="contact-link">{{ footerData.contact.contact_address }}</span>
            </div>
          </div>


        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="footer-bottom">
        <p class="copyright">
          {{ footerData.general?.footer_copyright || '© 2024 KadınAtlası.com. Tüm hakları saklıdır.' }}
        </p>
        <div class="bottom-links">
          <RouterLink to="/gizlilik-politikasi" class="bottom-link">Gizlilik Politikası</RouterLink>
          <RouterLink to="/kullanim-sartlari" class="bottom-link">Kullanım Şartları</RouterLink>
          <RouterLink to="/iletisim" class="bottom-link">İletişim</RouterLink>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const footerData = ref({})


const defaultQuickLinks = [
  { name: 'Hakkımızda', url: '/hakkimizda' },
  { name: 'İletişim', url: '/iletisim' },
  { name: 'SSS', url: '/sss' },
  { name: 'Destek', url: '/destek' }
]

const defaultCategoryLinks = [
  { name: 'Sağlık', url: '/saglik' },
  { name: 'Gebelik & Anne', url: '/gebelik' },
  { name: 'Güzellik', url: '/guzellik' },
  { name: 'Fitness', url: '/fitness' }
]

const loadFooterData = async () => {
  try {
    const response = await api.get('/footer')
    if (response.data.success) {
      footerData.value = response.data.data
    }
  } catch (error) {
    console.error('Footer data loading error:', error)
  }
}



onMounted(() => {
  loadFooterData()
})
</script>

<style scoped>
.modern-footer {
  background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
  color: white;
  position: relative;
  overflow: hidden;
}

.modern-footer::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, #ec4899, transparent);
}

.footer-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 60px 20px 20px;
}

.footer-main {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 40px;
  margin-bottom: 40px;
}

.footer-section {
  display: flex;
  flex-direction: column;
}

.company-info {
  max-width: 350px;
}

.logo-section {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.logo-icon {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 16px;
  box-shadow: 0 4px 20px rgba(236, 72, 153, 0.3);
}

.logo-icon span {
  color: white;
  font-weight: 800;
  font-size: 1.5rem;
}

.company-name {
  font-size: 1.8rem;
  font-weight: 800;
  background: linear-gradient(135deg, #ec4899, #f472b6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.company-description {
  color: #d1d5db;
  line-height: 1.6;
  margin-bottom: 24px;
  font-size: 0.95rem;
}

.social-links {
  display: flex;
  gap: 16px;
}

.social-link {
  width: 44px;
  height: 44px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.social-link:hover {
  background: rgba(236, 72, 153, 0.2);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.social-link svg {
  width: 20px;
  height: 20px;
  color: #d1d5db;
  transition: color 0.3s ease;
}

.social-link:hover svg {
  color: #ec4899;
}

.section-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: white;
  margin-bottom: 20px;
  position: relative;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 40px;
  height: 2px;
  background: linear-gradient(90deg, #ec4899, #f472b6);
  border-radius: 1px;
}

.link-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.footer-link {
  color: #d1d5db;
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  padding: 4px 0;
  position: relative;
}

.footer-link::before {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 0;
  height: 1px;
  background: #ec4899;
  transition: width 0.3s ease;
}

.footer-link:hover {
  color: #ec4899;
  padding-left: 8px;
}

.footer-link:hover::before {
  width: 20px;
}

.contact-section {
  max-width: 320px;
}

.contact-info {
  margin-bottom: 32px;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}

.contact-icon {
  width: 20px;
  height: 20px;
  color: #ec4899;
  flex-shrink: 0;
}

.contact-link {
  color: #d1d5db;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.3s ease;
}

.contact-link:hover {
  color: #ec4899;
}



.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 32px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

.copyright {
  color: #9ca3af;
  font-size: 0.9rem;
  text-align: center;
  margin: 0;
}

.bottom-links {
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
  justify-content: center;
}

.bottom-link {
  color: #9ca3af;
  text-decoration: none;
  font-size: 0.85rem;
  transition: color 0.3s ease;
}

.bottom-link:hover {
  color: #ec4899;
}

@media (min-width: 768px) {
  .footer-bottom {
    flex-direction: row;
    justify-content: space-between;
  }
  

}

@media (max-width: 767px) {
  .footer-container {
    padding: 40px 16px 20px;
  }
  
  .footer-main {
    gap: 32px;
  }
  

  
  .social-links {
    justify-content: center;
  }
}
</style>