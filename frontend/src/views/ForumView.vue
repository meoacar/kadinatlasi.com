<template>
  <div>

    <!-- Breadcrumb -->
    <nav style="background: white; border-bottom: 1px solid #e5e7eb; padding: 12px 0;">
      <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; color: #6b7280;">
          <router-link to="/" style="color: #e57399; text-decoration: none;">Ana Sayfa</router-link>
          <span>›</span>
          <span v-if="selectedCategory" style="color: #6b7280;">
            <router-link to="/forum" style="color: #e57399; text-decoration: none;">Forum</router-link>
            <span style="margin: 0 8px;">›</span>
            <span style="color: #111827; font-weight: 500;">{{ selectedCategory.name }}</span>
          </span>
          <span v-else style="color: #111827; font-weight: 500;">Forum</span>
        </div>
      </div>
    </nav>

    <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%);">
      <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        
        <!-- Header Section -->
        <header style="margin-bottom: 32px;">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
              <h1 v-if="!selectedCategory" style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 8px;">
                💬 Kadın Forumu
              </h1>
              <h1 v-else style="font-size: 2.5rem; font-weight: 800; color: #111827; margin-bottom: 8px; display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 2rem;">{{ selectedCategory.icon }}</span>
                {{ selectedCategory.name }}
              </h1>
              <p v-if="!selectedCategory" style="font-size: 1.125rem; color: #6b7280; max-width: 600px;">
                {{ stats.activeMembers?.toLocaleString() || '50.000' }}+ kadınla deneyim paylaş, uzman tavsiyeleri al
              </p>
              <p v-else style="font-size: 1.125rem; color: #6b7280; max-width: 600px;">
                {{ selectedCategory.description }}
              </p>
            </div>
            
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
              <button @click="showNewTopicModal = true" 
                      style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600; font-size: 0.875rem; box-shadow: 0 4px 15px rgba(229, 115, 153, 0.3); transition: transform 0.2s;"
                      @mouseover="$event.target.style.transform = 'translateY(-2px)'"
                      @mouseleave="$event.target.style.transform = 'translateY(0)'">
                ✨ Yeni Konu Aç
              </button>
              <button v-if="selectedCategory" @click="selectedCategory = null"
                      style="background: white; color: #6b7280; padding: 12px 24px; border-radius: 12px; border: 1px solid #e5e7eb; cursor: pointer; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;"
                      @mouseover="$event.target.style.background = '#f9fafb'"
                      @mouseleave="$event.target.style.background = 'white'">
                ← Tüm Kategoriler
              </button>
            </div>
          </div>
        </header>

        <!-- Quick Stats (sadece ana sayfada göster) -->
        <section v-if="!selectedCategory" style="margin-bottom: 32px;">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
            <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f3f4f6;">
              <div style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.totalTopics?.toLocaleString() || '12.847' }}
              </div>
              <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; margin-top: 4px;">Toplam Konu</div>
              <div style="color: #10b981; font-size: 0.75rem; margin-top: 2px;">+{{ stats.todayTopics || '23' }} bugün</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f3f4f6;">
              <div style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.activeMembers?.toLocaleString() || '52.341' }}
              </div>
              <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; margin-top: 4px;">Aktif Üye</div>
              <div style="color: #10b981; font-size: 0.75rem; margin-top: 2px;">{{ stats.onlineNow || '1.247' }} online</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f3f4f6;">
              <div style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.totalMessages?.toLocaleString() || '186.592' }}
              </div>
              <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; margin-top: 4px;">Toplam Mesaj</div>
              <div style="color: #10b981; font-size: 0.75rem; margin-top: 2px;">+{{ stats.todayMessages || '342' }} bugün</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f3f4f6;">
              <div style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.expertAnswers?.toLocaleString() || '8.934' }}
              </div>
              <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; margin-top: 4px;">Uzman Yanıt</div>
              <div style="color: #10b981; font-size: 0.75rem; margin-top: 2px;">Doktor & Uzmanlar</div>
            </div>
          </div>
        </section>

        <!-- Forum Categories (ana sayfa) -->
        <section v-if="!selectedCategory" style="margin-bottom: 48px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827;">
              🏆 Forum Kategorileri
            </h2>
            <div style="display: flex; gap: 8px;">
              <button @click="showAllTopics" 
                      style="background: white; color: #6b7280; padding: 8px 16px; border-radius: 8px; border: 1px solid #e5e7eb; cursor: pointer; font-weight: 500; font-size: 0.875rem; transition: all 0.2s;"
                      @mouseover="$event.target.style.background = '#f9fafb'"
                      @mouseleave="$event.target.style.background = 'white'">
                📋 Tüm Konular
              </button>
            </div>
          </div>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            
            <article v-for="category in categories" :key="category.id"
                     @click="selectCategory(category)"
                     class="category-card"
                     style="background: white; padding: 20px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); cursor: pointer; transition: all 0.3s; border: 1px solid #f3f4f6; position: relative; overflow: hidden;"
                     @mouseover="$event.currentTarget.style.transform = 'translateY(-4px)'; $event.currentTarget.style.boxShadow = '0 8px 25px rgba(0,0,0,0.12)'"
                     @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 15px rgba(0,0,0,0.08)'">
              
              <div style="position: absolute; top: 0; right: 0; width: 60px; height: 60px; background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 100%); border-radius: 0 0 0 60px; opacity: 0.3;"></div>
              
              <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; position: relative; z-index: 1;">
                <div style="font-size: 2rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 100%); border-radius: 12px;">
                  {{ category.icon }}
                </div>
                <div style="flex: 1;">
                  <h3 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin-bottom: 4px; line-height: 1.2;">
                    {{ category.name }}
                  </h3>
                  <div style="display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #6b7280;">
                    <span>{{ category.topicsCount || category.topics_count || 0 }} konu</span>
                    <span>•</span>
                    <span>{{ Math.floor((category.topicsCount || category.topics_count || 0) * 1.5) }} mesaj</span>
                  </div>
                </div>
              </div>
              
              <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.5; margin-bottom: 12px; position: relative; z-index: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ category.description }}
              </p>
              
              <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1;">
                <div style="display: flex; align-items: center; gap: 6px;">
                  <div style="display: flex;">
                    <div v-for="i in Math.min(3, Math.floor(Math.random() * 3) + 1)" :key="i"
                         style="width: 24px; height: 24px; border-radius: 50%; background: linear-gradient(135deg, #e57399, #be185d); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.625rem; margin-left: -4px; border: 1px solid white;"
                         :style="i === 1 ? 'margin-left: 0;' : ''">
                      {{ String.fromCharCode(65 + Math.floor(Math.random() * 26)) }}{{ String.fromCharCode(65 + Math.floor(Math.random() * 26)) }}
                    </div>
                  </div>
                  <span style="color: #9ca3af; font-size: 0.6875rem;">aktif üyeler</span>
                </div>
                <div style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 6px 12px; border-radius: 12px; font-weight: 600; font-size: 0.75rem;">
                  Gör →
                </div>
              </div>
            </article>

          </div>
        </section>

        <!-- Topics Section -->
        <section style="margin-bottom: 48px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827;">
              <span v-if="selectedCategory">{{ selectedCategory.icon }} {{ selectedCategory.name }} - Konular</span>
              <span v-else>🔥 Popüler Konular</span>
            </h2>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
              <button v-for="filter in filters" :key="filter.key"
                      @click="activeFilter = filter.key"
                      :style="`padding: 6px 12px; border-radius: 12px; border: none; cursor: pointer; font-weight: 500; font-size: 0.75rem; transition: all 0.2s; ${activeFilter === filter.key ? 'background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white;' : 'background: white; color: #6b7280; border: 1px solid #e5e7eb;'}`">
                {{ filter.label }}
              </button>
            </div>
          </div>

          <div style="background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f3f4f6;">
            
            <div v-if="filteredTopics.length">
              <article v-for="(topic, index) in filteredTopics" :key="topic.id"
                       @click="viewTopic(topic)"
                       :style="`padding: 20px; cursor: pointer; transition: all 0.2s; position: relative; ${index > 0 ? 'border-top: 1px solid #f3f4f6;' : ''}`"
                       @mouseover="$event.currentTarget.style.backgroundColor = '#fafafa'"
                       @mouseleave="$event.currentTarget.style.backgroundColor = 'white'">
                
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 16px;">
                  <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                      <div v-if="topic.isPinned || topic.is_pinned" style="background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 8px; font-size: 0.625rem; font-weight: 600;">
                        📌 Sabit
                      </div>
                      <div v-if="topic.isHot || (topic.viewsCount || topic.views_count || 0) > 1000" style="background: #fee2e2; color: #dc2626; padding: 2px 6px; border-radius: 8px; font-size: 0.625rem; font-weight: 600;">
                        🔥 Popüler
                      </div>
                      <div v-if="topic.hasExpertReply || topic.is_featured" style="background: #dcfce7; color: #16a34a; padding: 2px 6px; border-radius: 8px; font-size: 0.625rem; font-weight: 600;">
                        ✅ Uzman
                      </div>
                      <div v-if="!selectedCategory" style="background: #f3f4f6; color: #6b7280; padding: 2px 6px; border-radius: 8px; font-size: 0.625rem; font-weight: 500;">
                        {{ topic.category || (topic.forumCategory && topic.forumCategory.name) || 'Genel' }}
                      </div>
                    </div>
                    
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 6px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                      {{ topic.title }}
                    </h3>
                    
                    <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 12px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                      {{ topic.excerpt || (topic.content && topic.content.substring(0, 150) + '...') || 'Konu içeriği...' }}
                    </p>
                    
                    <div style="display: flex; align-items: center; gap: 16px; font-size: 0.75rem; color: #9ca3af; flex-wrap: wrap;">
                      <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #e57399, #be185d); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.625rem;">
                          {{ (topic.author?.avatar || topic.user?.name?.substring(0, 2) || 'U').toUpperCase() }}
                        </div>
                        <span style="font-weight: 500; color: #374151;">{{ topic.author?.name || topic.user?.name || 'Kullanıcı' }}</span>
                        <span v-if="topic.author?.isExpert || topic.user?.is_expert" style="background: #dbeafe; color: #1d4ed8; padding: 1px 4px; border-radius: 6px; font-size: 0.625rem; font-weight: 500;">
                          Uzman
                        </span>
                      </div>
                      <span>{{ formatDate(topic.createdAt || topic.created_at) }}</span>
                      <span>{{ topic.repliesCount || topic.replies_count || 0 }} yanıt</span>
                      <span>{{ (topic.viewsCount || topic.views_count || 0).toLocaleString() }} görüntüleme</span>
                      <span>{{ topic.likesCount || topic.likes_count || 0 }} beğeni</span>
                    </div>
                  </div>
                  
                  <div style="display: flex; flex-direction: column; align-items: end; gap: 6px; flex-shrink: 0;">
                    <div v-if="topic.lastReply || topic.lastPostUser" style="text-align: right; font-size: 0.6875rem; color: #9ca3af;">
                      <div style="margin-bottom: 2px;">Son yanıt:</div>
                      <div style="font-weight: 500; color: #374151;">{{ topic.lastReply?.author || topic.lastPostUser?.name || 'Bilinmiyor' }}</div>
                      <div>{{ formatDate(topic.lastReply?.createdAt || topic.last_post_at) }}</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 4px 8px; border-radius: 8px; font-size: 0.625rem; font-weight: 600;">
                      Oku →
                    </div>
                  </div>
                </div>
              </article>
            </div>

            <div v-if="loading" style="padding: 60px; text-align: center;">
              <div style="width: 40px; height: 40px; border: 3px solid #f3f4f6; border-top: 3px solid #e57399; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
              <p style="margin-top: 16px; color: #6b7280; font-weight: 500;">Konular yükleniyor...</p>
            </div>

            <div v-if="!loading && filteredTopics.length === 0" style="padding: 60px; text-align: center;">
              <div style="font-size: 4rem; margin-bottom: 16px;">💬</div>
              <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Henüz konu yok</h3>
              <p style="color: #6b7280; margin-bottom: 24px;">İlk konuyu sen aç, toplulukla paylaş!</p>
              <button @click="showNewTopicModal = true"
                      style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 12px 24px; border-radius: 20px; border: none; cursor: pointer; font-weight: 600;">
                İlk Konuyu Aç
              </button>
            </div>

          </div>
        </section>

        <!-- Expert Corner -->
        <section style="margin-bottom: 48px;">
          <div style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); border-radius: 20px; padding: 32px; color: white; text-align: center;">
            <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 16px;">
              👩‍⚕️ Uzman Köşesi
            </h2>
            <p style="font-size: 1.125rem; margin-bottom: 24px; opacity: 0.9;">
              Doktor, psikolog, diyetisyen ve uzmanlardan profesyonel destek al
            </p>
            <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
              <button @click="askExperts"
                      style="background: white; color: #e57399; padding: 12px 24px; border-radius: 20px; border: none; cursor: pointer; font-weight: 600; transition: transform 0.2s;"
                      @mouseover="$event.target.style.transform = 'translateY(-2px)'"
                      @mouseleave="$event.target.style.transform = 'translateY(0)'">
                Uzmanlara Sor
              </button>
              <button @click="becomeExpert"
                      style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); cursor: pointer; font-weight: 600; transition: all 0.2s;"
                      @mouseover="$event.target.style.background = 'rgba(255,255,255,0.3)'"
                      @mouseleave="$event.target.style.background = 'rgba(255,255,255,0.2)'">
                Uzman Ol
              </button>
            </div>
          </div>
        </section>

      </div>
    </div>

    <!-- New Topic Modal -->
    <div v-if="showNewTopicModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 16px;">
      <div style="background: white; border-radius: 20px; padding: 32px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 24px;">{{ newTopic.title.includes('Uzman') ? 'Uzmanlara Soru Sor' : 'Yeni Konu Aç' }}</h2>
        
        <form @submit.prevent="createTopic" style="display: flex; flex-direction: column; gap: 20px;">
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Kategori</label>
            <select v-model="newTopic.categoryId" required 
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; background: white;">
              <option value="">Kategori seçin</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Konu Başlığı</label>
            <input v-model="newTopic.title" type="text" required placeholder="Konunuzu özetleyen başlık yazın..."
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px;">
          </div>
          
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">İçerik</label>
            <textarea v-model="newTopic.content" required placeholder="Konunuzu detaylı olarak açıklayın..."
                      style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; height: 120px; resize: vertical;"></textarea>
          </div>
          
          <div style="display: flex; gap: 12px; justify-content: end;">
            <button type="button" @click="showNewTopicModal = false"
                    style="background: #f3f4f6; color: #6b7280; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600;">
              İptal
            </button>
            <button type="submit"
                    style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600;">
              Konuyu Yayınla
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Expert Application Modal -->
    <div v-if="showExpertApplicationModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 16px;">
      <div style="background: white; border-radius: 20px; padding: 32px; max-width: 700px; width: 100%; max-height: 90vh; overflow-y: auto;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 24px;">Uzman Başvuru Formu</h2>
        
        <form @submit.prevent="submitExpertApplication" style="display: flex; flex-direction: column; gap: 20px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
              <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Ad Soyad</label>
              <input v-model="expertApplication.name" type="text" required placeholder="Adınız ve soyadınız"
                     style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px;">
            </div>
            <div>
              <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">E-posta</label>
              <input v-model="expertApplication.email" type="email" required placeholder="E-posta adresiniz"
                     style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px;">
            </div>
          </div>
          
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Meslek/Uzmanlık Alanı</label>
            <select v-model="expertApplication.profession" required 
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; background: white;">
              <option value="">Mesleğinizi seçin</option>
              <option value="doktor">Doktor</option>
              <option value="psikolog">Psikolog</option>
              <option value="diyetisyen">Diyetisyen</option>
              <option value="ebe">Ebe</option>
              <option value="fizyoterapist">Fizyoterapist</option>
              <option value="avukat">Avukat</option>
              <option value="egitmen">Eğitmen/Koç</option>
              <option value="diger">Diğer</option>
            </select>
          </div>
          
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Deneyim Süresi</label>
            <input v-model="expertApplication.experience" type="text" required placeholder="Örn: 5 yıl"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px;">
          </div>
          
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Uzmanlık Alanı Detayı</label>
            <textarea v-model="expertApplication.specialization" required placeholder="Hangi konularda uzmanlığınız var? Detaylı açıklayın..."
                      style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; height: 80px; resize: vertical;"></textarea>
          </div>
          
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Sertifika/Diploma (PDF)</label>
            <input @change="handleFileUpload" type="file" accept=".pdf,.jpg,.jpeg,.png" 
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px;">
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 4px;">Mesleğinizi doğrulayan belge yükleyin</p>
          </div>
          
          <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Motivasyon</label>
            <textarea v-model="expertApplication.motivation" required placeholder="Neden KadınAtlası'nda uzman olmak istiyorsunuz?"
                      style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; height: 100px; resize: vertical;"></textarea>
          </div>
          
          <div style="display: flex; gap: 12px; justify-content: end;">
            <button type="button" @click="showExpertApplicationModal = false"
                    style="background: #f3f4f6; color: #6b7280; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600;">
              İptal
            </button>
            <button type="submit"
                    style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600;">
              Başvuru Gönder
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

