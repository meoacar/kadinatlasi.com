<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #fef7cd 0%, #fef3c7 100%);">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
      
      <!-- Header -->
      <div style="text-align: center; margin-bottom: 48px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #111827; margin-bottom: 16px;">💪 Fitness & Egzersiz</h1>
        <p style="font-size: 1.25rem; color: #6b7280;">Sağlıklı yaşam için kapsamlı egzersiz rehberiniz</p>
      </div>

      <!-- Quick Stats -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 48px;">
        <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); text-align: center;">
          <div style="font-size: 2.5rem; margin-bottom: 12px;">🔥</div>
          <div style="font-size: 2rem; font-weight: bold; color: #dc2626; margin-bottom: 4px;">{{ todayCalories }}</div>
          <div style="font-size: 0.875rem; color: #6b7280;">Bugün Yakılan Kalori</div>
        </div>
        
        <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); text-align: center;">
          <div style="font-size: 2.5rem; margin-bottom: 12px;">⏱️</div>
          <div style="font-size: 2rem; font-weight: bold; color: #059669; margin-bottom: 4px;">{{ todayMinutes }}</div>
          <div style="font-size: 0.875rem; color: #6b7280;">Bugün Egzersiz (dk)</div>
        </div>
        
        <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); text-align: center;">
          <div style="font-size: 2.5rem; margin-bottom: 12px;">📅</div>
          <div style="font-size: 2rem; font-weight: bold; color: #7c3aed; margin-bottom: 4px;">{{ weeklyStreak }}</div>
          <div style="font-size: 0.875rem; color: #6b7280;">Haftalık Seri</div>
        </div>
        
        <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); text-align: center;">
          <div style="font-size: 2.5rem; margin-bottom: 12px;">🎯</div>
          <div style="font-size: 2rem; font-weight: bold; color: #ea580c; margin-bottom: 4px;">{{ completedWorkouts }}</div>
          <div style="font-size: 0.875rem; color: #6b7280;">Tamamlanan Antrenman</div>
        </div>
      </div>

      <!-- Interactive Tools -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 24px;">🛠️ Fitness Araçları</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
          
          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease;"
               @click="openTool('workout-timer')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 16px;">⏰</div>
              <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Antrenman Zamanlayıcısı</h3>
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">HIIT, Tabata ve interval antrenmanları için zamanlayıcı</p>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease;"
               @click="openTool('body-tracker')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 16px;">📏</div>
              <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Vücut Ölçüm Takibi</h3>
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Kilo, ölçü ve ilerleme takibi yapın</p>
            </div>
          </div>

          <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s ease;"
               @click="openTool('exercise-generator')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 16px;">🎲</div>
              <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Rastgele Egzersiz</h3>
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Size uygun rastgele egzersiz önerileri alın</p>
            </div>
          </div>

        </div>
      </div>

      <!-- Workout Categories -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 24px;">🏋️ Antrenman Kategorileri</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
          
          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="selectCategory('cardio')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #fee2e2, #fecaca); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🏃‍♀️</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Kardiyovasküler</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Kalp sağlığını güçlendiren, yağ yakan ve dayanıklılık artıran egzersizler.
              </p>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #dc2626; font-weight: 600; font-size: 0.875rem;">{{ cardioCount }} Egzersiz</span>
                <span style="color: #dc2626; font-weight: 600; font-size: 0.875rem;">Keşfet →</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="selectCategory('strength')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">💪</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Güç Antrenmanı</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Kas kütlesi artıran, metabolizmayı hızlandıran güç egzersizleri.
              </p>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #059669; font-weight: 600; font-size: 0.875rem;">{{ strengthCount }} Egzersiz</span>
                <span style="color: #059669; font-weight: 600; font-size: 0.875rem;">Keşfet →</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="selectCategory('flexibility')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🧘‍♀️</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Esneklik & Yoga</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Esneklik artıran, stresi azaltan yoga ve stretching egzersizleri.
              </p>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #0369a1; font-weight: 600; font-size: 0.875rem;">{{ flexibilityCount }} Egzersiz</span>
                <span style="color: #0369a1; font-weight: 600; font-size: 0.875rem;">Keşfet →</span>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: all 0.3s ease;"
               @click="selectCategory('hiit')"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="background: linear-gradient(135deg, #f3e8ff, #e9d5ff); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">⚡</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">HIIT Antrenmanı</h3>
            </div>
            <div style="padding: 24px;">
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;">
                Yüksek yoğunluklu interval antrenmanları ile hızlı sonuçlar alın.
              </p>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #7c3aed; font-weight: 600; font-size: 0.875rem;">{{ hiitCount }} Egzersiz</span>
                <span style="color: #7c3aed; font-weight: 600; font-size: 0.875rem;">Keşfet →</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Weekly Challenge -->
      <div style="background: white; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); padding: 40px; margin-bottom: 48px;">
        <div style="text-align: center; margin-bottom: 32px;">
          <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 16px;">🏆 Bu Haftanın Meydan Okuması</h2>
          <p style="color: #6b7280; font-size: 1.125rem;">{{ currentChallenge.title }}</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 12px;">🎯</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Hedef</h3>
            <p style="color: #6b7280; font-size: 0.875rem;">{{ currentChallenge.target }}</p>
          </div>
          
          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 12px;">📈</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">İlerleme</h3>
            <p style="color: #6b7280; font-size: 0.875rem;">{{ currentChallenge.progress }}%</p>
          </div>
          
          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 12px;">⏰</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Kalan Süre</h3>
            <p style="color: #6b7280; font-size: 0.875rem;">{{ currentChallenge.timeLeft }} gün</p>
          </div>
        </div>
        
        <div style="background: #f3f4f6; border-radius: 12px; height: 12px; margin-bottom: 16px;">
          <div style="background: linear-gradient(90deg, #10b981, #059669); height: 100%; border-radius: 12px; transition: width 0.3s ease;" :style="{ width: currentChallenge.progress + '%' }"></div>
        </div>
        
        <div style="text-align: center;">
          <button 
            @click="joinChallenge"
            style="background: #10b981; color: white; padding: 12px 32px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.2s ease;"
            onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'"
            onmouseout="this.style.background='#10b981'; this.style.transform='translateY(0)'"
          >
            Meydan Okumaya Katıl
          </button>
        </div>
      </div>

      <!-- Daily Workout Suggestion -->
      <div style="background: linear-gradient(135deg, #fce7f3, #f3e8ff); border-radius: 20px; padding: 40px; margin-bottom: 48px;">
        <div style="text-align: center; margin-bottom: 24px;">
          <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 16px;">💡 Bugün İçin Önerilen Antrenman</h2>
          <div style="background: white; border-radius: 16px; padding: 24px; max-width: 500px; margin: 0 auto;">
            <div style="font-size: 3rem; margin-bottom: 16px;">{{ dailyWorkout.emoji }}</div>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 12px;">{{ dailyWorkout.name }}</h3>
            <p style="color: #6b7280; margin-bottom: 16px;">{{ dailyWorkout.description }}</p>
            <div style="display: flex; justify-content: center; gap: 16px; margin-bottom: 16px;">
              <span style="background: #f3f4f6; padding: 8px 16px; border-radius: 20px; font-size: 0.875rem; font-weight: 500;">
                ⏱️ {{ dailyWorkout.duration }} dk
              </span>
              <span style="background: #f3f4f6; padding: 8px 16px; border-radius: 20px; font-size: 0.875rem; font-weight: 500;">
                🔥 {{ dailyWorkout.calories }} kalori
              </span>
            </div>
            <button 
              @click="startDailyWorkout"
              style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;"
            >
              Antrenmana Başla
            </button>
          </div>
        </div>
      </div>

      <!-- Diet Section -->
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 24px; text-align: center;">🥗 Diyet & Beslenme Rehberi</h2>
        
        <!-- Diet Plans -->
        <div style="margin-bottom: 32px;">
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 20px;">📝 Popüler Diyet Planları</h3>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            
            <div @click="openDietPlan('mediterranean')" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
              <div style="background: linear-gradient(135deg, #fce7f3, #f3e8ff); padding: 24px; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 12px;">🥦</div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Akdeniz Diyeti</h4>
              </div>
              <div style="padding: 20px;">
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 12px;">Zeytinyağı, balık, sebze ağırlıklı sağlıklı beslenme</p>
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #9ca3af;">
                  <span>🔥 1800 kcal</span>
                  <span>⏱️ 4 hafta</span>
                  <span>⭐ 4.8/5</span>
                </div>
              </div>
            </div>

            <div @click="openDietPlan('detox')" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
              <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); padding: 24px; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 12px;">🥗</div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Detoks Diyeti</h4>
              </div>
              <div style="padding: 20px;">
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 12px;">Vücudu arındıran, metabolizmayı hızlandıran plan</p>
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #9ca3af;">
                  <span>🔥 1400 kcal</span>
                  <span>⏱️ 2 hafta</span>
                  <span>⭐ 4.6/5</span>
                </div>
              </div>
            </div>

            <div @click="openDietPlan('gluten-free')" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
              <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 24px; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 12px;">🌾</div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Glutensiz Diyet</h4>
              </div>
              <div style="padding: 20px;">
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 12px;">Gluten intoleransı olanlar için özel beslenme</p>
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #9ca3af;">
                  <span>🔥 1600 kcal</span>
                  <span>⏱️ Sürekli</span>
                  <span>⭐ 4.7/5</span>
                </div>
              </div>
            </div>

            <div @click="openDietPlan('keto')" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
              <div style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); padding: 24px; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 12px;">🥩</div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Keto Diyeti</h4>
              </div>
              <div style="padding: 20px;">
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 12px;">Düşük karbonhidrat, yüksek yağ oranı</p>
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #9ca3af;">
                  <span>🔥 1500 kcal</span>
                  <span>⏱️ 6 hafta</span>
                  <span>⭐ 4.5/5</span>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Meal Planning -->
        <div style="margin-bottom: 32px;">
          <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 20px;">🍽️ Günlük Öğün Planlama</h3>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            
            <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 24px;">
              <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <div style="font-size: 2rem; margin-right: 12px;">🌅</div>
                <h4 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Kahvaltı</h4>
              </div>
              <div style="space-y: 8px;">
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 6px;">
                  <span style="font-weight: 500; color: #374151;">Yulaf Ezmesi + Meyve</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">320 kcal</span>
                </div>
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 6px;">
                  <span style="font-weight: 500; color: #374151;">Yoğurt + Ceviz</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">180 kcal</span>
                </div>
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px;">
                  <span style="font-weight: 500; color: #374151;">Yeşil Çay</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">5 kcal</span>
                </div>
              </div>
            </div>

            <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 24px;">
              <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <div style="font-size: 2rem; margin-right: 12px;">☀️</div>
                <h4 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Öğle Yemeği</h4>
              </div>
              <div style="space-y: 8px;">
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 6px;">
                  <span style="font-weight: 500; color: #374151;">Izgara Tavuk + Salata</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">450 kcal</span>
                </div>
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 6px;">
                  <span style="font-weight: 500; color: #374151;">Bulgur Pilavı</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">200 kcal</span>
                </div>
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px;">
                  <span style="font-weight: 500; color: #374151;">Ayran</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">80 kcal</span>
                </div>
              </div>
            </div>

            <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 24px;">
              <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <div style="font-size: 2rem; margin-right: 12px;">🌙</div>
                <h4 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Akşam Yemeği</h4>
              </div>
              <div style="space-y: 8px;">
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 6px;">
                  <span style="font-weight: 500; color: #374151;">Somon + Buharda Sebze</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">380 kcal</span>
                </div>
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 6px;">
                  <span style="font-weight: 500; color: #374151;">Quinoa</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">150 kcal</span>
                </div>
                <div style="padding: 8px; background: #f9fafb; border-radius: 6px;">
                  <span style="font-weight: 500; color: #374151;">Bitki Çayı</span>
                  <span style="float: right; font-size: 0.875rem; color: #6b7280;">0 kcal</span>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Nutrition Tracking -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
          
          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">📊</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Günlük Besin Değerleri</h3>
            </div>
            <div style="padding: 24px;">
              <div style="space-y: 16px;">
                <div>
                  <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-weight: 600; color: #374151;">Protein</span>
                    <span style="color: #6b7280;">45g / 60g</span>
                  </div>
                  <div style="background: #e5e7eb; border-radius: 4px; height: 8px;">
                    <div style="background: #10b981; height: 8px; border-radius: 4px; width: 75%;"></div>
                  </div>
                </div>
                <div>
                  <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-weight: 600; color: #374151;">Karbonhidrat</span>
                    <span style="color: #6b7280;">120g / 150g</span>
                  </div>
                  <div style="background: #e5e7eb; border-radius: 4px; height: 8px;">
                    <div style="background: #f59e0b; height: 8px; border-radius: 4px; width: 80%;"></div>
                  </div>
                </div>
                <div>
                  <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-weight: 600; color: #374151;">Yağ</span>
                    <span style="color: #6b7280;">35g / 50g</span>
                  </div>
                  <div style="background: #e5e7eb; border-radius: 4px; height: 8px;">
                    <div style="background: #ef4444; height: 8px; border-radius: 4px; width: 70%;"></div>
                  </div>
                </div>
                <div>
                  <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-weight: 600; color: #374151;">Lif</span>
                    <span style="color: #6b7280;">18g / 25g</span>
                  </div>
                  <div style="background: #e5e7eb; border-radius: 4px; height: 8px;">
                    <div style="background: #8b5cf6; height: 8px; border-radius: 4px; width: 72%;"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">💧</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Su Takibi</h3>
            </div>
            <div style="padding: 24px; text-align: center;">
              <div style="font-size: 2rem; font-weight: bold; color: #0369a1; margin-bottom: 8px;">6/8</div>
              <p style="color: #6b7280; margin-bottom: 16px;">Bardak su içtiniz</p>
              <div style="display: flex; gap: 4px; justify-content: center; margin-bottom: 16px;">
                <div style="width: 20px; height: 20px; background: #0369a1; border-radius: 50%;"></div>
                <div style="width: 20px; height: 20px; background: #0369a1; border-radius: 50%;"></div>
                <div style="width: 20px; height: 20px; background: #0369a1; border-radius: 50%;"></div>
                <div style="width: 20px; height: 20px; background: #0369a1; border-radius: 50%;"></div>
                <div style="width: 20px; height: 20px; background: #0369a1; border-radius: 50%;"></div>
                <div style="width: 20px; height: 20px; background: #0369a1; border-radius: 50%;"></div>
                <div style="width: 20px; height: 20px; background: #e5e7eb; border-radius: 50%;"></div>
                <div style="width: 20px; height: 20px; background: #e5e7eb; border-radius: 50%;"></div>
              </div>
              <button style="background: #0369a1; color: white; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer;">Su Ekle</button>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); padding: 32px; text-align: center;">
              <div style="font-size: 3.5rem; margin-bottom: 16px;">🥑</div>
              <h3 style="font-size: 1.375rem; font-weight: 600; color: #111827;">Sağlıklı Tarifler</h3>
            </div>
            <div style="padding: 24px;">
              <div style="space-y: 12px;">
                <div style="padding: 12px; background: #f9fafb; border-radius: 8px; margin-bottom: 8px; cursor: pointer;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                  <h4 style="font-weight: 600; color: #374151; margin-bottom: 4px;">Avokado Toast</h4>
                  <p style="font-size: 0.875rem; color: #6b7280;">Protein: 8g • Kalori: 250 • ⏱️ 5dk</p>
                </div>
                <div style="padding: 12px; background: #f9fafb; border-radius: 8px; margin-bottom: 8px; cursor: pointer;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                  <h4 style="font-weight: 600; color: #374151; margin-bottom: 4px;">Quinoa Salatası</h4>
                  <p style="font-size: 0.875rem; color: #6b7280;">Protein: 12g • Kalori: 320 • ⏱️ 15dk</p>
                </div>
                <div style="padding: 12px; background: #f9fafb; border-radius: 8px; margin-bottom: 8px; cursor: pointer;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                  <h4 style="font-weight: 600; color: #374151; margin-bottom: 4px;">Somon Teriyaki</h4>
                  <p style="font-size: 0.875rem; color: #6b7280;">Protein: 25g • Kalori: 280 • ⏱️ 20dk</p>
                </div>
                <div style="padding: 12px; background: #f9fafb; border-radius: 8px; cursor: pointer;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                  <h4 style="font-weight: 600; color: #374151; margin-bottom: 4px;">Chia Pudding</h4>
                  <p style="font-size: 0.875rem; color: #6b7280;">Protein: 6g • Kalori: 180 • ⏱️ 2dk</p>
                </div>
              </div>
              <button style="width: 100%; margin-top: 12px; padding: 8px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer;">Tüm Tarifleri Gör</button>
            </div>
          </div>

        </div>
      </div>

      <!-- Fitness Tips -->
      <div style="background: white; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); padding: 40px;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 32px; text-align: center;">💡 Fitness & Diyet İpuçları</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
          
          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">💧</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Bol Su İçin</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Antrenman öncesi, sırası ve sonrasında bol su için. Dehidrasyon performansı düşürür.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🔥</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Isınma Yapın</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">5-10 dakika ısınma yaralanma riskini azaltır ve performansı artırır.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🥦</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Dengeli Beslenme</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Protein, karbonhidrat ve sağlıklı yağları dengeli tüketin.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🍎</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Porsiyon Kontrolü</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Küçük tabaklar kullanın ve yavaş yiyin.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">😴</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Dinlenme</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Kaslar dinlenme sırasında büyür. Yeterli uyku ve dinlenme çok önemli.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🥗</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Doğru Beslenme</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Antrenman sonrası 30 dakika içinde protein ve karbonhidrat alın.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">📈</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Kademeli Artış</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Yoğunluğu ve süreyi kademeli olarak artırın. Ani değişiklikler zararlı olabilir.</p>
          </div>

          <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🎯</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Hedef Belirleyin</h3>
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.4;">Spesifik, ölçülebilir hedefler belirleyin ve ilerlemenizi takip edin.</p>
          </div>

        </div>
      </div>

    </div>

    <!-- Workout Timer Modal -->
    <div v-if="showWorkoutTimer" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 16px;">
      <div style="background: white; border-radius: 16px; max-width: 400px; width: 100%; padding: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="font-size: 1.25rem; font-weight: 600; color: #111827;">⏰ Antrenman Zamanlayıcısı</h2>
          <button @click="closeWorkoutTimer" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✕</button>
        </div>
        
        <div style="text-align: center; margin-bottom: 24px;">
          <div style="font-size: 3rem; font-weight: bold; color: #2563eb; margin-bottom: 16px;">{{ formatTime(timerSeconds) }}</div>
          <div style="margin-bottom: 16px;">
            <select v-model="selectedWorkoutType" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
              <option value="hiit">HIIT Antrenmanı</option>
              <option value="cardio">Kardiyovasküler</option>
              <option value="strength">Güç Antrenmanı</option>
              <option value="yoga">Yoga/Stretching</option>
            </select>
          </div>
          <div style="display: flex; gap: 8px; justify-content: center;">
            <button @click="startTimer" v-if="!timerRunning" style="background: #10b981; color: white; padding: 8px 24px; border-radius: 8px; border: none; cursor: pointer;">Başla</button>
            <button @click="pauseTimer" v-if="timerRunning" style="background: #f59e0b; color: white; padding: 8px 24px; border-radius: 8px; border: none; cursor: pointer;">Duraklat</button>
            <button @click="stopTimer" style="background: #ef4444; color: white; padding: 8px 24px; border-radius: 8px; border: none; cursor: pointer;">Durdur</button>
          </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
          <button @click="setTimer(300)" style="background: #f3f4f6; padding: 8px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem;">5 dk</button>
          <button @click="setTimer(600)" style="background: #f3f4f6; padding: 8px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem;">10 dk</button>
          <button @click="setTimer(900)" style="background: #f3f4f6; padding: 8px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem;">15 dk</button>
          <button @click="setTimer(1200)" style="background: #f3f4f6; padding: 8px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem;">20 dk</button>
          <button @click="setTimer(1800)" style="background: #f3f4f6; padding: 8px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem;">30 dk</button>
          <button @click="setTimer(2700)" style="background: #f3f4f6; padding: 8px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem;">45 dk</button>
        </div>
      </div>
    </div>

    <!-- Body Tracker Modal -->
    <div v-if="showBodyTracker" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 16px;">
      <div style="background: white; border-radius: 16px; max-width: 400px; width: 100%; padding: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="font-size: 1.25rem; font-weight: 600; color: #111827;">📏 Vücut Ölçüm Takibi</h2>
          <button @click="closeBodyTracker" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✕</button>
        </div>
        
        <form @submit.prevent="saveBodyMeasurement" style="display: flex; flex-direction: column; gap: 16px;">
          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 4px;">Kilo (kg)</label>
              <input v-model="bodyData.weight" type="number" step="0.1" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="65.5">
            </div>
            <div>
              <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 4px;">Boy (cm)</label>
              <input v-model="bodyData.height" type="number" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="165">
            </div>
          </div>
          
          <button type="submit" :disabled="saving" style="width: 100%; background: #ec4899; color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
            {{ saving ? 'Kaydediliyor...' : 'Ölçümleri Kaydet' }}
          </button>
        </form>
      </div>
    </div>

    <!-- Exercise Generator Modal -->
    <div v-if="showExerciseGenerator" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 16px;">
      <div style="background: white; border-radius: 16px; max-width: 400px; width: 100%; padding: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="font-size: 1.25rem; font-weight: 600; color: #111827;">🎲 Rastgele Egzersiz</h2>
          <button @click="closeExerciseGenerator" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✕</button>
        </div>
        
        <div style="margin-bottom: 16px;">
          <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 8px;">Kategori Seçin</label>
          <select v-model="selectedCategory" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            <option value="all">Tüm Kategoriler</option>
            <option value="cardio">Kardiyovasküler</option>
            <option value="strength">Güç Antrenmanı</option>
            <option value="flexibility">Esneklik & Yoga</option>
            <option value="hiit">HIIT</option>
          </select>
        </div>
        
        <div v-if="randomExercise" style="text-align: center; margin-bottom: 24px; padding: 16px; background: #f9fafb; border-radius: 8px;">
          <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 8px;">{{ randomExercise.name }}</h3>
          <p style="color: #6b7280; margin-bottom: 12px;">{{ randomExercise.description }}</p>
          <div style="display: flex; justify-content: center; gap: 16px; font-size: 0.875rem;">
            <span style="background: #dbeafe; padding: 4px 12px; border-radius: 20px;">⏱️ {{ randomExercise.duration }} dk</span>
            <span style="background: #fee2e2; padding: 4px 12px; border-radius: 20px;">🔥 {{ randomExercise.calories }} kalori</span>
          </div>
        </div>
        
        <button @click="generateRandomExercise" style="width: 100%; background: #8b5cf6; color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; margin-bottom: 12px;">
          Yeni Egzersiz Öner
        </button>
        
        <button v-if="randomExercise" @click="startExercise" style="width: 100%; background: #10b981; color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
          Bu Egzersize Başla
        </button>
      </div>
    </div>

    <!-- Diet Plan Modal -->
    <div v-if="showDietPlanModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 16px;">
      <div style="background: white; border-radius: 16px; max-width: 500px; width: 100%; padding: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 1.5rem;">{{ selectedDietPlan?.emoji }}</span>
            <h2 style="font-size: 1.25rem; font-weight: 600; color: #111827;">{{ selectedDietPlan?.name }}</h2>
          </div>
          <button @click="closeDietPlanModal" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✕</button>
        </div>
        
        <div style="margin-bottom: 20px;">
          <p style="color: #6b7280; line-height: 1.6;">{{ selectedDietPlan?.description }}</p>
        </div>
        
        <div style="margin-bottom: 20px;">
          <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 12px;">✨ Faydaları</h3>
          <div style="display: flex; flex-direction: column; gap: 6px;">
            <div v-for="benefit in selectedDietPlan?.benefits" :key="benefit" style="display: flex; align-items: center; gap: 8px;">
              <span style="color: #10b981; font-weight: bold;">✓</span>
              <span style="color: #374151; font-size: 0.875rem;">{{ benefit }}</span>
            </div>
          </div>
        </div>
        
        <div style="margin-bottom: 20px;">
          <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 12px;">🥗 Önerilen Besinler</h3>
          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;">
            <div v-for="food in selectedDietPlan?.foods" :key="food" style="background: #f3f4f6; padding: 8px 12px; border-radius: 6px; font-size: 0.875rem; color: #374151;">
              {{ food }}
            </div>
          </div>
        </div>
        
        <div style="margin-bottom: 20px;">
          <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 12px;">🍽️ Örnek Günlük Menü</h3>
          <div style="background: #f9fafb; padding: 16px; border-radius: 8px; white-space: pre-line; font-size: 0.875rem; color: #374151; line-height: 1.5;">
            {{ selectedDietPlan?.sample }}
          </div>
        </div>
        
        <div style="display: flex; gap: 12px;">
          <button @click="closeDietPlanModal" style="flex: 1; background: #f3f4f6; color: #374151; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
            Kapat
          </button>
          <button style="flex: 1; background: #10b981; color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
            Diyetisyenle İletişim
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

