<?php

namespace App\Services;

use Iyzipay\Options;
use Iyzipay\Model\Payment;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;

class PaymentService
{
    private $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(env('IYZICO_API_KEY'));
        $this->options->setSecretKey(env('IYZICO_SECRET_KEY'));
        $this->options->setBaseUrl(env('IYZICO_BASE_URL'));
    }

    public function createPayment($orderData)
    {
        $request = new CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($orderData['conversation_id']);
        $request->setPrice($orderData['price']);
        $request->setPaidPrice($orderData['paid_price']);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($orderData['basket_id']);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);

        // Kart bilgileri
        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($orderData['card']['holder_name']);
        $paymentCard->setCardNumber($orderData['card']['number']);
        $paymentCard->setExpireMonth($orderData['card']['expire_month']);
        $paymentCard->setExpireYear($orderData['card']['expire_year']);
        $paymentCard->setCvc($orderData['card']['cvc']);
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        // Alıcı bilgileri
        $buyer = new Buyer();
        $buyer->setId($orderData['buyer']['id']);
        $buyer->setName($orderData['buyer']['name']);
        $buyer->setSurname($orderData['buyer']['surname']);
        $buyer->setGsmNumber($orderData['buyer']['gsm_number']);
        $buyer->setEmail($orderData['buyer']['email']);
        $buyer->setIdentityNumber($orderData['buyer']['identity_number']);
        $buyer->setRegistrationAddress($orderData['buyer']['registration_address']);
        $buyer->setIp($orderData['buyer']['ip']);
        $buyer->setCity($orderData['buyer']['city']);
        $buyer->setCountry($orderData['buyer']['country']);
        $request->setBuyer($buyer);

        // Teslimat adresi
        $shippingAddress = new Address();
        $shippingAddress->setContactName($orderData['shipping_address']['contact_name']);
        $shippingAddress->setCity($orderData['shipping_address']['city']);
        $shippingAddress->setCountry($orderData['shipping_address']['country']);
        $shippingAddress->setAddress($orderData['shipping_address']['address']);
        $request->setShippingAddress($shippingAddress);

        // Fatura adresi
        $billingAddress = new Address();
        $billingAddress->setContactName($orderData['billing_address']['contact_name']);
        $billingAddress->setCity($orderData['billing_address']['city']);
        $billingAddress->setCountry($orderData['billing_address']['country']);
        $billingAddress->setAddress($orderData['billing_address']['address']);
        $request->setBillingAddress($billingAddress);

        // Sepet öğeleri
        $basketItems = [];
        foreach ($orderData['basket_items'] as $item) {
            $basketItem = new BasketItem();
            $basketItem->setId($item['id']);
            $basketItem->setName($item['name']);
            $basketItem->setCategory1($item['category']);
            $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $basketItem->setPrice($item['price']);
            $basketItems[] = $basketItem;
        }
        $request->setBasketItems($basketItems);

        return Payment::create($request, $this->options);
    }
}