<template>
  <div class="astrology-page">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-content">
        <div class="hero-stars">✨</div>
        <h1 class="hero-title">Astroloji Dünyası</h1>
        <p class="hero-subtitle">Yıldızların size söylediklerini keşfedin</p>
        <div class="hero-decoration">
          <span class="star">⭐</span>
          <span class="moon">🌙</span>
          <span class="star">✨</span>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <div class="main-container">
      
      <!-- Zodiac Signs Grid -->
      <section class="zodiac-section">
        <h2 class="section-title">Burcunuzu Seçin</h2>
        <div class="zodiac-grid">
          <div 
            v-for="sign in zodiacSigns" 
            :key="sign.key"
            @click="selectSign(sign.key)"
            class="zodiac-card"
            :class="{ active: selectedSign === sign.key }"
          >
            <div class="zodiac-icon">{{ sign.icon }}</div>
            <h3 class="zodiac-name">{{ sign.name }}</h3>
            <p class="zodiac-dates">{{ sign.dates }}</p>
            <div class="zodiac-element">{{ sign.element }}</div>
          </div>
        </div>
      </section>

      <!-- Period Selection -->
      <section class="period-section">
        <div class="period-tabs">
          <button 
            v-for="period in periods" 
            :key="period.key"
            @click="selectPeriod(period.key)"
            class="period-tab"
            :class="{ active: selectedPeriod === period.key }"
          >
            <span class="period-icon">{{ period.icon }}</span>
            <span class="period-name">{{ period.name }}</span>
          </button>
        </div>
      </section>

      <!-- Horoscope Content -->
      <section v-if="horoscope && !loading" class="horoscope-section">
        <div class="horoscope-header">
          <div class="selected-sign">
            <span class="sign-icon">{{ getSignIcon(selectedSign) }}</span>
            <div class="sign-info">
              <h2 class="sign-name">{{ getSignName(selectedSign) }}</h2>
              <p class="sign-period">{{ getPeriodName(selectedPeriod) }} Yorumu</p>
            </div>
          </div>
          <div class="horoscope-date">{{ formatDate(horoscope.date) }}</div>
        </div>

        <!-- Categories Grid -->
        <div class="categories-grid">
          <div class="category-card love">
            <div class="category-header">
              <span class="category-icon">💕</span>
              <h3 class="category-title">Aşk & İlişkiler</h3>
            </div>
            <p class="category-content">{{ horoscope.love }}</p>
            <div class="category-rating">
              <span v-for="i in 5" :key="i" class="star" :class="{ filled: i <= horoscope.love_rating }">⭐</span>
            </div>
          </div>

          <div class="category-card career">
            <div class="category-header">
              <span class="category-icon">💼</span>
              <h3 class="category-title">Kariyer & İş</h3>
            </div>
            <p class="category-content">{{ horoscope.career }}</p>
            <div class="category-rating">
              <span v-for="i in 5" :key="i" class="star" :class="{ filled: i <= horoscope.career_rating }">⭐</span>
            </div>
          </div>

          <div class="category-card health">
            <div class="category-header">
              <span class="category-icon">🌿</span>
              <h3 class="category-title">Sağlık & Enerji</h3>
            </div>
            <p class="category-content">{{ horoscope.health }}</p>
            <div class="category-rating">
              <span v-for="i in 5" :key="i" class="star" :class="{ filled: i <= horoscope.health_rating }">⭐</span>
            </div>
          </div>

          <div class="category-card money">
            <div class="category-header">
              <span class="category-icon">💰</span>
              <h3 class="category-title">Para & Finans</h3>
            </div>
            <p class="category-content">{{ horoscope.money || 'Bu dönem için finansal durumunuz istikrarlı görünüyor.' }}</p>
            <div class="category-rating">
              <span v-for="i in 5" :key="i" class="star" :class="{ filled: i <= (horoscope.money_rating || 3) }">⭐</span>
            </div>
          </div>
        </div>

        <!-- General Reading -->
        <div class="general-reading">
          <div class="reading-header">
            <span class="reading-icon">🔮</span>
            <h3 class="reading-title">Genel Yorum</h3>
          </div>
          <div class="reading-content">
            <p>{{ horoscope.general }}</p>
          </div>
          <div class="reading-footer">
            <div class="lucky-numbers">
              <span class="label">Şanslı Sayılar:</span>
              <span class="numbers">{{ horoscope.lucky_numbers || '7, 14, 21' }}</span>
            </div>
            <div class="lucky-colors">
              <span class="label">Şanslı Renkler:</span>
              <span class="colors">{{ horoscope.lucky_colors || 'Mavi, Yeşil' }}</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Loading State -->
      <section v-if="loading" class="loading-section">
        <div class="loading-animation">
          <div class="crystal">💎</div>
          <div class="loading-text">Yıldızlar okunuyor...</div>
          <div class="loading-dots">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </section>

      <!-- Astrology Tools -->
      <section class="tools-section">
        <h2 class="section-title">Astroloji Araçları</h2>
        <div class="tools-grid">
          
          <!-- Birth Chart Calculator -->
          <div class="tool-card">
            <div class="tool-header">
              <span class="tool-icon">🎂</span>
              <h3 class="tool-title">Doğum Tarihi Burç Hesaplama</h3>
            </div>
            <div class="tool-content">
              <div class="input-group">
                <label class="input-label">Doğum Tarihiniz:</label>
                <input 
                  v-model="birthDate" 
                  type="date" 
                  class="date-input"
                  @change="calculateZodiacSign"
                >
              </div>
              <div v-if="calculatedSign" class="result-display">
                <div class="result-icon">{{ getSignIcon(calculatedSign) }}</div>
                <div class="result-text">
                  <h4>Burcunuz: {{ getSignName(calculatedSign) }}</h4>
                  <p>{{ getSignDescription(calculatedSign) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Love Compatibility -->
          <div class="tool-card">
            <div class="tool-header">
              <span class="tool-icon">💕</span>
              <h3 class="tool-title">Aşk Uyumu Hesaplama</h3>
            </div>
            <div class="tool-content">
              <div class="compatibility-inputs">
                <div class="input-group">
                  <label class="input-label">Sizin Burcunuz:</label>
                  <select v-model="compatibilitySign1" class="sign-select">
                    <option value="">Burç Seçin</option>
                    <option v-for="sign in zodiacSigns" :key="sign.key" :value="sign.key">
                      {{ sign.icon }} {{ sign.name }}
                    </option>
                  </select>
                </div>
                <div class="input-group">
                  <label class="input-label">Partnerinizin Burcu:</label>
                  <select v-model="compatibilitySign2" class="sign-select">
                    <option value="">Burç Seçin</option>
                    <option v-for="sign in zodiacSigns" :key="sign.key" :value="sign.key">
                      {{ sign.icon }} {{ sign.name }}
                    </option>
                  </select>
                </div>
                <button @click="calculateCompatibility" class="calculate-btn" :disabled="!compatibilitySign1 || !compatibilitySign2">
                  Uyumu Hesapla
                </button>
              </div>
              <div v-if="compatibilityResult" class="compatibility-result">
                <div class="compatibility-header">
                  <span class="sign-pair">
                    {{ getSignIcon(compatibilitySign1) }} & {{ getSignIcon(compatibilitySign2) }}
                  </span>
                  <div class="compatibility-score">
                    <div class="score-circle" :style="{ background: getCompatibilityColor(compatibilityResult.score) }">
                      {{ compatibilityResult.score }}%
                    </div>
                  </div>
                </div>
                <div class="compatibility-details">
                  <h4 class="compatibility-title">{{ compatibilityResult.title }}</h4>
                  <p class="compatibility-description">{{ compatibilityResult.description }}</p>
                  <div class="compatibility-aspects">
                    <div class="aspect">
                      <span class="aspect-label">Aşk:</span>
                      <div class="aspect-rating">
                        <span v-for="i in 5" :key="i" class="heart" :class="{ filled: i <= compatibilityResult.love_rating }">💖</span>
                      </div>
                    </div>
                    <div class="aspect">
                      <span class="aspect-label">İletişim:</span>
                      <div class="aspect-rating">
                        <span v-for="i in 5" :key="i" class="heart" :class="{ filled: i <= compatibilityResult.communication_rating }">💬</span>
                      </div>
                    </div>
                    <div class="aspect">
                      <span class="aspect-label">Uyum:</span>
                      <div class="aspect-rating">
                        <span v-for="i in 5" :key="i" class="heart" :class="{ filled: i <= compatibilityResult.harmony_rating }">🎵</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      <!-- Daily Tips -->
      <section class="tips-section">
        <h2 class="section-title">Günün Astroloji İpuçları</h2>
        <div class="tips-grid">
          <div class="tip-card">
            <span class="tip-icon">🌟</span>
            <h4 class="tip-title">Meditasyon Zamanı</h4>
            <p class="tip-content">Bugün 10 dakika meditasyon yaparak enerjinizi yenileyin.</p>
          </div>
          <div class="tip-card">
            <span class="tip-icon">🌙</span>
            <h4 class="tip-title">Ay Enerjisi</h4>
            <p class="tip-content">Ay'ın bugünkü konumu duygusal dengenizi destekliyor.</p>
          </div>
          <div class="tip-card">
            <span class="tip-icon">✨</span>
            <h4 class="tip-title">Kristal Önerisi</h4>
            <p class="tip-content">Ametist kristali bugün size huzur ve berraklık getirecek.</p>
          </div>
        </div>
      </section>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const selectedSign = ref('koc')
const selectedPeriod = ref('daily')
const horoscope = ref(null)
const loading = ref(false)

// Birth chart calculator
const birthDate = ref('')
const calculatedSign = ref('')

// Love compatibility
const compatibilitySign1 = ref('')
const compatibilitySign2 = ref('')
const compatibilityResult = ref(null)

const zodiacSigns = [
  { key: 'koc', name: 'Koç', icon: '♈', dates: '21 Mart - 19 Nisan', element: 'Ateş' },
  { key: 'boga', name: 'Boğa', icon: '♉', dates: '20 Nisan - 20 Mayıs', element: 'Toprak' },
  { key: 'ikizler', name: 'İkizler', icon: '♊', dates: '21 Mayıs - 20 Haziran', element: 'Hava' },
  { key: 'yengec', name: 'Yengeç', icon: '♋', dates: '21 Haziran - 22 Temmuz', element: 'Su' },
  { key: 'aslan', name: 'Aslan', icon: '♌', dates: '23 Temmuz - 22 Ağustos', element: 'Ateş' },
  { key: 'basak', name: 'Başak', icon: '♍', dates: '23 Ağustos - 22 Eylül', element: 'Toprak' },
  { key: 'terazi', name: 'Terazi', icon: '♎', dates: '23 Eylül - 22 Ekim', element: 'Hava' },
  { key: 'akrep', name: 'Akrep', icon: '♏', dates: '23 Ekim - 21 Kasım', element: 'Su' },
  { key: 'yay', name: 'Yay', icon: '♐', dates: '22 Kasım - 21 Aralık', element: 'Ateş' },
  { key: 'oglak', name: 'Oğlak', icon: '♑', dates: '22 Aralık - 19 Ocak', element: 'Toprak' },
  { key: 'kova', name: 'Kova', icon: '♒', dates: '20 Ocak - 18 Şubat', element: 'Hava' },
  { key: 'balik', name: 'Balık', icon: '♓', dates: '19 Şubat - 20 Mart', element: 'Su' }
]

const periods = [
  { key: 'daily', name: 'Günlük', icon: '☀️' },
  { key: 'weekly', name: 'Haftalık', icon: '📅' },
  { key: 'monthly', name: 'Aylık', icon: '🗓️' }
]

const selectSign = (signKey: string) => {
  selectedSign.value = signKey
  fetchHoroscope()
}

const selectPeriod = (periodKey: string) => {
  selectedPeriod.value = periodKey
  fetchHoroscope()
}

const getSignIcon = (signKey: string) => {
  return zodiacSigns.find(s => s.key === signKey)?.icon || '♈'
}

const getSignName = (signKey: string) => {
  return zodiacSigns.find(s => s.key === signKey)?.name || 'Koç'
}

const getPeriodName = (periodKey: string) => {
  return periods.find(p => p.key === periodKey)?.name || 'Günlük'
}

const formatDate = (dateStr: string) => {
  if (!dateStr) return new Date().toLocaleDateString('tr-TR')
  return new Date(dateStr).toLocaleDateString('tr-TR')
}

const fetchHoroscope = async () => {
  loading.value = true
  try {
    const response = await axios.get(`/api/astrology/${selectedPeriod.value}`, {
      params: { sign: selectedSign.value }
    })
    horoscope.value = response.data
  } catch (error) {
    console.error('Burç yorumu yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const calculateZodiacSign = () => {
  if (!birthDate.value) return
  
  const date = new Date(birthDate.value)
  const month = date.getMonth() + 1
  const day = date.getDate()
  
  if ((month === 3 && day >= 21) || (month === 4 && day <= 19)) {
    calculatedSign.value = 'koc'
  } else if ((month === 4 && day >= 20) || (month === 5 && day <= 20)) {
    calculatedSign.value = 'boga'
  } else if ((month === 5 && day >= 21) || (month === 6 && day <= 20)) {
    calculatedSign.value = 'ikizler'
  } else if ((month === 6 && day >= 21) || (month === 7 && day <= 22)) {
    calculatedSign.value = 'yengec'
  } else if ((month === 7 && day >= 23) || (month === 8 && day <= 22)) {
    calculatedSign.value = 'aslan'
  } else if ((month === 8 && day >= 23) || (month === 9 && day <= 22)) {
    calculatedSign.value = 'basak'
  } else if ((month === 9 && day >= 23) || (month === 10 && day <= 22)) {
    calculatedSign.value = 'terazi'
  } else if ((month === 10 && day >= 23) || (month === 11 && day <= 21)) {
    calculatedSign.value = 'akrep'
  } else if ((month === 11 && day >= 22) || (month === 12 && day <= 21)) {
    calculatedSign.value = 'yay'
  } else if ((month === 12 && day >= 22) || (month === 1 && day <= 19)) {
    calculatedSign.value = 'oglak'
  } else if ((month === 1 && day >= 20) || (month === 2 && day <= 18)) {
    calculatedSign.value = 'kova'
  } else {
    calculatedSign.value = 'balik'
  }
}

const getSignDescription = (signKey: string) => {
  const descriptions = {
    koc: 'Enerjik, cesur ve liderlik özelliklerine sahip bir ateş burcu.',
    boga: 'Kararlı, güvenilir ve pratik bir toprak burcu.',
    ikizler: 'Meraklı, sosyal ve çok yönlü bir hava burcu.',
    yengec: 'Duygusal, koruyucu ve sezgisel bir su burcu.',
    aslan: 'Yaratıcı, cömert ve dramatik bir ateş burcu.',
    basak: 'Mükemmeliyetçi, analitik ve hizmet odaklı bir toprak burcu.',
    terazi: 'Dengeli, diplomatik ve estetik bir hava burcu.',
    akrep: 'Yoğun, tutkulu ve gizemli bir su burcu.',
    yay: 'Özgür, iyimser ve maceraperest bir ateş burcu.',
    oglak: 'Disiplinli, hırslı ve sorumlu bir toprak burcu.',
    kova: 'Bağımsız, yenilikçi ve insancıl bir hava burcu.',
    balik: 'Hayal gücü kuvvetli, şefkatli ve sezgisel bir su burcu.'
  }
  return descriptions[signKey] || ''
}

const calculateCompatibility = () => {
  if (!compatibilitySign1.value || !compatibilitySign2.value) return
  
  const compatibilityData = {
    'koc-boga': { score: 65, title: 'Orta Uyum', love_rating: 3, communication_rating: 3, harmony_rating: 3 },
    'koc-ikizler': { score: 85, title: 'Yüksek Uyum', love_rating: 4, communication_rating: 5, harmony_rating: 4 },
    'koc-yengec': { score: 45, title: 'Düşük Uyum', love_rating: 2, communication_rating: 2, harmony_rating: 3 },
    'koc-aslan': { score: 95, title: 'Mükemmel Uyum', love_rating: 5, communication_rating: 5, harmony_rating: 5 },
    'koc-basak': { score: 55, title: 'Orta Uyum', love_rating: 3, communication_rating: 2, harmony_rating: 3 },
    'koc-terazi': { score: 75, title: 'İyi Uyum', love_rating: 4, communication_rating: 4, harmony_rating: 3 },
    'koc-akrep': { score: 70, title: 'İyi Uyum', love_rating: 4, communication_rating: 3, harmony_rating: 4 },
    'koc-yay': { score: 90, title: 'Mükemmel Uyum', love_rating: 5, communication_rating: 4, harmony_rating: 5 },
    'koc-oglak': { score: 60, title: 'Orta Uyum', love_rating: 3, communication_rating: 3, harmony_rating: 3 },
    'koc-kova': { score: 80, title: 'Yüksek Uyum', love_rating: 4, communication_rating: 4, harmony_rating: 4 },
    'koc-balik': { score: 50, title: 'Orta Uyum', love_rating: 3, communication_rating: 2, harmony_rating: 3 }
  }
  
  const key1 = `${compatibilitySign1.value}-${compatibilitySign2.value}`
  const key2 = `${compatibilitySign2.value}-${compatibilitySign1.value}`
  
  let result = compatibilityData[key1] || compatibilityData[key2]
  
  if (!result) {
    // Default compatibility for same signs
    if (compatibilitySign1.value === compatibilitySign2.value) {
      result = { score: 85, title: 'Yüksek Uyum', love_rating: 4, communication_rating: 5, harmony_rating: 4 }
    } else {
      result = { score: 70, title: 'İyi Uyum', love_rating: 3, communication_rating: 3, harmony_rating: 4 }
    }
  }
  
  result.description = getCompatibilityDescription(result.score)
  compatibilityResult.value = result
}

const getCompatibilityDescription = (score: number) => {
  if (score >= 90) return 'Bu eşleşme gerçekten mükemmel! Birbirinizi çok iyi anlıyor ve destekliyorsunuz.'
  if (score >= 75) return 'Harika bir uyumunuz var! Küçük farklılıklarınız ilişkinizi daha da zenginleştiriyor.'
  if (score >= 60) return 'İyi bir uyumunuz var. Biraz çaba ile harika bir ilişki kurabilirsiniz.'
  if (score >= 45) return 'Orta düzeyde uyumunuz var. Sabır ve anlayış ile güzel bir ilişki geliştirebilirsiniz.'
  return 'Zorlu ama öğretici bir eşleşme. Birbirinizden çok şey öğrenebilirsiniz.'
}

const getCompatibilityColor = (score: number) => {
  if (score >= 90) return 'linear-gradient(135deg, #ff6b9d, #c44569)'
  if (score >= 75) return 'linear-gradient(135deg, #f8b500, #feca57)'
  if (score >= 60) return 'linear-gradient(135deg, #48cae4, #0077b6)'
  if (score >= 45) return 'linear-gradient(135deg, #a8dadc, #457b9d)'
  return 'linear-gradient(135deg, #6c757d, #495057)'
}

onMounted(() => {
  fetchHoroscope()
})
</script>

<style scoped>
.astrology-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #0f0c29 0%, #24243e 50%, #302b63 100%);
  position: relative;
  overflow-x: hidden;
}

.astrology-page::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
  pointer-events: none;
}

/* Hero Section */
.hero-section {
  padding: 80px 20px 60px;
  text-align: center;
  position: relative;
  z-index: 1;
}

.hero-content {
  max-width: 800px;
  margin: 0 auto;
}

.hero-stars {
  font-size: 4rem;
  margin-bottom: 20px;
  animation: twinkle 2s ease-in-out infinite alternate;
}

@keyframes twinkle {
  0% { opacity: 0.5; transform: scale(1); }
  100% { opacity: 1; transform: scale(1.1); }
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 16px;
  text-shadow: 0 0 30px rgba(102, 126, 234, 0.5);
}

.hero-subtitle {
  font-size: 1.5rem;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 30px;
}

.hero-decoration {
  display: flex;
  justify-content: center;
  gap: 30px;
  font-size: 2rem;
}

.hero-decoration .star,
.hero-decoration .moon {
  animation: float 3s ease-in-out infinite;
}

.hero-decoration .star:nth-child(2) {
  animation-delay: -1s;
}

.hero-decoration .star:nth-child(3) {
  animation-delay: -2s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

/* Main Container */
.main-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 20px 80px;
  position: relative;
  z-index: 1;
}

/* Section Titles */
.section-title {
  font-size: 2.5rem;
  font-weight: 700;
  text-align: center;
  color: white;
  margin-bottom: 40px;
  text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
}

/* Zodiac Section */
.zodiac-section {
  margin-bottom: 60px;
}

.zodiac-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.zodiac-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  padding: 24px;
  text-align: center;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.zodiac-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transition: left 0.6s;
}

.zodiac-card:hover::before {
  left: 100%;
}

.zodiac-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
  border-color: rgba(102, 126, 234, 0.5);
}

