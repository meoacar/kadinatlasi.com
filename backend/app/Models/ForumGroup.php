<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'is_private',
        'requires_approval',
        'creator_id',
        'member_count',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'forum_group_members', 'group_id', 'user_id')
            ->withPivot(['role', 'is_approved', 'joined_at'])
            ->withTimestamps();
    }

    public function approvedMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('is_approved', true);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(ForumTopic::class, 'group_id');
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isAdmin(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public function isModerator(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->whereIn('role', ['admin', 'moderator'])
            ->exists();
    }
}