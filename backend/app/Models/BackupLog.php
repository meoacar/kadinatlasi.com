<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackupLog extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status',
        'file_path',
        'file_size',
        'started_at',
        'completed_at',
        'error_message',
        'metadata',
        'created_by'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
        'file_size' => 'integer'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return 'N/A';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->file_size;
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->started_at || !$this->completed_at) return null;
        
        $diff = $this->completed_at->diffInSeconds($this->started_at);
        
        if ($diff < 60) return $diff . ' saniye';
        if ($diff < 3600) return round($diff / 60, 1) . ' dakika';
        return round($diff / 3600, 1) . ' saat';
    }
}