.zodiac-card.active {
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
  border-color: #667eea;
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
}

.zodiac-icon {
  font-size: 3rem;
  margin-bottom: 12px;
  filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
}

.zodiac-name {
  font-size: 1.25rem;
  font-weight: 700;
  color: white;
  margin-bottom: 8px;
}

.zodiac-dates {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 8px;
}

.zodiac-element {
  display: inline-block;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* Period Section */
.period-section {
  margin-bottom: 60px;
}

.period-tabs {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
}

.period-tab {
  display: flex;
  align-items: center;
  gap: 12px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 50px;
  padding: 16px 32px;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.period-tab:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
}

.period-tab.active {
  background: linear-gradient(135deg, #667eea, #764ba2);
  border-color: #667eea;
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.period-icon {
  font-size: 1.5rem;
}

.period-name {
  font-size: 1.1rem;
}

/* Horoscope Section */
.horoscope-section {
  margin-bottom: 60px;
}

.horoscope-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  padding: 24px;
  margin-bottom: 30px;
}

.selected-sign {
  display: flex;
  align-items: center;
  gap: 16px;
}

.sign-icon {
  font-size: 3rem;
  filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
}

.sign-name {
  font-size: 2rem;
  font-weight: 700;
  color: white;
  margin: 0;
}

.sign-period {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.7);
  margin: 0;
}

.horoscope-date {
  color: rgba(255, 255, 255, 0.8);
  font-size: 1.1rem;
  font-weight: 500;
}

/* Categories Grid */
.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
  margin-bottom: 40px;
}

