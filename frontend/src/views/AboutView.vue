<template>
  <div style="min-height: 100vh; background-color: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">💖 Hakkımızda</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">KadınAtlası.com - Kadınlar için kapsamlı yaşam platformu</p>
      </div>

      <!-- Mission -->
      <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 32px;">
        <h2 style="font-size: 1.75rem; font-weight: bold; color: #111827; margin-bottom: 16px; text-align: center;">Misyonumuz</h2>
        <p style="color: #6b7280; font-size: 1.125rem; line-height: 1.7; text-align: center; max-width: 800px; margin: 0 auto;">
          KadınAtlası.com olarak, kadınların günlük hayatını kolaylaştıracak, bilgi alabilecekleri, hesaplamalar yapabilecekleri, 
          topluluk oluşturabilecekleri ve kadın sağlığı, yaşam, kariyer gibi tüm ilgi alanlarına hitap eden kapsayıcı bir dijital platform sunuyoruz.
        </p>
      </div>

      <!-- Features -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.75rem; font-weight: bold; color: #111827; margin-bottom: 32px; text-align: center;">Neler Sunuyoruz?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
          
          <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📝</div>
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 12px;">Blog & İçerik</h3>
            <p style="color: #6b7280;">8 ana kategoride uzman yazıları ve kullanıcı deneyimleri</p>
          </div>

          <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">🧮</div>
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 12px;">Hesaplama Araçları</h3>
            <p style="color: #6b7280;">VKİ, regl takvimi, gebelik ve kalori hesaplayıcıları</p>
          </div>

          <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">💬</div>
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 12px;">Forum & Topluluk</h3>
            <p style="color: #6b7280;">Kadınların deneyim paylaştığı güvenli topluluk alanı</p>
          </div>

          <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">🤱</div>
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 12px;">Gebelik & Anne</h3>
            <p style="color: #6b7280;">Haftalık gebelik rehberi ve bebek isimleri</p>
          </div>

          <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">💪</div>
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 12px;">Fitness & Diyet</h3>
            <p style="color: #6b7280;">Egzersiz programları ve sağlıklı yemek tarifleri</p>
          </div>

          <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 24px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">🧠</div>
            <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 12px;">Psikolojik Destek</h3>
            <p style="color: #6b7280;">Ruh hali takibi, motivasyon ve psikolojik testler</p>
          </div>

        </div>
      </div>

      <!-- Partnerships -->
      <PartnershipSection />

      <!-- Contact -->
      <div style="background: linear-gradient(135deg, #ec4899, #f472b6); border-radius: 12px; padding: 32px; text-align: center; color: white; margin-top: 48px;">
        <h2 style="font-size: 1.75rem; font-weight: bold; margin-bottom: 16px;">İletişim</h2>
        <p style="font-size: 1.125rem; margin-bottom: 24px; opacity: 0.9;">
          Sorularınız, önerileriniz veya işbirliği teklifleriniz için bizimle iletişime geçin.
        </p>
        <div style="display: flex; justify-content: center; gap: 24px; flex-wrap: wrap;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            <span>info@kadinatlasi.com</span>
          </div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            <span>Türkiye</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import PartnershipSection from '@/components/PartnershipSection.vue'
</script>