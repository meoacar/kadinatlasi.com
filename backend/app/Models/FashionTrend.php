<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FashionTrend extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'icon',
        'season',
        'category',
        'color_palette',
        'style_tips',
        'is_active',
        'featured_image',
        'gallery_images'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'color_palette' => 'array',
        'style_tips' => 'array',
        'gallery_images' => 'array'
    ];
}