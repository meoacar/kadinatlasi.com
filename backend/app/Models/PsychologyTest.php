<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsychologyTest extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'questions',
        'results',
        'duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'questions' => 'array',
        'results' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function calculateResult($answers)
    {
        $totalScore = 0;
        foreach ($answers as $index => $answer) {
            if (isset($this->questions[$index])) {
                $weight = $this->questions[$index]['weight'] ?? 1;
                $totalScore += $answer * $weight;
            }
        }

        foreach ($this->results as $result) {
            if ($totalScore >= $result['min_score'] && $totalScore <= $result['max_score']) {
                return $result;
            }
        }

        return null;
    }
}
