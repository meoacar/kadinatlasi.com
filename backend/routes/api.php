<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\BlogCommentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\CalculatorController;
use App\Http\Controllers\Api\ForumTopicController;
use App\Http\Controllers\Api\ForumPostController;
use App\Http\Controllers\Api\ForumController;
use App\Http\Controllers\Api\HoroscopeController;
use App\Http\Controllers\Api\ForumGroupController;
use App\Http\Controllers\Api\ModerationController;
use App\Http\Controllers\Api\ForumPollController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SupportResourceController;
use App\Http\Controllers\Api\AdvertisementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Site settings (public)
Route::get('/site-settings', function() {
    return response()->json([
        'site_name' => \App\Models\SiteSetting::get('site_name', 'KadınAtlası.com'),
        'site_description' => \App\Models\SiteSetting::get('site_description', 'Kadınların günlük hayatını kolaylaştıran dijital platform'),
        'theme_color' => \App\Models\SiteSetting::get('theme_color', '#E57399'),
        'enable_forum' => \App\Models\SiteSetting::get('enable_forum', '1') === '1',
        'enable_comments' => \App\Models\SiteSetting::get('enable_comments', '1') === '1',
        'enable_blog' => \App\Models\SiteSetting::get('enable_blog', '1') === '1',
        'enable_calculators' => \App\Models\SiteSetting::get('enable_calculators', '1') === '1',
        'enable_marketplace' => \App\Models\SiteSetting::get('enable_marketplace', '1') === '1',
        'user_registration' => \App\Models\SiteSetting::get('user_registration', '1') === '1',
        'maintenance_mode' => \App\Models\SiteSetting::get('maintenance_mode', '0') === '1',
        'social_media' => [
            'facebook' => \App\Models\SiteSetting::get('facebook_url', ''),
            'instagram' => \App\Models\SiteSetting::get('instagram_url', ''),
            'twitter' => \App\Models\SiteSetting::get('twitter_url', ''),
            'youtube' => \App\Models\SiteSetting::get('youtube_url', ''),
        ]
    ]);
});

// Public content routes (cached)
Route::middleware('cache:30')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/blog-posts', [BlogPostController::class, 'index']);
    Route::get('/blog-posts/{id}', [BlogPostController::class, 'show']);
    Route::get('/blog-stats', [BlogPostController::class, 'getStats']);
    
    // Blog Comments (public read)
    Route::get('/blog-posts/{id}/comments', [BlogPostController::class, 'getComments']);
});

// Blog interaction endpoints (auth required)
Route::middleware('auth:sanctum')->group(function () {
    // Blog Comments
    Route::post('/blog-posts/{id}/comments', [BlogPostController::class, 'storeComment']);
    
    // Blog Likes
    Route::get('/blog-posts/{id}/like-status', [BlogPostController::class, 'getLikeStatus']);
    Route::post('/blog-posts/{id}/toggle-like', [BlogPostController::class, 'toggleLike']);
});

// Forum routes (public read)
Route::get('/forum/topics', [ForumController::class, 'topics']);
Route::get('/forum/topics/{topic}', [ForumController::class, 'show']);
Route::get('/forum/categories', [ForumController::class, 'categories']);
Route::get('/forum/stats', [ForumController::class, 'stats']);
Route::get('/forum/limits', [ForumController::class, 'limits'])->middleware('auth:sanctum');

// Forum groups (public read)
Route::get('/forum/groups', [ForumGroupController::class, 'index']);
Route::get('/forum/groups/{id}', [ForumGroupController::class, 'show']);

// Forum polls (public read)
Route::get('/forum/polls', [ForumPollController::class, 'index']);
Route::get('/forum/polls/{id}', [ForumPollController::class, 'show']);

// Horoscope routes (public)
Route::get('/horoscope/zodiac-signs', [HoroscopeController::class, 'getZodiacSigns']);
Route::get('/horoscope/today', [HoroscopeController::class, 'getTodayHoroscope']);
Route::get('/horoscope/weekly', [HoroscopeController::class, 'getWeeklyHoroscope']);
Route::get('/horoscope/monthly', [HoroscopeController::class, 'getMonthlyHoroscope']);
Route::get('/horoscope', [HoroscopeController::class, 'getHoroscope']);
Route::post('/horoscope/refresh', [HoroscopeController::class, 'refreshAllHoroscopes']);

