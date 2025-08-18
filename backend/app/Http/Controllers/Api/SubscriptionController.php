<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use App\Models\Payment;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function plans()
    {
        $plans = MembershipPlan::active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:membership_plans,id'
        ]);

        try {
            $user = Auth::user();
            $plan = MembershipPlan::findOrFail($request->plan_id);

            // Pending subscription ve payment oluştur
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'status' => 'pending',
                'starts_at' => now(),
                'expires_at' => now()->addDays($plan->duration_days),
            ]);

            $payment = Payment::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'payment_id' => 'temp_' . uniqid(),
                'amount' => $plan->price,
                'status' => 'pending',
            ]);

            $result = $this->subscriptionService->createSubscription($user, $plan);

            // Payment ID'yi güncelle
            $payment->update([
                'payment_id' => $result['token'],
                'conversation_id' => $result['token'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function callback(Request $request)
    {
        try {
            $token = $request->input('token');
            $result = $this->subscriptionService->handleCallback($token);

            if ($result['success']) {
                return redirect()->to(config('app.frontend_url') . '/premium/success');
            } else {
                return redirect()->to(config('app.frontend_url') . '/premium/failed');
            }

        } catch (\Exception $e) {
            return redirect()->to(config('app.frontend_url') . '/premium/failed');
        }
    }

    public function mySubscription()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        if (!$subscription) {
            return response()->json([
                'success' => true,
                'data' => null,
                'membership_type' => $user->membership_type,
                'membership_expires_at' => $user->membership_expires_at,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $subscription->load('membershipPlan'),
            'membership_type' => $user->membership_type,
            'membership_expires_at' => $user->membership_expires_at,
            'remaining_days' => $subscription->remaining_days,
        ]);
    }

    public function cancel()
    {
        try {
            $user = Auth::user();
            $subscription = $user->activeSubscription;

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aktif abonelik bulunamadı'
                ], 404);
            }

            $this->subscriptionService->cancelSubscription($subscription);

            return response()->json([
                'success' => true,
                'message' => 'Aboneliğiniz başarıyla iptal edildi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function paymentHistory()
    {
        $user = Auth::user();
        $payments = $user->payments()
                         ->with('subscription.membershipPlan')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }
    
    public function paytrCallback(Request $request)
    {
        try {
            $paytrService = new \App\Services\PayTRService();
            $result = $paytrService->handleCallback($request->all());
            
            if ($result['success']) {
                echo 'OK';
            } else {
                echo 'FAIL';
            }
        } catch (\Exception $e) {
            echo 'FAIL';
        }
    }
    
    public function paytrSuccess(Request $request)
    {
        return redirect()->to(config('app.frontend_url') . '/premium/success');
    }
    
    public function paytrFailed(Request $request)
    {
        return redirect()->to(config('app.frontend_url') . '/premium/failed');
    }
}