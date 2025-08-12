<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
    protected $fillable = [
        'level',
        'action',
        'model',
        'model_id',
        'user_id',
        'ip_address',
        'user_agent',
        'description',
        'old_values',
        'new_values'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log($level, $action, $description, $model = null, $modelId = null, $oldValues = null, $newValues = null)
    {
        return static::create([
            'level' => $level,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues
        ]);
    }
}