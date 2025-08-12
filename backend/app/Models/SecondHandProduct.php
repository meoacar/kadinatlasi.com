<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondHandProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'condition',
        'images',
        'category',
        'category_id',
        'seller_id',
        'user_id',
        'seller_name',
        'seller_email',
        'seller_phone',
        'status',
        'is_featured',
        'location',
        'contact_info'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->name)) {
                $model->name = $model->title;
            }
            if (empty($model->slug)) {
                $model->slug = \Str::slug($model->title);
            }
        });
    }

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_featured' => 'boolean'
    ];

    protected $appends = [
        'discount_percentage',
        'is_favorited',
        'average_rating',
        'review_count'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price && $this->price < $this->original_price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function messages()
    {
        return $this->hasMany(SecondHandMessage::class, 'product_id');
    }

    public function favorites()
    {
        return $this->hasMany(SecondHandFavorite::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(SecondHandReview::class, 'product_id');
    }

    public function getIsFavoritedAttribute()
    {
        if (!auth()->check()) return false;
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}