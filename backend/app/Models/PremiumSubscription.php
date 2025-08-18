<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_type',
        'price',
        'start_date',
        'end_date',
        'status',
        'payment_method',
        'features'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'features' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}