<template>
  <div>

    <!-- Breadcrumb -->
    <nav style="background: white; border-bottom: 1px solid #e5e7eb; padding: 12px 0;">
      <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; color: #6b7280;">
          <router-link to="/" style="color: #e57399; text-decoration: none;">Ana Sayfa</router-link>
          <span>›</span>
          <span style="color: #111827; font-weight: 500;">Forum</span>
        </div>
      </div>
    </nav>

    <div style="min-height: 100vh; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #f3e8ff 100%);">
      <div style="max-width: 1280px; margin: 0 auto; padding: 40px 16px;">
        
        <!-- Hero Section -->
        <header style="text-align: center; margin-bottom: 48px;">
          <h1 style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 16px;">
            💬 Kadın Forumu & Topluluk
          </h1>
          <p style="font-size: 1.25rem; color: #6b7280; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            50.000+ kadınla deneyim paylaş, uzman tavsiyeleri al, güçlü bir toplulukla büyü
          </p>
          <div style="margin-top: 24px; display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
            <button @click="showNewTopicModal = true" 
                    style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 12px 32px; border-radius: 25px; border: none; cursor: pointer; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 15px rgba(229, 115, 153, 0.3); transition: transform 0.2s;"
                    @mouseover="$event.target.style.transform = 'translateY(-2px)'"
                    @mouseleave="$event.target.style.transform = 'translateY(0)'">
              ✨ Yeni Konu Aç
            </button>
            <button style="background: white; color: #e57399; padding: 12px 32px; border-radius: 25px; border: 2px solid #e57399; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.2s;"
                    @mouseover="$event.target.style.background = '#e57399'; $event.target.style.color = 'white'"
                    @mouseleave="$event.target.style.background = 'white'; $event.target.style.color = '#e57399'">
              📊 İstatistikler
            </button>
          </div>
        </header>

        <!-- Live Stats -->
        <section style="margin-bottom: 48px;">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.totalTopics.toLocaleString() }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Toplam Konu</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">+{{ stats.todayTopics }} bugün</div>
            </div>
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.activeMembers.toLocaleString() }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Aktif Üye</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">{{ stats.onlineNow }} şu an online</div>
            </div>
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.totalMessages.toLocaleString() }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Toplam Mesaj</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">+{{ stats.todayMessages }} bugün</div>
            </div>
            <div style="background: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
              <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #e57399 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ stats.expertAnswers.toLocaleString() }}
              </div>
              <div style="color: #6b7280; font-weight: 500; margin-top: 4px;">Uzman Yanıt</div>
              <div style="color: #10b981; font-size: 0.875rem; margin-top: 4px;">Doktor & Uzmanlar</div>
            </div>
          </div>
        </section>

        <!-- Popular Categories -->
        <section style="margin-bottom: 48px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 2rem; font-weight: 700; color: #111827;">
              🏆 Popüler Kategoriler
            </h2>
            <button @click="showAllTopics" 
                    style="background: #e57399; color: white; padding: 8px 16px; border-radius: 12px; border: none; cursor: pointer; font-weight: 500; font-size: 0.875rem;">
              Tüm Konular
            </button>
          </div>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            
            <article v-for="category in categories" :key="category.id"
                     @click="selectCategory(category)"
                     class="category-card"
                     :class="{ 'selected': selectedCategoryId === category.id }"
                     style="background: white; padding: 28px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s; border: 1px solid #f3f4f6; position: relative; overflow: hidden;"
                     @mouseover="$event.currentTarget.style.transform = 'translateY(-8px)'; $event.currentTarget.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)'"
                     @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)'">
              
              <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 100%); border-radius: 0 0 0 100px; opacity: 0.5;"></div>
              
              <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px; position: relative; z-index: 1;">
                <div style="font-size: 3rem; padding: 12px; background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 100%); border-radius: 16px;">
                  {{ category.icon }}
                </div>
                <div>
                  <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 4px;">
                    {{ category.name }}
                  </h3>
                  <div style="display: flex; align-items: center; gap: 12px; font-size: 0.875rem; color: #6b7280;">
                    <span>{{ category.topicsCount }} konu</span>
                    <span>•</span>
                    <span>{{ category.membersCount }} üye</span>
                  </div>
                </div>
              </div>
              
              <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6; margin-bottom: 16px; position: relative; z-index: 1;">
                {{ category.description }}
              </p>
              
              <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <div style="display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: reverse; flex-direction: row-reverse;">
                    <div v-for="(avatar, index) in category.recentMembers" :key="index"
                         style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #e57399, #be185d); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem; margin-left: -8px; border: 2px solid white;">
                      {{ avatar }}
                    </div>
                  </div>
                  <span style="color: #9ca3af; font-size: 0.75rem;">+{{ category.membersCount - 3 }} diğer</span>
                </div>
                <div style="background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.875rem;">
                  Katıl →
                </div>
              </div>
            </article>

          </div>
        </section>

        <!-- Trending Topics -->
        <section style="margin-bottom: 48px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 2rem; font-weight: 700; color: #111827;">
              🔥 Trend Konular
            </h2>
            <div style="display: flex; gap: 8px;">
              <button v-for="filter in filters" :key="filter.key"
                      @click="activeFilter = filter.key"
                      :style="`padding: 8px 16px; border-radius: 20px; border: none; cursor: pointer; font-weight: 500; transition: all 0.2s; ${activeFilter === filter.key ? 'background: linear-gradient(135deg, #e57399 0%, #be185d 100%); color: white;' : 'background: white; color: #6b7280; border: 1px solid #e5e7eb;'}`">
                {{ filter.label }}
              </button>
            </div>
          </div>

          <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #f3f4f6;">
            
            <div v-if="filteredTopics.length" style="divide-y: 1px solid #f3f4f6;">
              <article v-for="(topic, index) in filteredTopics" :key="topic.id"
                       @click="viewTopic(topic)"
                       style="padding: 24px; cursor: pointer; transition: all 0.2s; position: relative;"
                       @mouseover="$event.currentTarget.style.backgroundColor = '#fafafa'"
                       @mouseleave="$event.currentTarget.style.backgroundColor = 'white'">
                
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                  <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                      <div v-if="topic.isPinned" style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                        📌 Sabitlenmiş
                      </div>
                      <div v-if="topic.isHot" style="background: #fee2e2; color: #dc2626; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                        🔥 Popüler
                      </div>
                      <div v-if="topic.hasExpertReply" style="background: #dcfce7; color: #16a34a; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                        ✅ Uzman Yanıtı
                      </div>
                    </div>
                    
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px; line-height: 1.4;">
                      {{ topic.title }}
                    </h3>
                    
                    <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 16px; line-height: 1.5;">
                      {{ topic.excerpt }}
                    </p>
                    
                    <div style="display: flex; align-items: center; gap: 20px; font-size: 0.875rem; color: #9ca3af;">
                      <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #e57399, #be185d); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem;">
                          {{ topic.author.avatar }}
                        </div>
                        <span style="font-weight: 500; color: #374151;">{{ topic.author.name }}</span>
                        <span v-if="topic.author.isExpert" style="background: #dbeafe; color: #1d4ed8; padding: 2px 6px; border-radius: 8px; font-size: 0.75rem; font-weight: 500;">
                          Uzman
                        </span>
                      </div>
                      <span>{{ formatDate(topic.createdAt) }}</span>
                      <span>{{ topic.repliesCount }} yanıt</span>
                      <span>{{ topic.viewsCount.toLocaleString() }} görüntüleme</span>
                      <span>{{ topic.likesCount }} beğeni</span>
                    </div>
                  </div>
                  
                  <div style="display: flex; flex-direction: column; align-items: end; gap: 8px;">
                    <div style="background: linear-gradient(135deg, #fce7f3 0%, #f3e8ff 100%); color: #be185d; padding: 6px 12px; border-radius: 16px; font-size: 0.75rem; font-weight: 600;">
                      {{ topic.category }}
                    </div>
                    <div v-if="topic.lastReply" style="text-align: right; font-size: 0.75rem; color: #9ca3af;">
                      <div>Son yanıt:</div>
                      <div style="font-weight: 500; color: #374151;">{{ topic.lastReply.author }}</div>
                      <div>{{ formatDate(topic.lastReply.createdAt) }}</div>
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
const selectedCategoryId = ref<number | null>(null)

