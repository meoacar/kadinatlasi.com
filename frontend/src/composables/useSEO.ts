export function useSEO(data: any = {}) {
  const defaultData = {
    title: 'KadınAtlası.com - Kadınlar İçin Kapsamlı Yaşam Platformu',
    description: 'Kadın sağlığı, gebelik, güzellik, diyet, astroloji ve daha fazlası.',
    keywords: 'kadın sağlığı, gebelik, güzellik, diyet, astroloji'
  }

  const seoData = { ...defaultData, ...data }

  // Sadece title güncelle
  if (typeof document !== 'undefined' && seoData.title) {
    document.title = seoData.title
  }

  return {
    seoData
  }
}