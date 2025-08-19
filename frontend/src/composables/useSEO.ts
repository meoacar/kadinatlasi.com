export function useSEO(data: any = {}) {
  const defaultData = {
    title: 'KadınAtlası.com - Kadınlar İçin Kapsamlı Yaşam Platformu',
    description: 'Kadın sağlığı, gebelik, güzellik, diyet, astroloji ve daha fazlası.',
    keywords: 'kadın sağlığı, gebelik, güzellik, diyet, astroloji',
    type: 'website',
    url: 'https://kadinatlasi.com',
    image: 'https://kadinatlasi.com/logo.png'
  }

  const seoData = { ...defaultData, ...data }

  if (typeof document !== 'undefined') {
    // Title
    if (seoData.title) {
      document.title = seoData.title
    }

    // Clear existing meta tags
    const existingMetas = document.querySelectorAll('meta[data-seo]')
    existingMetas.forEach(meta => meta.remove())

    // Helper function to create meta tag
    const createMeta = (attrs: Record<string, string>) => {
      const meta = document.createElement('meta')
      Object.entries(attrs).forEach(([key, value]) => {
        meta.setAttribute(key, value)
      })
      meta.setAttribute('data-seo', 'true')
      document.head.appendChild(meta)
    }

    // Basic SEO tags
    if (seoData.description) {
      createMeta({ name: 'description', content: seoData.description })
    }
    if (seoData.keywords) {
      createMeta({ name: 'keywords', content: seoData.keywords })
    }
    if (seoData.author) {
      createMeta({ name: 'author', content: seoData.author })
    }

    // Open Graph tags
    createMeta({ property: 'og:title', content: seoData.title })
    createMeta({ property: 'og:description', content: seoData.description })
    createMeta({ property: 'og:type', content: seoData.type })
    createMeta({ property: 'og:url', content: seoData.url })
    createMeta({ property: 'og:image', content: seoData.image })
    createMeta({ property: 'og:site_name', content: 'KadınAtlası' })

    // Twitter Card tags
    createMeta({ name: 'twitter:card', content: 'summary_large_image' })
    createMeta({ name: 'twitter:title', content: seoData.title })
    createMeta({ name: 'twitter:description', content: seoData.description })
    createMeta({ name: 'twitter:image', content: seoData.image })

    // Article specific tags
    if (seoData.type === 'article') {
      if (seoData.publishedTime) {
        createMeta({ property: 'article:published_time', content: seoData.publishedTime })
      }
      if (seoData.modifiedTime) {
        createMeta({ property: 'article:modified_time', content: seoData.modifiedTime })
      }
      if (seoData.author) {
        createMeta({ property: 'article:author', content: seoData.author })
      }
      if (seoData.section) {
        createMeta({ property: 'article:section', content: seoData.section })
      }
      if (seoData.tags && Array.isArray(seoData.tags)) {
        seoData.tags.forEach((tag: string) => {
          createMeta({ property: 'article:tag', content: tag })
        })
      }
    }

    // JSON-LD Structured Data
    if (seoData.type === 'article') {
      const structuredData = {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": seoData.title,
        "description": seoData.description,
        "image": seoData.image,
        "author": {
          "@type": "Person",
          "name": seoData.author || "KadınAtlası"
        },
        "publisher": {
          "@type": "Organization",
          "name": "KadınAtlası",
          "logo": {
            "@type": "ImageObject",
            "url": "https://kadinatlasi.com/logo.png"
          }
        },
        "datePublished": seoData.publishedTime,
        "dateModified": seoData.modifiedTime,
        "url": seoData.url
      }

      // Remove existing JSON-LD
      const existingJsonLd = document.querySelector('script[type="application/ld+json"][data-seo]')
      if (existingJsonLd) {
        existingJsonLd.remove()
      }

      // Add new JSON-LD
      const script = document.createElement('script')
      script.type = 'application/ld+json'
      script.setAttribute('data-seo', 'true')
      script.textContent = JSON.stringify(structuredData)
      document.head.appendChild(script)
    }
  }

  return {
    seoData
  }
}