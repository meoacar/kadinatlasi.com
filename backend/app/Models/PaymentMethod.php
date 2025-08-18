<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'config',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Aktif ödeme yöntemlerini al
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sıralı ödeme yöntemlerini al
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Slug'a göre ödeme yöntemini al
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Konfigürasyon değeri al
     */
    public function getConfig($key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Konfigürasyon değeri ayarla
     */
    public function setConfig($key, $value)
    {
        $config = $this->config ?: [];
        data_set($config, $key, $value);
        $this->config = $config;
    }

    /**
     * İkon HTML'ini al
     */
    public function getIconHtml()
    {
        if (!$this->icon) {
            return '';
        }

        // Eğer icon bir emoji ise
        if (mb_strlen($this->icon) <= 2) {
            return '<span class="text-lg">' . $this->icon . '</span>';
        }

        // Eğer icon bir CSS class ise
        if (str_starts_with($this->icon, 'fa-') || str_starts_with($this->icon, 'icon-')) {
            return '<i class="' . $this->icon . '"></i>';
        }

        // Eğer icon bir SVG ise
        if (str_starts_with($this->icon, '<svg')) {
            return $this->icon;
        }

        // Eğer icon bir resim URL'si ise
        if (filter_var($this->icon, FILTER_VALIDATE_URL)) {
            return '<img src="' . $this->icon . '" alt="' . $this->name . '" class="w-6 h-6">';
        }

        return $this->icon;
    }

    /**
     * Ödeme yönteminin kullanılabilir olup olmadığını kontrol et
     */
    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        // Ödeme yöntemine özel kontroller burada yapılabilir
        switch ($this->slug) {
            case 'credit-card':
                return $this->getConfig('api_key') && $this->getConfig('secret_key');
            case 'paypal':
                return $this->getConfig('client_id') && $this->getConfig('client_secret');
            case 'bank-transfer':
                return $this->getConfig('bank_accounts') && count($this->getConfig('bank_accounts', [])) > 0;
            default:
                return true;
        }
    }

    /**
     * Minimum tutar kontrolü
     */
    public function checkMinimumAmount($amount)
    {
        $minAmount = $this->getConfig('min_amount', 0);
        return $amount >= $minAmount;
    }

    /**
     * Maksimum tutar kontrolü
     */
    public function checkMaximumAmount($amount)
    {
        $maxAmount = $this->getConfig('max_amount');
        return !$maxAmount || $amount <= $maxAmount;
    }

    /**
     * Komisyon hesapla
     */
    public function calculateFee($amount)
    {
        $feeType = $this->getConfig('fee_type', 'none'); // none, fixed, percentage
        $feeValue = $this->getConfig('fee_value', 0);

        switch ($feeType) {
            case 'fixed':
                return $feeValue;
            case 'percentage':
                return $amount * ($feeValue / 100);
            default:
                return 0;
        }
    }
}