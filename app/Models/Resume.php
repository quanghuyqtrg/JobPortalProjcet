<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'user_id', 
        'title', 
        'summary', 
        'skills', 
        'experience', 
        'education', 
        'cv_file_id',
        'parsing_status',
        'parsing_error',
        'parsed_data',
        'parsed_skills',
        'parsed_experience',
        'parsed_education',
        'parsed_summary',
        'total_years_experience'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cvFile()
    {
        return $this->belongsTo(CvFile::class);
    }
    
}