// Calculator routes (public)
Route::post('/calculator/bmi', [CalculatorController::class, 'bmi']);
Route::post('/calculator/menstrual-cycle', [CalculatorController::class, 'menstrualCycle']);
Route::post('/calculator/pregnancy', [CalculatorController::class, 'pregnancy']);
Route::post('/calculator/calorie', [CalculatorController::class, 'calorie']);
Route::post('/calculator/water-intake', [CalculatorController::class, 'waterIntake']);
Route::post('/calculator/financial-planner', [CalculatorController::class, 'financialPlanner']);
Route::post('/calculator/fertility-tracker', [CalculatorController::class, 'fertilityTracker']);
Route::post('/calculator/ideal-weight', [CalculatorController::class, 'idealWeightCalculator']);
Route::post('/calculator/ovulation', [CalculatorController::class, 'ovulationCalculator']);
Route::post('/calculator/due-date', [CalculatorController::class, 'dueDateCalculator']);
Route::post('/calculator/body-fat', [CalculatorController::class, 'bodyFatCalculator']);

// Astrology routes (public)
Route::get('/astrology/daily', [\App\Http\Controllers\Api\AstrologyController::class, 'daily']);
Route::get('/astrology/weekly', [\App\Http\Controllers\Api\AstrologyController::class, 'weekly']);
Route::get('/astrology/monthly', [\App\Http\Controllers\Api\AstrologyController::class, 'monthly']);
Route::get('/astrology/sign-by-date', [\App\Http\Controllers\Api\AstrologyController::class, 'getSignByDate']);

// E-commerce routes (public)
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
Route::get('/product-categories', [\App\Http\Controllers\Api\ProductController::class, 'categories']);

// Second-hand market routes
Route::get('/second-hand', [\App\Http\Controllers\Api\SecondHandController::class, 'index']);
Route::get('/second-hand/categories', [\App\Http\Controllers\Api\SecondHandController::class, 'getCategories']);
Route::get('/second-hand/stats', [\App\Http\Controllers\Api\SecondHandController::class, 'getStats']);
Route::get('/second-hand/{id}', [\App\Http\Controllers\Api\SecondHandController::class, 'show']);
Route::post('/second-hand', [\App\Http\Controllers\Api\SecondHandController::class, 'store'])->middleware('auth:sanctum');
Route::get('/second-hand/limits/user', [\App\Http\Controllers\Api\SecondHandController::class, 'getListingLimits'])->middleware('auth:sanctum');

// Second-hand market authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    // Messages
    Route::get('/second-hand/{id}/messages', [\App\Http\Controllers\Api\SecondHandController::class, 'getMessages']);
    Route::post('/second-hand/{id}/messages', [\App\Http\Controllers\Api\SecondHandController::class, 'sendMessage']);
    
    // Favorites
    Route::post('/second-hand/{id}/favorite', [\App\Http\Controllers\Api\SecondHandController::class, 'toggleFavorite']);
    Route::get('/second-hand/favorites/my', [\App\Http\Controllers\Api\SecondHandController::class, 'getFavorites']);
    
    // Reviews
    Route::post('/second-hand/{id}/review', [\App\Http\Controllers\Api\SecondHandController::class, 'addReview']);
});

// Second-hand reviews (public)
Route::get('/second-hand/{id}/reviews', [\App\Http\Controllers\Api\SecondHandController::class, 'getReviews']);

// User profiles (public)
Route::get('/second-hand/users/{id}', [\App\Http\Controllers\Api\SecondHandController::class, 'getUserProfile']);

// Admin routes for second-hand market
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::put('/second-hand/{id}', [\App\Http\Controllers\Api\SecondHandController::class, 'update']);
    Route::delete('/second-hand/{id}', [\App\Http\Controllers\Api\SecondHandController::class, 'destroy']);
});