// Remove FitnessTools import since we're using inline modals

const authStore = useAuthStore()

// Stats
const todayCalories = ref(245)
const todayMinutes = ref(32)
const weeklyStreak = ref(4)
const completedWorkouts = ref(12)

// Category counts
const cardioCount = ref(25)
const strengthCount = ref(18)
const flexibilityCount = ref(15)
const hiitCount = ref(12)

// Challenge
const currentChallenge = ref({
  title: '7 Günde 150 Dakika Egzersiz',
  target: '150 dakika egzersiz',
  progress: 65,
  timeLeft: 3
})

// Daily workout
const dailyWorkout = ref({
  emoji: '🏃‍♀️',
  name: '20 Dakika HIIT Antrenmanı',
  description: 'Yüksek yoğunluklu interval antrenmanı ile metabolizmanızı hızlandırın',
  duration: 20,
  calories: 180
})

// Remove old tools modal code

// Fitness Tools State
const showWorkoutTimer = ref(false)
const showBodyTracker = ref(false)
const showExerciseGenerator = ref(false)
const timerSeconds = ref(0)
const timerRunning = ref(false)
const timerInterval = ref(null)
const selectedWorkoutType = ref('hiit')
const saving = ref(false)
const bodyData = ref({ weight: '', height: '' })
const selectedCategory = ref('all')
const randomExercise = ref(null)

