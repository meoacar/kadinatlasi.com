<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'type',
        'url',
        'phone',
        'email',
        'address',
        'working_hours',
        'is_emergency',
        'is_free',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_emergency' => 'boolean',
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'working_hours' => 'array'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}