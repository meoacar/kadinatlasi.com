<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'profession',
        'experience',
        'specialization',
        'motivation',
        'certificate_path',
        'status',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}