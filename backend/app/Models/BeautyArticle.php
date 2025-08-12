<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'icon',
        'color',
        'read_time',
        'category',
        'is_active',
        'featured_image',
        'meta_description',
        'tags'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tags' => 'array'
    ];
}