// Newsletter routes (public)
Route::post('/newsletter/subscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'subscribe']);
Route::post('/newsletter/unsubscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'unsubscribe']);



// Pregnancy routes (public)
Route::get('/pregnancy/weeks', [\App\Http\Controllers\Api\PregnancyController::class, 'getAllWeeks']);
Route::get('/pregnancy/week/{week}', [\App\Http\Controllers\Api\PregnancyController::class, 'getWeeklyGuide']);

// Postpartum routes (public)
Route::get('/postpartum/guides', [\App\Http\Controllers\Api\PostpartumController::class, 'getGuides']);
Route::get('/postpartum/guides/{id}', [\App\Http\Controllers\Api\PostpartumController::class, 'getGuide']);
Route::get('/postpartum/breastfeeding-tips', [\App\Http\Controllers\Api\PostpartumController::class, 'getBreastfeedingTips']);
Route::get('/postpartum/nutrition-guide', [\App\Http\Controllers\Api\PostpartumController::class, 'getNutritionGuide']);

// Baby care routes (public)
Route::get('/baby-care/tips', [\App\Http\Controllers\Api\BabyCareController::class, 'getTips']);
Route::get('/baby-care/tips/{id}', [\App\Http\Controllers\Api\BabyCareController::class, 'getTip']);
Route::get('/baby-care/categories', [\App\Http\Controllers\Api\BabyCareController::class, 'getCategories']);
Route::get('/baby-care/category/{category}', [\App\Http\Controllers\Api\BabyCareController::class, 'getTipsByCategory']);

// Baby names routes (public)
Route::get('/baby-names', [\App\Http\Controllers\Api\BabyNameController::class, 'index']);
Route::get('/baby-names/{id}', [\App\Http\Controllers\Api\BabyNameController::class, 'show']);
Route::get('/baby-names/random', [\App\Http\Controllers\Api\BabyNameController::class, 'random']);

// Fitness routes (public)
Route::get('/fitness/random-exercise', [\App\Http\Controllers\Api\FitnessController::class, 'getRandomExercise']);
Route::get('/exercises', [\App\Http\Controllers\Api\FitnessController::class, 'getExercises']);
Route::get('/exercises/{id}', [\App\Http\Controllers\Api\FitnessController::class, 'getExercise']);
Route::get('/workout-plans', [\App\Http\Controllers\Api\FitnessController::class, 'getWorkoutPlans']);
Route::get('/workout-plans/{id}', [\App\Http\Controllers\Api\FitnessController::class, 'getWorkoutPlan']);
Route::get('/fitness/categories', [\App\Http\Controllers\Api\FitnessController::class, 'getCategories']);
Route::post('/fitness/personal-program', [\App\Http\Controllers\Api\FitnessController::class, 'createPersonalProgram']);
Route::get('/diet-plans', [\App\Http\Controllers\Api\FitnessController::class, 'getDietPlans']);
Route::get('/exercise-videos', [\App\Http\Controllers\Api\FitnessController::class, 'getExerciseVideos']);

// Recipe & Diet routes (public)
Route::get('/recipes', [\App\Http\Controllers\Api\RecipeController::class, 'index']);
Route::get('/recipes/{id}', [\App\Http\Controllers\Api\RecipeController::class, 'show']);
Route::get('/diet-plans', [\App\Http\Controllers\Api\RecipeController::class, 'getDietPlans']);
Route::get('/diet-plans/{id}', [\App\Http\Controllers\Api\RecipeController::class, 'getDietPlan']);
Route::get('/recipes/categories', [\App\Http\Controllers\Api\RecipeController::class, 'getCategories']);

// Psychology routes (public)
Route::get('/psychology/daily-quote', [\App\Http\Controllers\Api\PsychologyController::class, 'getDailyQuote']);
Route::get('/psychology/quotes', [\App\Http\Controllers\Api\PsychologyController::class, 'getQuotesByCategory']);
Route::get('/psychology/tests', [\App\Http\Controllers\Api\PsychologyController::class, 'getPsychologyTests']);
Route::get('/psychology/tests/{id}', [\App\Http\Controllers\Api\PsychologyController::class, 'getPsychologyTest']);
Route::post('/psychology/tests/{id}/submit', [\App\Http\Controllers\Api\PsychologyController::class, 'submitPsychologyTest']);
Route::get('/psychology/categories', [\App\Http\Controllers\Api\PsychologyController::class, 'getCategories']);
Route::get('/psychology/support-resources', [\App\Http\Controllers\Api\PsychologyController::class, 'getSupportResources']);

// Support Resources routes (public)
Route::get('/support-resources', [\App\Http\Controllers\Api\SupportResourceController::class, 'index']);
Route::get('/support-resources/categories', [\App\Http\Controllers\Api\SupportResourceController::class, 'categories']);
Route::get('/support-resources/{supportResource}', [\App\Http\Controllers\Api\SupportResourceController::class, 'show']);

// Advertisement routes (public)
Route::get('/advertisements', [\App\Http\Controllers\Api\AdvertisementController::class, 'getActiveAds']);
Route::post('/advertisements/{id}/click', [\App\Http\Controllers\Api\AdvertisementController::class, 'trackClick']);

// Beauty & Fashion routes (public)
Route::get('/beauty/categories', [\App\Http\Controllers\Api\BeautyController::class, 'categories']);
Route::get('/beauty/articles', [\App\Http\Controllers\Api\BeautyController::class, 'articles']);
Route::get('/beauty/articles/{id}', [\App\Http\Controllers\Api\BeautyController::class, 'article']);
Route::get('/beauty/products', [\App\Http\Controllers\Api\BeautyController::class, 'products']);
Route::get('/beauty/videos', [\App\Http\Controllers\Api\BeautyController::class, 'videos']);
Route::get('/beauty/tips', [\App\Http\Controllers\Api\BeautyController::class, 'tips']);
Route::get('/beauty/outfits', [\App\Http\Controllers\Api\BeautyController::class, 'outfits']);
Route::post('/beauty/outfits', [\App\Http\Controllers\Api\BeautyController::class, 'storeOutfit'])->middleware('auth:sanctum');

// Premium plans (public)
Route::get('/premium/plans', [\App\Http\Controllers\Api\PremiumController::class, 'plans']);

// Subscription routes (public)
Route::get('/subscription/plans', [\App\Http\Controllers\Api\SubscriptionController::class, 'plans']);
Route::post('/subscription/callback', [\App\Http\Controllers\Api\SubscriptionController::class, 'callback']);
Route::post('/subscription/paytr/callback', [\App\Http\Controllers\Api\SubscriptionController::class, 'paytrCallback']);
Route::get('/subscription/paytr/success', [\App\Http\Controllers\Api\SubscriptionController::class, 'paytrSuccess']);
Route::get('/subscription/paytr/failed', [\App\Http\Controllers\Api\SubscriptionController::class, 'paytrFailed']);

// Courses (public read)
Route::get('/courses', [\App\Http\Controllers\Api\CourseController::class, 'index']);
Route::get('/courses/{id}', [\App\Http\Controllers\Api\CourseController::class, 'show']);

// Footer data (public)
Route::get('/footer', [\App\Http\Controllers\Api\FooterController::class, 'getFooterData']);

// Pages (public)
Route::get('/pages/{slug}', [\App\Http\Controllers\Api\PageController::class, 'show']);

// Contact (public)
Route::post('/contact', [\App\Http\Controllers\Api\ContactController::class, 'store']);

// Partnerships (public)
Route::get('/partnerships', [\App\Http\Controllers\Api\PartnershipController::class, 'index']);
Route::get('/partnerships/stats', [\App\Http\Controllers\Api\PartnershipController::class, 'stats']);

// Hero Sliders (public)
Route::get('/hero-sliders', [\App\Http\Controllers\Api\HeroSliderController::class, 'index']);

// Daily Check-in (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checkin/today', [\App\Http\Controllers\Api\DailyCheckinController::class, 'todayStatus']);
    Route::post('/checkin', [\App\Http\Controllers\Api\DailyCheckinController::class, 'checkin']);
    Route::get('/checkin/stats', [\App\Http\Controllers\Api\DailyCheckinController::class, 'stats']);
});

