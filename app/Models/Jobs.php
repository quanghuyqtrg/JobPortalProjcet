<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'title',
        'code',
        'location',
        'content',
        'description',
        'skills',
        'objectives',
        'responsibilities',
        'minimum_salary',
        'maximum_salary',
        'work_model',
        'experience',
        'type'
    ];

    protected $casts = [
        'skills' => 'array',
        'objectives' => 'array',
        'responsibilities' => 'array'
    ];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}