<template>
  <div v-if="showTest" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 16px;">
    <div style="background: white; border-radius: 20px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
      
      <!-- Header -->
      <div style="padding: 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin: 0;">{{ testData.title }}</h2>
        <button @click="closeTest" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">×</button>
      </div>

      <!-- Test Content -->
      <div style="padding: 24px;">
        
        <!-- Question -->
        <div v-if="!showResult" style="margin-bottom: 24px;">
          <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 16px;">
            <span style="font-size: 0.875rem; color: #6b7280;">Soru {{ currentQuestion + 1 }} / {{ testData.questions.length }}</span>
            <div style="background: #f3f4f6; border-radius: 10px; height: 8px; flex: 1; margin: 0 16px;">
              <div style="background: #6366f1; height: 100%; border-radius: 10px; transition: width 0.3s ease;" :style="{ width: ((currentQuestion + 1) / testData.questions.length * 100) + '%' }"></div>
            </div>
          </div>
          
          <h3 style="font-size: 1.125rem; font-weight: 500; color: #111827; margin-bottom: 20px; line-height: 1.5;">
            {{ testData.questions[currentQuestion].question }}
          </h3>
          
          <div style="display: flex; flex-direction: column; gap: 12px;">
            <button
              v-for="(option, index) in testData.questions[currentQuestion].options"
              :key="index"
              @click="selectAnswer(index)"
              style="padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; background: white; cursor: pointer; text-align: left; transition: all 0.2s ease; font-size: 0.875rem;"
              onmouseover="this.style.borderColor='#6366f1'; this.style.background='#f8fafc'"
              onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='white'"
            >
              {{ option.text }}
            </button>
          </div>
        </div>

        <!-- Result -->
        <div v-else style="text-align: center;">
          <div style="font-size: 4rem; margin-bottom: 16px;">{{ getResultEmoji() }}</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">{{ getResultTitle() }}</h3>
          <p style="color: #6b7280; font-size: 1rem; line-height: 1.6; margin-bottom: 24px;">{{ getResultDescription() }}</p>
          
          <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h4 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Öneriler:</h4>
            <ul style="text-align: left; color: #6b7280; font-size: 0.875rem; line-height: 1.5;">
              <li v-for="suggestion in getResultSuggestions()" :key="suggestion" style="margin-bottom: 8px;">• {{ suggestion }}</li>
            </ul>
          </div>

          <div style="display: flex; gap: 12px; justify-content: center;">
            <button @click="restartTest" style="background: #6366f1; color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500;">
              Tekrar Yap
            </button>
            <button @click="closeTest" style="background: #f3f4f6; color: #374151; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500;">
              Kapat
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  testType: String,
  showTest: Boolean
})

const emit = defineEmits(['close'])

const currentQuestion = ref(0)
const answers = ref([])
const showResult = ref(false)