.category-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  padding: 24px;
  transition: all 0.3s ease;
}

.category-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.category-card.love {
  border-left: 4px solid #ff6b9d;
}

.category-card.career {
  border-left: 4px solid #4ecdc4;
}

.category-card.health {
  border-left: 4px solid #95e1d3;
}

.category-card.money {
  border-left: 4px solid #ffd93d;
}

.category-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}

.category-icon {
  font-size: 2rem;
}

.category-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: white;
  margin: 0;
}

.category-content {
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.6;
  margin-bottom: 16px;
}

.category-rating {
  display: flex;
  gap: 4px;
}

.category-rating .star {
  font-size: 1.2rem;
  opacity: 0.3;
  transition: opacity 0.3s ease;
}

.category-rating .star.filled {
  opacity: 1;
}

/* General Reading */
.general-reading {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  padding: 32px;
  margin-bottom: 40px;
}

.reading-header {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 20px;
}

.reading-icon {
  font-size: 2.5rem;
}

.reading-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
  margin: 0;
}

.reading-content p {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.1rem;
  line-height: 1.7;
  margin-bottom: 24px;
}

.reading-footer {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
}

.lucky-numbers,
.lucky-colors {
  display: flex;
  align-items: center;
  gap: 8px;
}

.label {
  color: rgba(255, 255, 255, 0.7);
  font-weight: 600;
}

