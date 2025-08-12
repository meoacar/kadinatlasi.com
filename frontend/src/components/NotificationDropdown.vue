<template>
  <div class="notification-dropdown">
    <button class="notification-button">
      <svg class="notification-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 1 6 6v2.25l2.25 2.25v2.25H2.25V14.25L4.5 12V9.75a6 6 0 0 1 6-6z"/>
      </svg>
    </button>
  </div>
</template>

<script setup lang="ts">
// Basit notification dropdown
</script>

<style scoped>
.notification-button {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #374151;
  background: rgba(236, 72, 153, 0.08);
  border: 1px solid rgba(236, 72, 153, 0.2);
  padding: 10px;
  border-radius: 12px;
  transition: all 0.2s ease;
  cursor: pointer;
}

.notification-button:hover {
  background: rgba(236, 72, 153, 0.15);
  color: #ec4899;
  transform: translateY(-1px);
}

.notification-icon {
  width: 22px;
  height: 22px;
}
</style>