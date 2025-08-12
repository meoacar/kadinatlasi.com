<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'shipping_cost',
        'total',
        'billing_info',
        'notes',
    ];

    protected $casts = [
        'billing_info' => 'array',
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Beklemede',
            'processing' => 'Hazırlanıyor',
            'shipped' => 'Kargoda',
            'delivered' => 'Teslim Edildi',
            'cancelled' => 'İptal Edildi',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Beklemede',
            'paid' => 'Ödendi',
            'failed' => 'Başarısız',
            'refunded' => 'İade Edildi',
        ];

        return $statuses[$this->payment_status] ?? $this->payment_status;
    }
}