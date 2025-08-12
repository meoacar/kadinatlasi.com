<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOutfit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'season',
        'likes_count',
        'is_approved',
        'image1',
        'image2',
        'image3',
        'image4',
        'tags'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'outfit_items' => 'array',
        'tags' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(OutfitLike::class, 'outfit_id');
    }
}