<template>
  <div style="min-height: 100vh; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 2rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
      
      <!-- Header Section -->
      <div style="text-align: center; margin-bottom: 3rem; background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="font-size: 3rem; font-weight: 800; color: #1f2937; margin-bottom: 1rem;">🏆 Görevler ve Başarımlar</h1>
        <p style="font-size: 1.2rem; color: #6b7280; max-width: 600px; margin: 0 auto;">Günlük görevleri tamamla, başarımları kazan ve liderlik tablosunda yüksel!</p>
      </div>

      <!-- User Stats Card -->
      <div style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%); border-radius: 20px; padding: 2rem; margin-bottom: 3rem; box-shadow: 0 20px 40px rgba(236, 72, 153, 0.3); color: white;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
          <div style="text-align: center; background: rgba(255,255,255,0.2); padding: 1.5rem; border-radius: 15px; backdrop-filter: blur(10px);">
            <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;">{{ userStats.level }}</div>
            <div style="font-size: 1.1rem; font-weight: 600; opacity: 0.9;">Seviye</div>
          </div>
          <div style="text-align: center; background: rgba(255,255,255,0.2); padding: 1.5rem; border-radius: 15px; backdrop-filter: blur(10px);">
            <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;">{{ userStats.points }}</div>
            <div style="font-size: 1.1rem; font-weight: 600; opacity: 0.9;">Puan</div>
          </div>
          <div style="text-align: center; background: rgba(255,255,255,0.2); padding: 1.5rem; border-radius: 15px; backdrop-filter: blur(10px);">
            <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;">{{ userStats.daily_streak }}</div>
            <div style="font-size: 1.1rem; font-weight: 600; opacity: 0.9;">Günlük Seri</div>
          </div>
          <div style="text-align: center; background: rgba(255,255,255,0.2); padding: 1.5rem; border-radius: 15px; backdrop-filter: blur(10px);">
            <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;">{{ userStats.achievements_count }}</div>
            <div style="font-size: 1.1rem; font-weight: 600; opacity: 0.9;">Başarım</div>
          </div>
        </div>
        
        <!-- XP Progress -->
        <div style="background: rgba(255,255,255,0.2); padding: 1.5rem; border-radius: 15px; backdrop-filter: blur(10px);">
          <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-weight: 600;">
            <span>Seviye {{ userStats.level }}</span>
            <span>{{ userStats.current_xp }} / {{ userStats.xp_for_next_level }} XP</span>
          </div>
          <div style="background: rgba(255,255,255,0.3); border-radius: 10px; height: 12px; overflow: hidden;">
            <div style="background: linear-gradient(90deg, #fbbf24, #f59e0b); height: 100%; border-radius: 10px; transition: width 0.5s ease;" 
                 :style="{ width: userStats.xp_progress + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div style="display: flex; justify-content: center; margin-bottom: 3rem; background: white; padding: 1rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
          <button @click="activeTab = 'daily'" 
                  :style="{ 
                    background: activeTab === 'daily' ? 'linear-gradient(135deg, #ec4899, #f472b6)' : '#f3f4f6',
                    color: activeTab === 'daily' ? 'white' : '#6b7280',
                    padding: '1rem 2rem',
                    borderRadius: '15px',
                    border: 'none',
                    fontSize: '1.1rem',
                    fontWeight: '600',
                    cursor: 'pointer',
                    transition: 'all 0.3s ease',
                    boxShadow: activeTab === 'daily' ? '0 10px 20px rgba(236, 72, 153, 0.3)' : 'none'
                  }">
            📅 Günlük Görevler
          </button>
          <button @click="activeTab = 'weekly'" 
                  :style="{ 
                    background: activeTab === 'weekly' ? 'linear-gradient(135deg, #ec4899, #f472b6)' : '#f3f4f6',
                    color: activeTab === 'weekly' ? 'white' : '#6b7280',
                    padding: '1rem 2rem',
                    borderRadius: '15px',
                    border: 'none',
                    fontSize: '1.1rem',
                    fontWeight: '600',
                    cursor: 'pointer',
                    transition: 'all 0.3s ease',
                    boxShadow: activeTab === 'weekly' ? '0 10px 20px rgba(236, 72, 153, 0.3)' : 'none'
                  }">
            📊 Haftalık Görevler
          </button>
          <button @click="activeTab = 'achievements'" 
                  :style="{ 
                    background: activeTab === 'achievements' ? 'linear-gradient(135deg, #ec4899, #f472b6)' : '#f3f4f6',
                    color: activeTab === 'achievements' ? 'white' : '#6b7280',
                    padding: '1rem 2rem',
                    borderRadius: '15px',
                    border: 'none',
                    fontSize: '1.1rem',
                    fontWeight: '600',
                    cursor: 'pointer',
                    transition: 'all 0.3s ease',
                    boxShadow: activeTab === 'achievements' ? '0 10px 20px rgba(236, 72, 153, 0.3)' : 'none'
                  }">
            🏆 Başarımlar
          </button>
          <button @click="activeTab = 'leaderboard'" 
                  :style="{ 
                    background: activeTab === 'leaderboard' ? 'linear-gradient(135deg, #ec4899, #f472b6)' : '#f3f4f6',
                    color: activeTab === 'leaderboard' ? 'white' : '#6b7280',
                    padding: '1rem 2rem',
                    borderRadius: '15px',
                    border: 'none',
                    fontSize: '1.1rem',
                    fontWeight: '600',
                    cursor: 'pointer',
                    transition: 'all 0.3s ease',
                    boxShadow: activeTab === 'leaderboard' ? '0 10px 20px rgba(236, 72, 153, 0.3)' : 'none'
                  }">
            👑 Liderlik Tablosu
          </button>
        </div>
      </div>

      <!-- Daily Tasks -->
      <div v-if="activeTab === 'daily'" style="display: flex; flex-direction: column; gap: 2rem;">
        <div v-for="task in dailyTasks" :key="task.id" 
             style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 6px solid #ec4899;">
          
          <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 1.5rem;">
            <div style="font-size: 4rem; min-width: 80px; text-align: center;">{{ task.icon }}</div>
            <div style="flex: 1;">
              <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">{{ task.name }}</h3>
              <p style="font-size: 1.1rem; color: #6b7280; line-height: 1.6; margin-bottom: 1rem;">{{ task.description }}</p>
              
              <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <span style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                  💰 {{ task.points }} Puan
                </span>
                <span style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                  ⚡ {{ task.xp_reward }} XP
                </span>
                <span style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                  📂 {{ getCategoryText(task.category) }}
                </span>
              </div>
            </div>
          </div>

          <div style="border-top: 2px solid #f3f4f6; padding-top: 1.5rem;">
            <div v-if="task.is_completed" 
                 style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 2rem; border-radius: 15px; text-align: center; font-weight: 700; font-size: 1.2rem;">
              ✅ Görev Tamamlandı!
            </div>
            <div v-else style="display: flex; align-items: center; justify-content: space-between; gap: 2rem;">
              <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                  <span style="font-weight: 600; color: #374151;">İlerleme</span>
                  <span style="font-weight: 700; color: #ec4899;">{{ task.progress }} / {{ task.target_count }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 10px; height: 12px; overflow: hidden;">
                  <div style="background: linear-gradient(90deg, #ec4899, #f472b6); height: 100%; border-radius: 10px; transition: width 0.5s ease;" 
                       :style="{ width: task.progress_percentage + '%' }"></div>
                </div>
              </div>
              <div style="text-align: center; min-width: 100px;">
                <div style="font-size: 2rem; font-weight: 900; color: #ec4899;">%{{ Math.round(task.progress_percentage) }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; font-weight: 600;">Tamamlandı</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Weekly Tasks -->
      <div v-if="activeTab === 'weekly'" style="display: flex; flex-direction: column; gap: 2rem;">
        <div v-for="task in weeklyTasks" :key="task.id" 
             style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 6px solid #f59e0b;">
          
          <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 1.5rem;">
            <div style="font-size: 4rem; min-width: 80px; text-align: center;">{{ task.icon }}</div>
            <div style="flex: 1;">
              <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">{{ task.name }}</h3>
              <p style="font-size: 1.1rem; color: #6b7280; line-height: 1.6; margin-bottom: 1rem;">{{ task.description }}</p>
              
              <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <span style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                  💰 {{ task.points }} Puan
                </span>
                <span style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                  ⚡ {{ task.xp_reward }} XP
                </span>
                <span style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                  📂 {{ getCategoryText(task.category) }}
                </span>
              </div>
            </div>
          </div>

          <div style="border-top: 2px solid #f3f4f6; padding-top: 1.5rem;">
            <div v-if="task.is_completed" 
                 style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 2rem; border-radius: 15px; text-align: center; font-weight: 700; font-size: 1.2rem;">
              ✅ Görev Tamamlandı!
            </div>
            <div v-else style="display: flex; align-items: center; justify-content: space-between; gap: 2rem;">
              <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                  <span style="font-weight: 600; color: #374151;">İlerleme</span>
                  <span style="font-weight: 700; color: #f59e0b;">{{ task.progress }} / {{ task.target_count }}</span>
                </div>
                <div style="background: #e5e7eb; border-radius: 10px; height: 12px; overflow: hidden;">
                  <div style="background: linear-gradient(90deg, #f59e0b, #d97706); height: 100%; border-radius: 10px; transition: width 0.5s ease;" 
                       :style="{ width: task.progress_percentage + '%' }"></div>
                </div>
              </div>
              <div style="text-align: center; min-width: 100px;">
                <div style="font-size: 2rem; font-weight: 900; color: #f59e0b;">%{{ Math.round(task.progress_percentage) }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; font-weight: 600;">Tamamlandı</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Achievements -->
      <div v-if="activeTab === 'achievements'" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
        <div v-for="achievement in achievements" :key="achievement.id" 
             style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; border: 3px solid #f3f4f6;"
             :style="{ borderColor: achievement.is_completed ? '#fbbf24' : '#f3f4f6', background: achievement.is_completed ? 'linear-gradient(135deg, #fef3c7, #fde68a)' : 'white' }">
          
          <div style="font-size: 5rem; margin-bottom: 1rem;">{{ achievement.icon }}</div>
          <h3 style="font-size: 1.4rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">{{ achievement.name }}</h3>
          <p style="font-size: 1rem; color: #6b7280; line-height: 1.6; margin-bottom: 1.5rem;">{{ achievement.description }}</p>
          
          <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;"
                  :style="{ background: getDifficultyColor(achievement.difficulty), color: 'white' }">
              {{ getDifficultyText(achievement.difficulty) }}
            </span>
            <span style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
              💰 {{ achievement.points }} Puan
            </span>
          </div>

          <div v-if="achievement.is_completed" 
               style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem 2rem; border-radius: 15px; font-weight: 700; font-size: 1.1rem;">
            🏆 Başarım Kazanıldı!
          </div>
          <div v-else>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
              <span style="font-weight: 600; color: #374151;">İlerleme</span>
              <span style="font-weight: 700; color: #ec4899;">{{ achievement.progress }} / {{ achievement.target }}</span>
            </div>
            <div style="background: #e5e7eb; border-radius: 10px; height: 12px; overflow: hidden; margin-bottom: 0.5rem;">
              <div style="background: linear-gradient(90deg, #fbbf24, #f59e0b); height: 100%; border-radius: 10px; transition: width 0.5s ease;" 
                   :style="{ width: achievement.progress_percentage + '%' }"></div>
            </div>
            <div style="font-size: 1.2rem; font-weight: 700; color: #fbbf24;">%{{ Math.round(achievement.progress_percentage) }} Tamamlandı</div>
          </div>
        </div>
      </div>

      <!-- Leaderboard -->
      <div v-if="activeTab === 'leaderboard'" style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden;">
        
        <div style="background: linear-gradient(135deg, #ec4899, #f472b6); color: white; padding: 2rem;">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-size: 2rem; font-weight: 700; margin: 0;">👑 Liderlik Tablosu</h3>
            <select v-model="leaderboardType" @change="loadLeaderboard" 
                    style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; padding: 0.75rem 1rem; border-radius: 15px; font-weight: 600; backdrop-filter: blur(10px);">
              <option value="points" style="color: #1f2937;">💰 Puan</option>
              <option value="level" style="color: #1f2937;">⭐ Seviye</option>
              <option value="achievements" style="color: #1f2937;">🏆 Başarım</option>
              <option value="streak" style="color: #1f2937;">🔥 Seri</option>
            </select>
          </div>
        </div>
        
        <div>
          <div v-for="(entry, index) in leaderboard" :key="entry.user.id" 
               style="padding: 1.5rem 2rem; border-bottom: 2px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between;"
               :style="{ background: entry.user.id === currentUserId ? 'linear-gradient(135deg, #fef3c7, #fde68a)' : 'white' }">
            
            <div style="display: flex; align-items: center; gap: 2rem;">
              <div style="font-size: 2rem; font-weight: 900; min-width: 60px; text-align: center;"
                   :style="{ 
                     color: entry.rank === 1 ? '#fbbf24' : entry.rank === 2 ? '#9ca3af' : entry.rank === 3 ? '#f97316' : '#6b7280'
                   }">
                #{{ entry.rank }}
              </div>
              <div>
                <div style="font-size: 1.3rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">{{ entry.user.name }}</div>
                <div style="font-size: 1rem; color: #6b7280; font-weight: 500;">
                  ⭐ Seviye {{ entry.level }} • 🏆 {{ entry.achievements_count }} Başarım
                </div>
              </div>
            </div>
            
            <div style="text-align: right;">
              <div style="font-size: 2rem; font-weight: 900; color: #ec4899; margin-bottom: 0.25rem;">{{ entry.score }}</div>
              <div style="font-size: 1rem; color: #6b7280; font-weight: 600;">{{ getScoreLabel(leaderboardType) }}</div>
            </div>
          </div>
        </div>
        
        <div v-if="currentUserRank" style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb); padding: 1.5rem 2rem; text-align: center;">
          <div style="font-size: 1.2rem; color: #374151; font-weight: 600;">
            🎯 Senin Sıralaman: <span style="font-weight: 900; color: #ec4899; font-size: 1.4rem;">#{{ currentUserRank }}</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import apiService from '@/services/api'

const authStore = useAuthStore()
const currentUserId = authStore.user?.id

const activeTab = ref('daily')
const userStats = ref({
  level: 1,
  current_xp: 0,
  total_xp: 0,
  xp_for_next_level: 100,
  xp_progress: 0,
  points: 0,
  daily_streak: 0,
  max_streak: 0,
  achievements_count: 0,
  tasks_completed_today: 0,
  total_tasks_today: 0,
  tasks_completion_rate: 0
})

const dailyTasks = ref([])
const weeklyTasks = ref([])
const achievements = ref([])
const leaderboard = ref([])
const leaderboardType = ref('points')
const currentUserRank = ref(null)

const loadUserStats = async () => {
  try {
    const response = await apiService.get('/gamification/stats')
    if (response.data.success) {
      userStats.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading user stats:', error)
  }
}

const loadDailyTasks = async () => {
  try {
    const response = await apiService.get('/gamification/tasks/daily')
    if (response.data.success) {
      dailyTasks.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading daily tasks:', error)
  }
}

const loadWeeklyTasks = async () => {
  try {
    const response = await apiService.get('/gamification/tasks/weekly')
    if (response.data.success) {
      weeklyTasks.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading weekly tasks:', error)
  }
}

const loadAchievements = async () => {
  try {
    const response = await apiService.get('/gamification/achievements')
    if (response.data.success) {
      achievements.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading achievements:', error)
  }
}

const loadLeaderboard = async () => {
  try {
    const response = await apiService.get('/gamification/leaderboard', {
      params: { type: leaderboardType.value }
    })
    if (response.data.success) {
      leaderboard.value = response.data.data.leaderboard
      currentUserRank.value = response.data.data.current_user_rank
    }
  } catch (error) {
    console.error('Error loading leaderboard:', error)
  }
}

const getDifficultyColor = (difficulty: string) => {
  const colors = {
    bronze: 'linear-gradient(135deg, #cd7f32, #b8860b)',
    silver: 'linear-gradient(135deg, #c0c0c0, #a8a8a8)',
    gold: 'linear-gradient(135deg, #ffd700, #ffb347)',
    platinum: 'linear-gradient(135deg, #e5e4e2, #b8b8b8)',
    diamond: 'linear-gradient(135deg, #b9f2ff, #87ceeb)'
  }
  return colors[difficulty] || 'linear-gradient(135deg, #6b7280, #4b5563)'
}

const getDifficultyText = (difficulty: string) => {
  const texts = {
    bronze: 'Bronz',
    silver: 'Gümüş',
    gold: 'Altın',
    platinum: 'Platin',
    diamond: 'Elmas'
  }
  return texts[difficulty] || difficulty
}

const getCategoryText = (category: string) => {
  const texts = {
    health: 'Sağlık',
    social: 'Sosyal',
    learning: 'Öğrenme',
    wellness: 'Wellness',
    engagement: 'Etkileşim'
  }
  return texts[category] || category
}

const getScoreLabel = (type: string) => {
  const labels = {
    points: 'Puan',
    level: 'Seviye',
    achievements: 'Başarım',
    streak: 'Seri'
  }
  return labels[type] || 'Puan'
}

const trackLogin = async () => {
  try {
    await apiService.post('/gamification/track', {
      action_type: 'login',
      action_target: 'dashboard',
      metadata: { timestamp: new Date().toISOString() }
    })
    // Refresh data after tracking
    setTimeout(() => {
      loadUserStats()
      loadDailyTasks()
      loadWeeklyTasks()
      loadAchievements()
    }, 500)
  } catch (error) {
    console.error('Error tracking login:', error)
  }
}

onMounted(() => {
  trackLogin()
  loadUserStats()
  loadDailyTasks()
  loadWeeklyTasks()
  loadAchievements()
  loadLeaderboard()
})
</script>