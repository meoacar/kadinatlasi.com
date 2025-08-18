<template>
  <div v-if="isPremium" :class="badgeClass">
    <span class="premium-icon">👑</span>
    <span class="premium-text">{{ badgeText }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  isPremium: boolean
  premiumType?: string
  size?: 'small' | 'medium' | 'large'
  variant?: 'badge' | 'banner' | 'overlay'
}

const props = withDefaults(defineProps<Props>(), {
  premiumType: 'premium',
  size: 'medium',
  variant: 'badge'
})

const badgeText = computed(() => {
  switch (props.premiumType) {
    case 'basic':
      return 'Basic'
    case 'premium':
      return 'Premium'
    case 'vip':
      return 'VIP'
    default:
      return 'Premium'
  }
})

const badgeClass = computed(() => {
  const baseClass = 'premium-badge'
  const sizeClass = `premium-badge--${props.size}`
  const variantClass = `premium-badge--${props.variant}`
  const typeClass = `premium-badge--${props.premiumType}`
  
  return `${baseClass} ${sizeClass} ${variantClass} ${typeClass}`
})
</script>

<style scoped>
.premium-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  border-radius: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.premium-badge--small {
  padding: 2px 6px;
  font-size: 0.7rem;
}

.premium-badge--medium {
  padding: 4px 8px;
  font-size: 0.75rem;
}

.premium-badge--large {
  padding: 6px 12px;
  font-size: 0.85rem;
}

.premium-badge--basic {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.premium-badge--premium {
  background: linear-gradient(135deg, #e57399, #be185d);
  color: white;
}

.premium-badge--vip {
  background: linear-gradient(135deg, #fbbf24, #d97706);
  color: white;
}

.premium-badge--banner {
  width: 100%;
  justify-content: center;
  padding: 8px 16px;
  margin-bottom: 16px;
}

.premium-badge--overlay {
  position: absolute;
  top: 8px;
  right: 8px;
  z-index: 10;
}

.premium-icon {
  font-size: 1em;
}

.premium-text {
  font-size: inherit;
}
</style>