<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">🧠 Psikoloji & Mental Sağlık</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Ruh sağlığınız için kapsamlı rehber ve destek</p>
      </div>

      <!-- Quick Assessment Tools -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px;">🔍 Hızlı Değerlendirme</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
          
          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
               @click="openAssessment('stres')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="font-size: 2.5rem;">😰</div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 6px;">Stres Testi</h3>
                <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Stres seviyenizi ölçün ve yönetim önerilerini alın</p>
              </div>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
               @click="openAssessment('anksiyete')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="font-size: 2.5rem;">😟</div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 6px;">Anksiyete Testi</h3>
                <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Kaygı durumunuzu değerlendirin ve çözüm önerileri alın</p>
              </div>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
               @click="openAssessment('depresyon')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="font-size: 2.5rem;">😔</div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 6px;">Depresyon Testi</h3>
                <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Ruh halinizi değerlendirin ve destek alın</p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Psychology Topics -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px;">🧘 Psikoloji Konuları</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
          
          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('stres-yonetimi')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #fef3c7, #fef7cd); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🧘♀️</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Stres Yönetimi</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Günlük stresi azaltmak için etkili teknikler, nefes egzersizleri ve rahatlama yöntemleri.
              </p>
              <div style="color: #6366f1; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('anksiyete-kaygı')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #e0f2fe, #e1f5fe); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">😌</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Anksiyete & Kaygı</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Kaygı bozukluklarını anlama, başa çıkma stratejileri ve profesyonel destek alma rehberi.
              </p>
              <div style="color: #6366f1; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('depresyon')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #fce7f3, #f3e8ff); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">💜</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Depresyon</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Depresyonu tanıma, belirtileri anlama ve iyileşme sürecinde destek alma yolları.
              </p>
              <div style="color: #6366f1; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('ozguven')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #ecfdf5, #f0fdf4); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">💪</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Özgüven</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Özgüven geliştirme teknikleri, kendini kabul etme ve pozitif düşünce alışkanlıkları.
              </p>
              <div style="color: #6366f1; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('iliskiler')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #fef7cd, #fef3c7); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">❤️</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">İlişkiler</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Sağlıklı ilişkiler kurma, iletişim becerileri ve çift terapisi rehberi.
              </p>
              <div style="color: #6366f1; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('mindfulness')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #e0e7ff, #e5e7eb); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🧘</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Mindfulness</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Farkındalık meditasyonu, şimdiki ana odaklanma ve zihinsel berraklık teknikleri.
              </p>
              <div style="color: #6366f1; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Interactive Tools -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px;">🛠️ İnteraktif Araçlar</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
          
          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease;"
               @click="openTool('mood-tracker')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 16px;">📊</div>
              <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Ruh Hali Takibi</h3>
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günlük ruh halinizi kaydedin ve değişimleri takip edin</p>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease;"
               @click="openTool('breathing')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 16px;">🫁</div>
              <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Nefes Egzersizi</h3>
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Rehberli nefes egzersizleri ile rahatlamayı öğrenin</p>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease;"
               @click="openTool('meditation')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 16px;">🎵</div>
              <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Meditasyon</h3>
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Rehberli meditasyon seansları ve rahatlatıcı müzikler</p>
            </div>
          </div>

        </div>
      </div>

      <!-- Daily Tips -->
      <div style="background: white; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); padding: 40px; margin-bottom: 48px;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 32px; text-align: center;">💡 Günlük Mental Sağlık İpuçları</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
          
          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🌅</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Sabah Rutini</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Güne pozitif bir başlangıç yapın. 5 dakika meditasyon ile başlayın.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">📝</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Günlük Tutun</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Duygularınızı yazarak ifade edin. Bu zihinsel berraklık sağlar.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🤝</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Sosyal Bağlantı</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Sevdiklerinizle vakit geçirin. İnsan ilişkileri çok önemli.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🚫</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Sınır Koyun</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">"Hayır" demeyi öğrenin. Kendinizi korumak önemli.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🎨</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Yaratıcılık</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Sanat, müzik veya yazı ile kendinizi ifade edin.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🌿</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Doğa</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Doğada vakit geçirin. Yeşillik zihinsel sağlığa iyi gelir.</p>
          </div>

        </div>
      </div>

      <!-- Professional Support -->
      <div style="background: linear-gradient(135deg, #e0f2fe, #e1f5fe); border-radius: 20px; padding: 40px; text-align: center;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 16px;">👩‍⚕️ Profesyonel Destek</h2>
        <p style="color: #6b7280; font-size: 1.125rem; margin-bottom: 24px; max-width: 600px; margin-left: auto; margin-right: auto;">
          Psikolojik destek almak cesaret ister. Uzman psikologlarımızdan yardım almaktan çekinmeyin.
        </p>
        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
          <button 
            @click="$router.push('/uzman-soru-sor')"
            style="background: #6366f1; color: white; padding: 16px 32px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; font-size: 1rem; transition: all 0.2s ease;"
            onmouseover="this.style.background='#4f46e5'; this.style.transform='translateY(-2px)'"
            onmouseout="this.style.background='#6366f1'; this.style.transform='translateY(0)'"
          >
            Psikolog Soru Sor
          </button>
          <button 
            @click="$router.push('/forum')"
            style="background: white; color: #6366f1; padding: 16px 32px; border-radius: 12px; font-weight: 600; border: 2px solid #6366f1; cursor: pointer; font-size: 1rem; transition: all 0.2s ease;"
            onmouseover="this.style.background='#6366f1'; this.style.color='white'"
            onmouseout="this.style.background='white'; this.style.color='#6366f1'"
          >
            Forum'a Katıl
          </button>
        </div>
      </div>

    </div>

    <!-- Psychology Test Modal -->
    <PsychologyTest 
      :test-type="currentTestType" 
      :show-test="showTest" 
      @close="closeTest"
    />

    <!-- Psychology Tools Modal -->
    <PsychologyTools 
      :tool-type="currentToolType" 
      :show-tool="showTool" 
      @close="closeTool"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import PsychologyTest from '@/components/PsychologyTest.vue'
import PsychologyTools from '@/components/PsychologyTools.vue'

const router = useRouter()
const showTest = ref(false)
const currentTestType = ref('')
const showTool = ref(false)
const currentToolType = ref('')

const navigateToTopic = (topic) => {
  router.push(`/blog?kategori=${topic}`)
}

const openAssessment = (type) => {
  currentTestType.value = type
  showTest.value = true
}

const closeTest = () => {
  showTest.value = false
  currentTestType.value = ''
}

const openTool = (tool) => {
  currentToolType.value = tool
  showTool.value = true
}

const closeTool = () => {
  showTool.value = false
  currentToolType.value = ''
}
</script>