const testData = computed(() => {
  const tests = {
    stres: {
      title: '🔍 Stres Seviyesi Testi',
      questions: [
        {
          question: 'Son bir hafta içinde ne sıklıkla kendinizi gergin hissettiniz?',
          options: [
            { text: 'Hiçbir zaman', score: 0 },
            { text: 'Nadiren', score: 1 },
            { text: 'Bazen', score: 2 },
            { text: 'Sık sık', score: 3 },
            { text: 'Her zaman', score: 4 }
          ]
        },
        {
          question: 'Uyku kaliteniz nasıl?',
          options: [
            { text: 'Çok iyi, rahat uyuyorum', score: 0 },
            { text: 'Genellikle iyi', score: 1 },
            { text: 'Orta, bazen sorun yaşıyorum', score: 2 },
            { text: 'Kötü, sık sık uyanıyorum', score: 3 },
            { text: 'Çok kötü, uyuyamıyorum', score: 4 }
          ]
        },
        {
          question: 'Günlük işlerinizi yaparken ne hissediyorsunuz?',
          options: [
            { text: 'Rahat ve kontrol halinde', score: 0 },
            { text: 'Genellikle rahat', score: 1 },
            { text: 'Bazen zorlanıyorum', score: 2 },
            { text: 'Sık sık bunalmış hissediyorum', score: 3 },
            { text: 'Sürekli baskı altındayım', score: 4 }
          ]
        },
        {
          question: 'Fiziksel belirtiler yaşıyor musunuz? (baş ağrısı, kas gerginliği vb.)',
          options: [
            { text: 'Hiç yaşamıyorum', score: 0 },
            { text: 'Nadiren', score: 1 },
            { text: 'Bazen', score: 2 },
            { text: 'Sık sık', score: 3 },
            { text: 'Sürekli', score: 4 }
          ]
        },
        {
          question: 'Sosyal aktivitelere katılma isteğiniz nasıl?',
          options: [
            { text: 'Çok istekliyim', score: 0 },
            { text: 'Genellikle istekliyim', score: 1 },
            { text: 'Kararsızım', score: 2 },
            { text: 'Pek istemiyorum', score: 3 },
            { text: 'Hiç istemiyorum', score: 4 }
          ]
        }
      ]
    },
    anksiyete: {
      title: '🔍 Anksiyete Değerlendirmesi',
      questions: [
        {
          question: 'Gelecek hakkında endişelenme sıklığınız?',
          options: [
            { text: 'Hiç endişelenmem', score: 0 },
            { text: 'Nadiren', score: 1 },
            { text: 'Bazen', score: 2 },
            { text: 'Sık sık', score: 3 },
            { text: 'Sürekli', score: 4 }
          ]
        },
        {
          question: 'Kalp çarpıntısı yaşar mısınız?',
          options: [
            { text: 'Hiçbir zaman', score: 0 },
            { text: 'Nadiren', score: 1 },
            { text: 'Bazen', score: 2 },
            { text: 'Sık sık', score: 3 },
            { text: 'Çok sık', score: 4 }
          ]
        },
        {
          question: 'Sosyal durumlarda kendinizi nasıl hissedersiniz?',
          options: [
            { text: 'Çok rahat', score: 0 },
            { text: 'Genellikle rahat', score: 1 },
            { text: 'Biraz gergin', score: 2 },
            { text: 'Çok gergin', score: 3 },
            { text: 'Panik halinde', score: 4 }
          ]
        },
        {
          question: 'Nefes almakta zorlanır mısınız?',
          options: [
            { text: 'Hiçbir zaman', score: 0 },
            { text: 'Nadiren', score: 1 },
            { text: 'Bazen', score: 2 },
            { text: 'Sık sık', score: 3 },
            { text: 'Çok sık', score: 4 }
          ]
        },
        {
          question: 'Kontrolü kaybetme korkusu yaşar mısınız?',
          options: [
            { text: 'Hiçbir zaman', score: 0 },
            { text: 'Nadiren', score: 1 },
            { text: 'Bazen', score: 2 },
            { text: 'Sık sık', score: 3 },
            { text: 'Sürekli', score: 4 }
          ]
        }
      ]
    },
    depresyon: {
      title: '🔍 Ruh Hali Değerlendirmesi',
      questions: [
        {
          question: 'Son iki hafta içinde ruh haliniz nasıldı?',
          options: [
            { text: 'Çok iyi, mutluydum', score: 0 },
            { text: 'Genellikle iyi', score: 1 },
            { text: 'Karışık, inişli çıkışlı', score: 2 },
            { text: 'Çoğunlukla üzgün', score: 3 },
            { text: 'Sürekli üzgün ve umutsuz', score: 4 }
          ]
        },
        {
          question: 'Günlük aktivitelere ilginiz nasıl?',
          options: [
            { text: 'Çok ilgiliyim', score: 0 },
            { text: 'Genellikle ilgiliyim', score: 1 },
            { text: 'Bazen ilgimi çekiyor', score: 2 },
            { text: 'Pek ilgimi çekmiyor', score: 3 },
            { text: 'Hiç ilgimi çekmiyor', score: 4 }
          ]
        },
        {
          question: 'Enerji seviyeniz nasıl?',
          options: [
            { text: 'Çok enerjik', score: 0 },
            { text: 'Genellikle enerjik', score: 1 },
            { text: 'Orta seviye', score: 2 },
            { text: 'Düşük enerji', score: 3 },
            { text: 'Çok yorgun, bitkin', score: 4 }
          ]
        },
        {
          question: 'Kendiniz hakkındaki düşünceleriniz?',
          options: [
            { text: 'Kendimi değerli hissediyorum', score: 0 },
            { text: 'Genellikle olumlu', score: 1 },
            { text: 'Karışık duygular', score: 2 },
            { text: 'Kendimi değersiz hissediyorum', score: 3 },
            { text: 'Kendimden nefret ediyorum', score: 4 }
          ]
        },
        {
          question: 'Konsantrasyon problemi yaşıyor musunuz?',
          options: [
            { text: 'Hiç yaşamıyorum', score: 0 },
            { text: 'Nadiren', score: 1 },
            { text: 'Bazen', score: 2 },
            { text: 'Sık sık', score: 3 },
            { text: 'Sürekli', score: 4 }
          ]
        }
      ]
    }
  }
  return tests[props.testType] || tests.stres
})