const openTool = (toolType) => {
  if (toolType === 'workout-timer') {
    showWorkoutTimer.value = true
    setTimer(1200) // 20 minutes default
  } else if (toolType === 'body-tracker') {
    showBodyTracker.value = true
  } else if (toolType === 'exercise-generator') {
    showExerciseGenerator.value = true
    generateRandomExercise()
  }
}

// Remove old closeToolModal function

const selectCategory = (category) => {
  alert(`${category} kategorisi yakında aktif olacak! 🏋️‍♀️`)
}

const joinChallenge = () => {
  alert('Meydan okumaya katıldınız! 🏆 İlerlemenizi takip etmeye başlayabilirsiniz.')
}

const startDailyWorkout = () => {
  alert('Günlük antrenman başlatılıyor! 💪 Hazır mısınız?')
}

// Load user fitness stats
const loadFitnessStats = async () => {
  if (authStore.isAuthenticated) {
    try {
      const response = await api.get('/fitness/stats')
      const stats = response.data.stats
      
      todayCalories.value = stats.today_calories || 0
      todayMinutes.value = stats.today_minutes || 0
      weeklyStreak.value = stats.weekly_streak || 0
      completedWorkouts.value = stats.completed_workouts || 0
    } catch (error) {
      console.error('Failed to load fitness stats:', error)
    }
  }
}

