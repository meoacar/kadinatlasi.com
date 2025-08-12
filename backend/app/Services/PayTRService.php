<?php

namespace App\Services;

use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use App\Models\Payment;

class PayTRService
{
    private $merchantId;
    private $merchantKey;
    private $merchantSalt;
    private $testMode;

    public function __construct()
    {
        $this->merchantId = config('services.paytr.merchant_id');
        $this->merchantKey = config('services.paytr.merchant_key');
        $this->merchantSalt = config('services.paytr.merchant_salt');
        $this->testMode = config('services.paytr.test_mode');

        if (!$this->merchantId || !$this->merchantKey || !$this->merchantSalt) {
            throw new \Exception('PayTR API bilgileri bulunamadı. .env dosyasını kontrol edin.');
        }
    }

    public function createPayment(User $user, MembershipPlan $plan)
    {
        // Ödeme kaydı oluştur
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $plan->price,
            'currency' => 'TRY',
            'status' => 'pending',
            'gateway' => 'paytr',
            'gateway_transaction_id' => uniqid('paytr_'),
        ]);

        // Abonelik kaydı oluştur
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'status' => 'pending',
            'starts_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
        ]);

        $payment->update(['subscription_id' => $subscription->id]);

        // PayTR ödeme formu oluştur
        $paymentData = $this->createPaymentForm($user, $plan, $payment);

        return [
            'success' => true,
            'payment_form' => $paymentData,
            'payment_id' => $payment->id
        ];
    }

    private function createPaymentForm(User $user, MembershipPlan $plan, Payment $payment)
    {
        $merchantOid = $payment->id;
        $userBasket = base64_encode(json_encode([
            [$plan->name . ' Üyelik', $plan->price, 1]
        ]));

        $paytrToken = base64_encode(hash_hmac('sha256', 
            $this->merchantId . $user->request()->ip() . $merchantOid . $user->email . $plan->price . $userBasket . 
            ($this->testMode ? '1' : '0') . 'TRY', 
            $this->merchantKey, true
        ));

        return [
            'merchant_id' => $this->merchantId,
            'user_ip' => $user->request()->ip() ?? '127.0.0.1',
            'merchant_oid' => $merchantOid,
            'email' => $user->email,
            'payment_amount' => $plan->price * 100, // PayTR kuruş cinsinden
            'paytr_token' => $paytrToken,
            'user_basket' => $userBasket,
            'debug_on' => $this->testMode ? 1 : 0,
            'no_installment' => 0,
            'max_installment' => 0,
            'user_name' => $user->name,
            'user_address' => 'Türkiye',
            'user_phone' => '5555555555',
            'merchant_ok_url' => config('app.url') . '/api/subscription/paytr/success',
            'merchant_fail_url' => config('app.url') . '/api/subscription/paytr/failed',
            'timeout_limit' => 30,
            'currency' => 'TL',
            'test_mode' => $this->testMode ? 1 : 0,
        ];
    }

    public function handleCallback($postData)
    {
        $merchantOid = $postData['merchant_oid'];
        $status = $postData['status'];
        $totalAmount = $postData['total_amount'];
        $hash = $postData['hash'];

        // Hash doğrulama
        $hashStr = $merchantOid . $this->merchantSalt . $status . $totalAmount;
        $calculatedHash = base64_encode(hash_hmac('sha256', $hashStr, $this->merchantKey, true));

        if ($hash !== $calculatedHash) {
            return ['success' => false, 'message' => 'Hash doğrulaması başarısız'];
        }

        $payment = Payment::find($merchantOid);
        if (!$payment) {
            return ['success' => false, 'message' => 'Ödeme kaydı bulunamadı'];
        }

        if ($status === 'success') {
            return $this->processSuccessfulPayment($payment, $postData);
        } else {
            return $this->processFailedPayment($payment, $postData);
        }
    }

    private function processSuccessfulPayment(Payment $payment, $postData)
    {
        $payment->update([
            'status' => 'success',
            'gateway_response' => json_encode($postData),
            'paid_at' => now(),
        ]);

        // Aboneliği aktif et
        $subscription = $payment->subscription;
        if ($subscription) {
            $subscription->update(['status' => 'active']);

            // Kullanıcının membership bilgilerini güncelle
            $user = $subscription->user;
            $plan = $subscription->membershipPlan;
            
            $user->update([
                'membership_type' => $plan->slug,
                'membership_expires_at' => $subscription->expires_at,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Ödeme başarılı! Premium üyeliğiniz aktif edildi.',
            'subscription' => $subscription
        ];
    }

    private function processFailedPayment(Payment $payment, $postData)
    {
        $payment->update([
            'status' => 'failed',
            'failure_reason' => $postData['failed_reason_msg'] ?? 'Ödeme başarısız',
            'gateway_response' => json_encode($postData),
        ]);

        return [
            'success' => false,
            'message' => 'Ödeme başarısız: ' . ($postData['failed_reason_msg'] ?? 'Bilinmeyen hata')
        ];
    }
}