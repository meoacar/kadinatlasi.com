<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'position',
        'content',
        'image_url',
        'link_url',
        'start_date',
        'end_date',
        'price',
        'clicks',
        'impressions',
        'is_active',
        'client_name',
        'client_email'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'clicks' => 'integer',
        'impressions' => 'integer'
    ];
}