// Fitness Tools Functions
const formatTime = (seconds) => {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
}

const setTimer = (seconds) => {
  timerSeconds.value = seconds
}

const startTimer = () => {
  timerRunning.value = true
  timerInterval.value = setInterval(() => {
    if (timerSeconds.value > 0) {
      timerSeconds.value--
    } else {
      completeWorkout()
    }
  }, 1000)
}

const pauseTimer = () => {
  timerRunning.value = false
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
}

const stopTimer = () => {
  timerRunning.value = false
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
  timerSeconds.value = 0
}

const completeWorkout = async () => {
  timerRunning.value = false
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
  
  if (authStore.isAuthenticated) {
    try {
      const duration = 20 // example duration
      const estimatedCalories = duration * 8
      
      await api.post('/fitness/workout-timer', {
        duration,
        type: selectedWorkoutType.value,
        calories_burned: estimatedCalories
      })
      
      alert('🎉 Tebrikler! Antrenmanınız tamamlandı ve kaydedildi.')
      loadFitnessStats() // Refresh stats
    } catch (error) {
      console.error('Workout save error:', error)
    }
  } else {
    alert('🎉 Tebrikler! Antrenmanınız tamamlandı.')
  }
  
  timerSeconds.value = 0
}

const closeWorkoutTimer = () => {
  showWorkoutTimer.value = false
  stopTimer()
}

