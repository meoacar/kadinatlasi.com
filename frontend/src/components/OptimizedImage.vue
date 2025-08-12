<template>
  <picture>
    <source 
      v-if="webpSrc" 
      :srcset="webpSrcSet" 
      type="image/webp"
    >
    <img 
      :src="src" 
      :alt="alt" 
      :loading="loading"
      :class="imageClass"
      :style="imageStyle"
      @load="onLoad"
      @error="onError"
    >
  </picture>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

interface Props {
  src: string
  alt: string
  webpSrc?: string
  sizes?: string[]
  loading?: 'lazy' | 'eager'
  class?: string
  style?: string | object
}

const props = withDefaults(defineProps<Props>(), {
  loading: 'lazy',
  sizes: () => [400, 800, 1200]
})

const emit = defineEmits<{
  load: [event: Event]
  error: [event: Event]
}>()

const isLoaded = ref(false)
const hasError = ref(false)

const webpSrcSet = computed(() => {
  if (!props.webpSrc) return ''
  
  if (props.sizes.length > 1) {
    return props.sizes
      .map(size => `${getWebPPath(props.webpSrc!, size)} ${size}w`)
      .join(', ')
  }
  
  return props.webpSrc
})

const imageClass = computed(() => {
  const classes = [props.class]
  if (!isLoaded.value) classes.push('loading')
  if (hasError.value) classes.push('error')
  return classes.filter(Boolean).join(' ')
})

const imageStyle = computed(() => {
  if (typeof props.style === 'string') return props.style
  return props.style
})

const getWebPPath = (originalPath: string, size?: number) => {
  if (!size) return originalPath.replace(/\.(jpg|jpeg|png)$/i, '.webp')
  
  const pathParts = originalPath.split('.')
  const extension = pathParts.pop()
  const basePath = pathParts.join('.')
  
  return `${basePath}_${size}w.webp`
}

const onLoad = (event: Event) => {
  isLoaded.value = true
  emit('load', event)
}

const onError = (event: Event) => {
  hasError.value = true
  emit('error', event)
}
</script>

<style scoped>
img {
  transition: opacity 0.3s ease;
}

img.loading {
  opacity: 0.7;
}

img.error {
  opacity: 0.5;
  filter: grayscale(100%);
}
</style>