const stats = ref({
  totalTopics: 12847,
  todayTopics: 23,
  activeMembers: 52341,
  onlineNow: 1247,
  totalMessages: 186592,
  todayMessages: 342,
  expertAnswers: 8934
})

const categories = ref([
  {
    id: 1,
    name: 'Anneler Kulübü',
    icon: '🤱',
    description: 'Hamilelik, doğum, bebek bakımı ve çocuk gelişimi konularında deneyim paylaşımı',
    topicsCount: 3245,
    membersCount: 18420,
    recentMembers: ['AK', 'MZ', 'SY']
  },
  {
    id: 2,
    name: 'Kariyer & İş Hayatı',
    icon: '💼',
    description: 'İş arama, kariyer gelişimi, girişimcilik ve iş-yaşam dengesi',
    topicsCount: 1892,
    membersCount: 12650,
    recentMembers: ['EL', 'BT', 'NK']
  },
  {
    id: 3,
    name: 'Sağlık & Wellness',
    icon: '🏥',
    description: 'Kadın sağlığı, beslenme, egzersiz ve mental sağlık',
    topicsCount: 2156,
    membersCount: 15780,
    recentMembers: ['DK', 'FG', 'HL']
  },
  {
    id: 4,
    name: 'Güzellik & Bakım',
    icon: '💄',
    description: 'Cilt bakımı, makyaj, saç bakımı ve güzellik ipuçları',
    topicsCount: 1567,
    membersCount: 9340,
    recentMembers: ['ZA', 'PL', 'QW']
  },
  {
    id: 5,
    name: 'İlişkiler & Evlilik',
    icon: '💕',
    description: 'Romantik ilişkiler, evlilik, aile hayatı ve sosyal ilişkiler',
    topicsCount: 2890,
    membersCount: 16720,
    recentMembers: ['RT', 'UI', 'OP']
  },
  {
    id: 6,
    name: 'Hobi & Yaşam',
    icon: '🎨',
    description: 'El sanatları, yemek, dekorasyon ve yaşam tarzı',
    topicsCount: 1234,
    membersCount: 8560,
    recentMembers: ['AS', 'DF', 'GH']
  }
])

