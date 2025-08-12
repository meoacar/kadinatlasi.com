<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'instructor_name',
        'instructor_id',
        'price',
        'duration',
        'level',
        'category',
        'image_url',
        'video_url',
        'content',
        'is_active',
        'enrollment_count',
        'rating'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'enrollment_count' => 'integer',
        'rating' => 'decimal:1'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }
}