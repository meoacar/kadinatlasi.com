<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PremiumSubscription;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function plans()
    {
        $plans = \App\Models\PremiumPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->plan_id,
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'duration' => $plan->duration,
                    'popular' => $plan->is_popular,
                    'features' => $plan->features
                ];
            });

        return response()->json([
            'success' => true,
            'plans' => $plans
        ]);
    }

    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'plan_type' => 'required|in:basic,premium,vip'
            ]);

            // Get plan from database
            $plan = \App\Models\PremiumPlan::where('plan_id', $request->plan_type)
                ->where('is_active', true)
                ->first();
                
            if (!$plan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seçilen plan bulunamadı!'
                ], 404);
            }

            // Create pending subscription
            $subscription = PremiumSubscription::create([
                'user_id' => auth()->id(),
                'plan_type' => $request->plan_type,
                'price' => $plan->price,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'pending',
                'payment_method' => 'Pending',
                'features' => $plan->features
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ödeme sayfasına yönlendiriliyorsunuz...',
                'subscription_id' => $subscription->id,
                'redirect_url' => '/premium/payment/' . $subscription->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Premium subscription error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Üyelik işlemi sırasında bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completePayment(Request $request, $subscriptionId)
    {
        $subscription = PremiumSubscription::findOrFail($subscriptionId);
        
        if ($subscription->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Simulate payment success
        $subscription->update([
            'status' => 'active',
            'payment_method' => 'Kredi Kartı'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Premium üyeliğiniz başarıyla aktif edildi!',
            'subscription' => $subscription
        ]);
    }

    private function getPlanFeatures($planType)
    {
        $features = [
            'basic' => ['Reklamsız deneyim', 'Temel hesaplama araçları', 'Forum erişimi'],
            'premium' => ['Tüm Temel özellikler', 'Premium astroloji analizleri', 'Kişiye özel diyet listeleri', 'Uzman danışmanlık', 'Öncelikli destek'],
            'vip' => ['Tüm Premium özellikler', 'Birebir koçluk seansları', 'Özel grup etkinlikleri', 'İleri düzey raporlar', '7/24 destek']
        ];

        return $features[$planType] ?? [];
    }

    public function getUserSubscription()
    {
        $subscription = PremiumSubscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        return response()->json([
            'success' => true,
            'subscription' => $subscription,
            'is_premium' => $subscription !== null
        ]);
    }
}