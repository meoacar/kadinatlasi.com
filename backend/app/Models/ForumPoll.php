<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumPoll extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'group_id',
        'user_id',
        'question',
        'options',
        'multiple_choice',
        'anonymous',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'multiple_choice' => 'boolean',
        'anonymous' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class, 'topic_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ForumGroup::class, 'group_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ForumPollVote::class, 'poll_id');
    }

    public function getResultsAttribute()
    {
        $totalVotes = $this->votes->count();
        $results = [];

        foreach ($this->options as $index => $option) {
            $optionVotes = $this->votes->filter(function ($vote) use ($index) {
                return in_array($index, $vote->selected_options);
            })->count();

            $results[] = [
                'option' => $option,
                'votes' => $optionVotes,
                'percentage' => $totalVotes > 0 ? round(($optionVotes / $totalVotes) * 100, 1) : 0
            ];
        }

        return $results;
    }

    public function hasUserVoted(User $user): bool
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function getUserVote(User $user)
    {
        return $this->votes()->where('user_id', $user->id)->first();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canVote(): bool
    {
        return $this->is_active && !$this->isExpired();
    }
}