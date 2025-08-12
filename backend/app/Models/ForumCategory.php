<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'topics_count',
        'posts_count',
        'is_active'
    ];

    protected $casts = [
        'topics_count' => 'integer',
        'posts_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean'
    ];

    public function topics()
    {
        return $this->hasMany(ForumTopic::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}