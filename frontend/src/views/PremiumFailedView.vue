<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; padding: 40px; text-align: center; max-width: 500px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
      <div style="font-size: 4rem; margin-bottom: 20px;">❌</div>
      <h1 style="color: #dc2626; font-size: 2rem; font-weight: bold; margin: 0 0 15px 0;">
        Ödeme Başarısız
      </h1>
      <p style="color: #6b7280; margin: 0 0 30px 0; font-size: 1.1rem; line-height: 1.6;">
        Ödeme işlemi sırasında bir hata oluştu. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.
      </p>
      <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <RouterLink 
          to="/premium" 
          style="background: #dc2626; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
        >
          Tekrar Dene
        </RouterLink>
        <RouterLink 
          to="/" 
          style="background: #f3f4f6; color: #374151; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
        >
          Ana Sayfa
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>