.numbers,
.colors {
  color: white;
  font-weight: 700;
  background: rgba(255, 255, 255, 0.1);
  padding: 4px 12px;
  border-radius: 12px;
}

/* Loading Section */
.loading-section {
  text-align: center;
  padding: 80px 20px;
}

.loading-animation {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

.crystal {
  font-size: 4rem;
  animation: rotate 2s linear infinite;
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.loading-text {
  font-size: 1.5rem;
  color: white;
  font-weight: 600;
}

.loading-dots {
  display: flex;
  gap: 8px;
}

.loading-dots span {
  width: 12px;
  height: 12px;
  background: #667eea;
  border-radius: 50%;
  animation: bounce 1.4s ease-in-out infinite both;
}

.loading-dots span:nth-child(1) { animation-delay: -0.32s; }
.loading-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes bounce {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1); }
}

/* Tools Section */
.tools-section {
  margin-bottom: 60px;
}

.tools-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 30px;
  margin-bottom: 60px;
}

.tool-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  padding: 30px;
  transition: all 0.3s ease;
}

.tool-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
}

.tool-header {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
}

.tool-icon {
  font-size: 2.5rem;
  filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
}

.tool-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
  margin: 0;
}

.tool-content {
  color: white;
}

.input-group {
  margin-bottom: 20px;
}

