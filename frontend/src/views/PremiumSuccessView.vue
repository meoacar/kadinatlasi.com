<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; padding: 40px; text-align: center; max-width: 500px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
      <div style="font-size: 4rem; margin-bottom: 20px;">🎉</div>
      <h1 style="color: #16a34a; font-size: 2rem; font-weight: bold; margin: 0 0 15px 0;">
        Ödeme Başarılı!
      </h1>
      <p style="color: #6b7280; margin: 0 0 30px 0; font-size: 1.1rem; line-height: 1.6;">
        Premium üyeliğiniz başarıyla aktif edildi. Artık tüm premium özelliklerden yararlanabilirsiniz.
      </p>
      <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <RouterLink 
          to="/dashboard" 
          style="background: #16a34a; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
        >
          Dashboard'a Git
        </RouterLink>
        <RouterLink 
          to="/premium" 
          style="background: #f3f4f6; color: #374151; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
        >
          Premium Sayfası
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>