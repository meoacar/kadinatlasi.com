<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReputation extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'points',
        'source_type',
        'source_id',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public static function addPoints($userId, $actionType, $points, $source = null, $description = null)
    {
        return self::create([
            'user_id' => $userId,
            'action_type' => $actionType,
            'points' => $points,
            'source_type' => $source ? get_class($source) : null,
            'source_id' => $source ? $source->id : null,
            'description' => $description
        ]);
    }
}