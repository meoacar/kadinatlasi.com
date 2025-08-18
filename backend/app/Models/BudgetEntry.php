<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetEntry extends Model
{
    protected $fillable = [
        'user_id',
        'budget_category_id',
        'title',
        'description',
        'amount',
        'type',
        'entry_date',
        'is_recurring',
        'recurring_type'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
        'is_recurring' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BudgetCategory::class, 'budget_category_id');
    }
}