<?php

namespace App\Services;

use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\PaymentSetting;
use App\Services\PayTRService;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Currency;

class SubscriptionService
{
    private $options;

    public function __construct()
    {
        $this->options = new Options();
        $apiKey = config('services.iyzico.api_key');
        $secretKey = config('services.iyzico.secret_key');
        $baseUrl = config('services.iyzico.base_url');
        
        if (!$apiKey || !$secretKey) {
            throw new \Exception('İyzico API bilgileri bulunamadı. .env dosyasını kontrol edin.');
        }
        
        $this->options->setApiKey($apiKey);
        $this->options->setSecretKey($secretKey);
        $this->options->setBaseUrl($baseUrl);
    }

    public function createSubscription(User $user, MembershipPlan $plan)
    {
        // Mevcut aktif aboneliği kontrol et
        $activeSubscription = $user->activeSubscription;
        if ($activeSubscription) {
            throw new \Exception('Zaten aktif bir aboneliğiniz bulunmaktadır.');
        }

        $gateway = config('services.payment.gateway', 'iyzico');
        
        if ($gateway === 'paytr') {
            return $this->createPayTRPayment($user, $plan);
        } else {
            return $this->createIyzicoPayment($user, $plan);
        }
    }
    
    private function createPayTRPayment(User $user, MembershipPlan $plan)
    {
        $paytrService = new PayTRService();
        return $paytrService->createPayment($user, $plan);
    }
    
    private function createIyzicoPayment(User $user, MembershipPlan $plan)
    {
        // Ödeme formunu oluştur
        $paymentForm = $this->createPaymentForm($user, $plan);
        
        if ($paymentForm->getStatus() === 'success') {
            return [
                'success' => true,
                'payment_page_url' => $paymentForm->getPaymentPageUrl(),
                'token' => $paymentForm->getToken()
            ];
        }

        throw new \Exception('Ödeme formu oluşturulamadı: ' . $paymentForm->getErrorMessage());
    }

    private function createPaymentForm(User $user, MembershipPlan $plan)
    {
        $request = new CreateCheckoutFormInitializeRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId(uniqid());
        $request->setPrice($plan->price);
        $request->setPaidPrice($plan->price);
        $request->setCurrency(Currency::TL);
        $request->setBasketId("B" . time());
        $request->setPaymentGroup("SUBSCRIPTION");
        $request->setCallbackUrl(config('app.url') . '/api/subscription/callback');

        // Alıcı bilgileri
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($user->id);
        $buyer->setName(explode(' ', $user->name)[0] ?? $user->name);
        $buyer->setSurname(explode(' ', $user->name)[1] ?? '');
        $buyer->setEmail($user->email);
        $buyer->setIdentityNumber("11111111111");
        $buyer->setRegistrationAddress("Türkiye");
        $buyer->setCity("İstanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34000");
        $request->setBuyer($buyer);

        // Teslimat adresi
        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($user->name);
        $shippingAddress->setCity("İstanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("Türkiye");
        $shippingAddress->setZipCode("34000");
        $request->setShippingAddress($shippingAddress);

        // Fatura adresi
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($user->name);
        $billingAddress->setCity("İstanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("Türkiye");
        $billingAddress->setZipCode("34000");
        $request->setBillingAddress($billingAddress);

        // Sepet öğeleri
        $basketItems = [];
        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setId($plan->id);
        $basketItem->setName($plan->name . " Üyelik");
        $basketItem->setCategory1("Üyelik");
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($plan->price);
        $basketItems[] = $basketItem;
        $request->setBasketItems($basketItems);

        return CheckoutFormInitialize::create($request, $this->options);
    }

    public function handleCallback($token)
    {
        $request = new RetrieveCheckoutFormRequest();
        $request->setToken($token);

        $checkoutForm = CheckoutForm::retrieve($request, $this->options);

        if ($checkoutForm->getStatus() === 'success') {
            return $this->processSuccessfulPayment($checkoutForm);
        }

        return $this->processFailedPayment($checkoutForm);
    }

    private function processSuccessfulPayment($checkoutForm)
    {
        $paymentId = $checkoutForm->getPaymentId();
        $conversationId = $checkoutForm->getConversationId();
        $paidPrice = $checkoutForm->getPaidPrice();

        // Ödeme kaydını bul veya oluştur
        $payment = Payment::where('payment_id', $paymentId)->first();
        
        if (!$payment) {
            return ['success' => false, 'message' => 'Ödeme kaydı bulunamadı'];
        }

        $payment->update([
            'status' => 'success',
            'gateway_response' => $checkoutForm->jsonSerialize(),
            'paid_at' => now(),
        ]);

        // Aboneliği aktif et
        $subscription = $payment->subscription;
        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
            ]);

            // Kullanıcının membership bilgilerini güncelle
            $user = $subscription->user;
            $plan = $subscription->membershipPlan;
            
            $user->update([
                'membership_type' => $plan->slug,
                'membership_expires_at' => now()->addDays($plan->duration_days),
            ]);

            // Fatura oluştur
            $invoiceService = new InvoiceService();
            $invoice = $invoiceService->createInvoice($user, $subscription);
            $invoice->markAsPaid();
        }

        return [
            'success' => true,
            'message' => 'Ödeme başarılı! Premium üyeliğiniz aktif edildi.',
            'subscription' => $subscription
        ];
    }

    private function processFailedPayment($checkoutForm)
    {
        $paymentId = $checkoutForm->getPaymentId();
        
        $payment = Payment::where('payment_id', $paymentId)->first();
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $checkoutForm->getErrorMessage(),
                'gateway_response' => $checkoutForm->jsonSerialize(),
            ]);
        }

        return [
            'success' => false,
            'message' => 'Ödeme başarısız: ' . $checkoutForm->getErrorMessage()
        ];
    }

    public function cancelSubscription(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Kullanıcının membership'ini normal'e çevir
        $subscription->user->update([
            'membership_type' => 'normal',
            'membership_expires_at' => null,
        ]);

        return true;
    }

    public function renewSubscription(Subscription $subscription)
    {
        $plan = $subscription->membershipPlan;
        
        $subscription->update([
            'expires_at' => $subscription->expires_at->addDays($plan->duration_days),
        ]);

        $subscription->user->update([
            'membership_expires_at' => $subscription->expires_at,
        ]);

        return $subscription;
    }
}