// Events (public)
Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);
Route::get('/events/{id}', [\App\Http\Controllers\Api\EventController::class, 'show']);

// Event registration (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events/{id}/register', [\App\Http\Controllers\Api\EventController::class, 'register']);
    Route::delete('/events/{id}/unregister', [\App\Http\Controllers\Api\EventController::class, 'unregister']);
    Route::get('/events/my/registered', [\App\Http\Controllers\Api\EventController::class, 'myEvents']);
});

// Achievements (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/achievements/my', [\App\Http\Controllers\Api\AchievementController::class, 'myAchievements']);
    Route::get('/achievements/progress', [\App\Http\Controllers\Api\AchievementController::class, 'progress']);
});

// Budget routes (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/budget/categories', [\App\Http\Controllers\Api\BudgetController::class, 'getCategories']);
    Route::get('/budget/entries', [\App\Http\Controllers\Api\BudgetController::class, 'getEntries']);
    Route::post('/budget/entries', [\App\Http\Controllers\Api\BudgetController::class, 'store']);
    Route::put('/budget/entries/{id}', [\App\Http\Controllers\Api\BudgetController::class, 'update']);
    Route::delete('/budget/entries/{id}', [\App\Http\Controllers\Api\BudgetController::class, 'destroy']);
    Route::get('/budget/stats', [\App\Http\Controllers\Api\BudgetController::class, 'getStats']);
});

