<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PregnancyWeek extends Model
{
    protected $fillable = [
        'week_number',
        'title',
        'description',
        'baby_development',
        'mother_changes',
        'tips',
        'baby_size',
        'baby_weight',
        'image'
    ];

    protected $casts = [
        'tips' => 'array'
    ];
}