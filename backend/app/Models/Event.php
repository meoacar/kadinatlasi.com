<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'location',
        'online_link',
        'max_participants',
        'price',
        'is_free',
        'is_active',
        'image',
        'tags'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'tags' => 'array',
        'price' => 'decimal:2'
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function attendees()
    {
        return $this->participants()->where('status', 'registered');
    }

    public function getAvailableSpotsAttribute()
    {
        if (!$this->max_participants) return null;
        return $this->max_participants - $this->attendees()->count();
    }

    public function getIsFullAttribute()
    {
        return $this->max_participants && $this->attendees()->count() >= $this->max_participants;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
}