const closeBodyTracker = () => {
  showBodyTracker.value = false
  bodyData.value = { weight: '', height: '' }
}

const closeExerciseGenerator = () => {
  showExerciseGenerator.value = false
  randomExercise.value = null
}

const saveBodyMeasurement = async () => {
  if (!authStore.isAuthenticated) {
    alert('Ölçümleri kaydetmek için giriş yapmalısınız.')
    return
  }
  
  saving.value = true
  try {
    await api.post('/fitness/body-measurement', bodyData.value)
    alert('✅ Vücut ölçümleriniz başarıyla kaydedildi!')
    closeBodyTracker()
  } catch (error) {
    console.error('Body measurement save error:', error)
    alert('❌ Kaydetme sırasında bir hata oluştu.')
  } finally {
    saving.value = false
  }
}

const generateRandomExercise = async () => {
  try {
    const response = await api.get('/fitness/random-exercise', {
      params: { category: selectedCategory.value }
    })
    randomExercise.value = response.data.exercise
  } catch (error) {
    console.error('Random exercise error:', error)
  }
}

const startExercise = () => {
  if (randomExercise.value) {
    setTimer(randomExercise.value.duration * 60)
    closeExerciseGenerator()
    showWorkoutTimer.value = true
  }
}

// Diet Plan Modal
const showDietPlanModal = ref(false)
const selectedDietPlan = ref(null)

