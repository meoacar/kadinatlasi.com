<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeautyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'background_gradient',
        'order',
        'is_active',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function articles()
    {
        return $this->hasMany(BeautyArticle::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(BeautyProduct::class, 'category_id');
    }
}