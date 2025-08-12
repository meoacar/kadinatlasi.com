<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail',
        'duration',
        'views_count',
        'category_id',
        'tags',
        'is_active',
        'featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'boolean',
        'tags' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(BeautyCategory::class);
    }
}