const topics = ref([
  {
    id: 1,
    title: 'Bebeğim 6 aylık oldu, ek gıdaya nasıl başlamalıyım?',
    excerpt: 'Merhaba anneler, bebeğim 6 aylık oldu ve doktor ek gıdaya başlamamızı söyledi. Hangi yiyeceklerle başlamalıyım? Deneyimlerinizi paylaşır mısınız?',
    category: 'Anneler Kulübü',
    author: { name: 'Ayşe M.', avatar: 'AM', isExpert: false },
    createdAt: '2024-01-15T10:30:00Z',
    repliesCount: 23,
    viewsCount: 1247,
    likesCount: 18,
    isPinned: false,
    isHot: true,
    hasExpertReply: true,
    lastReply: { author: 'Dr. Elif K.', createdAt: '2024-01-15T14:20:00Z' }
  },
  {
    id: 2,
    title: 'İş görüşmesinde hamile olduğumu söylemeli miyim?',
    excerpt: '3 aylık hamileyim ve yeni bir işe başvurdum. İş görüşmesinde hamile olduğumu belirtmeli miyim? Hukuki durumu bilen var mı?',
    category: 'Kariyer & İş Hayatı',
    author: { name: 'Zeynep K.', avatar: 'ZK', isExpert: false },
    createdAt: '2024-01-15T09:15:00Z',
    repliesCount: 31,
    viewsCount: 2156,
    likesCount: 42,
    isPinned: true,
    isHot: true,
    hasExpertReply: true,
    lastReply: { author: 'Av. Murat B.', createdAt: '2024-01-15T13:45:00Z' }
  },
  {
    id: 3,
    title: 'Cilt bakım rutinimde ne değiştirmeliyim?',
    excerpt: 'Yaşım 35, karma cilt tipim var. Son zamanlarda cildimde değişiklikler fark ettim. Hangi ürünleri kullanmalıyım?',
    category: 'Güzellik & Bakım',
    author: { name: 'Selin A.', avatar: 'SA', isExpert: false },
    createdAt: '2024-01-15T08:45:00Z',
    repliesCount: 15,
    viewsCount: 892,
    likesCount: 12,
    isPinned: false,
    isHot: false,
    hasExpertReply: false,
    lastReply: { author: 'Fatma Y.', createdAt: '2024-01-15T12:30:00Z' }
  }
])

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
  // Filter topics by selected category
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
  // Navigate to topic detail
  console.log('View topic:', topic.title)
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