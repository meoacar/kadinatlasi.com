<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetCategory extends Model
{
    protected $fillable = [
        'name',
        'type',
        'icon',
        'color',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(BudgetEntry::class);
    }
}