// Gamification routes (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/gamification/stats', [\App\Http\Controllers\Api\GamificationController::class, 'getUserStats']);
    Route::get('/gamification/tasks/daily', [\App\Http\Controllers\Api\GamificationController::class, 'getDailyTasks']);
    Route::get('/gamification/tasks/weekly', [\App\Http\Controllers\Api\GamificationController::class, 'getWeeklyTasks']);
    Route::get('/gamification/achievements', [\App\Http\Controllers\Api\GamificationController::class, 'getAchievements']);
    Route::get('/gamification/leaderboard', [\App\Http\Controllers\Api\GamificationController::class, 'getLeaderboard']);
    Route::get('/gamification/activity', [\App\Http\Controllers\Api\GamificationController::class, 'getActivityHistory']);
    Route::post('/gamification/track', [\App\Http\Controllers\Api\GamificationController::class, 'trackAction']);
    Route::post('/gamification/tasks/{taskId}/complete', [\App\Http\Controllers\Api\GamificationController::class, 'completeTask']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // User profile routes
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::put('/profile', [UserProfileController::class, 'update']);
    Route::post('/profile/avatar', [UserProfileController::class, 'uploadAvatar']);
    
    // Blog post management
    Route::post('/blog-posts', [BlogPostController::class, 'store']);
    Route::put('/blog-posts/{id}', [BlogPostController::class, 'update']);
    Route::delete('/blog-posts/{id}', [BlogPostController::class, 'destroy']);
    
    // Blog comments
    Route::post('/blog-posts/{blogPostId}/comments', [BlogCommentController::class, 'store']);
    Route::put('/blog-comments/{id}', [BlogCommentController::class, 'update']);
    Route::delete('/blog-comments/{id}', [BlogCommentController::class, 'destroy']);
    
    // Forum management
    Route::post('/forum/topics', [ForumController::class, 'store']);
    Route::post('/forum/topics/{topic}/reply', [ForumController::class, 'reply']);
    
    // Forum moderation (requires admin or moderator role)
    Route::middleware('role:admin|moderator')->group(function () {
        Route::post('/forum/topics/{id}/pin', [ForumTopicController::class, 'pin']);
        Route::post('/forum/topics/{id}/unpin', [ForumTopicController::class, 'unpin']);
        Route::post('/forum/topics/{id}/lock', [ForumTopicController::class, 'lock']);
        Route::post('/forum/topics/{id}/unlock', [ForumTopicController::class, 'unlock']);
    });
    
    // Forum posts
    Route::post('/forum/topics/{topicId}/posts', [ForumPostController::class, 'store']);
    Route::put('/forum/posts/{id}', [ForumPostController::class, 'update']);
    Route::delete('/forum/posts/{id}', [ForumPostController::class, 'destroy']);
    
    // Forum groups
    Route::post('/forum/groups', [ForumGroupController::class, 'store']);
    Route::post('/forum/groups/{id}/join', [ForumGroupController::class, 'join']);
    Route::post('/forum/groups/{id}/leave', [ForumGroupController::class, 'leave']);
    Route::post('/forum/groups/{id}/moderate', [ForumGroupController::class, 'moderate']);
    
    // Forum polls
    Route::post('/forum/polls', [ForumPollController::class, 'store']);
    Route::post('/forum/polls/{id}/vote', [ForumPollController::class, 'vote']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    
    // Push Notifications
    Route::post('/push-notifications/subscribe', [\App\Http\Controllers\Api\PushNotificationController::class, 'subscribe']);
    Route::post('/push-notifications/unsubscribe', [\App\Http\Controllers\Api\PushNotificationController::class, 'unsubscribe']);
    Route::post('/push-notifications/test', [\App\Http\Controllers\Api\PushNotificationController::class, 'sendTest']);
    
    // Expert applications
    Route::post('/expert-applications', [\App\Http\Controllers\Api\ExpertApplicationController::class, 'store']);
    
    // Expert questions
    Route::get('/expert-questions', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'index']);
    Route::post('/expert-questions', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'store']);
    Route::get('/expert-questions/{expertQuestion}', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'show']);
    Route::post('/expert-questions/{expertQuestion}/answer', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'answer']);
    Route::get('/expert-questions/my/questions', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'myQuestions']);
    Route::get('/expert-questions/pending/list', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'pendingQuestions']);
    Route::get('/expert-questions/categories', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'categories']);
    Route::get('/expert-questions/limits', [\App\Http\Controllers\Api\ExpertQuestionController::class, 'questionLimits']);
    
    // Premium subscriptions
    Route::post('/premium/subscribe', [\App\Http\Controllers\Api\PremiumController::class, 'subscribe']);
    Route::post('/premium/complete-payment/{id}', [\App\Http\Controllers\Api\PremiumController::class, 'completePayment']);
    Route::get('/premium/subscription', [\App\Http\Controllers\Api\PremiumController::class, 'getUserSubscription']);
    
    // Subscription management
    Route::post('/subscription/subscribe', [\App\Http\Controllers\Api\SubscriptionController::class, 'subscribe']);
    Route::get('/subscription/my', [\App\Http\Controllers\Api\SubscriptionController::class, 'mySubscription']);
    Route::post('/subscription/cancel', [\App\Http\Controllers\Api\SubscriptionController::class, 'cancel']);
    Route::get('/subscription/payment-history', [\App\Http\Controllers\Api\SubscriptionController::class, 'paymentHistory']);
    
    // Invoice management
    Route::get('/invoices', [\App\Http\Controllers\Api\InvoiceController::class, 'index']);
    Route::get('/invoices/{id}', [\App\Http\Controllers\Api\InvoiceController::class, 'show']);
    Route::get('/invoices/{id}/download', [\App\Http\Controllers\Api\InvoiceController::class, 'downloadPDF']);
    
    // Premium content access
    Route::middleware('premium:premium_content')->group(function () {
        Route::get('/premium/blog-posts', [BlogPostController::class, 'premiumPosts']);
        Route::get('/premium/calculators/advanced', [CalculatorController::class, 'advancedCalculators']);
    });
    
    // Badge and Reputation system
    Route::get('/badges', [\App\Http\Controllers\Api\BadgeController::class, 'index']);
    Route::get('/badges/my', [\App\Http\Controllers\Api\BadgeController::class, 'userBadges']);
    Route::get('/reputation/my', [\App\Http\Controllers\Api\BadgeController::class, 'userReputation']);
    
    // Admin badge management
    Route::middleware('role:admin')->group(function () {
        Route::post('/badges/award', [\App\Http\Controllers\Api\BadgeController::class, 'awardBadge']);
    });
    
    // Course enrollment (protected)
    Route::post('/courses/{id}/enroll', [\App\Http\Controllers\Api\CourseController::class, 'enroll']);
    
    // Cart routes
    Route::get('/cart', [\App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post('/cart/items', [\App\Http\Controllers\Api\CartController::class, 'addItem']);
    Route::put('/cart/items/{id}', [\App\Http\Controllers\Api\CartController::class, 'updateItem']);
    Route::delete('/cart/items/{id}', [\App\Http\Controllers\Api\CartController::class, 'removeItem']);
    
    // Order routes
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::post('/orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
    
    // Pregnancy tracker routes
    Route::get('/pregnancy/tracker', [\App\Http\Controllers\Api\PregnancyController::class, 'getPregnancyTracker']);
    Route::post('/pregnancy/tracker', [\App\Http\Controllers\Api\PregnancyController::class, 'createPregnancyTracker']);
    
    // Psychology protected routes
    Route::get('/psychology/mood-tracker', [\App\Http\Controllers\Api\PsychologyController::class, 'getMoodTracker']);
    Route::post('/psychology/mood-tracker', [\App\Http\Controllers\Api\PsychologyController::class, 'saveMoodTracker']);
    Route::get('/psychology/mood-history', [\App\Http\Controllers\Api\PsychologyController::class, 'getMoodHistory']);
    
    // Mood tracker routes
    Route::post('/mood-tracker', [\App\Http\Controllers\Api\MoodTrackerController::class, 'store']);
    Route::get('/mood-tracker', [\App\Http\Controllers\Api\MoodTrackerController::class, 'index']);
    Route::get('/mood-tracker/stats', [\App\Http\Controllers\Api\MoodTrackerController::class, 'stats']);
    
    // Fitness tracker routes
    Route::post('/fitness/workout-timer', [\App\Http\Controllers\Api\FitnessController::class, 'saveWorkoutTimer']);
    Route::post('/fitness/body-measurement', [\App\Http\Controllers\Api\FitnessController::class, 'saveBodyMeasurement']);
    Route::get('/fitness/stats', [\App\Http\Controllers\Api\FitnessController::class, 'getFitnessStats']);
    
    // Category management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
        
        // Moderation routes
        Route::get('/moderation/pending', [ModerationController::class, 'getPendingContent']);
        Route::post('/moderation/forum-topics/{id}', [ModerationController::class, 'moderateForumTopic']);
        Route::get('/moderation/stats', [ModerationController::class, 'getStats']);
    });
});