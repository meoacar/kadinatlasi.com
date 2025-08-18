<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expert_id',
        'category_id',
        'title',
        'question',
        'answer',
        'status',
        'is_public',
        'answered_at',
        'is_approved',
        'moderation_notes',
        'moderated_at',
        'moderated_by'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'answered_at' => 'datetime',
        'is_approved' => 'boolean',
        'moderated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    public function scopeForExpert($query, $expertId)
    {
        return $query->where('expert_id', $expertId)->orWhere('expert_id', null);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeNeedsModeration($query)
    {
        return $query->where('is_approved', false);
    }
}