<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpertQuestion;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpertQuestionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->is_expert) {
            // Uzman için bekleyen sorular
            $questions = ExpertQuestion::with(['user', 'category'])
                ->pending()
                ->where('category_id', $user->expertApplication->category_id ?? null)
                ->latest()
                ->paginate(10);
        } else {
            // Normal kullanıcı için kendi soruları
            $questions = ExpertQuestion::with(['expert', 'category'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return response()->json($questions);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user can ask questions
        if (!\App\Models\ExpertQuestionLimit::canAskQuestion($user)) {
            $membershipType = $user->premium_subscription->plan->name ?? 'Ücretsiz';
            $remaining = \App\Models\ExpertQuestionLimit::getRemainingQuestions($user);
            
            if (!$user->premium_subscription) {
                return response()->json([
                    'message' => 'Uzmanlara soru sorabilmek için premium üyelik gereklidir. Lütfen üyeliğinizi yükseltin.'
                ], 403);
            }
            
            return response()->json([
                'message' => "Bu ay soru sorma limitinizi doldurdunuz. {$membershipType} üyeliğinizle ayda maksimum " . \App\Models\ExpertQuestionLimit::getQuestionLimit($user->premium_subscription->plan->slug) . " soru sorabilirsiniz."
            ], 403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'question' => 'required|string|max:2000',
            'is_public' => 'boolean'
        ]);

        $question = ExpertQuestion::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'question' => $request->question,
            'is_public' => $request->is_public ?? false,
            'status' => 'pending'
        ]);

        // Increment question count
        \App\Models\ExpertQuestionLimit::incrementQuestionCount($user->id);

        return response()->json([
            'message' => 'Sorunuz başarıyla gönderildi. Uzmanlarımız en kısa sürede cevap verecektir.',
            'question' => $question->load(['category'])
        ], 201);
    }

    public function show(ExpertQuestion $expertQuestion)
    {
        $user = Auth::user();
        
        // Sadece soru sahibi veya uzman görebilir
        if ($expertQuestion->user_id !== $user->id && !$user->is_expert) {
            return response()->json(['message' => 'Bu soruyu görme yetkiniz yok.'], 403);
        }

        return response()->json($expertQuestion->load(['user', 'expert', 'category']));
    }

    public function answer(Request $request, ExpertQuestion $expertQuestion)
    {
        $user = Auth::user();
        
        if (!$user->is_expert) {
            return response()->json(['message' => 'Bu işlem için uzman yetkisi gereklidir.'], 403);
        }

        $request->validate([
            'answer' => 'required|string|max:5000'
        ]);

        $expertQuestion->update([
            'expert_id' => $user->id,
            'answer' => $request->answer,
            'status' => 'answered',
            'answered_at' => now()
        ]);

        // Soru sahibine bildirim gönder
        Notification::create([
            'user_id' => $expertQuestion->user_id,
            'title' => 'Sorunuz Cevaplandı',
            'message' => "'{$expertQuestion->title}' başlıklı sorunuz uzmanımız tarafından cevaplandı.",
            'type' => 'expert_answer',
            'data' => json_encode(['question_id' => $expertQuestion->id])
        ]);

        return response()->json([
            'message' => 'Cevabınız başarıyla kaydedildi.',
            'question' => $expertQuestion->load(['user', 'expert', 'category'])
        ]);
    }

    public function categories()
    {
        $categories = Category::select('id', 'name', 'slug')->get();
        return response()->json($categories);
    }

    public function myQuestions()
    {
        $questions = ExpertQuestion::with(['expert', 'category'])
            ->where('user_id', Auth::id())
            ->approved() // Only show approved questions to users
            ->latest()
            ->paginate(10);

        return response()->json($questions);
    }

    public function pendingQuestions()
    {
        $user = Auth::user();
        
        if (!$user->is_expert) {
            return response()->json(['message' => 'Bu işlem için uzman yetkisi gereklidir.'], 403);
        }

        $questions = ExpertQuestion::with(['user', 'category'])
            ->pending()
            ->approved() // Only show approved questions to experts
            ->where('category_id', $user->expertApplication->category_id ?? null)
            ->latest()
            ->paginate(10);

        return response()->json($questions);
    }

    public function questionLimits()
    {
        $user = Auth::user();
        
        $membershipType = $user->premium_subscription->plan->slug ?? 'free';
        $membershipName = $user->premium_subscription->plan->name ?? 'Ücretsiz';
        $limit = \App\Models\ExpertQuestionLimit::getQuestionLimit($membershipType);
        $used = \App\Models\ExpertQuestionLimit::getUserQuestionCount($user->id);
        $remaining = \App\Models\ExpertQuestionLimit::getRemainingQuestions($user);
        $canAsk = \App\Models\ExpertQuestionLimit::canAskQuestion($user);
        
        return response()->json([
            'membership_type' => $membershipName,
            'limit' => $limit,
            'used' => $used,
            'remaining' => $remaining,
            'can_ask' => $canAsk,
            'month' => now()->format('F Y')
        ]);
    }
}