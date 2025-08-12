<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">🏥 Sağlık & Yaşam</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Kadın sağlığı, beslenme ve yaşam kalitesi rehberiniz</p>
      </div>

      <!-- Quick Tools -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px;">⚡ Hızlı Araçlar</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
          
          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
               @click="$router.push('/hesaplama/vki')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="font-size: 2.5rem;">⚖️</div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 6px;">VKİ Hesapla</h3>
                <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Vücut kitle indeksinizi kontrol edin ve ideal kilonuzu öğrenin</p>
              </div>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
               @click="$router.push('/hesaplama/kalori')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="font-size: 2.5rem;">🍎</div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 6px;">Kalori Hesapla</h3>
                <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günlük kalori ihtiyacınızı ve hedeflerinizi belirleyin</p>
              </div>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
               @click="$router.push('/hesaplama/su-ihtiyaci')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="font-size: 2.5rem;">💧</div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 6px;">Su İhtiyacı</h3>
                <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günlük su tüketiminizi hesaplayın ve takip edin</p>
              </div>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
               @click="$router.push('/hesaplama/regl-takvimi')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="font-size: 2.5rem;">🌸</div>
              <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 6px;">Regl Takvimi</h3>
                <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Regl döngünüzü takip edin ve tahmin edin</p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Health Topics -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 24px;">🩺 Sağlık Konuları</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
          
          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('kadin-sagligi')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #fce7f3, #f3e8ff); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🩺</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Kadın Sağlığı</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Jinekolojik sağlık, hormonal değişimler, menopoz ve kadına özel sağlık konuları hakkında kapsamlı bilgiler.
              </p>
              <div style="color: #ec4899; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('beslenme')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #ecfdf5, #f0fdf4); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🥗</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Beslenme & Diyet</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Sağlıklı beslenme, diyet önerileri, vitamin-mineral rehberi ve besin değerleri hakkında uzman tavsiyeleri.
              </p>
              <div style="color: #ec4899; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('fitness')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #fef3c7, #fef7cd); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🏃‍♀️</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Fitness & Egzersiz</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Kadınlara özel egzersiz programları, yoga, pilates, fitness ipuçları ve aktif yaşam rehberi.
              </p>
              <div style="color: #ec4899; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('mental-saglik')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #e0f2fe, #e1f5fe); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🧘‍♀️</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Mental Sağlık</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Stres yönetimi, meditasyon, mindfulness ve ruh sağlığı için pratik öneriler ve teknikler.
              </p>
              <div style="color: #ec4899; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('cilt-bakim')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #fef7cd, #fef3c7); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">✨</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Cilt Bakımı</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Cilt bakım rutinleri, doğal maskeler, anti-aging önerileri ve cilt tipine göre bakım rehberi.
              </p>
              <div style="color: #ec4899; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="navigateToTopic('uyku-sagligi')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #e0e7ff, #e5e7eb); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">😴</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Uyku Sağlığı</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Kaliteli uyku için öneriler, uyku bozuklukları ve dinlendirici uyku rutinleri hakkında bilgiler.
              </p>
              <div style="color: #ec4899; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                Keşfet <span>→</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Health Tips -->
      <div style="background: white; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); padding: 40px; margin-bottom: 48px;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 32px; text-align: center;">💡 Günlük Sağlık İpuçları</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
          
          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">💧</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Bol Su İçin</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günde en az 2-2.5 litre su için. Cildiniz ve metabolizmanız için çok önemli.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🚶‍♀️</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Düzenli Hareket</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günde 30-45 dakika yürüyüş veya hafif egzersiz yapın.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">😴</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Kaliteli Uyku</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">7-8 saat düzenli uyku alın. Uyku saatlerinizi düzenleyin.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🥬</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Sebze & Meyve</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günde 5-7 porsiyon taze meyve ve sebze tüketin.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🧘‍♀️</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Stres Yönetimi</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günde 10 dakika meditasyon veya nefes egzersizi yapın.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">☀️</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">D Vitamini</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Günde 15-20 dakika güneş ışığından faydalanın.</p>
          </div>

        </div>
      </div>

      <!-- Expert Consultation -->
      <div style="background: linear-gradient(135deg, #fce7f3, #f3e8ff); border-radius: 20px; padding: 40px; text-align: center;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 16px;">👩‍⚕️ Uzman Desteği</h2>
        <p style="color: #6b7280; font-size: 1.125rem; margin-bottom: 24px; max-width: 600px; margin-left: auto; margin-right: auto;">
          Sağlık konularında uzman görüşü almak istiyorsanız, deneyimli doktorlarımızdan destek alabilirsiniz.
        </p>
        <button 
          @click="$router.push('/uzman-soru-sor')"
          style="background: #ec4899; color: white; padding: 16px 32px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; font-size: 1rem; transition: all 0.2s ease;"
          onmouseover="this.style.background='#db2777'; this.style.transform='translateY(-2px)'"
          onmouseout="this.style.background='#ec4899'; this.style.transform='translateY(0)'"
        >
          Uzman Soru Sor
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const navigateToTopic = (topic) => {
  // Şimdilik blog sayfasına yönlendir, ileride özel sayfalar yapılabilir
  router.push(`/blog?kategori=${topic}`)
}
</script>