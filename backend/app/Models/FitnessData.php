<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'date'
    ];

    protected $casts = [
        'data' => 'array',
        'date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}