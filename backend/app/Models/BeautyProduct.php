<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'rating',
        'price',
        'brand',
        'category',
        'ingredients',
        'pros',
        'cons',
        'is_active',
        'featured_image',
        'gallery_images'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'ingredients' => 'array',
        'pros' => 'array',
        'cons' => 'array',
        'gallery_images' => 'array'
    ];
}