<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'partnership_type',
        'description',
        'budget',
        'start_date',
        'end_date',
        'status',
        'commission_rate',
        'total_revenue'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'commission_rate' => 'decimal:2',
        'total_revenue' => 'decimal:2'
    ];
}