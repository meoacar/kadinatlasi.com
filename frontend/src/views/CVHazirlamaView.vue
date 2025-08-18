<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 2rem 0;">
    <!-- Hero Section -->
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 4rem 0; margin-bottom: 3rem;">
      <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center;">
        <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1.5rem; color: white;">📝 CV Hazırlama Rehberi</h1>
        <p style="font-size: 1.3rem; max-width: 800px; margin: 0 auto; opacity: 0.95;">Profesyonel CV hazırlama ipuçları, örnekler ve adım adım rehber ile hayalinizdeki işe başvurun</p>
      </div>
    </div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
      <!-- CV Builder Tool -->
      <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem; color: #1f2937; text-align: center;">
          🚀 Hızlı CV Oluşturucu
        </h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
          <!-- Form Section -->
          <div>
            <div style="margin-bottom: 2rem;">
              <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Kişisel Bilgiler</h3>
              <div style="display: grid; gap: 1rem;">
                <input v-model="cvData.name" placeholder="Ad Soyad" style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                <input v-model="cvData.email" placeholder="E-posta" style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                <input v-model="cvData.phone" placeholder="Telefon" style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                <input v-model="cvData.location" placeholder="Şehir" style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
              </div>
            </div>

            <div style="margin-bottom: 2rem;">
              <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Özet</h3>
              <textarea v-model="cvData.summary" placeholder="Kendinizi kısaca tanıtın..." style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; width: 100%; height: 100px; resize: vertical;"></textarea>
            </div>

            <div style="margin-bottom: 2rem;">
              <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Deneyim</h3>
              <div v-for="(exp, index) in cvData.experience" :key="index" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                <input v-model="exp.position" placeholder="Pozisyon" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; width: 100%; margin-bottom: 0.5rem;">
                <input v-model="exp.company" placeholder="Şirket" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; width: 100%; margin-bottom: 0.5rem;">
                <input v-model="exp.duration" placeholder="Süre (örn: 2020-2023)" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; width: 100%; margin-bottom: 0.5rem;">
                <textarea v-model="exp.description" placeholder="Açıklama" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; width: 100%; height: 60px;"></textarea>
              </div>
              <button @click="addExperience" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer;">+ Deneyim Ekle</button>
            </div>

            <div style="margin-bottom: 2rem;">
              <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Eğitim</h3>
              <div v-for="(edu, index) in cvData.education" :key="index" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                <input v-model="edu.degree" placeholder="Derece/Bölüm" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; width: 100%; margin-bottom: 0.5rem;">
                <input v-model="edu.school" placeholder="Okul" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; width: 100%; margin-bottom: 0.5rem;">
                <input v-model="edu.year" placeholder="Yıl" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; width: 100%;">
              </div>
              <button @click="addEducation" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer;">+ Eğitim Ekle</button>
            </div>

            <div>
              <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Yetenekler</h3>
              <input v-model="skillInput" @keyup.enter="addSkill" placeholder="Yetenek ekleyin ve Enter'a basın" style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; width: 100%; margin-bottom: 1rem;">
              <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <span v-for="(skill, index) in cvData.skills" :key="index" style="background: #3b82f6; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; cursor: pointer;" @click="removeSkill(index)">
                  {{ skill }} ×
                </span>
              </div>
            </div>
          </div>

          <!-- Preview Section -->
          <div style="background: #f9fafb; border-radius: 12px; padding: 2rem; border: 2px solid #e5e7eb;">
            <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1.5rem; color: #374151; text-align: center;">CV Önizleme</h3>
            
            <div class="cv-preview" :style="`background: ${currentTemplate.bg}; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: Arial, sans-serif; line-height: 1.4;`">
              <!-- Header -->
              <div :style="`text-align: center; margin-bottom: 2rem; border-bottom: 2px solid ${currentTemplate.colors.secondary}; padding-bottom: 1rem;`">
                <h1 :style="`font-size: 1.8rem; font-weight: 700; color: ${currentTemplate.colors.primary}; margin-bottom: 0.5rem;`">{{ cvData.name || 'Ad Soyad' }}</h1>
                <div style="color: #6b7280; font-size: 0.9rem;">
                  {{ cvData.email || 'email@example.com' }} | {{ cvData.phone || '+90 555 123 45 67' }} | {{ cvData.location || 'İstanbul' }}
                </div>
              </div>

              <!-- Summary -->
              <div v-if="cvData.summary" style="margin-bottom: 1.5rem;">
                <h2 :style="`font-size: 1.1rem; font-weight: 600; color: ${currentTemplate.colors.secondary}; margin-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;`">ÖZET</h2>
                <p style="color: #374151; font-size: 0.9rem;">{{ cvData.summary }}</p>
              </div>

              <!-- Experience -->
              <div v-if="cvData.experience.length > 0" style="margin-bottom: 1.5rem;">
                <h2 :style="`font-size: 1.1rem; font-weight: 600; color: ${currentTemplate.colors.secondary}; margin-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;`">DENEYİM</h2>
                <div v-for="exp in cvData.experience" :key="exp" style="margin-bottom: 1rem;">
                  <div style="font-weight: 600; color: #1f2937; font-size: 0.95rem;">{{ exp.position || 'Pozisyon' }}</div>
                  <div style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.25rem;">{{ exp.company || 'Şirket' }} | {{ exp.duration || 'Süre' }}</div>
                  <div style="color: #374151; font-size: 0.85rem;">{{ exp.description }}</div>
                </div>
              </div>

              <!-- Education -->
              <div v-if="cvData.education.length > 0" style="margin-bottom: 1.5rem;">
                <h2 :style="`font-size: 1.1rem; font-weight: 600; color: ${currentTemplate.colors.secondary}; margin-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;`">EĞİTİM</h2>
                <div v-for="edu in cvData.education" :key="edu" style="margin-bottom: 0.75rem;">
                  <div style="font-weight: 600; color: #1f2937; font-size: 0.9rem;">{{ edu.degree || 'Derece/Bölüm' }}</div>
                  <div style="color: #6b7280; font-size: 0.85rem;">{{ edu.school || 'Okul' }} | {{ edu.year || 'Yıl' }}</div>
                </div>
              </div>

              <!-- Skills -->
              <div v-if="cvData.skills.length > 0">
                <h2 :style="`font-size: 1.1rem; font-weight: 600; color: ${currentTemplate.colors.secondary}; margin-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;`">YETENEKLER</h2>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                  <span v-for="skill in cvData.skills" :key="skill" style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                    {{ skill }}
                  </span>
                </div>
              </div>
            </div>

            <div style="text-align: center; margin-top: 1.5rem;">
              <button @click="downloadCV" style="background: #10b981; color: white; padding: 0.75rem 2rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 1rem;">
                📄 CV'yi İndir
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- CV Tips -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
          <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1f2937; display: flex; align-items: center;">
            <span style="font-size: 2rem; margin-right: 0.5rem;">✅</span>
            CV Hazırlama İpuçları
          </h3>
          <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• Kısa ve öz tutun (maksimum 2 sayfa)</li>
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• Anahtar kelimeleri kullanın</li>
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• Başarılarınızı sayılarla destekleyin</li>
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• Profesyonel bir e-posta adresi kullanın</li>
            <li style="padding: 0.75rem 0; color: #374151;">• Yazım hatalarını kontrol edin</li>
          </ul>
        </div>

        <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
          <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1f2937; display: flex; align-items: center;">
            <span style="font-size: 2rem; margin-right: 0.5rem;">❌</span>
            Kaçınılması Gerekenler
          </h3>
          <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• Fotoğraf eklemeyin (gerekli değilse)</li>
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• Kişisel bilgileri fazla paylaşmayın</li>
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• Çok renkli tasarımlar kullanmayın</li>
            <li style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; color: #374151;">• İlgisiz deneyimleri eklemeyin</li>
            <li style="padding: 0.75rem 0; color: #374151;">• Yalan bilgi vermeyin</li>
          </ul>
        </div>
      </div>

      <!-- CV Templates -->
      <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem; color: #1f2937; text-align: center;">
          🎨 CV Şablonları
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
          <div v-for="template in cvTemplates" :key="template.id" :style="`border: 2px solid ${selectedTemplate === template.id ? template.colors.secondary : '#e5e7eb'}; border-radius: 12px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s ease; background: ${selectedTemplate === template.id ? template.colors.bg : 'white'};`" @click="useTemplate(template)">
            <div style="font-size: 3rem; margin-bottom: 1rem;">{{ template.icon }}</div>
            <h4 style="font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">{{ template.name }}</h4>
            <p style="color: #6b7280; font-size: 0.875rem;">{{ template.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, watch } from 'vue'

const cvData = reactive({
  name: '',
  email: '',
  phone: '',
  location: '',
  summary: '',
  experience: [{ position: '', company: '', duration: '', description: '' }],
  education: [{ degree: '', school: '', year: '' }],
  skills: []
})

const skillInput = ref('')
const selectedTemplate = ref('classic')

const cvTemplates = ref([
  {
    id: 'classic',
    name: 'Klasik',
    icon: '📄',
    description: 'Geleneksel ve profesyonel tasarım',
    colors: { primary: '#1f2937', secondary: '#3b82f6', bg: '#ffffff' }
  },
  {
    id: 'modern',
    name: 'Modern',
    icon: '🎯',
    description: 'Çağdaş ve şık görünüm',
    colors: { primary: '#0f172a', secondary: '#8b5cf6', bg: '#f8fafc' }
  },
  {
    id: 'creative',
    name: 'Kreatif',
    icon: '🎨',
    description: 'Yaratıcı sektörler için',
    colors: { primary: '#7c2d12', secondary: '#f59e0b', bg: '#fffbeb' }
  },
  {
    id: 'minimal',
    name: 'Minimal',
    icon: '✨',
    description: 'Sade ve temiz tasarım',
    colors: { primary: '#374151', secondary: '#10b981', bg: '#f9fafb' }
  }
])

const currentTemplate = ref(cvTemplates.value[0])

const addExperience = () => {
  cvData.experience.push({ position: '', company: '', duration: '', description: '' })
}

const addEducation = () => {
  cvData.education.push({ degree: '', school: '', year: '' })
}

const addSkill = () => {
  if (skillInput.value.trim()) {
    cvData.skills.push(skillInput.value.trim())
    skillInput.value = ''
  }
}

const removeSkill = (index: number) => {
  cvData.skills.splice(index, 1)
}

const useTemplate = (template: any) => {
  selectedTemplate.value = template.id
  currentTemplate.value = template
  saveCV()
}

const saveCV = () => {
  const cvDataToSave = {
    ...cvData,
    template: selectedTemplate.value,
    savedAt: new Date().toISOString()
  }
  localStorage.setItem('cvData', JSON.stringify(cvDataToSave))
}

const loadCV = () => {
  const saved = localStorage.getItem('cvData')
  if (saved) {
    const data = JSON.parse(saved)
    Object.assign(cvData, data)
    selectedTemplate.value = data.template || 'classic'
    currentTemplate.value = cvTemplates.value.find(t => t.id === selectedTemplate.value) || cvTemplates.value[0]
  }
}

const downloadCV = () => {
  const cvElement = document.querySelector('.cv-preview')
  if (!cvElement) {
    alert('CV onizlemesi bulunamadi!')
    return
  }
  
  try {
    const printWindow = window.open('', '_blank')
    if (!printWindow) {
      alert('Pop-up engelleyici aktif olabilir. Lutfen pop-up lari etkinlestirin.')
      return
    }
    
    printWindow.document.write(`
      <!DOCTYPE html>
      <html>
        <head>
          <title>CV - ${cvData.name || 'CV'}</title>
          <style>
            body { 
              font-family: Arial, sans-serif; 
              margin: 20px; 
              line-height: 1.4;
            }
            @media print { 
              body { margin: 0; }
              * { -webkit-print-color-adjust: exact !important; }
            }
          </style>
        </head>
        <body>
          ${cvElement.innerHTML}
        </body>
      </html>
    `)
    
    printWindow.document.close()
    
    setTimeout(() => {
      printWindow.print()
      setTimeout(() => printWindow.close(), 1000)
    }, 500)
    
  } catch (error) {
    console.error('CV indirme hatası:', error)
    alert('CV indirilemedi. Lutfen tekrar deneyin.')
  }
}

// Auto-save when data changes
watch(cvData, () => {
  saveCV()
}, { deep: true })

onMounted(() => {
  loadCV()
})
</script>

<style>
@media (max-width: 768px) {
  div[style*="grid-template-columns: 1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>