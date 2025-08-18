<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BabyName extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'origin',
        'meaning',
        'description',
        'popularity_rank',
        'is_popular'
    ];

    protected $casts = [
        'is_popular' => 'boolean'
    ];

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender)->orWhere('gender', 'unisex');
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }
}