<template>
  <div style="min-height: 100vh; background: #f9fafb;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">🧮 Hesaplama Araçları</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Sağlık ve yaşam kalitesi için pratik hesaplayıcılar</p>
      </div>

      <!-- Calculator Cards -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
        
        <!-- BMI Calculator -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/vki')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">⚖️</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">VKİ Hesaplayıcı</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Vücut kitle indeksinizi hesaplayın ve ideal kilonuzu öğrenin</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Menstrual Cycle -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/regl-takvimi')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">📅</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Regl Takvimi</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Regl döngünüzü takip edin ve ovulasyon tarihini öğrenin</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Pregnancy Calculator -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/gebelik')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">🤱</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Gebelik Hesaplayıcı</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Gebelik haftanızı ve doğum tarihinizi hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Calorie Calculator -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/kalori')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">🍎</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Kalori Hesaplayıcı</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Günlük kalori ihtiyacınızı hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Water Intake -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/su-ihtiyaci')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">💧</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Su İhtiyacı</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Günlük su ihtiyacınızı hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Ovulation Calculator -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/ovulasyon')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">🥚</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Ovulasyon Hesaplayıcı</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Ovulasyon tarihinizi ve verimli dönemlerinizi öğrenin</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Ideal Weight Calculator -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/ideal-kilo')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">🎯</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">İdeal Kilo</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Farklı formüllerle ideal kilonuzu hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Body Fat Calculator -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/vucut-yag-orani')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">📊</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Vücut Yağ Oranı</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Vücut yağ oranınızı ve kas kütlenizi hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Due Date Calculator -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/dogum-tarihi')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">👶</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Doğum Tarihi</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Tahmini doğum tarihinizi hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Fertility Tracker -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/dogurganlik-takibi')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">🌸</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Doğurganlık Takibi</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Doğurganlık oranınızı ve hamile kalma şansınızı hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Financial Planner -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/hesaplama/finans-planlayici')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">💰</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Finans Planlayıcı</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Bütçe planlaması ve finansal hedeflerinizi hesaplayın</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Hesapla
          </div>
        </div>

        <!-- Budget Tracker -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/butce-takibi')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">📋</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Bütçe Takibi</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Gelir ve giderlerinizi takip edin, bütçenizi kontrol edin</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Takip Et
          </div>
        </div>

        <!-- Gamification -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 32px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;"
             @click="$router.push('/gorevler-basarimlar')"
             @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)'"
             @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'">
          <div style="font-size: 3rem; margin-bottom: 16px;">🏆</div>
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 12px;">Görevler ve Başarımlar</h3>
          <p style="color: #6b7280; margin-bottom: 20px;">Günlük görevleri tamamla, başarımları kazan ve liderlik tablosunda yüksel</p>
          <div style="background: #ec4899; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block; font-weight: 500;">
            Görevlere Başla
          </div>
        </div>

      </div>

      <!-- Info Section -->
      <div style="margin-top: 64px; text-align: center;">
        <h2 style="font-size: 2rem; font-weight: 600; color: #111827; margin-bottom: 16px;">14 Farklı Araç ve Özellik</h2>
        <p style="color: #6b7280; font-size: 1.125rem; max-width: 600px; margin: 0 auto;">
          Sağlıklı yaşam için doğru bilgilere sahip olmak önemlidir. Hesaplama araçlarımız, 
          kişisel sağlık verilerinizi takip etmenize ve bilinçli kararlar vermenize yardımcı olur.
        </p>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
// Hesaplama araçları ana sayfası
</script>