// Reactive data
const loading = ref(false)
const showNewTopicModal = ref(false)
const showExpertApplicationModal = ref(false)
const expertApplication = ref({
  name: '',
  email: '',
  profession: '',
  experience: '',
  specialization: '',
  certificate: null as File | null,
  motivation: ''
})
const activeFilter = ref('trending')
const selectedCategory = ref<any>(null)
const selectedCategoryId = ref<number | null>(null)

const stats = ref({
  totalTopics: 0,
  todayTopics: 0,
  activeMembers: 0,
  onlineNow: 0,
  totalMessages: 0,
  todayMessages: 0,
  expertAnswers: 0
})

const categories = ref([])

const topics = ref([])

const filters = ref([
  { key: 'trending', label: '🔥 Trend' },
  { key: 'recent', label: '🕒 En Yeni' },
  { key: 'popular', label: '👑 Popüler' },
  { key: 'expert', label: '👩‍⚕️ Uzman Yanıtlı' }
])

const newTopic = ref({
  categoryId: '',
  title: '',
  content: ''
})

// Computed
const filteredTopics = computed(() => {
  let filtered = [...topics.value]
  
  switch (activeFilter.value) {
    case 'trending':
      return filtered.filter(t => t.isHot).sort((a, b) => b.viewsCount - a.viewsCount)
    case 'recent':
      return filtered.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
    case 'popular':
      return filtered.sort((a, b) => b.likesCount - a.likesCount)
    case 'expert':
      return filtered.filter(t => t.hasExpertReply)
    default:
      return filtered
  }
})