.input-label {
  display: block;
  color: rgba(255, 255, 255, 0.9);
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 1rem;
}

.date-input,
.sign-select {
  width: 100%;
  padding: 12px 16px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  color: white;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.date-input:focus,
.sign-select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.sign-select option {
  background: #2d3748;
  color: white;
}

.calculate-btn {
  width: 100%;
  padding: 14px 24px;
  background: linear-gradient(135deg, #667eea, #764ba2);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 10px;
}

.calculate-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.calculate-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.result-display {
  display: flex;
  align-items: center;
  gap: 20px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 20px;
  margin-top: 20px;
  border: 1px solid rgba(102, 126, 234, 0.3);
}

.result-icon {
  font-size: 3rem;
  filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.7));
}

.result-text h4 {
  color: white;
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0 0 8px 0;
}

.result-text p {
  color: rgba(255, 255, 255, 0.8);
  margin: 0;
  line-height: 1.5;
}

.compatibility-inputs {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.compatibility-result {
  margin-top: 24px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 24px;
  border: 1px solid rgba(102, 126, 234, 0.3);
}

.compatibility-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.sign-pair {
  font-size: 2.5rem;
  filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
}

.compatibility-score {
  display: flex;
  align-items: center;
}

.score-circle {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  font-weight: 800;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.compatibility-title {
  color: white;
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 12px 0;
}

.compatibility-description {
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.6;
  margin-bottom: 20px;
}

.compatibility-aspects {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.aspect {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
}

.aspect-label {
  color: rgba(255, 255, 255, 0.8);
  font-weight: 600;
}

.aspect-rating {
  display: flex;
  gap: 4px;
}

.aspect-rating .heart {
  font-size: 1.2rem;
  opacity: 0.3;
  transition: opacity 0.3s ease;
}

.aspect-rating .heart.filled {
  opacity: 1;
}

/* Tips Section */
.tips-section {
  margin-bottom: 60px;
}

.tips-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}

.tip-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  padding: 24px;
  text-align: center;
  transition: all 0.3s ease;
}

.tip-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.tip-icon {
  font-size: 2.5rem;
  margin-bottom: 16px;
  display: block;
}

.tip-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: white;
  margin-bottom: 12px;
}

.tip-content {
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.2rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .zodiac-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
  }
  
  .period-tabs {
    flex-direction: column;
    align-items: center;
  }
  
  .horoscope-header {
    flex-direction: column;
    gap: 16px;
    text-align: center;
  }
  
  .categories-grid {
    grid-template-columns: 1fr;
  }
  
  .reading-footer {
    flex-direction: column;
    align-items: center;
  }
  
  .tools-grid {
    grid-template-columns: 1fr;
  }
  
  .compatibility-header {
    flex-direction: column;
    gap: 16px;
    text-align: center;
  }
  
  .result-display {
    flex-direction: column;
    text-align: center;
  }
  
  .aspect {
    flex-direction: column;
    gap: 8px;
    text-align: center;
  }
}
</style>