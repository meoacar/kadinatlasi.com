<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyTip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'icon',
        'color',
        'category',
        'difficulty_level',
        'time_required',
        'ingredients',
        'steps',
        'is_active',
        'featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'boolean',
        'ingredients' => 'array',
        'steps' => 'array'
    ];
}