// Methods
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return 'Az önce'
  if (diffInHours < 24) return `${diffInHours} saat önce`
  if (diffInHours < 48) return 'Dün'
  
  return date.toLocaleDateString('tr-TR', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const selectCategory = (category: any) => {
  // Set selected category
  selectedCategory.value = category
  selectedCategoryId.value = category.id
  fetchTopicsByCategory(category.id)
  console.log('Selected category:', category.name)
}

const fetchTopicsByCategory = async (categoryId: number) => {
  try {
    loading.value = true
    const response = await api.get(`/forum/topics?category_id=${categoryId}`)
    topics.value = response.data.data || response.data
  } catch (error) {
    console.error('Kategori konuları yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const viewTopic = (topic: any) => {
  // Navigate to topic detail - ForumTopicView.vue sayfasına git
  console.log('View topic:', topic.title)
  // TODO: router.push(`/forum/topic/${topic.id}`)
}

const askExperts = () => {
  showNewTopicModal.value = true
  // Pre-select health category for expert questions
  const expertCategory = categories.value.find(c => c.name.includes('Sağlık'))
  if (expertCategory) {
    newTopic.value.categoryId = expertCategory.id.toString()
  }
  // Pre-fill title to indicate expert question
  newTopic.value.title = 'Uzman Sorusu: '
}

const becomeExpert = () => {
  showExpertApplicationModal.value = true
}

const createTopic = async () => {
  try {
    loading.value = true
    // API call to create topic
    await api.post('/forum/topics', newTopic.value)
    
    // Reset form
    newTopic.value = { categoryId: '', title: '', content: '' }
    showNewTopicModal.value = false
    
    // Refresh topics
    await fetchTopics()
  } catch (error) {
    console.error('Konu oluşturulurken hata:', error)
  } finally {
    loading.value = false
  }
}

const fetchTopics = async () => {
  try {
    loading.value = true
    const response = await api.get('/forum/topics')
    topics.value = response.data.data || response.data
  } catch (error) {
    console.error('Konular yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
}

const showAllTopics = () => {
  selectedCategory.value = null
  selectedCategoryId.value = null
  fetchTopics()
}

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    expertApplication.value.certificate = target.files[0]
  }
}

const submitExpertApplication = async () => {
  try {
    loading.value = true
    
    const formData = new FormData()
    formData.append('name', expertApplication.value.name)
    formData.append('email', expertApplication.value.email)
    formData.append('profession', expertApplication.value.profession)
    formData.append('experience', expertApplication.value.experience)
    formData.append('specialization', expertApplication.value.specialization)
    formData.append('motivation', expertApplication.value.motivation)
    
    if (expertApplication.value.certificate) {
      formData.append('certificate', expertApplication.value.certificate)
    }
    
    // API call to submit expert application
    await api.post('/expert-applications', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    // Reset form
    expertApplication.value = {
      name: '',
      email: '',
      profession: '',
      experience: '',
      specialization: '',
      certificate: null,
      motivation: ''
    }
    
    showExpertApplicationModal.value = false
    alert('Başvurunuz başarıyla gönderildi! En kısa sürede size dönüş yapacağız.')
    
  } catch (error) {
    console.error('Uzman başvurusu gönderilirken hata:', error)
    alert('Başvuru gönderilirken bir hata oluştu. Lütfen tekrar deneyin.')
  } finally {
    loading.value = false
  }
}

const refreshData = async () => {
  await Promise.all([
    fetchTopics(),
    // Refresh other data if needed
  ])
}

// Lifecycle
onMounted(async () => {
  try {
    const [topicsRes, categoriesRes, statsRes] = await Promise.all([
      api.get('/forum/topics'),
      api.get('/forum/categories'),
      api.get('/forum/stats')
    ])
    
    if (topicsRes.data.success) {
      topics.value = topicsRes.data.data
    }
    
    if (categoriesRes.data.success) {
      categories.value = categoriesRes.data.data
    }
    
    if (statsRes.data.success) {
      stats.value = statsRes.data.data
    }
  } catch (error) {
    console.error('Forum verileri yüklenirken hata:', error)
  }
})
</script>

<style scoped>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.category-card.selected {
  border: 2px solid #e57399 !important;
  background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%) !important;
}

.category-card:hover {
  transform: translateY(-8px) !important;
  box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}
</style>