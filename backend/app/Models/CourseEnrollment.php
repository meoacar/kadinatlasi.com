<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'price_paid',
        'enrollment_date',
        'completion_date',
        'progress',
        'status'
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'enrollment_date' => 'date',
        'completion_date' => 'date',
        'progress' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}