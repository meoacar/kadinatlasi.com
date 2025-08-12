<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivationalQuote extends Model
{
    protected $fillable = [
        'quote',
        'author',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRandom($query)
    {
        return $query->inRandomOrder();
    }
}