const selectAnswer = (optionIndex) => {
  answers.value[currentQuestion.value] = testData.value.questions[currentQuestion.value].options[optionIndex].score
  
  if (currentQuestion.value < testData.value.questions.length - 1) {
    currentQuestion.value++
  } else {
    showResult.value = true
  }
}

const getTotalScore = () => {
  return answers.value.reduce((sum, score) => sum + score, 0)
}

const getResultEmoji = () => {
  const score = getTotalScore()
  const maxScore = testData.value.questions.length * 4
  const percentage = (score / maxScore) * 100
  
  if (percentage <= 20) return '😊'
  if (percentage <= 40) return '😐'
  if (percentage <= 60) return '😟'
  if (percentage <= 80) return '😰'
  return '😔'
}

const getResultTitle = () => {
  const score = getTotalScore()
  const maxScore = testData.value.questions.length * 4
  const percentage = (score / maxScore) * 100
  
  if (percentage <= 20) return 'Harika! Çok iyi durumdasınız'
  if (percentage <= 40) return 'İyi durumdasınız'
  if (percentage <= 60) return 'Orta seviye - Dikkat edin'
  if (percentage <= 80) return 'Yüksek seviye - Destek alın'
  return 'Çok yüksek - Mutlaka uzman desteği alın'
}

const getResultDescription = () => {
  const score = getTotalScore()
  const maxScore = testData.value.questions.length * 4
  const percentage = (score / maxScore) * 100
  
  if (props.testType === 'stres') {
    if (percentage <= 20) return 'Stres seviyeniz çok düşük. Hayatınızı iyi yönetiyorsunuz.'
    if (percentage <= 40) return 'Stres seviyeniz normal aralıkta. Küçük iyileştirmeler yapabilirsiniz.'
    if (percentage <= 60) return 'Orta seviye stres yaşıyorsunuz. Stres yönetimi tekniklerini öğrenmelisiniz.'
    if (percentage <= 80) return 'Yüksek stres seviyeniz var. Yaşam tarzı değişiklikleri gerekli.'
    return 'Çok yüksek stres yaşıyorsunuz. Mutlaka profesyonel destek almalısınız.'
  }
  
  if (props.testType === 'anksiyete') {
    if (percentage <= 20) return 'Anksiyete seviyeniz çok düşük. Mental sağlığınız iyi durumda.'
    if (percentage <= 40) return 'Hafif anksiyete belirtileri gösteriyorsunuz. Normal aralıkta.'
    if (percentage <= 60) return 'Orta seviye anksiyete yaşıyorsunuz. Rahatlama tekniklerini deneyin.'
    if (percentage <= 80) return 'Yüksek anksiyete seviyeniz var. Uzman desteği faydalı olacaktır.'
    return 'Çok yüksek anksiyete yaşıyorsunuz. Mutlaka bir uzmana başvurun.'
  }
  
  // depresyon
  if (percentage <= 20) return 'Ruh haliniz çok iyi. Pozitif mental sağlığa sahipsiniz.'
  if (percentage <= 40) return 'Ruh haliniz genel olarak iyi. Küçük dalgalanmalar normal.'
  if (percentage <= 60) return 'Ruh halinizde bazı sorunlar var. Kendinize daha çok özen gösterin.'
  if (percentage <= 80) return 'Depresif belirtiler gösteriyorsunuz. Destek almanız önemli.'
  return 'Ciddi depresif belirtiler var. Mutlaka bir uzman ile görüşün.'
}

const getResultSuggestions = () => {
  const score = getTotalScore()
  const maxScore = testData.value.questions.length * 4
  const percentage = (score / maxScore) * 100
  
  if (percentage <= 40) {
    return [
      'Mevcut pozitif alışkanlıklarınızı sürdürün',
      'Düzenli egzersiz yapmaya devam edin',
      'Sosyal bağlantılarınızı güçlü tutun',
      'Yeterli uyku almaya özen gösterin'
    ]
  } else if (percentage <= 60) {
    return [
      'Günlük meditasyon veya nefes egzersizleri yapın',
      'Düzenli uyku saatleri oluşturun',
      'Stres kaynaklarınızı belirleyin ve azaltın',
      'Sevdiklerinizle daha fazla vakit geçirin'
    ]
  } else {
    return [
      'Mutlaka bir uzman psikolog ile görüşün',
      'Günlük rutininizi gözden geçirin',
      'Rahatlama tekniklerini öğrenin',
      'Destek gruplarına katılmayı düşünün',
      'Fiziksel aktiviteyi artırın'
    ]
  }
}

const restartTest = () => {
  currentQuestion.value = 0
  answers.value = []
  showResult.value = false
}

const closeTest = () => {
  restartTest()
  emit('close')
}
</script>