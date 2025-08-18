<x-filament-panels::page>
    <div class="fi-section-content-ctn">
        <div class="fi-section-content">
            <div class="fi-section-header">
                <h3 class="fi-section-header-heading">İyzico Ödeme Ayarları</h3>
                <p class="fi-section-header-description">İyzico API bilgilerinizi .env dosyasında ayarlayın.</p>
            </div>

            <div class="fi-section-content-ctn">
                <div class="fi-section-content">
                    <div class="grid gap-6">
                        <div class="fi-fo-field-wrp">
                            <div class="fi-fo-field-wrp-label">
                                <label class="fi-fo-field-wrp-label-text">API Key</label>
                            </div>
                            <div class="fi-input-wrp">
                                <div class="fi-input">
                                    {{ config('services.iyzico.api_key') ? '••••••••••••••••' : 'Ayarlanmamış' }}
                                </div>
                            </div>
                        </div>

                        <div class="fi-fo-field-wrp">
                            <div class="fi-fo-field-wrp-label">
                                <label class="fi-fo-field-wrp-label-text">Secret Key</label>
                            </div>
                            <div class="fi-input-wrp">
                                <div class="fi-input">
                                    {{ config('services.iyzico.secret_key') ? '••••••••••••••••' : 'Ayarlanmamış' }}
                                </div>
                            </div>
                        </div>

                        <div class="fi-fo-field-wrp">
                            <div class="fi-fo-field-wrp-label">
                                <label class="fi-fo-field-wrp-label-text">Base URL</label>
                            </div>
                            <div class="fi-input-wrp">
                                <div class="fi-input">
                                    {{ config('services.iyzico.base_url', 'Ayarlanmamış') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="fi-fo-field-wrp">
                            <div class="fi-fo-field-wrp-label">
                                <label class="fi-fo-field-wrp-label-text">Aktif Gateway</label>
                            </div>
                            <div class="fi-input-wrp">
                                <div class="fi-input">
                                    {{ config('services.payment.gateway', 'iyzico') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fi-section-content-ctn mt-6">
                <div class="fi-section-content">
                    <div class="fi-section-header">
                        <h3 class="fi-section-header-heading">.env Dosyası Ayarları</h3>
                    </div>
                    <div class="fi-input-wrp">
                        <pre class="fi-input">IYZICO_API_KEY=your-api-key-here
IYZICO_SECRET_KEY=your-secret-key-here
IYZICO_BASE_URL=https://sandbox-api.iyzipay.com

# PayTR Settings
PAYTR_MERCHANT_ID=your-merchant-id
PAYTR_MERCHANT_KEY=your-merchant-key
PAYTR_MERCHANT_SALT=your-merchant-salt
PAYTR_TEST_MODE=true

# Gateway Selection
PAYMENT_GATEWAY=iyzico</pre>
                    </div>
                </div>
            </div>

            <div class="fi-form-actions">
                <a href="/premium" target="_blank" class="fi-btn fi-btn-color-primary fi-btn-size-md">
                    Premium Sayfasını Test Et
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page>