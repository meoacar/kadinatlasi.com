<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'revenue_type',
        'amount',
        'description',
        'date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date'
    ];
}