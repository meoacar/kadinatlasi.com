<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAdvancedFilters;

class BlogPost extends Model
{
    use HasFactory, HasAdvancedFilters;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'tags',
        'status',
        'views_count',
        'likes_count',
        'published_at',
        'is_premium',
        'premium_type',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_premium' => 'boolean',
    ];

    /**
     * Aranabilir alanlar
     */
    protected $searchable = [
        'title',
        'excerpt',
        'content',
        'user.name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class)
                    ->approved()
                    ->topLevel()
                    ->with('user', 'replies')
                    ->latest();
    }

    public function allComments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function likes()
    {
        return $this->hasMany(BlogLike::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    public function canUserAccess($user = null)
    {
        if (!$this->is_premium) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->canAccessPremiumContent($this->premium_type);
    }
}