const dietPlans = {
  'mediterranean': {
    name: 'Akdeniz Diyeti',
    emoji: '🥦',
    description: 'Kalp sağlığını destekleyen, zeytinyağı, balık ve sebze ağırlıklı beslenme planı.',
    benefits: ['Kalp sağlığını destekler', 'Antioksidan açısından zengin', 'Uzun yaşamı destekler'],
    foods: ['Zeytinyağı', 'Balık', 'Sebzeler', 'Meyveler', 'Tam tahıllar', 'Kuruyemişler'],
    sample: 'Kahvaltı: Zeytinyağlı domates, peynir\nÖğle: Izgara balık, salata\nAkşam: Sebze yemeği, yoğurt'
  },
  'detox': {
    name: 'Detoks Diyeti',
    emoji: '🥗',
    description: 'Vücudu toksinlerden arındıran, metabolizmayı hızlandıran kısa süreli plan.',
    benefits: ['Metabolizmayı hızlandırır', 'Vücudu arındırır', 'Enerji seviyesini artırır'],
    foods: ['Yeşil yapraklı sebzeler', 'Limon', 'Zencefil', 'Yeşil çay', 'Meyveler', 'Bol su'],
    sample: 'Sabah: Limonlu sıcak su\nKahvaltı: Yeşil smoothie\nÖğle: Sebze çorbası\nAkşam: Buharda sebze'
  },
  'gluten-free': {
    name: 'Glutensiz Diyet',
    emoji: '🌾',
    description: 'Çölyak hastalığı ve gluten intoleransı olanlar için özel beslenme planı.',
    benefits: ['Sindirim sorunlarını azaltır', 'Enerji seviyesini artırır', 'Cilt sağlığını iyileştirir'],
    foods: ['Pirinç', 'Quinoa', 'Patates', 'Et ve balık', 'Sebze ve meyveler', 'Glutensiz tahıllar'],
    sample: 'Kahvaltı: Glutensiz ekmek, yumurta\nÖğle: Quinoa salatası\nAkşam: Izgara et, pirinç pilavı'
  },
  'keto': {
    name: 'Keto Diyeti',
    emoji: '🥩',
    description: 'Düşük karbonhidrat, yüksek yağ oranına sahip ketojenik beslenme planı.',
    benefits: ['Hızlı kilo verme', 'Kan şekerini dengeler', 'Zihinsel netliği artırır'],
    foods: ['Avokado', 'Zeytinyağı', 'Et ve balık', 'Yumurta', 'Peynir', 'Yeşil yapraklı sebzeler'],
    sample: 'Kahvaltı: Yumurta, avokado\nÖğle: Somon salatası\nAkşam: Izgara et, brokoli'
  }
}

const openDietPlan = (planType) => {
  selectedDietPlan.value = dietPlans[planType]
  showDietPlanModal.value = true
}

const closeDietPlanModal = () => {
  showDietPlanModal.value = false
  selectedDietPlan.value = null
}

onMounted(() => {
  loadFitnessStats()
  
  // Update stats periodically
  setInterval(() => {
    loadFitnessStats()
  }, 60000) // Update every minute
})
</script>