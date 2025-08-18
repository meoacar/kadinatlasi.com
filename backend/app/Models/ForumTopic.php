<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumTopic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'forum_category_id',
        'user_id',
        'is_pinned',
        'is_locked',
        'is_featured',
        'views_count',
        'replies_count',
        'likes_count',
        'status',
        'tags',
        'last_post_at',
        'last_post_user_id'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'replies_count' => 'integer',
        'likes_count' => 'integer',
        'tags' => 'array',
        'last_post_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function forumCategory()
    {
        return $this->belongsTo(ForumCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'topic_id');
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'forum_topic_id');
    }

    public function lastPostUser()
    {
        return $this->belongsTo(User::class, 'last_post_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}