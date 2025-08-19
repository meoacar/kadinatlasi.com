import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import HomeView from '../views/HomeView-Simple.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/hesaplama',
      name: 'hesaplama',
      component: () => import('../views/HesaplamaView.vue'),
    },
    {
      path: '/hesaplama/vki',
      name: 'bmi-calculator',
      component: () => import('../views/calculator/BMICalculatorView.vue'),
    },
    {
      path: '/hesaplama/regl-takvimi',
      name: 'menstrual-cycle',
      component: () => import('../views/calculator/MenstrualCycleView.vue'),
    },
    {
      path: '/hesaplama/gebelik',
      name: 'pregnancy-calculator',
      component: () => import('../views/calculator/PregnancyCalculatorView.vue'),
    },
    {
      path: '/hesaplama/kalori',
      name: 'calorie-calculator',
      component: () => import('../views/calculator/CalorieCalculatorView.vue'),
    },
    {
      path: '/hesaplama/su-ihtiyaci',
      name: 'water-intake-calculator',
      component: () => import('../views/calculator/WaterIntakeView.vue'),
    },
    {
      path: '/hesaplama/finans-planlayici',
      name: 'financial-planner',
      component: () => import('../views/calculator/FinancialPlannerView.vue'),
    },
    {
      path: '/hesaplama/finans',
      redirect: '/hesaplama/finans-planlayici'
    },
    {
      path: '/hesaplama/dogurganlik-takibi',
      name: 'fertility-tracker',
      component: () => import('../views/calculator/FertilityTrackerView.vue'),
    },
    {
      path: '/hesaplama/ideal-kilo',
      name: 'ideal-weight',
      component: () => import('../views/calculator/IdealWeightView.vue'),
    },
    {
      path: '/hesaplama/ovulasyon',
      name: 'ovulation-calculator',
      component: () => import('../views/calculator/OvulationCalculatorView.vue'),
    },
    {
      path: '/hesaplama/dogum-tarihi',
      name: 'due-date-calculator',
      component: () => import('../views/calculator/DueDateCalculatorView.vue'),
    },
    {
      path: '/hesaplama/vucut-yag-orani',
      name: 'body-fat-calculator',
      component: () => import('../views/calculator/BodyFatCalculatorView.vue'),
    },
    {
      path: '/blog',
      name: 'blog',
      component: () => import('../views/BlogView.vue'),
    },
    {
      path: '/blog/:id',
      name: 'blog-detail',
      component: () => import('../views/BlogDetailView.vue'),
    },
    {
      path: '/forum',
      name: 'forum',
      component: () => import('../views/ForumView.vue'),
    },
    {
      path: '/forum/topic/:id',
      name: 'forum-topic',
      component: () => import('../views/ForumTopicView.vue'),
    },
    {
      path: '/saglik',
      name: 'saglik',
      component: () => import('../views/SaglikView.vue'),
    },
    {
      path: '/guzellik',
      name: 'guzellik',
      component: () => import('../views/GuzellikView.vue'),
    },
    {
      path: '/astroloji',
      name: 'astroloji',
      component: () => import('../views/AstrolojiView.vue'),
    },
    {
      path: '/shop',
      name: 'shop',
      component: () => import('../views/ShopViewNew.vue'),
    },
    {
      path: '/shop/product/:id',
      name: 'product-detail',
      component: () => import('../views/ProductDetailView.vue'),
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('../views/CartViewNew.vue'),
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: () => import('../views/CheckoutView.vue'),
    },
    {
      path: '/gebelik',
      name: 'gebelik',
      component: () => import('../views/GebelikView.vue'),
    },
    {
      path: '/bebek-isimleri',
      name: 'baby-names',
      component: () => import('../views/BabyNamesView.vue'),
    },
    {
      path: '/lohusalik',
      name: 'postpartum',
      component: () => import('../views/LohusalikView.vue'),
    },
    {
      path: '/bebek-bakimi',
      name: 'baby-care',
      component: () => import('../views/BebekBakimView.vue'),
    },
    {
      path: '/gebelik-takibi',
      name: 'pregnancy-tracker',
      component: () => import('../views/PregnancyView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/fitness',
      name: 'fitness',
      component: () => import('../views/FitnessView.vue'),
    },
    {
      path: '/tarifler',
      name: 'recipes',
      component: () => import('../views/RecipeView.vue'),
    },
    {
      path: '/psikoloji',
      name: 'psychology',
      component: () => import('../views/PsikolojiView.vue'),
    },
    {
      path: '/iletisim',
      name: 'contact',
      component: () => import('../views/ContactView.vue'),
    },
    {
      path: '/etkinlikler',
      name: 'events',
      component: () => import('../views/EventsView.vue'),
    },

    {
      path: '/bildirimler',
      name: 'notifications',
      component: () => import('../views/NotificationsView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('../views/ProfileView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/orders',
      name: 'orders',
      component: () => import('../views/OrdersView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/dogum-sonrasi-rehber',
      name: 'postpartum-guide',
      component: () => import('../views/PostpartumGuideView.vue'),
    },
    {
      path: '/destek-kaynaklari',
      name: 'support-resources',
      component: () => import('../views/SupportResourcesView.vue'),
    },
    {
      path: '/kisisel-egzersiz',
      name: 'personal-workout',
      component: () => import('../views/PersonalWorkoutView.vue'),
    },
    {
      path: '/ikinci-el-pazar',
      name: 'second-hand-market',
      component: () => import('../views/SecondHandMarketView.vue'),
    },
    {
      path: '/ikinci-el-pazar/urun/:id',
      name: 'second-hand-product-detail',
      component: () => import('../views/SecondHandProductDetailView.vue'),
    },
    {
      path: '/ikinci-el-pazar/kullanici/:id',
      name: 'second-hand-user-profile',
      component: () => import('../views/SecondHandUserProfileView.vue'),
    },
    {
      path: '/ikinci-el-pazar/favorilerim',
      name: 'second-hand-favorites',
      component: () => import('../views/SecondHandFavoritesView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/butce-planlayici',
      name: 'budget-planner',
      component: () => import('../views/BudgetPlannerView.vue'),
    },
    {
      path: '/butce-takibi',
      name: 'budget-tracker',
      component: () => import('../views/BudgetTrackerView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/gorevler-basarimlar',
      name: 'gamification',
      component: () => import('../views/GamificationView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/premium',
      name: 'premium',
      component: () => import('../views/PremiumView.vue')
    },
    {
      path: '/premium/success',
      name: 'premium-success',
      component: () => import('../views/PremiumSuccessView.vue')
    },
    {
      path: '/premium/failed',
      name: 'premium-failed',
      component: () => import('../views/PremiumFailedView.vue')
    },
    {
      path: '/premium/payment/:id',
      name: 'premium-payment',
      component: () => import('../views/PremiumPaymentView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/courses',
      name: 'courses',
      component: () => import('../views/CoursesView.vue')
    },
    {
      path: '/courses/:id',
      name: 'course-detail',
      component: () => import('../views/CourseDetailView.vue')
    },
    {
      path: '/uzman-soru-sor',
      name: 'expert-question',
      component: () => import('../views/ExpertQuestionView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/sorularim',
      name: 'my-questions',
      component: () => import('../views/MyQuestionsView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/uzman-sorulari',
      name: 'expert-dashboard',
      component: () => import('../views/ExpertDashboardView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/kariyer',
      name: 'kariyer',
      component: () => import('../views/KariyerView.vue'),
    },
    {
      path: '/cv-hazirlama',
      name: 'cv-hazirlama',
      component: () => import('../views/CVHazirlamaView.vue'),
    },

    {
      path: '/:slug',
      name: 'page',
      component: () => import('../views/PageView.vue'),
    },
  ],
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next('/dashboard')
  } else {
    next()
  }
})

export default router
